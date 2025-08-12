<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ContactController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/system-health', [SystemHealthController::class, 'index'])->name('admin.system-health');
    });
    
    // Newsletter Management - accessible by admin and manager
    Route::middleware(['permission:manage newsletters'])->group(function () {
        Route::resource('newsletters', NewsletterController::class)->names([
            'index' => 'admin.newsletters.index',
            'create' => 'admin.newsletters.create',
            'store' => 'admin.newsletters.store',
            'show' => 'admin.newsletters.show',
            'edit' => 'admin.newsletters.edit',
            'update' => 'admin.newsletters.update',
            'destroy' => 'admin.newsletters.destroy',
        ]);
        
        // Newsletter API endpoints
        Route::post('/newsletters/subscribe', [NewsletterController::class, 'subscribe'])->name('admin.newsletters.subscribe');
        Route::post('/newsletters/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('admin.newsletters.unsubscribe');
        Route::get('/newsletters/stats', [NewsletterController::class, 'stats'])->name('admin.newsletters.stats');
    });
    
    // Vacancy Management - accessible by admin and manager
    Route::middleware(['permission:manage vacancies'])->group(function () {
        Route::resource('vacancies', VacancyController::class)->names([
            'index' => 'admin.vacancies.index',
            'create' => 'admin.vacancies.create',
            'store' => 'admin.vacancies.store',
            'show' => 'admin.vacancies.show',
            'edit' => 'admin.vacancies.edit',
            'update' => 'admin.vacancies.update',
            'destroy' => 'admin.vacancies.destroy',
        ]);
    });
    
    // Contact Management - accessible by admin and manager
    Route::middleware(['permission:manage contacts'])->group(function () {
        Route::resource('contacts', ContactController::class)->names([
            'index' => 'admin.contacts.index',
            'show' => 'admin.contacts.show',
            'update' => 'admin.contacts.update',
        ]);
        Route::post('/contacts/{contact}/mark-replied', [ContactController::class, 'markAsReplied'])->name('admin.contacts.mark-replied');
        Route::get('/contacts/stats', [ContactController::class, 'stats'])->name('admin.contacts.stats');
    });
}); 