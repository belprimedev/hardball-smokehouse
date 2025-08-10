<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class NewReservationCreated extends Notification
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
            ->subject('🎉 Reservation Confirmed - Hardball Caribbean Smokehouse')
            ->view('emails.reservation-confirmation', [
                'reservation' => $reservation
            ])
            ->with([
                'reservation' => $reservation
            ]);
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