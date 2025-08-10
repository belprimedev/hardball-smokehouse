<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use App\Models\ReservationSetting;
use App\Events\NewReservationCreated as NewReservationCreatedEvent;
use App\Models\Notification;
use App\Notifications\NewReservationCreated;
use App\Notifications\ReservationCancelled;
use App\Notifications\SystemAlert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage reservations')->except(['store', 'checkAvailability']);
    }
    
public function index()
{
    $reservation = Reservation::latest('created_at')
        ->paginate(8)
        ->through(function ($reservations) {
            return [
                'id' => $reservations->id,
                'customer_name' => $reservations->customer_name,
                'customer_phone' => $reservations->customer_phone,
                'customer_email' => $reservations->customer_email,
                'reservation_date' => $reservations->reservation_date->format('Y-m-d'), // ✅ Format date
                'reservation_time' => \Carbon\Carbon::parse($reservations->reservation_time)->format('h:i A'), // ✅ Format time
                'number_of_guest' => $reservations->number_of_guest,
                'special_request' => $reservations->special_request,
            ];
        });

        return Inertia::render('Reservation/Index', [
            'reservations' => $reservation,
        ]);
    }





    public function create()
    {
        return Inertia::render('Reservation/Create'); 
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_phone'    => 'required|string|max:20',
            'customer_email'    => 'nullable|string|max:255',
            'reservation_date'  => 'required|date|after_or_equal:today',
            'reservation_time'  => 'required',
            'number_of_guest'   => 'required|integer|min:1',
            'special_request'   => 'nullable|string',
        ]);

        // Get the day of week for the reservation date
        $dayOfWeek = strtolower(date('l', strtotime($validated['reservation_date'])));
        
        // Get settings for this day
        $settings = \App\Models\ReservationSetting::where('day_of_week', $dayOfWeek)->first();
        
        if (!$settings || !$settings->is_open) {
            return back()->withErrors([
                'reservation_date' => 'The restaurant is closed on this day.'
            ])->withInput();
        }
        
        // Check if the time is within operating hours
        $reservationTime = $validated['reservation_time'];
        if ($reservationTime < $settings->opening_time || $reservationTime >= $settings->closing_time) {
            return back()->withErrors([
                'reservation_time' => 'This time is outside of operating hours.'
            ])->withInput();
        }
        
        // Check capacity limit for the specific date and time
        $existingReservations = Reservation::where('reservation_date', $validated['reservation_date'])
            ->where('reservation_time', $validated['reservation_time'])
            ->count();

        if ($existingReservations >= $settings->max_capacity_per_hour) {
            return back()->withErrors([
                'reservation_time' => 'This time slot is full. Please select a different time or date.'
            ])->withInput();
        }

        $reservation = Reservation::create($validated);

        // Create notification
        Notification::create([
            'type' => 'reservation',
            'title' => 'New Reservation',
            'message' => "New reservation from {$reservation->customer_name} for {$reservation->number_of_guest} guests",
            'data' => [
                'reservation_id' => $reservation->id,
                'customer_name' => $reservation->customer_name,
                'customer_email' => $reservation->customer_email,
                'customer_phone' => $reservation->customer_phone,
                'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
                'reservation_time' => $reservation->reservation_time,
                'number_of_guest' => $reservation->number_of_guest,
                'special_request' => $reservation->special_request,
            ],
        ]);

        // Send email notification to customer if email is provided
        if ($reservation->customer_email) {
            try {
                // Send notification to the reservation model
                $reservation->notify(new NewReservationCreated($reservation));
            } catch (\Exception $e) {
                // Log the error but don't fail the reservation creation
                Log::error('Failed to send reservation email: ' . $e->getMessage());
            }
        }

        // Send system alert to admin email
        $adminEmail = 'info@hardballsmokehouse.co.uk';
        
        $testUser = new User();
        $testUser->email = $adminEmail;
        $testUser->name = 'Hardball Admin';
        $testUser->notify(new SystemAlert(
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

        // Fire event for real-time notifications
        event(new NewReservationCreatedEvent($reservation));

        // Check if this is a public submission (from the public reservation form)
        if ($request->route()->getName() === 'reservation.store.public') {
            // Redirect back to the public reservation page with success message
            return redirect()->route('make-reservation')->with('success', 'Thank you for your reservation! We have received your booking request and will contact you shortly to confirm your table. Please check your email for a confirmation message.');
        }

        // Admin submission - redirect to admin reservation page
        return redirect()->route('reservation.index')->with('success', 'Reservation created successfully!');
    }

    public function show($id)
    {
        $reservation = Reservation::findOrFail($id);
        return Inertia::render('Reservation/Show', [
            'reservation' => $reservation
        ]);
    }

   
    public function edit(Reservation $reservation)
{
    return Inertia::render('Reservation/Edit', [
        'reservation' => $reservation,
    ]);
}


    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'customer_phone'    => 'required|string|max:20',
            'customer_email'    => 'required|email|max:255',
            'reservation_date'  => 'required|date',
            'reservation_time'  => 'required',
            'number_of_guest'   => 'required|integer|min:1',
            'special_request'   => 'nullable|string',
        ]);

        $reservation->update($validated);

        return redirect()->route('reservation.index')->with('success', 'Reservation updated successfully.');
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        // Send cancellation notification to customer if email is provided
        if ($reservation->customer_email) {
            try {
                $reservation->notify(new ReservationCancelled($reservation, 'Cancelled by administrator'));
            } catch (\Exception $e) {
                Log::error('Failed to send cancellation email: ' . $e->getMessage());
            }
        }

        // Send system alert to admin email
        $adminEmail = 'info@hardballsmokehouse.co.uk';
        
        $testUser = new User();
        $testUser->email = $adminEmail;
        $testUser->name = 'Hardball Admin';
        $testUser->notify(new SystemAlert(
            'Reservation Cancelled',
            "Reservation from {$reservation->customer_name} for {$reservation->number_of_guest} guests on {$reservation->reservation_date->format('M d, Y')} at {$reservation->reservation_time} has been cancelled",
            'warning',
            [
                'reservation_id' => $reservation->id,
                'customer_name' => $reservation->customer_name,
                'customer_email' => $reservation->customer_email,
                'reservation_date' => $reservation->reservation_date->format('Y-m-d'),
                'reservation_time' => $reservation->reservation_time,
            ]
        ));

        $reservation->delete();

        return redirect()->route('reservation.index')->with('success', 'Reservation deleted successfully');
    }

    public function checkAvailability(Request $request)
    {
        $date = $request->query('date');
        $time = $request->query('time');
        
        if (!$date || !$time) {
            return response()->json(['error' => 'Date and time are required'], 400);
        }
        
        // Get the day of week for the reservation date
        $dayOfWeek = strtolower(date('l', strtotime($date)));
        
        // Get settings for this day
        $settings = ReservationSetting::where('day_of_week', $dayOfWeek)->first();
        
        if (!$settings || !$settings->is_open) {
            return response()->json([
                'available' => false,
                'current_count' => 0,
                'max_capacity' => 0,
                'message' => 'Restaurant is closed on this day'
            ]);
        }
        
        // Check if the time is within operating hours
        if ($time < $settings->opening_time || $time >= $settings->closing_time) {
            return response()->json([
                'available' => false,
                'current_count' => 0,
                'max_capacity' => 0,
                'message' => 'Time is outside of operating hours'
            ]);
        }
        
        $existingReservations = Reservation::where('reservation_date', $date)
            ->where('reservation_time', $time)
            ->count();
        
        return response()->json([
            'available' => $existingReservations < $settings->max_capacity_per_hour,
            'current_count' => $existingReservations,
            'max_capacity' => $settings->max_capacity_per_hour
        ]);
    }

}
