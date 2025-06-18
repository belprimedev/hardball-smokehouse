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

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
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
