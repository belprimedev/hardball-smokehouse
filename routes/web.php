<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\MenuController;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\ReservationSettingController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\NewsletterController;

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
    return Inertia::render('OnlineReservation', [
        'success' => session('success'),
        'error' => session('error'),
    ]);
})->name('make-reservation');

// Public reservation submission route
Route::post('/reservation/public', [App\Http\Controllers\ReservationController::class, 'store'])->name('reservation.store.public');

// Public availability check route
Route::get('/api/reservations/check-availability', [App\Http\Controllers\ReservationController::class, 'checkAvailability']);

// Public reservation settings API route
Route::get('/api/reservation-settings', [App\Http\Controllers\ReservationSettingController::class, 'apiIndex']);

// Public general settings API route
Route::get('/api/general-settings', [App\Http\Controllers\GeneralSettingController::class, 'apiIndex']);

// Notification routes
Route::get('/api/notifications', [App\Http\Controllers\NotificationController::class, 'index']);
Route::get('/api/notifications/unread', [App\Http\Controllers\NotificationController::class, 'unread']);
Route::post('/api/notifications/mark-as-read', [App\Http\Controllers\NotificationController::class, 'markAsRead']);
Route::post('/api/notifications/mark-all-as-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead']);
Route::post('/api/notifications', [App\Http\Controllers\NotificationController::class, 'store']);

// Test email route (remove in production)
Route::get('/test-email', function () {
    try {
        // Check if Resend is configured
        if (!env('RESEND_API_KEY')) {
            return 'Error: RESEND_API_KEY not configured in .env file';
        }
        
        // Test email configuration
        \Illuminate\Support\Facades\Mail::raw('Test email from Hardball Caribbean Smokehouse', function ($message) {
            $message->to('info@hardballsmokehouse.co.uk') // Admin email
                    ->subject('Test Email - Hardball Caribbean Smokehouse');
        });
        
        return 'Test email sent successfully! Check your Resend dashboard for delivery status.';
    } catch (\Exception $e) {
        return 'Failed to send test email: ' . $e->getMessage() . '<br><br>Stack trace: ' . $e->getTraceAsString();
    }
});

// Test notification route (remove in production)
Route::get('/test-notification', function () {
    try {
        $user = \App\Models\User::first();
        if (!$user) {
            return 'No users found in database. Please create a user first.';
        }
        
        $user->notify(new \App\Notifications\SystemAlert(
            'Test System Alert',
            'This is a test system alert to verify email notifications are working.',
            'info',
            ['test' => 'data']
        ));
        
        return 'Test notification sent successfully to ' . $user->email . '!';
    } catch (\Exception $e) {
        return 'Failed to send test notification: ' . $e->getMessage() . '<br><br>Stack trace: ' . $e->getTraceAsString();
    }
});

// Test reservation creation route (remove in production)
Route::get('/test-reservation', function () {
    try {
        // Create a test reservation
        $reservation = new \App\Models\Reservation([
            'customer_name' => 'Test Customer',
            'customer_email' => 'info@hardballsmokehouse.co.uk',
            'customer_phone' => '1234567890',
            'reservation_date' => '2025-08-02',
            'reservation_time' => '19:00',
            'number_of_guest' => 2,
            'special_request' => 'Test reservation'
        ]);
        
        // Save the reservation
        $reservation->save();
        
        // Send customer notification
        if ($reservation->customer_email) {
            try {
                $notification = new \App\Notifications\NewReservationCreated($reservation);
                $notification->toMail((object) ['email' => $reservation->customer_email]);
            } catch (\Exception $e) {
                // Log error but don't fail
            }
        }
        
        // Send admin notification
        $testUser = new \App\Models\User();
        $testUser->email = 'info@hardballsmokehouse.co.uk';
        $testUser->name = 'Hardball Admin';
        $testUser->notify(new \App\Notifications\SystemAlert(
            'New Reservation Created',
            "New reservation from {$reservation->customer_name} for {$reservation->number_of_guest} guests on {$reservation->reservation_date->format('M d, Y')} at {$reservation->reservation_time}",
            'info',
            [
                'reservation_id' => $reservation->id,
                'customer_name' => $reservation->customer_name,
                'customer_email' => $reservation->customer_email,
                'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
                'reservation_time' => $reservation->reservation_time,
            ]
        ));
        
        return 'Test reservation created successfully! Check your email for notifications.';
    } catch (\Exception $e) {
        return 'Failed to create test reservation: ' . $e->getMessage() . '<br><br>Stack trace: ' . $e->getTraceAsString();
    }
});

// Queue monitor route (remove in production)
Route::get('/queue-status', function () {
    $stats = [
        'jobs_in_queue' => \Illuminate\Support\Facades\DB::table('jobs')->count(),
        'failed_jobs' => \Illuminate\Support\Facades\DB::table('failed_jobs')->count(),
        'recent_jobs' => \Illuminate\Support\Facades\DB::table('jobs')
            ->latest('created_at')
            ->limit(5)
            ->get(['id', 'queue', 'attempts', 'created_at']),
        'recent_failed' => \Illuminate\Support\Facades\DB::table('failed_jobs')
            ->latest('failed_at')
            ->limit(5)
            ->get(['id', 'queue', 'failed_at', 'exception'])
    ];
    
    return response()->json($stats);
});

