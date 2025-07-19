<?php

namespace Database\Factories;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->name(),
            'customer_email' => $this->faker->email(),
            'customer_phone' => $this->faker->phoneNumber(),
            'reservation_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'reservation_time' => $this->faker->randomElement([
                '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00', '18:00:00',
                '19:00:00', '20:00:00', '21:00:00', '22:00:00'
            ]),
            'number_of_guest' => $this->faker->numberBetween(1, 8),
            'special_request' => $this->faker->optional()->sentence(),
        ];
    }
} 