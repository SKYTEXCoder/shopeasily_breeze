<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Helpers\CartManagementDatabase;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Product Details Page - ShopEasily™')]
class ProductDetailPage extends Component
{

    use LivewireAlert;

    public $slug;
    public $quantity = 1;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function increaseQty() {
        $this->quantity++;
    }

    public function decreaseQty() {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart($product_id){

        $product = Product::find($product_id);

        if (!$product) {
            $this->alert('error', 'Product not found.', [
                'position' => 'bottom-end',
                'timer' => 3000,
                'toast' => true,
            ]);
            return;
        }

        $total_count = Auth::check() ? CartManagementDatabase::addItemToCartWithQty($product_id, $this->quantity) : CartManagement::addItemToCartWithQty($product_id, $this->quantity);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', "\"{$product->name}\" has been successfully added to your cart.", [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.product-detail-page', [
            'product' => Product::where('slug', $this->slug)->firstOrFail(),
        ]);
    }
}
