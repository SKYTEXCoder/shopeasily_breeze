<?php

namespace App\Livewire;

use App\Helpers\CookieCartManagement;
use App\Helpers\DatabaseCartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products Page - ShopEasily™')]
class ProductsPage extends Component
{
    use WithPagination;
    use LivewireAlert;

    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured;

    #[Url]
    public $on_sale;

    #[Url]
    public $in_stock;

    #[Url]
    public $price_range;

    #[Url]
    public $max_price_of_queried_products;

    #[Url]
    public $sort = 'latest';

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

        $total_count = Auth::check() ? DatabaseCartManagement::addItemToCart($product_id) : CookieCartManagement::addItemToCart($product_id);

        $this->dispatch('update-cart-count', total_count: $total_count)->to(Navbar::class);

        $this->alert('success', "\"{$product->name}\" has been successfully added to your cart.", [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function mount() {
        $this->price_range = Product::query()->where('is_active', 1)->max('final_price');
        $this->max_price_of_queried_products = $this->price_range;
    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);
        if (!empty($this->selected_categories)) {
            $productQuery->whereIn('category_id', $this->selected_categories);
        }

        if (!empty($this->selected_brands)) {
            $productQuery->whereIn('brand_id', $this->selected_brands);
        }

        if ($this->featured) {
            $productQuery->where('is_featured', 1);
        }

        if ($this->on_sale) {
            $productQuery->where('on_sale', 1);
        }

        if ($this->in_stock) {
            $productQuery->where('in_stock', 1);
        }

        if ($this->price_range) {
            $productQuery->whereBetween('final_price', [0, $this->price_range]);
        }

        if ($this->sort == 'latest') {
            $productQuery->latest();
        }

        if ($this->sort == 'oldest') {
            $productQuery->oldest();
        }

        if ($this->sort == 'price-ascending') {
            $productQuery->orderBy('final_price', 'asc');
        }

        if ($this->sort == 'price-descending') {
            $productQuery->orderBy('final_price', 'desc');
        }

        return view('livewire.products-page', [
            'products' => $productQuery->paginate(10),
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
            'max_price_of_queried_products' => $this->max_price_of_queried_products,
        ]);
    }
}
