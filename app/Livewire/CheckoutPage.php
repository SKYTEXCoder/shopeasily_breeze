<?php

namespace App\Livewire;

use App\Helpers\DatabaseCartManagement;
use App\Models\User;
use Auth;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Request;

#[Title('ShopEasily™ - Check Out Your Shopping Cart')]
class CheckoutPage extends Component
{
    public $cart_items = [];
    public $selected_cart_items = [];
    public $grand_total = 0;
    public $tax_percentage;
    public $tax_cost;
    public $shipping_cost;
    public $ultimate_grand_total;
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
                $this->cart_items = DatabaseCartManagement::getCartItemsFromDatabase($this->selected_cart_items, columns: ['id', 'product_id', 'name', 'image', 'quantity', 'total_amount']);
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
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);
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
