<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    
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
            'reservation_date'  => 'required|date',
            'reservation_time'  => 'required',
            'number_of_guest'   => 'required|integer|min:1',
            'special_request'   => 'nullable|string',
        ]);

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

}
