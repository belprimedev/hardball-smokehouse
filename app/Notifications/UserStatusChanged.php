<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class UserStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $oldStatus;
    protected $newStatus;
    protected $reason;
    protected $adminUser;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, string $oldStatus, string $newStatus, string $reason = null, User $adminUser = null)
    {
        $this->user = $user;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->reason = $reason;
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
        
        $message = (new MailMessage)
            ->subject('Account Status Update - Hardball Caribbean Smokehouse')
            ->greeting('Hello ' . $this->user->name . '!')
            ->line('Your account status has been updated.')
            ->line('Status Change:')
            ->line('• Previous Status: ' . ucfirst($this->oldStatus))
            ->line('• New Status: ' . ucfirst($this->newStatus))
            ->line('• Updated by: ' . $adminName);
            
        if ($this->reason) {
            $message->line('• Reason: ' . $this->reason);
        }
        
        switch ($this->newStatus) {
            case 'active':
                $message->line('Your account is now active and you can access the system.')
                    ->action('Access Dashboard', url('/dashboard'));
                break;
            case 'suspended':
                $message->line('Your account has been suspended. You will not be able to access the system until it is reactivated.')
                    ->line('If you believe this is an error, please contact the administrator.');
                break;
            case 'disabled':
                $message->line('Your account has been disabled. You will not be able to access the system.')
                    ->line('If you believe this is an error, please contact the administrator.');
                break;
        }
        
        $message->line('If you have any questions, please contact the administrator.')
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
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'reason' => $this->reason,
            'admin_name' => $this->adminUser ? $this->adminUser->name : 'System',
        ];
    }
} 