// Email diagnostic route (remove in production)
Route::get('/email-diagnostic', function () {
    $diagnostics = [];
    
    // Check environment
    $diagnostics['environment'] = [
        'app_env' => env('APP_ENV'),
        'app_debug' => env('APP_DEBUG'),
        'app_url' => env('APP_URL'),
    ];
    
    // Check mail configuration
    $diagnostics['mail'] = [
        'mail_mailer' => env('MAIL_MAILER'),
        'mail_from_address' => env('MAIL_FROM_ADDRESS'),
        'mail_from_name' => env('MAIL_FROM_NAME'),
        'resend_api_key' => env('RESEND_API_KEY') ? 'Configured' : 'Not configured',
    ];
    
    // Check queue configuration
    $diagnostics['queue'] = [
        'queue_connection' => env('QUEUE_CONNECTION'),
        'queue_default' => config('queue.default'),
        'jobs_table_exists' => \Illuminate\Support\Facades\Schema::hasTable('jobs'),
        'failed_jobs_table_exists' => \Illuminate\Support\Facades\Schema::hasTable('failed_jobs'),
    ];
    
    // Check queue status
    $diagnostics['queue_status'] = [
        'jobs_in_queue' => \Illuminate\Support\Facades\DB::table('jobs')->count(),
        'failed_jobs' => \Illuminate\Support\Facades\DB::table('failed_jobs')->count(),
        'recent_failed_jobs' => \Illuminate\Support\Facades\DB::table('failed_jobs')
            ->latest('failed_at')
            ->limit(3)
            ->get(['id', 'queue', 'failed_at', 'exception'])
    ];
    
    // Test Resend API
    try {
        if (env('RESEND_API_KEY')) {
            // Skip Resend API test for now to avoid linter issues
            $diagnostics['resend'] = [
                'api_key_valid' => 'API key configured',
                'note' => 'Resend API test skipped in diagnostic'
            ];
        } else {
            $diagnostics['resend'] = [
                'api_key_valid' => false,
                'error' => 'No API key configured'
            ];
        }
    } catch (\Exception $e) {
        $diagnostics['resend'] = [
            'api_key_valid' => false,
            'error' => $e->getMessage()
        ];
    }
    
    // Test direct email sending (synchronous)
    try {
        \Illuminate\Support\Facades\Mail::raw('Test email from diagnostic route', function ($message) {
            $message->to('info@hardballsmokehouse.co.uk')
                    ->subject('Email Diagnostic Test - ' . now()->format('Y-m-d H:i:s'));
        });
        $diagnostics['direct_email_test'] = 'Success';
    } catch (\Exception $e) {
        $diagnostics['direct_email_test'] = 'Failed: ' . $e->getMessage();
    }
    
    return response()->json($diagnostics, 200, [], JSON_PRETTY_PRINT);
});

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
    
    // Get recent notifications
    $recentNotifications = \App\Models\Notification::latest()
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
        'recentNotifications' => $recentNotifications,
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
Route::get('/api/menu-categories', function () {
    return MenuCategory::withCount('menuItems')->get();
});

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

Route::get('/terms', function () {
    return Inertia::render('Terms');
})->name('terms');

Route::get('/privacy', function () {
    return Inertia::render('Privacy');
})->name('privacy');

Route::get('/cursor-test', function () {
    return Inertia::render('CursorTest');
})->name('cursor-test');

// Test email template route (remove in production)
Route::get('/test-email-template', function () {
    $reservation = \App\Models\Reservation::latest()->first();
    return view('emails.reservation-confirmation', ['reservation' => $reservation]);
});

// Vacancy routes
Route::get('/vacancy', [App\Http\Controllers\VacancyController::class, 'index'])->name('vacancy.index');
Route::get('/vacancy/create', [App\Http\Controllers\VacancyController::class, 'create'])->name('vacancy.create');
Route::post('/vacancy', [App\Http\Controllers\VacancyController::class, 'store'])->name('vacancy.store');
Route::get('/vacancy/{vacancy}', [App\Http\Controllers\VacancyController::class, 'show'])->name('vacancy.show');
Route::get('/vacancy/{vacancy}/edit', [App\Http\Controllers\VacancyController::class, 'edit'])->name('vacancy.edit');
Route::put('/vacancy/{vacancy}', [App\Http\Controllers\VacancyController::class, 'update'])->name('vacancy.update');
Route::delete('/vacancy/{vacancy}', [App\Http\Controllers\VacancyController::class, 'destroy'])->name('vacancy.destroy');

// Newsletter API routes for frontend
Route::post('/api/newsletters/subscribe', [NewsletterController::class, 'subscribe']);
Route::post('/api/newsletters/unsubscribe', [NewsletterController::class, 'unsubscribe']);

// Contact form submission route
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Test contact route (temporary, remove in production)
Route::post('/test-contact', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string|max:5000',
    ]);
    
    $contact = \App\Models\Contact::create($validated);
    
    return response()->json([
        'success' => true,
        'message' => 'Contact created successfully!',
        'contact_id' => $contact->id
    ]);
})->name('test.contact');

// Mail preview route for testing email templates
Route::get('/mail-preview', function () {
    $reservation = new \App\Models\Reservation([
        'customer_name' => 'John Doe',
        'customer_email' => 'john@example.com',
        'customer_phone' => '1234567890',
        'reservation_date' => '2025-08-25',
        'reservation_time' => '19:00',
        'number_of_guest' => 4,
        'special_request' => 'Test reservation for email template'
    ]);

    return view('emails.system-alert', [
        'title' => 'TEST - New Blue Design',
        'alertMessage' => 'This is a test to see the NEW blue/teal email template design. The old template was brown/orange, this should be blue/teal with modern styling!',
        'type' => 'info',
        'data' => [
            'reservation_id' => 9999,
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'reservation_date' => '2025-08-25',
            'reservation_time' => '19:00',
            'number_of_guests' => 4,
            'special_request' => 'Testing new email template design'
        ]
    ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/user-management.php';
// Admin routes with prefix
Route::prefix('admin')->group(function () {
    require __DIR__.'/admin.php';
});
