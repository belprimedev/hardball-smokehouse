# Notification Feature Implementation

## Overview
This notification system provides real-time notifications for new reservations and other system events. It includes both database storage and real-time broadcasting capabilities.

## Features

### 1. Database Notifications
- Store notifications in the database with read/unread status
- Support for different notification types (reservation, system, warning)
- JSON data storage for additional context
- Automatic timestamp tracking

### 2. Real-time Broadcasting
- Uses Pusher for real-time notifications
- Browser notifications support
- Automatic notification badge updates
- Real-time notification panel updates

### 3. Frontend Components
- Notification panel in the app header
- Dashboard notifications section
- Mark as read functionality
- Notification count badges

## Database Schema

### notifications table
```sql
- id (primary key)
- type (string) - notification type (reservation, system, warning)
- title (string) - notification title
- message (text) - notification message
- data (json) - additional data (reservation details, etc.)
- is_read (boolean) - read status
- read_at (timestamp) - when marked as read
- created_at (timestamp)
- updated_at (timestamp)
```

## API Endpoints

### GET /api/notifications
Get all notifications (limited to 50)

### GET /api/notifications/unread
Get unread notifications (limited to 10)

### POST /api/notifications/mark-as-read
Mark a specific notification as read
```json
{
  "notification_id": 1
}
```

### POST /api/notifications/mark-all-as-read
Mark all notifications as read

### POST /api/notifications
Create a new notification (for testing)
```json
{
  "type": "reservation",
  "title": "New Reservation",
  "message": "New reservation from John Doe",
  "data": {
    "reservation_id": 1,
    "customer_name": "John Doe"
  }
}
```

## Events

### NewReservationCreated
Fired when a new reservation is created. Automatically:
- Creates a notification in the database
- Broadcasts the event via Pusher
- Includes reservation details in the notification

## Frontend Components

### NotificationPanel.vue
- Real-time notification display
- Mark as read functionality
- Browser notification support
- Pusher integration for live updates

### Dashboard Notifications Section
- Displays recent notifications
- Shows unread count
- Reservation details display
- Time formatting

## Configuration

### Environment Variables
Add these to your `.env` file for Pusher integration:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

### Frontend Environment Variables
Add these to your `.env` file for the frontend:

```env
VITE_PUSHER_APP_KEY=your_app_key
VITE_PUSHER_APP_CLUSTER=mt1
```

## Usage

### Creating Notifications
Notifications are automatically created when:
- A new reservation is made (via ReservationController)

### Manual Notification Creation
```php
use App\Models\Notification;

Notification::create([
    'type' => 'reservation',
    'title' => 'New Reservation',
    'message' => 'New reservation from John Doe',
    'data' => [
        'reservation_id' => 1,
        'customer_name' => 'John Doe',
        // ... other data
    ],
]);
```

### Broadcasting Events
```php
use App\Events\NewReservationCreated;

event(new NewReservationCreated($reservation));
```

## Testing

### Seeding Sample Notifications
```bash
php artisan db:seed --class=NotificationSeeder
```

### Testing Real-time Notifications
1. Set up Pusher credentials
2. Create a new reservation
3. Check the notification panel for real-time updates

## Browser Notifications
The system requests permission for browser notifications and will show them when:
- Permission is granted
- New reservations are created
- User is on the site

## Styling
Notifications use Tailwind CSS classes and support:
- Dark mode
- Responsive design
- Hover effects
- Color-coded notification types

## Future Enhancements
- Email notifications
- SMS notifications
- Notification preferences
- Notification categories
- Notification history
- Bulk actions 