<?php

namespace App\Livewire;

use App\Helpers\CookieCartHelper;
use App\Helpers\DatabaseCartHelper;
use App\Livewire\Partials\Navbar;
use Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Your Cart Page - TechGear™')]
class CartPage extends Component
{

    public $cart_items = [];
    public $selected_cart_items = [];
    public $deselected_cart_items =[];
    public $selected_all_cart_items = true;
    public $grand_total = 0;
    public $tax_percentage;
    public $tax_cost;
    public $shipping_cost;
    public $ultimate_grand_total;

    public function mount()
    {
        if (Auth::check()) {
            $this->cart_items = DatabaseCartHelper::getCartItemsFromDatabase(columns: ['product_id', 'name', 'image', 'slug', 'quantity', 'total_amount', 'unit_amount'])->toArray();
            $this->selected_cart_items = array_column($this->cart_items, 'product_id');
            $this->grand_total = DatabaseCartHelper::calculateGrandTotal($this->selected_cart_items);
        } else {
            $this->cart_items = CookieCartHelper::getCartItemsFromCookie();
            $this->selected_cart_items = array_column($this->cart_items, 'product_id');
            $this->grand_total = CookieCartHelper::calculateGrandTotal($this->cart_items, $this->selected_cart_items);
        }
        $this->tax_percentage = config('checkout.tax_percentage');
        $this->shipping_cost = config('checkout.shipping_cost');
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
    }

    public function updatedSelectedCartItems()
    {
        $this->selected_all_cart_items = count($this->selected_cart_items) == count(array_column($this->cart_items, 'product_id'));
        $this->grand_total = Auth::check() ? DatabaseCartHelper::calculateGrandTotal($this->selected_cart_items) : CookieCartHelper::calculateGrandTotal($this->cart_items, $this->selected_cart_items);
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
    }

    public function updatedSelectedAllCartItems() {
        $this->selected_cart_items = $this->selected_all_cart_items ? array_column($this->cart_items, 'product_id') : [];
        $this->grand_total = Auth::check() ? DatabaseCartHelper::calculateGrandTotal($this->selected_cart_items) : CookieCartHelper::calculateGrandTotal($this->cart_items, $this->selected_cart_items);
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
    }

    public function removeItem($product_id)
    {
        $this->selected_cart_items = array_values(array_filter($this->selected_cart_items, function ($item) use ($product_id) {
            return $item != $product_id;
        }));
        if (Auth::check()) {
            $this->cart_items = DatabaseCartHelper::removeCartItem($product_id)->toArray();
            $this->grand_total = DatabaseCartHelper::calculateGrandTotal($this->selected_cart_items);
        } else {
            $this->cart_items = CookieCartHelper::removeCartItem($product_id);
            $this->grand_total = CookieCartHelper::calculateGrandTotal($this->cart_items, $this->selected_cart_items);
        }
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function removeAllSelectedCartItems() {
        if (Auth::check()) {
            $this->cart_items = DatabaseCartHelper::removeCartItems($this->selected_cart_items)->toArray();
            $this->grand_total = DatabaseCartHelper::calculateGrandTotal();
        } else {
            $this->cart_items = CookieCartHelper::removeCartItems($this->selected_cart_items);
            $this->grand_total = CookieCartHelper::calculateGrandTotal($this->cart_items);
        }
        $this->selected_cart_items = [];
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function increaseQty($product_id)
    {
        if (Auth::check()) {
            $this->cart_items = DatabaseCartHelper::incrementQuantityToCartItem($product_id)->toArray();
            $this->grand_total = DatabaseCartHelper::calculateGrandTotal($this->selected_cart_items);
        } else {
            $this->cart_items = CookieCartHelper::incrementQuantityToCartItem($product_id);
            $this->grand_total = CookieCartHelper::calculateGrandTotal($this->cart_items, $this->selected_cart_items);
        }
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function decreaseQty($product_id)
    {
        $this->cart_items = Auth::check() ? DatabaseCartHelper::decrementQuantityToCartItem($product_id)->toArray() : CookieCartHelper::decrementQuantityToCartItem($product_id);
        $this->selected_cart_items = array_values(array_filter($this->selected_cart_items, function ($product_id) {
            $item = current(array_filter($this->cart_items, function ($item) use ($product_id) {
                return $item['product_id'] == $product_id;
            }));
            return $item && $item['quantity'] > 0;
        }));
        $this->grand_total = Auth::check() ? DatabaseCartHelper::calculateGrandTotal($this->selected_cart_items) : CookieCartHelper::calculateGrandTotal($this->cart_items, $this->selected_cart_items);
        $this->tax_cost = $this->grand_total * $this->tax_percentage;
        $this->ultimate_grand_total = $this->calculateUltimateGrandTotal();
        $this->dispatch('update-cart-count', total_count: array_reduce($this->cart_items, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0))->to(Navbar::class);
    }

    public function calculateUltimateGrandTotal() {
        return $this->grand_total + $this->tax_cost + $this->shipping_cost;
    }

    public function proceedToCheckout() {
        return redirect()->route('checkout', [
            'selected_cart_items' => json_encode($this->selected_cart_items),
        ]);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
