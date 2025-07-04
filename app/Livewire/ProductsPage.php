<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products Page - TechGear™')]
class ProductsPage extends Component
{
    use WithPagination;

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
    public $price_range = 0;
    #[Url]
    public $max_price_of_queried_products = 0;
    #[Url]
    public $sort = 'latest';
    #[Url]
    public $search = '';
    #[Url]
    public $category = '';
    #[Url]
    public $comingFromCTAButton = false;

    public function mount() {
        if (request()->has('searchQuery')) {
            $this->search = request()->get('searchQuery');
        }
        if (request()->has('productCategory') && request()->get('productCategory') != '0') {
            $this->category = request()->get('productCategory');
            if (!in_array($this->category, $this->selected_categories)) {
                $this->selected_categories[] = $this->category;
            }
        }
        if ($this->price_range > 0 && $this->comingFromCTAButton) {
            $this->price_range = (int) $this->price_range;
        } else {
            $this->price_range = Product::query()->where('is_active', 1)->max('final_price') ?? 0;
        }
        $this->max_price_of_queried_products = $this->price_range;
    }

    public function render()
    {
        $productQuery = Product::query()->where('is_active', 1);

        // Apply search query filter
        if (!empty($this->search)) {
            $productQuery->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('brand', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            });
        }

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
            'products' => $productQuery->paginate(12),
            'brands' => Brand::where('is_active', 1)->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', 1)->get(['id', 'name', 'slug']),
            'max_price_of_queried_products' => $this->max_price_of_queried_products,
            'hasProducts' => $productQuery->exists(),
        ]);
    }
}
