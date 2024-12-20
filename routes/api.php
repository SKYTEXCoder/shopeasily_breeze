<?php

use App\Http\Controllers\MidtransPaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payments/midtrans', [MidtransPaymentController::class, 'create'])->name('api.payments.midtrans.create');
Route::post('/webhooks/midtrans', [MidtransPaymentController::class, 'webhook'])->name('webhooks.midtrans');
