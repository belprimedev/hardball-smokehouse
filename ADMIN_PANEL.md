# Admin Panel Documentation

## Overview

The Admin Panel provides comprehensive system monitoring and administration capabilities for the Hardball Caribbean Smokehouse application. It's designed to give administrators real-time insights into system health, user management, and technical monitoring.

## Access Control

- **Admin Only**: The admin panel is restricted to users with the `admin` role
- **Secure Routes**: All admin routes are protected with `auth`, `verified`, and `role:admin` middleware
- **Telescope & Horizon**: Both monitoring tools are secured behind admin authentication

## Features

### 1. Admin Dashboard (`/admin/dashboard`)

**Overview Metrics:**
- Today's reservations count
- Failed jobs count
- Pending jobs count
- System health status
- Active users count
- Recent notifications

**System Information:**
- Disk usage monitoring
- Queue worker status
- Last cron job execution
- Recent error tracking

### 2. System Health Monitoring (`/admin/system-health`)

**Tabbed Interface:**
- **Overview**: Quick system metrics
- **Emails**: Failed email deliveries and notification status
- **Jobs**: Pending and failed job management with retry capabilities
- **Errors**: Recent system errors and error log entries
- **Reservations**: Reservation system health and statistics
- **Logs**: System logs and Telescope entry monitoring

**Key Features:**
- Real-time job retry functionality
- Log download capability
- Error tracking and grouping
- Email delivery status monitoring

### 3. User Management

Integrated with existing user management system:
- User creation, editing, and deletion
- Role assignment (Admin, Manager, Staff)
- User status management (Active, Suspended, Disabled)
- Last login tracking

## Technical Monitoring Tools

### Laravel Telescope
- **Access**: `/telescope` (admin only)
- **Features**: Request monitoring, database queries, exceptions, mail tracking
- **Security**: Protected by `role:admin` middleware

### Laravel Horizon
- **Access**: `/horizon` (admin only)
- **Features**: Queue monitoring, job processing, failed job management
- **Security**: Protected by `role:admin` middleware

## Navigation

The admin panel is accessible through:
1. **Sidebar Navigation**: "Admin Panel" link (admin users only)
2. **Direct URLs**: `/admin/dashboard`, `/admin/system-health`
3. **Quick Links**: From dashboard to system health and user management

## Security Features

- **Role-based Access**: Only users with `admin` role can access
- **Middleware Protection**: All routes use `auth`, `verified`, and `role:admin`
- **Secure Monitoring Tools**: Telescope and Horizon are admin-only
- **Session Management**: Proper authentication checks throughout

## System Requirements

- Laravel 12.x
- Vue 3 with Inertia.js
- TailwindCSS
- Spatie Laravel-Permission
- Laravel Telescope
- Laravel Horizon

## Installation & Setup

1. **Install Dependencies:**
   ```bash
   composer require laravel/telescope laravel/horizon
   ```

2. **Publish Configurations:**
   ```bash
   php artisan telescope:install
   php artisan horizon:install
   php artisan migrate
   ```

3. **Configure Security:**
   - Telescope and Horizon are automatically secured
   - Admin routes are protected by middleware

4. **Create Admin User:**
   ```bash
   php artisan tinker
   $user = User::find(1);
   $user->assignRole('admin');
   ```

## Usage

1. **Access Dashboard**: Navigate to `/admin/dashboard`
2. **Monitor System Health**: Use the system health tabs for detailed monitoring
3. **Manage Users**: Access user management through the sidebar
4. **Retry Failed Jobs**: Use the retry button in the jobs tab
5. **Download Logs**: Use the download button for system logs

## Monitoring Capabilities

- **Email Delivery**: Track failed emails and notification status
- **Job Processing**: Monitor queue health and retry failed jobs
- **Error Tracking**: View recent errors and system exceptions
- **Reservation System**: Monitor reservation success/failure rates
- **System Resources**: Track disk usage and queue worker status

## Troubleshooting

- **Access Denied**: Ensure user has `admin` role
- **Missing Data**: Check if Telescope/Horizon are properly configured
- **Job Retries**: Failed jobs can be retried from the jobs tab
- **Log Access**: System logs can be downloaded for analysis 