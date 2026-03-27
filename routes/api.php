<?php

use Illuminate\Support\Facades\Route;
use App\Models\MenuItem;
use App\Models\Reservation;
use App\Models\MenuCategory;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Http\Request;

Route::get('/dessert-items', function () {
    return MenuItem::whereHas('category', function ($q) {
        $q->where('name', 'Dessert');
    })->get();
});

Route::get('/menu-categories', function () {
    return MenuCategory::withCount('menuItems')->get();
});

Route::get('/reservations/check-availability', [App\Http\Controllers\ReservationController::class, 'checkAvailability']);

// Notification routes
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/unread', [NotificationController::class, 'unread']);
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/notifications', [NotificationController::class, 'store']);

// Cart routes
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart', [CartController::class, 'add']);
Route::put('/cart/{item}', [CartController::class, 'update']);
Route::delete('/cart/{item}', [CartController::class, 'remove']);
Route::delete('/cart', [CartController::class, 'clear']);
Route::post('/cart/transfer', [CartController::class, 'transferSessionToUser'])->middleware('auth:sanctum');

// Order routes
Route::get('/orders', [OrderController::class, 'index'])->middleware('auth:sanctum');
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);
Route::get('/orders/{order}/status', [OrderController::class, 'status']);
Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->middleware('auth:sanctum');

// Kitchen dashboard routes
Route::get('/kitchen/queue', [OrderController::class, 'kitchenQueue'])->middleware('auth:sanctum');
Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->middleware('auth:sanctum');

// Checkout routes
Route::get('/checkout/config', [CheckoutController::class, 'config']);
Route::post('/checkout/payment-intent', [CheckoutController::class, 'createPaymentIntent']);
Route::post('/checkout/webhook', [CheckoutController::class, 'webhook'])->name('stripe.webhook');