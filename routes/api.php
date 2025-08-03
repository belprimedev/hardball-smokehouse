use App\Models\MenuItem;
use App\Models\Reservation;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;

Route::get('/dessert-items', function () {
    return MenuItem::whereHas('category', function ($q) {
        $q->where('name', 'Dessert');
    })->get();
});

Route::get('/reservations/check-availability', [App\Http\Controllers\ReservationController::class, 'checkAvailability']); 

// Notification routes
Route::get('/notifications', [NotificationController::class, 'index']);
Route::get('/notifications/unread', [NotificationController::class, 'unread']);
Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead']);
Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
Route::post('/notifications', [NotificationController::class, 'store']);