<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MenuController;
use App\Models\MenuItem;

Route::get('/', function () {
    $dessertItems = MenuItem::whereHas('category', function ($q) {
        $q->where('name', 'Dessert');
    })->get();
    
    $menuItems = MenuItem::whereHas('category', function ($q) {
        $q->whereIn('name', ['Starters', 'Jerk Dishes', 'Curry Dishes', 'Meals']);
    })->with('category')->get();
    
    return Inertia::render('Welcome', [
        'dessertItems' => $dessertItems,
        'menuItems' => $menuItems,
    ]);
})->name('home');

Route::get('/make-reservation', function () {
    return Inertia::render('OnlineReservation');
})->name('make-reservation');

// Public reservation submission route
Route::post('/reservation/public', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservation.store.public');

// Public availability check route
Route::get('/api/reservations/check-availability', [App\Http\Controllers\ReservationController::class, 'checkAvailability']);

// Public reservation settings API route
Route::get('/api/reservation-settings', [App\Http\Controllers\ReservationSettingController::class, 'apiIndex']);

// Public general settings API route
Route::get('/api/general-settings', [App\Http\Controllers\GeneralSettingController::class, 'apiIndex']);

Route::get('dashboard', function () {
    // Get statistics for dashboard
    $totalMenuItems = \App\Models\MenuItem::count();
    $totalCategories = \App\Models\MenuCategory::count();
    $totalUsers = \App\Models\User::count();
    $totalReservations = \App\Models\Reservation::count();
    
    // Get featured and chef special items
    $featuredItems = \App\Models\MenuItem::where('is_featured', true)
        ->where('is_visible', true)
        ->where('is_available', true)
        ->with('category')
        ->limit(5)
        ->get();
    
    $chefSpecialItems = \App\Models\MenuItem::where('is_chef_special', true)
        ->where('is_visible', true)
        ->where('is_available', true)
        ->with('category')
        ->limit(5)
        ->get();
    
    // Get recent reservations
    $recentReservations = \App\Models\Reservation::latest()
        ->limit(10)
        ->get();
    
    // Get menu items by category for chart
    $menuItemsByCategory = \App\Models\MenuCategory::withCount('menuItems')
        ->get();
    
    // Get reservations by date (last 7 days)
    $reservationsByDate = \App\Models\Reservation::selectRaw('DATE(reservation_date) as date, COUNT(*) as count')
        ->where('reservation_date', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    // Get top categories by item count
    $topCategories = \App\Models\MenuCategory::withCount('menuItems')
        ->orderBy('menu_items_count', 'desc')
        ->limit(5)
        ->get();
    
    return Inertia::render('Dashboard', [
        'stats' => [
            'totalMenuItems' => $totalMenuItems,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'totalReservations' => $totalReservations,
        ],
        'featuredItems' => $featuredItems,
        'chefSpecialItems' => $chefSpecialItems,
        'recentReservations' => $recentReservations,
        'menuItemsByCategory' => $menuItemsByCategory,
        'reservationsByDate' => $reservationsByDate,
        'topCategories' => $topCategories,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/menu', function () {
    return Inertia::render('Menu', [
        'menuCategories' => \App\Models\MenuCategory::all(),
        'menuItems' => \App\Models\MenuItem::all(),
    ]);
})->name('menu');

Route::get('/api/featured-menu-items', [MenuController::class, 'getFeaturedItems']);
Route::get('/api/chef-special-items', [MenuController::class, 'getChefSpecialItems']);
Route::get('/api/menu-items', [MenuController::class, 'getAllMenuItems']);

Route::get('/cocktail', function () {
    return Inertia::render('Cocktail');
})->name('cocktail');

Route::get('/events', function () {
    return Inertia::render('Events');
})->name('events');

Route::get('/gallery', function () {
    return Inertia::render('Gallery');
})->name('gallery');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/faq', function () {
    return Inertia::render('FAQ');
})->name('faq');

Route::get('/vacancies', function () {
    return Inertia::render('Vacancies');
})->name('vacancies');

Route::get('/terms', function () {
    return Inertia::render('Terms');
})->name('terms');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
