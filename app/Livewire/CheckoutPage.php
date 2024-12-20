<?php

namespace App\Livewire;

use App\Helpers\DatabaseCartManagement;
use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Request;
use Redirect;
use Str;

#[Title('ShopEasily™ - Check Out Your Shopping Cart')]
class CheckoutPage extends Component
{
    public $cart_items = [];
    public $selected_cart_items = [];
    public $grand_total = 0;
    public $tax_percentage = 0;
    public $tax_cost = 0;
    public $shipping_cost = 0;
    public $ultimate_grand_total = 0;
    public $shipping_method;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $street_address;
    public $address_line_2;
    public $city;
    public $state;
    public $zip_code;
    public $payment_method;

    public function mount(Request $request)
    {
        $user = User::find(Auth::id());
        $shipping_information = $user->shipping_information()->first();
        $selected_cart_items = $request::query('selected_cart_items');
        $this->tax_percentage = config('checkout.tax_percentage');
        $this->shipping_cost = config('checkout.shipping_cost');
        // If it exists, decode and process the items
        if ($selected_cart_items) {
            $this->selected_cart_items = json_decode($selected_cart_items, true);
            if (!assert('is_array($this->selected_cart_items)')) {
                $this->selected_cart_items = [];
            } else {
                $this->selected_cart_items = array_map('intval', $this->selected_cart_items); // Ensure items are integers
                $this->cart_items = DatabaseCartManagement::getCartItemsFromDatabase($this->selected_cart_items, columns: ['id', 'product_id', 'name', 'image', 'quantity', 'unit_amount', 'total_amount']);
                $this->grand_total = DatabaseCartManagement::calculateGrandTotal($this->selected_cart_items);
            }
        }
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
        $this->first_name = $user->first_name ?? "";
        $this->last_name = $user->last_name ?? "";
        $this->phone_number = $user->phone_number ?? "";
        if ($shipping_information) {
            $this->street_address = $shipping_information->street_address ?? "";
            $this->address_line_2 = $shipping_information->address_line_2 ?? "";
            $this->city = $shipping_information->city_or_regency ?? "";
            $this->state = $shipping_information->state ?? "";
            $this->zip_code = $shipping_information->zip_code ?? "";
        }
    }

    public function placeOrder() {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'nullable',
            'phone_number' => 'required',
            'street_address' => 'required',
            'address_line_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
            'shipping_method' => 'required',
        ]);

        $stripe_line_items = [];
        $redirect_url = '';

        $order = new Order();
        $order->user_id = Auth::id();
        $order->grand_total = $this->ultimate_grand_total;
        $order->payment_method = $this->payment_method;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'idr';
        $order->shipping_amount = $this->shipping_cost;
        $order->shipping_method = $this->shipping_method;
        $order->notes = 'An order placed by ' . auth()->user()->name;
        $order->save();
        $order->products()->createMany($this->cart_items->map(function ($cart_item) use ($order) {
            return [
                'product_id' => $cart_item->product_id,
                'quantity' => $cart_item->quantity,
                'unit_amount' => $cart_item->unit_amount,
                'total_amount' => $cart_item->total_amount,
                'order_id' => $order->id
            ];
        })->toArray());

        $address = new Address();
        $address->order_id = $order->id;
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone_number;
        $address->street_address = $this->street_address;
        $address->address_line_2 = $this->address_line_2;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zip_code;
        $address->save();

        if ($this->payment_method == 'midtrans') {
            $request = Request::create(route('api.payments.midtrans.create'), 'POST', [
                'checkout_cart_items' => $this->cart_items,
                'shipping_cost' => $this->shipping_cost,
                'tax_cost' => $this->tax_cost,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email_address' => auth()->user()->email,
                'phone_number' => $this->phone_number,
                'street_address' => $this->street_address,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
                'order_id' => $order->id,
            ]);
            $response = app()->handle($request);
            if ($response->isSuccessful()) {
                $responseData = json_decode($response->getContent(), true);
                $redirect_url = $responseData['redirect_url'];
                DatabaseCartManagement::clearCartItems($this->selected_cart_items);
                $this->dispatch('redirectToMidtransPaymentUrl', $redirect_url);
            }
            else {
                session()->flash('error', 'The MidTrans Payment Gateway is not available or reachable at this moment. Please try again later.');
                return;
            }
        }
        elseif ($this->payment_method == 'stripe') {

        }
        elseif ($this->payment_method == 'paypal') {

        }
        elseif ($this->payment_method == 'doku') {

        }
        else {
            $redirect_url = route('success');
            DatabaseCartManagement::clearCartItems($this->selected_cart_items);
            return redirect($redirect_url);
        }
    }

    public function calculateUltimateGrandTotal() {
        return $this->grand_total + $this->tax_cost + $this->shipping_cost;
    }

    public function render()
    {
        if (empty($this->selected_cart_items)) {
            $this->redirectRoute('cart', absolute: true, navigate: true);
        }
        return view('livewire.checkout-page', [
            'cart_items' => $this->cart_items,
            'selected_cart_items' => $this->selected_cart_items,
            'grand_total' => $this->grand_total,
        ]);
    }
}
