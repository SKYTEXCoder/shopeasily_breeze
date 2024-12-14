<?php

namespace App\Livewire;

use App\Helpers\CartManagementDatabase;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('ShopEasily™ - Check Out Your Cart')]
class CheckoutPage extends Component
{
    public function render()
    {
        $cart_items = CartManagementDatabase::getCartItemsFromDatabase();
        $grand_total = CartManagementDatabase::calculateGrandTotal();
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'grand_total' => $grand_total
        ]);
    }
}
