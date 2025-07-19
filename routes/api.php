use App\Models\MenuItem;
use App\Models\Reservation;
use Illuminate\Http\Request;

Route::get('/dessert-items', function () {
    return MenuItem::whereHas('category', function ($q) {
        $q->where('name', 'Dessert');
    })->get();
});

Route::get('/reservations/check-availability', [App\Http\Controllers\ReservationController::class, 'checkAvailability']); 