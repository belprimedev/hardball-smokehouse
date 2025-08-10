
# Admin Panel with Technical Monitoring (Laravel + Vue 3 + TailwindCSS)

## ✅ Objective
Build an **Admin Panel** for a Restaurant App that includes:
- Full **user management** (Admins, Managers, Staff)
- **System monitoring dashboard** (technical health & logs)
- Integration of **Laravel Telescope** and **Laravel Horizon**
- A **custom UI** to surface technical insights for non-technical users

---

## ✅ Features Breakdown

### 1. Dashboard Overview
- Total reservations today
- Pending/failed jobs
- Latest errors
- System uptime summary
- Quick links (view reservations, manage users, view system health)

---

### 2. User Management
- List of users (Admin, Manager, Staff)
- Role assignment using **Spatie Laravel-Permission**
- Create / Edit / Delete users
- Show last login and status (active/inactive)
- Ability to reset passwords

---

### 3. Technical Monitoring Section

#### **A. Email Delivery Status**
- Table showing:
  - Recipient email
  - Subject
  - Status: Sent / Failed / Retrying
  - Timestamp
- Pull from `failed_jobs` and `notifications` logs

#### **B. Authentication Health**
- Recent failed logins
- Last login per user
- Show active sessions (via Laravel session table if used)

#### **C. Error Logs**
- Show recent **Laravel exceptions** with:
  - Message
  - File & Line
  - Timestamp
- Option to download error logs
- Group similar errors for clarity

#### **D. Reservation System Health**
- Track reservation attempts vs success
- Highlight failed reservations (e.g., due to DB or slot full)
- Show most recent 10 failed attempts

#### **E. Job Queue Monitoring**
- Integrate **Laravel Horizon**
- Show:
  - Pending jobs
  - Failed jobs
  - Processing jobs
- Retry failed jobs from the admin panel

#### **F. Laravel Telescope Integration**
- Display:
  - Requests (last 50)
  - Exceptions
  - DB queries
  - Emails
- Use Telescope API or link to embedded dashboard

#### **G. Server & Cron Job Health**
- Show last successful `schedule:run`
- Disk usage (via Laravel `Storage::disk()` checks)
- Queue worker status (running or not)

#### **H. Notifications & Alerts**
- Real-time alert banner for:
  - Failed jobs > X count
  - Email sending errors
  - Reservation failures

---

### 4. UI/UX Requirements
- **Tech Stack**: Laravel + Vue 3 + Inertia.js + TailwindCSS
- Responsive admin layout
- Sidebar navigation:
  - Dashboard
  - Users
  - Reservations
  - System Health (with tabs for each technical category)
- Use **Vue 3 `<script setup>`** for components
- Use Tailwind for clean modern UI

---

### 5. Setup & Packages
- Install **Spatie Laravel-Permission** for RBAC
- Install **Laravel Telescope**
  ```bash
  composer require laravel/telescope
  php artisan telescope:install
  php artisan migrate
  ```
- Install **Laravel Horizon**
  ```bash
  composer require laravel/horizon
  php artisan horizon:install
  php artisan migrate
  ```
- Secure Horizon & Telescope behind `admin` middleware
- Add Telescope & Horizon metrics to custom UI using:
  - Telescope API endpoints
  - Horizon metrics via `Horizon::stats()`

---

## ✅ Deliverables
- **Routes**
  - `/admin/dashboard`
  - `/admin/users`
  - `/admin/system-health`
- **Vue Pages**
  - `Dashboard.vue`
  - `Users.vue`
  - `SystemHealth.vue` (with tabs: Emails, Jobs, Errors, Reservations, Logs)
- **Backend Controllers**
  - `Admin/DashboardController.php`
  - `Admin/SystemHealthController.php`
- **Blade Layout**
  - `resources/views/admin.blade.php` (Inertia root)
- **Middleware**
  - `role:admin` for all admin routes

---

## ✅ Bonus (Optional)
- Real-time monitoring using Laravel Echo + Pusher
- Export logs to CSV
- Dark mode toggle

---

## ✅ Deployment Notes
- Ensure Telescope & Horizon routes are protected:
  ```php
  Telescope::auth(function ($request) {
      return auth()->check() && auth()->user()->hasRole('admin');
  });

  Horizon::auth(function ($request) {
      return auth()->check() && auth()->user()->hasRole('admin');
  });
  ```
- Schedule Horizon monitoring in `app/Console/Kernel.php`:
  ```php
  $schedule->command('horizon:snapshot')->everyFiveMinutes();
  ```

---

### Prompt Summary:
Build a **full-featured admin panel** with:
- **User management**
- **System health dashboard**
- **Laravel Telescope & Horizon integration**
- Custom Vue UI for monitoring:
  - Emails
  - Errors
  - Jobs
  - Reservations
  - Server health

Ensure **RBAC security** for all admin routes.
