<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use App\Models\ReservationSetting;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage reservations');
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

        Reservation::create($validated);

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
