<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use App\Helpers\CartManagementDatabase;
use App\Models\Category;
use Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Navbar extends Component
{

    public $total_count = 0;

    public function mount() {
        $this->total_count = Auth::check() ? array_reduce(CartManagementDatabase::getCartItemsFromDatabase(columns: ['quantity'])->toArray(), function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0) : array_reduce(CartManagement::getCartItemsFromCookie(), function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
    }

    #[On('update-cart-count')]
    public function updateCartCount($total_count) {
        $this->total_count = $total_count;
    }

    public function render()
    {
        $profileImagePath = auth()->check() ? auth()->user()->image : null;
        $categories = Category::where('is_active', 1)->get();
        return view('livewire.partials.navbar', [
            'categories' => $categories,
            'profileImagePath' => $profileImagePath,
        ]);
    }
}
