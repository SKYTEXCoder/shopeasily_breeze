<?php

namespace App\Livewire;

use App\Helpers\DatabaseCartManagement;
use Livewire\Component;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Request;

#[Title('ShopEasily™ - Check Out Your Cart')]
class CheckoutPage extends Component
{
    public $cart_items = [];
    public $selected_cart_items = [];
    public $grand_total;

    public function mount(Request $request)
    {
        $selected_cart_items = $request::query('selected_cart_items');

        // If it exists, decode and process the items
        if ($selected_cart_items) {
            $this->selected_cart_items = json_decode($selected_cart_items, true);
            if (!assert('is_array($this->selected_cart_items)')) {
                $this->selected_cart_items = [];
            } else {
                $this->selected_cart_items = array_map('intval', $this->selected_cart_items); // Ensure items are integers
                $this->cart_items = DatabaseCartManagement::getCartItemsFromDatabase($this->selected_cart_items);
                $this->grand_total = DatabaseCartManagement::calculateGrandTotal($this->selected_cart_items);
            }
        }
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
