<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationCapacityTest extends TestCase
{
    use RefreshDatabase;

    public function test_reservation_can_be_created_when_under_capacity()
    {
        // Create 19 reservations for the same date and time
        Reservation::factory()->count(19)->create([
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
        ]);

        $response = $this->post('/reservation/public', [
            'customer_name' => 'John Doe',
            'customer_phone' => '1234567890',
            'customer_email' => 'john@example.com',
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
            'number_of_guest' => 1,
            'special_request' => 'Test reservation',
        ]);

        $response->assertRedirect(route('reservation.index'));
        $this->assertDatabaseHas('reservations', [
            'customer_name' => 'John Doe',
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
        ]);
    }

    public function test_reservation_cannot_be_created_when_at_capacity()
    {
        // Create 20 reservations for the same date and time
        Reservation::factory()->count(20)->create([
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
        ]);

        $response = $this->post('/reservation/public', [
            'customer_name' => 'John Doe',
            'customer_phone' => '1234567890',
            'customer_email' => 'john@example.com',
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
            'number_of_guest' => 1,
            'special_request' => 'Test reservation',
        ]);

        $response->assertSessionHasErrors(['reservation_time']);
        $this->assertDatabaseMissing('reservations', [
            'customer_name' => 'John Doe',
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
        ]);
    }

    public function test_availability_api_returns_correct_information()
    {
        // Create 15 reservations for the same date and time
        Reservation::factory()->count(15)->create([
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
        ]);

        $response = $this->get('/api/reservations/check-availability?date=2025-07-21&time=20:00:00');

        $response->assertStatus(200);
        $response->assertJson([
            'available' => true,
            'current_count' => 15,
            'max_capacity' => 20,
        ]);
    }

    public function test_availability_api_returns_full_when_at_capacity()
    {
        // Create 20 reservations for the same date and time
        Reservation::factory()->count(20)->create([
            'reservation_date' => '2025-07-21',
            'reservation_time' => '20:00:00',
        ]);

        $response = $this->get('/api/reservations/check-availability?date=2025-07-21&time=20:00:00');

        $response->assertStatus(200);
        $response->assertJson([
            'available' => false,
            'current_count' => 20,
            'max_capacity' => 20,
        ]);
    }
} 