<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Helpers\CartManagementDatabase;
use App\Livewire\Partials\Navbar;
use Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Your Cart Page - ShopEasily™')]
class CartPage extends Component
{

    public $cart_items = [];
    public $grand_total;

    public function mount()
    {
        if (Auth::check()) {
            $this->cart_items = CartManagementDatabase::getCartItemsFromDatabase()->toArray();
            $this->grand_total = CartManagementDatabase::calculateGrandTotal();
        } else {
            $this->cart_items = CartManagement::getCartItemsFromCookie();
            $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        }
    }

    public function removeItem($product_id)
    {
        if (Auth::check()) {
            $this->cart_items = CartManagementDatabase::removeCartItem($product_id)->toArray();
            $this->grand_total = CartManagementDatabase::calculateGrandTotal();
        } else {
            $this->cart_items = CartManagement::removeCartItem($product_id);
            $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        }
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function increaseQty($product_id)
    {
        if (Auth::check()) {
            $this->cart_items = CartManagementDatabase::incrementQuantityToCartItem($product_id)->toArray();
            $this->grand_total = CartManagementDatabase::calculateGrandTotal();
        } else {
            $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id);
            $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        }
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function decreaseQty($product_id)
    {
        if (Auth::check()) {
            $this->cart_items = CartManagementDatabase::decrementQuantityToCartItem($product_id)->toArray();
            $this->grand_total = CartManagementDatabase::calculateGrandTotal();
        } else {
            $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
            $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
        }
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
