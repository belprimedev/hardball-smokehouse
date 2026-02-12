<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\NewsletterEditionController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\ContactController;

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin-only routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/system-health', [SystemHealthController::class, 'index'])->name('admin.system-health');
    });
    
    // Newsletter Management - accessible by admin and manager
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

    // Newsletter Editions (weekly send)
    Route::get('/newsletter-editions', [NewsletterEditionController::class, 'index'])->name('admin.newsletter-editions.index');
    Route::get('/newsletter-editions/create', [NewsletterEditionController::class, 'create'])->name('admin.newsletter-editions.create');
    Route::post('/newsletter-editions', [NewsletterEditionController::class, 'store'])->name('admin.newsletter-editions.store');
    Route::get('/newsletter-editions/{newsletter_edition}/edit', [NewsletterEditionController::class, 'edit'])->name('admin.newsletter-editions.edit');
    Route::put('/newsletter-editions/{newsletter_edition}', [NewsletterEditionController::class, 'update'])->name('admin.newsletter-editions.update');
    Route::delete('/newsletter-editions/{newsletter_edition}', [NewsletterEditionController::class, 'destroy'])->name('admin.newsletter-editions.destroy');
    Route::post('/newsletter-editions/{newsletter_edition}/send-test', [NewsletterEditionController::class, 'sendTest'])->name('admin.newsletter-editions.send-test');
    Route::post('/newsletter-editions/{newsletter_edition}/send', [NewsletterEditionController::class, 'send'])->name('admin.newsletter-editions.send');
    
    // Vacancy Management - accessible by admin and manager
    Route::resource('vacancies', VacancyController::class)->names([
        'index' => 'admin.vacancies.index',
        'create' => 'admin.vacancies.create',
        'store' => 'admin.vacancies.store',
        'show' => 'admin.vacancies.show',
        'edit' => 'admin.vacancies.edit',
        'update' => 'admin.vacancies.update',
        'destroy' => 'admin.vacancies.destroy',
    ]);
    
    // Event Management - accessible by admin and manager
    Route::resource('events', EventController::class)->names([
        'index' => 'admin.events.index',
        'create' => 'admin.events.create',
        'store' => 'admin.events.store',
        'show' => 'admin.events.show',
        'edit' => 'admin.events.edit',
        'update' => 'admin.events.update',
        'destroy' => 'admin.events.destroy',
    ]);

    // Contact Management - accessible by admin and manager
    Route::resource('contacts', ContactController::class)->names([
        'index' => 'admin.contacts.index',
        'show' => 'admin.contacts.show',
        'update' => 'admin.contacts.update',
    ]);
    Route::post('/contacts/{contact}/mark-replied', [ContactController::class, 'markAsReplied'])->name('admin.contacts.mark-replied');
    Route::get('/contacts/stats', [ContactController::class, 'stats'])->name('admin.contacts.stats');
}); 