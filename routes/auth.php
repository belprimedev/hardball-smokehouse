<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

Route::middleware('guest')->group(function () {
    // Registration routes disabled - users can only be created through admin panel
    // Route::get('register', [RegisteredUserController::class, 'create'])
    //     ->name('register');
    // Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

        // Reservation routes - accessible by staff, manager, and admin
        Route::middleware('permission:manage reservations')->group(function () {
            Route::get('/reservation', [ReservationController::class, 'index'])->name('reservation.index');
            Route::get('/reservation/create', [ReservationController::class, 'create'])->name('reservation.create');
            Route::post('/reservation', [ReservationController::class, 'store'])->name('reservation.store');
            Route::get('/reservation/{reservation}', [ReservationController::class, 'show'])->name('reservation.show');
            Route::get('/reservation/{reservation}/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
            Route::put('/reservation/{reservation}', [ReservationController::class, 'update'])->name('reservation.update');
            Route::delete('/reservation/{reservation}', [ReservationController::class, 'destroy'])->name('reservation.destroy');
        });

        // Menu management routes - accessible by manager and admin
        Route::middleware('permission:manage menu')->group(function () {
            Route::resource('menu-category', MenuCategoryController::class);
            Route::resource('menu-items', MenuController::class);
        });
        
        // Settings routes - accessible by admin only
        Route::middleware('permission:manage settings')->group(function () {
            // Reservation Settings Routes
            Route::get('/reservation-settings', [App\Http\Controllers\ReservationSettingController::class, 'index'])->name('reservation-settings.index');
            Route::put('/reservation-settings', [App\Http\Controllers\ReservationSettingController::class, 'update'])->name('reservation-settings.update');
            
            // General Settings Routes
            Route::get('/settings/general', [App\Http\Controllers\GeneralSettingController::class, 'index'])->name('settings.general');
            Route::put('/settings/general', [App\Http\Controllers\GeneralSettingController::class, 'update'])->name('settings.general.update');
        });
     
});
