<?php

namespace App\Livewire;

use App\Helpers\CookieCartHelper;
use App\Helpers\DatabaseCartHelper;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class ProductListingCard extends Component
{
    use LivewireAlert;

    public Product $product;

    public function mount(Product $product) {
        $this->product = $product;
    }

    public function addToCart() {
        if (!$this->product) {
            $this->alert('error', 'Product not found.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        $total_count = Auth::check() ? DatabaseCartHelper::addItemToCart($this->product->id) : CookieCartHelper::addItemToCart($this->product->id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', "\"{$this->product->name}\" has been successfully added to your cart.", [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.partials.product-listing-card');
    }
}
