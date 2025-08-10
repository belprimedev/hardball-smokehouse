<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservationCancelled extends Notification
{
    use Queueable;

    protected $reservation;
    protected $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation, string $reason = null)
    {
        $this->reservation = $reservation;
        $this->reason = $reason;
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
        
        $message = (new MailMessage)
            ->from('noreply@mail.hardballsmokehouse.co.uk', 'Hardball Caribbean Smokehouse')
            ->subject('❌ Reservation Cancelled - Hardball Caribbean Smokehouse')
            ->greeting('Hello ' . $reservation->customer_name . '!')
            ->line('❌ Your reservation has been cancelled.')
            ->line('')
            ->line('📅 Cancelled Reservation Details:')
            ->line('🗓️ Date: ' . $reservation->formattedDate)
            ->line('🕐 Time: ' . $reservation->reservation_time)
            ->line('👥 Number of Guests: ' . $reservation->number_of_guest);
            
        if ($this->reason) {
            $message->line('📝 Reason: ' . $this->reason);
        }
        
        $message->line('')
            ->line('😔 We\'re sorry to see you go!')
            ->line('')
            ->action('🔄 Make New Reservation', url('/make-reservation'))
            ->line('')
            ->line('📞 If you have any questions, please contact us at +44 01473 807117.')
            ->line('')
            ->line('🌴 We hope to see you again soon!')
            ->salutation('Best regards, Hardball Caribbean Smokehouse Team');
            
        return $message;
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
            'cancellation_reason' => $this->reason,
        ];
    }
} 