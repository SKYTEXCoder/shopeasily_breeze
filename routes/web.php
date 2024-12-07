<?php

use App\Livewire\CategoriesPage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

// Default Laravel Routes (Comes with the Breeze starter kit, commented because we don't need it anymore)
/* Route::view('/', 'welcome');

 Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile'); */

Route::get('/', HomePage::class);
Route::get('/categories', CategoriesPage::class);


require __DIR__.'/auth.php';
