<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\VacancyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/system-health', [SystemHealthController::class, 'index'])->name('admin.system-health');
    
    // Newsletter Management
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
    
    // Vacancy Management
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