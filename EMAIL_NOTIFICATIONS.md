# Email Notifications System - Hardball Caribbean Smokehouse

## Overview

This document outlines the email notification system implemented for the Hardball Caribbean Smokehouse restaurant management system. The system uses **Resend** as the email service provider and sends notifications for various important events.

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Email Configuration
MAIL_MAILER=resend
MAIL_FROM_ADDRESS=noreply@hardballsmokehouse.co.uk
MAIL_FROM_NAME="Hardball Caribbean Smokehouse"

# Resend Configuration
RESEND_API_KEY=your_resend_api_key_here
```

### Package Installation

The Resend Laravel package has been installed:

```bash
composer require resend/resend-laravel
php artisan vendor:publish --provider="Resend\Laravel\ResendServiceProvider"
```

### Resend Setup

1. **Create Resend Account**: Sign up at [resend.com](https://resend.com)
2. **Get API Key**: Copy your API key from the Resend dashboard
3. **Verify Domain**: Add and verify your domain in Resend
4. **Update Environment**: Add the API key to your `.env` file

## Email Notifications Implemented

### 1. New User Created
**Trigger**: When an admin creates a new user account
**Recipient**: New user
**Content**: Welcome message with account details and login instructions

**File**: `app/Notifications/NewUserCreated.php`

### 2. New Reservation Created
**Trigger**: When a customer makes a reservation
**Recipient**: Customer (if email provided) + Admin users
**Content**: Reservation confirmation with details

**File**: `app/Notifications/NewReservationCreated.php`

### 3. Reservation Reminder
**Trigger**: Scheduled reminder (24 hours before reservation)
**Recipient**: Customer
**Content**: Friendly reminder about upcoming reservation

**File**: `app/Notifications/ReservationReminder.php`

### 4. Reservation Cancelled
**Trigger**: When admin cancels a reservation
**Recipient**: Customer + Admin users
**Content**: Cancellation notification with reason

**File**: `app/Notifications/ReservationCancelled.php`

### 5. User Status Changed
**Trigger**: When admin changes user status (suspend/activate/disable)
**Recipient**: Affected user
**Content**: Status change notification with details

**File**: `app/Notifications/UserStatusChanged.php`

### 6. System Alert
**Trigger**: Various system events
**Recipient**: Admin users
**Content**: System notifications and alerts

**File**: `app/Notifications/SystemAlert.php`

## Implementation Details

### Notification Classes

All notification classes:
- Extend `Illuminate\Notifications\Notification`
- Implement `ShouldQueue` for better performance
- Use `Queueable` trait for queue management
- Include both `toMail()` and `toArray()` methods

### Email Templates

Laravel's built-in mail templates are used with customization:
- Professional branding with Hardball colors
- Responsive design
- Clear call-to-action buttons
- Consistent footer with contact information

### Queue System

Notifications are queued for better performance:
- Prevents blocking user interactions
- Handles email delivery in background
- Automatic retry on failure
- Logging for debugging

## Usage Examples

### Sending Notifications

```php
// Send to a user
$user->notify(new NewUserCreated($user, $adminUser));

// Send to customer (without user account)
$notification = new NewReservationCreated($reservation);
$notification->toMail((object) ['email' => $customerEmail]);

// Send system alert to admins
$adminUsers = User::whereHas('roles', function ($query) {
    $query->where('name', 'admin');
})->get();

foreach ($adminUsers as $admin) {
    $admin->notify(new SystemAlert(
        'System Alert Title',
        'Alert message here',
        'info',
        ['additional' => 'data']
    ));
}
```

### Testing Email System

1. **Test Route**: Visit `/test-email` to send a test email
2. **Command Line**: Use `php artisan tinker` to test notifications
3. **Queue Testing**: Check queue status with `php artisan queue:work`

## Scheduled Tasks

### Reservation Reminders

The system includes a command to send reservation reminders:

```bash
# Send reminders for reservations 24 hours from now
php artisan reservations:send-reminders

# Send reminders for reservations 12 hours from now
php artisan reservations:send-reminders --hours=12
```

**Recommended Cron Job**:
```bash
# Add to crontab - runs every hour
0 * * * * cd /path/to/your/app && php artisan reservations:send-reminders
```

## Error Handling

### Graceful Degradation

- Email failures don't block user actions
- Errors are logged for debugging
- Fallback to system notifications
- Queue retry mechanism

### Logging

All email errors are logged:
```php
Log::error('Failed to send reservation email: ' . $e->getMessage());
```

## Security Considerations

### Email Validation

- All email addresses are validated
- Rate limiting on email sending
- Spam protection through Resend
- Bounce handling

### Privacy

- Customer emails are only used for notifications
- No marketing emails without consent
- GDPR compliant data handling
- Easy unsubscribe mechanism

## Monitoring and Analytics

### Resend Dashboard

- Track email delivery rates
- Monitor bounce rates
- View email analytics
- Set up webhooks for events

### Application Logs

- Email sending logs
- Error tracking
- Performance monitoring
- Queue status

## Future Enhancements

### Planned Features

1. **Email Templates**: Custom HTML templates
2. **Email Preferences**: User notification settings
3. **Marketing Emails**: Promotional campaigns
4. **SMS Integration**: Text message notifications
5. **Advanced Scheduling**: More flexible reminder timing

### Technical Improvements

1. **Template Engine**: Blade templates for emails
2. **A/B Testing**: Email content optimization
3. **Analytics**: Detailed email tracking
4. **Webhooks**: Real-time email events
5. **Bulk Sending**: Mass email capabilities

## Troubleshooting

### Common Issues

1. **Emails Not Sending**
   - Check Resend API key
   - Verify domain verification
   - Check queue workers
   - Review error logs

2. **Queue Issues**
   - Start queue worker: `php artisan queue:work`
   - Check queue status: `php artisan queue:monitor`
   - Clear failed jobs: `php artisan queue:flush`

3. **Template Issues**
   - Clear view cache: `php artisan view:clear`
   - Check template syntax
   - Verify CSS rendering

### Debug Commands

```bash
# Test email configuration
php artisan tinker
Mail::raw('Test', function($m) { $m->to('test@example.com')->subject('Test'); });

# Check queue status
php artisan queue:work --once

# Clear all caches
php artisan optimize:clear

# Test routes (remove in production)
# Visit: /test-email
# Visit: /test-notification
```

## Production Checklist

- [ ] Resend API key configured
- [ ] Domain verified in Resend
- [ ] Queue workers running
- [ ] Cron jobs set up
- [ ] Error logging configured
- [ ] Email templates tested
- [ ] Rate limits configured
- [ ] Monitoring set up

## Support

For issues with the email system:
1. Check application logs
2. Verify Resend dashboard
3. Test with `/test-email` route
4. Review queue status
5. Contact system administrator

---

*Last updated: December 2024*
*System Version: 1.0.0* 