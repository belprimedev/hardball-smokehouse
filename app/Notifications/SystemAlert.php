<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemAlert extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $type;
    protected $data;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $message, string $type = 'info', array $data = [])
    {
        $this->title = $title;
        $this->message = $message;
        $this->type = $type;
        $this->data = $data;
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
        return (new MailMessage)
            ->from('noreply@mail.hardballsmokehouse.co.uk', 'Hardball Caribbean Smokehouse')
            ->subject('🔔 ' . $this->title . ' - Hardball Caribbean Smokehouse')
            ->view('emails.system-alert', [
                'title' => $this->title,
                'alertMessage' => $this->message,
                'type' => $this->type,
                'data' => $this->data
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
            'title' => $this->title,
            'message' => $this->message,
            'type' => $this->type,
            'data' => $this->data,
        ];
    }
} 