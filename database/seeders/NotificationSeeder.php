<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some sample notifications
        Notification::create([
            'type' => 'reservation',
            'title' => 'New Reservation',
            'message' => 'New reservation from John Doe for 4 guests',
            'data' => [
                'reservation_id' => 1,
                'customer_name' => 'John Doe',
                'customer_email' => 'john@example.com',
                'customer_phone' => '+44 123 456 7890',
                'reservation_date' => '2025-07-25',
                'reservation_time' => '19:00:00',
                'number_of_guest' => 4,
                'special_request' => 'Window seat preferred',
            ],
            'is_read' => false,
        ]);

        Notification::create([
            'type' => 'reservation',
            'title' => 'New Reservation',
            'message' => 'New reservation from Jane Smith for 2 guests',
            'data' => [
                'reservation_id' => 2,
                'customer_name' => 'Jane Smith',
                'customer_email' => 'jane@example.com',
                'customer_phone' => '+44 987 654 3210',
                'reservation_date' => '2025-07-26',
                'reservation_time' => '20:00:00',
                'number_of_guest' => 2,
                'special_request' => null,
            ],
            'is_read' => true,
        ]);

        Notification::create([
            'type' => 'system',
            'title' => 'System Update',
            'message' => 'Notification system has been successfully implemented',
            'data' => [
                'update_type' => 'feature',
                'version' => '1.0.0',
            ],
            'is_read' => false,
        ]);
    }
}
