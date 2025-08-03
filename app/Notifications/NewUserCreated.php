<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class NewUserCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $adminUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, User $adminUser = null)
    {
        $this->user = $user;
        $this->adminUser = $adminUser;
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
        $adminName = $this->adminUser ? $this->adminUser->name : 'System Administrator';
        
        return (new MailMessage)
            ->subject('New User Account Created - Hardball Caribbean Smokehouse')
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('Your user account has been created successfully.')
            ->line('Account Details:')
            ->line('• Email: ' . $this->user->email)
            ->line('• Created by: ' . $adminName)
            ->line('• Status: ' . ucfirst($this->user->status))
            ->action('Access Dashboard', url('/dashboard'))
            ->line('You can now log in to the system using your email address.')
            ->line('If you have any questions, please contact the administrator.')
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
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'admin_name' => $this->adminUser ? $this->adminUser->name : 'System',
            'status' => $this->user->status,
        ];
    }
} 