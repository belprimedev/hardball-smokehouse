<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    // User Management Routes
    Route::resource('user-management', UserManagementController::class)->parameters([
        'user-management' => 'user'
    ]);
    
    // Additional route for assigning permissions
    Route::post('user-management/{user}/permissions', [UserManagementController::class, 'assignPermissions'])
        ->name('user-management.permissions');
    
    // User Status Management Routes
    Route::post('user-management/{user}/suspend', [UserManagementController::class, 'suspend'])
        ->name('user-management.suspend');
    Route::post('user-management/{user}/activate', [UserManagementController::class, 'activate'])
        ->name('user-management.activate');
    Route::post('user-management/{user}/disable', [UserManagementController::class, 'disable'])
        ->name('user-management.disable');
}); 