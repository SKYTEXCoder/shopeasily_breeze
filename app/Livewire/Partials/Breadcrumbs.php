<?php

namespace App\Livewire\Partials;

use Livewire\Component;

class Breadcrumbs extends Component
{
    public $breadcrumbs = [];

    public function mount()
    {
        $this->generateBreadcrumbs();
    }

    private function generateBreadcrumbs()
    {
        $currentRoute = request()->route();
        $routeName = $currentRoute->getName();
        $routeParams = $currentRoute->parameters();

        // Always start with Home
        $this->breadcrumbs = [
            ['name' => 'Home', 'url' => url('/'), 'active' => false]
        ];

        switch ($routeName) {
            case 'index':
                $this->breadcrumbs[0]['active'] = true;
                break;

            case 'search':
                $searchQuery = request()->get('searchQuery');
                $categoryId = request()->get('productCategory');

                $this->breadcrumbs[] = ['name' => 'Products', 'url' => url('/products'), 'active' => false];

                if ($searchQuery) {
                    if ($categoryId && $categoryId != '0') {
                        $category = \App\Models\Category::find($categoryId);
                        if ($category) {
                            $this->breadcrumbs[] = ['name' => $category->name, 'url' => '#', 'active' => false];
                        }
                    }
                    $this->breadcrumbs[] = ['name' => "Search: \"{$searchQuery}\"", 'url' => '#', 'active' => true];
                } else {
                    $this->breadcrumbs[count($this->breadcrumbs) - 1]['active'] = true;
                }
                break;

            default:
                $this->handleStandardRoutes($routeName, $routeParams);
                break;
        }
    }

    private function handleStandardRoutes($routeName, $routeParams)
    {
        $routeMap = [
            'products' => [
                ['name' => 'Products', 'url' => url('/products'), 'active' => true]
            ],
            'categories' => [
                ['name' => 'Categories', 'url' => url('/categories'), 'active' => true]
            ],
            'brands' => [
                ['name' => 'Brands', 'url' => url('/brands'), 'active' => true]
            ],
            'cart' => [
                ['name' => 'Shopping Cart', 'url' => url('/cart'), 'active' => true]
            ],
            'my-orders' => [
                ['name' => 'My Orders', 'url' => url('/my-orders'), 'active' => true]
            ],
            'my-orders.show' => [
                ['name' => 'My Orders', 'url' => url('/my-orders'), 'active' => false],
                ['name' => 'Order #' . (isset($routeParams['order_id']) ? $routeParams['order_id'] : 'Unknown'), 'url' => '#', 'active' => true]
            ],
            'my-wishlists' => [
                ['name' => 'My Wishlists', 'url' => url('/my-wishlists'), 'active' => true]
            ],
            'my-wishlists.show' => [
                ['name' => 'My Wishlists', 'url' => url('/my-wishlists'), 'active' => false],
                ['name' => 'Wishlist #' . (isset($routeParams['wishlist_id']) ? $routeParams['wishlist_id'] : 'Unknown'), 'url' => '#', 'active' => true]
            ],
            'profile' => [
                ['name' => 'Profile Settings', 'url' => url('/your-profile'), 'active' => true]
            ],
            'success' => [
                ['name' => 'Payment Success', 'url' => '#', 'active' => true]
            ],
            'cancelled' => [
                ['name' => 'Payment Cancelled', 'url' => '#', 'active' => true]
            ]
        ];
        // Handle product detail pages
        if (request()->is('products/*')) {
            $slug = $routeParams['slug'] ?? null;
            if ($slug) {
                $product = \App\Models\Product::where('slug', $slug)->first();
                if ($product) {
                    $this->breadcrumbs[] = ['name' => 'Products', 'url' => url('/products'), 'active' => false];
                    $this->breadcrumbs[] = ['name' => $product->category->name, 'url' => url('/products?selected_categories[]=' . $product->category->id), 'active' => false];
                    $this->breadcrumbs[] = ['name' => $product->name, 'url' => '#', 'active' => true];
                }
            }
        }
        // Handle checkout page
        elseif (request()->is('checkout')) {
            $this->breadcrumbs[] = ['name' => 'Shopping Cart', 'url' => url('/cart'), 'active' => false];
            $this->breadcrumbs[] = ['name' => 'Checkout', 'url' => '#', 'active' => true];
        }
        // Handle standard routes
        elseif (isset($routeMap[$routeName])) {
            foreach ($routeMap[$routeName] as $breadcrumb) {
                $this->breadcrumbs[] = $breadcrumb;
            }
        }
    }
    public function render()
    {
        return view('livewire.partials.breadcrumbs');
    }
}
