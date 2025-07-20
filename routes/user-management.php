<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'permission:manage users'])->group(function () {
    // User Management Routes
    Route::resource('user-management', UserManagementController::class)->parameters([
        'user-management' => 'user'
    ]);
    
    // Additional route for assigning permissions
    Route::post('user-management/{user}/permissions', [UserManagementController::class, 'assignPermissions'])
        ->name('user-management.permissions');
}); 