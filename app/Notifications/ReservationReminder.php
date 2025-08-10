<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reservation = $this->reservation;
        
        return (new MailMessage)
            ->from('noreply@mail.hardballsmokehouse.co.uk', 'Hardball Caribbean Smokehouse')
            ->subject('⏰ Reservation Reminder - Hardball Caribbean Smokehouse')
            ->greeting('Hello ' . $reservation->customer_name . '!')
            ->line('⏰ This is a friendly reminder about your upcoming reservation!')
            ->line('')
            ->line('📅 Reservation Details:')
            ->line('🗓️ Date: ' . $reservation->formattedDate)
            ->line('🕐 Time: ' . $reservation->reservation_time)
            ->line('👥 Number of Guests: ' . $reservation->number_of_guest)
            ->line('📍 Location: Hardball Caribbean Smokehouse, Ipswich')
            ->line('')
            ->line('🎵 Live Music & Caribbean Vibes')
            ->line('🍖 Authentic Southern BBQ')
            ->line('🍹 Craft Cocktails')
            ->line('')
            ->action('📋 View Reservation Details', url('/reservations/' . $reservation->id))
            ->line('')
            ->line('🎉 We look forward to seeing you!')
            ->line('If you need to modify or cancel your reservation, please contact us at +44 01473 807117 as soon as possible.')
            ->line('')
            ->line('🌴 Come for the food, stay for the vibes!')
            ->salutation('Best regards, Hardball Caribbean Smokehouse Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'reservation_id' => $this->reservation->id,
            'customer_name' => $this->reservation->customer_name,
            'customer_email' => $this->reservation->customer_email,
            'reservation_date' => $this->reservation->reservation_date,
            'reservation_time' => $this->reservation->reservation_time,
            'number_of_guest' => $this->reservation->number_of_guest,
        ];
    }
} 