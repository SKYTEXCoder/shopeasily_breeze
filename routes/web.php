<?php

use App\Http\Controllers\MidtransPaymentController;
use App\Livewire\BrandsPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyWishListDetailPage;
use App\Livewire\MyWishListsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProfileManagementPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

// Default Laravel Routes (Comes with the Breeze starter kit, commented because we don't need it anymore)
/* Route::view('/', 'welcome');

 Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');*/

Route::get('/your-profile', ProfileManagementPage::class)
    ->middleware(['auth'])
    ->name('profile');

Route::get('/', HomePage::class)->name('index');
Route::get('/brands', BrandsPage::class)->name('brands');
Route::get('/categories', CategoriesPage::class)->name('categories');
Route::get('/products', ProductsPage::class)->name('products');
Route::get('/products/{slug}', ProductDetailPage::class)->name('product.detail');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/contact-sales-team', )->name('contact');


Route::middleware(['auth'])->group(function () {
    Route::get('/logout', function() {
        auth()->logout();
        return redirect('/');
    })->name('logout');
    Route::get('/checkout', CheckoutPage::class)->name('checkout');
    Route::get('/my-orders', MyOrdersPage::class)->name('my-orders');
    Route::get('/my-orders/{order_id}', MyOrderDetailPage::class)->name('my-orders.show');
    Route::get('/my-wishlists', MyWishListsPage::class)->name('my-wishlists');
    Route::get('my-wishlists/{wishlist_id}', MyWishListDetailPage::class)->name('my-wishlists.show');
    Route::get('/payment-success', SuccessPage::class)->name('success');
    Route::get('/payment-cancelled', CancelPage::class)->name('cancelled');
});

Route::get('/search', ProductsPage::class)->name('search');

Route::post('/payments/midtrans', [MidtransPaymentController::class, 'create'])->name('web.payments.midtrans.create');

require __DIR__.'/auth.php';
