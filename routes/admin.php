<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SystemHealthController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // System Health Routes
    Route::get('/system-health', [SystemHealthController::class, 'index'])->name('system-health');
    Route::get('/system-health/emails', [SystemHealthController::class, 'emails'])->name('system-health.emails');
    Route::get('/system-health/jobs', [SystemHealthController::class, 'jobs'])->name('system-health.jobs');
    Route::get('/system-health/errors', [SystemHealthController::class, 'errors'])->name('system-health.errors');
    Route::get('/system-health/reservations', [SystemHealthController::class, 'reservations'])->name('system-health.reservations');
    Route::get('/system-health/logs', [SystemHealthController::class, 'logs'])->name('system-health.logs');
    
    // Retry failed jobs
    Route::post('/system-health/retry-job/{id}', [SystemHealthController::class, 'retryJob'])->name('system-health.retry-job');
    
    // Download logs
    Route::get('/system-health/download-logs', [SystemHealthController::class, 'downloadLogs'])->name('system-health.download-logs');
    
    // Admin Vacancy Management Routes
    Route::get('/vacancies', [VacancyController::class, 'index'])->name('vacancies.index');
    Route::get('/vacancies/create', [VacancyController::class, 'create'])->name('vacancies.create');
    Route::post('/vacancies', [VacancyController::class, 'store'])->name('vacancies.store');
    Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show'])->name('vacancies.show');
    Route::get('/vacancies/{vacancy}/edit', [VacancyController::class, 'edit'])->name('vacancies.edit');
    Route::put('/vacancies/{vacancy}', [VacancyController::class, 'update'])->name('vacancies.update');
    Route::delete('/vacancies/{vacancy}', [VacancyController::class, 'destroy'])->name('vacancies.destroy');
}); 