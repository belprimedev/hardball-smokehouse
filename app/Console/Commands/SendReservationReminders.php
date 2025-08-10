<?php

namespace App\Console\Commands;

use App\Models\Reservation;
use App\Notifications\ReservationReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendReservationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reservations:send-reminders {--hours=24 : Hours before reservation to send reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for upcoming reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $reminderTime = now()->addHours($hours);

        $reservations = Reservation::where('reservation_date', $reminderTime->toDateString())
            ->where('reservation_time', '>=', $reminderTime->format('H:i:s'))
            ->where('reservation_time', '<', $reminderTime->addHour()->format('H:i:s'))
            ->whereNotNull('customer_email')
            ->get();

        $this->info("Found {$reservations->count()} reservations to send reminders for.");

        $sentCount = 0;
        $failedCount = 0;

        foreach ($reservations as $reservation) {
            try {
                // Send notification to the reservation model
                $reservation->notify(new ReservationReminder($reservation));
                
                $sentCount++;
                $this->info("Sent reminder for reservation {$reservation->id} to {$reservation->customer_email}");
            } catch (\Exception $e) {
                $failedCount++;
                Log::error("Failed to send reminder for reservation {$reservation->id}: " . $e->getMessage());
                $this->error("Failed to send reminder for reservation {$reservation->id}: " . $e->getMessage());
            }
        }

        $this->info("Reminder sending completed. Sent: {$sentCount}, Failed: {$failedCount}");
    }
} 