# PROJECT 3: Hardball Smokehouse (hardballsmokehouse.co.uk)

## 1. Tech Stack

### Backend
- **PHP 8.2+** - Server-side programming language
- **Laravel 12.0** - PHP framework
- **Laravel Horizon 5.33** - Queue monitoring and management dashboard
- **Laravel Telescope 5.10** - Application debugging and monitoring
- **Laravel Tinker 2.10.1** - REPL for Laravel
- **Spatie Laravel Permission 6.20** - Role and permission management
- **Inertia.js 2.0** - Server-driven single-page applications
- **Resend Laravel 0.19.0** - Email delivery service
- **Pusher PHP Server 7.2** - Real-time WebSocket communication
- **Tightenco Ziggy 2.4** - Laravel route helper for JavaScript

### Frontend
- **Vue 3.5.13** - Progressive JavaScript framework
- **TypeScript 5.2.2** - Typed JavaScript
- **Inertia.js Vue3 2.0.0-beta.3** - Vue adapter for Inertia
- **Vite 6.2.0** - Next-generation frontend build tool
- **Tailwind CSS 3.4.1** - Utility-first CSS framework
- **Tailwind CSS Animate 1.0.7** - Animation utilities
- **Radix Vue 1.9.11** - Headless UI components
- **VueUse Core 12.0.0** - Vue composition utilities
- **VueUse Motion 3.0.3** - Motion animations
- **Lucide Vue Next 0.468.0** - Icon library
- **Vue3 Carousel 0.15.0** - Carousel component
- **Pusher JS 8.4.0** - Real-time WebSocket client
- **Ziggy JS 2.4.2** - Laravel route helper for JavaScript

### Development Tools
- **ESLint 9.17.0** - JavaScript/TypeScript linter
- **Prettier 3.4.2** - Code formatter
- **Vue TSC 2.2.4** - TypeScript checker for Vue
- **PHPUnit 11.5.3** - PHP testing framework
- **Laravel Pint 1.18** - PHP code style fixer
- **Laravel Pail 1.2.2** - Real-time log viewer
- **Laravel Sail 1.41** - Docker development environment

### Database
- **SQLite** (development) / **MySQL/PostgreSQL** (production) - Database
- **Laravel Migrations** - Database schema management

### Infrastructure & Services
- **Laravel Queues** - Background job processing
- **Redis** - Queue driver and caching (via Horizon)
- **Pusher** - Real-time broadcasting
- **Resend** - Transactional email service

## 2. Key Features

### Public-Facing Features
1. **Online Reservation System**
   - Real-time availability checking
   - Day-of-week based operating hours configuration
   - Capacity management per time slot
   - Email confirmations to customers
   - Special requests handling
   - Validation for operating hours and capacity limits

2. **Menu Management & Display**
   - Dynamic menu with categories (Starters, Jerk Dishes, Curry Dishes, Meals, Dessert)
   - Featured items and chef specials
   - Menu item images
   - Price display
   - Availability and visibility toggles
   - Public menu browsing page

3. **Contact Form**
   - Customer inquiry submission
   - Admin notification system
   - Contact management dashboard

4. **Newsletter Subscription**
   - Public subscription/unsubscription
   - Email list management
   - Newsletter statistics

5. **Vacancy/Job Postings**
   - Public job listings
   - Application submission
   - Admin vacancy management

6. **Website Pages**
   - Homepage with featured menu items
   - Menu page
   - Cocktail page
   - Events page
   - Gallery page
   - About page
   - FAQ page
   - Terms & Privacy pages
   - Contact page

### Admin Features
1. **Dashboard**
   - System statistics (menu items, categories, users, reservations)
   - Recent reservations overview
   - Featured and chef special items display
   - Menu items by category charts
   - Reservation trends (last 7 days)
   - Recent notifications

2. **Reservation Management**
   - View all reservations with pagination
   - Create, edit, and delete reservations
   - Filter and search capabilities
   - Real-time notifications for new reservations
   - Reservation capacity monitoring

3. **Menu Management**
   - Full CRUD for menu items
   - Menu category management
   - Image upload for menu items
   - Advanced filtering (category, price range, availability, visibility, featured, chef special)
   - Search functionality
   - Sorting capabilities
   - Featured and chef special flags

4. **User Management**
   - Role-based access control (Admin, Manager, Staff)
   - User creation, editing, and deletion
   - User status management (active, suspended, disabled)
   - Permission assignment
   - Last login tracking

5. **Settings Management**
   - General settings (business name, address, contact info, operation hours)
   - Reservation settings (per day-of-week: opening/closing times, capacity limits, open/closed status)
   - System configuration

6. **Notification System**
   - Real-time notifications via Pusher
   - Browser push notifications
   - Email notifications for:
     - New reservations
     - Reservation cancellations
     - System alerts
     - User status changes
   - Notification history
   - Mark as read/unread functionality

7. **Contact Management**
   - View customer inquiries
   - Mark as replied
   - Contact statistics

8. **Newsletter Management**
   - Subscriber list
   - Newsletter statistics
   - Subscription management

9. **System Health Monitoring**
   - Queue status monitoring
   - Failed jobs tracking
   - Error logging (via Telescope)
   - System uptime information
   - Disk usage monitoring

10. **Vacancy Management**
    - Create and manage job postings
    - Public vacancy listings

## 3. Target Audience

### Primary Users
1. **Restaurant Customers**
   - People looking to make reservations online
   - Customers browsing the menu
   - Newsletter subscribers
   - Job applicants

2. **Restaurant Staff & Management**
   - Restaurant owners and managers
   - Front-of-house staff
   - Kitchen staff (for menu management)
   - HR staff (for vacancy management)

### Problem Solved
- **For Customers**: Provides a convenient online reservation system, eliminating the need for phone calls. Customers can check availability in real-time, make reservations 24/7, and receive instant email confirmations.

- **For Restaurant**: Streamlines reservation management, reduces no-shows through email confirmations, provides a centralized system for managing menu items, staff, and customer inquiries. The real-time notification system ensures staff are immediately alerted to new reservations.

- **Business Operations**: Automates many manual processes (reservation tracking, menu updates, customer communication), provides analytics and insights through the dashboard, and enables better capacity management through configurable settings per day of the week.

## 4. Integrations

### Third-Party Services
1. **Resend API**
   - Transactional email delivery
   - Email templates for reservations, notifications, and system alerts
   - Configuration via `RESEND_API_KEY`

2. **Pusher**
   - Real-time WebSocket communication
   - Live notifications for new reservations
   - Browser push notifications
   - Configuration via `PUSHER_APP_KEY`, `PUSHER_APP_SECRET`, `PUSHER_APP_ID`, `PUSHER_APP_CLUSTER`

3. **AWS SES** (Optional)
   - Alternative email service
   - Configuration via `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`

4. **Postmark** (Optional)
   - Alternative email service
   - Configuration via `POSTMARK_TOKEN`

5. **Slack** (Optional)
   - Notification integration
   - Configuration via `SLACK_BOT_USER_OAUTH_TOKEN`

### Internal Services
1. **Laravel Horizon**
   - Queue monitoring dashboard
   - Job processing metrics
   - Failed job management

2. **Laravel Telescope**
   - Application debugging
   - Query monitoring
   - Exception tracking
   - Request/response logging

3. **Redis**
   - Queue driver
   - Cache storage
   - Session storage (optional)

## 5. Infrastructure

### Development Environment
- **Laravel Herd** - Local development environment (based on file paths)
- **Vite Dev Server** - Hot module replacement for frontend
- **Laravel Queue Worker** - Background job processing
- **Laravel Pail** - Real-time log viewing
- **Concurrent Development Scripts** - Runs server, queue, logs, and Vite simultaneously

### Deployment
- **Laravel Horizon** - Production queue monitoring
- **Queue Workers** - Background job processing (configurable via Horizon)
- **Vite Build** - Production asset compilation
- **SSR Support** - Server-side rendering capability (configured but optional)

### CI/CD
- No explicit CI/CD configuration files found in the codebase
- Likely uses standard Laravel deployment practices:
  - Database migrations
  - Asset compilation
  - Queue worker restart
  - Cache clearing

### Hosting Considerations
- Requires PHP 8.2+
- Node.js for asset compilation
- Redis for queues and caching
- Database (MySQL/PostgreSQL recommended for production)
- Web server (Nginx/Apache)
- SSL certificate for HTTPS
- Environment variable configuration

## 6. Setback & Solution

### Challenge: Real-Time Reservation Notifications

**The Problem**: 
The application needed to notify restaurant staff immediately when a new reservation was created, without requiring page refreshes. Initial implementation only sent email notifications, which had delays and didn't provide instant feedback in the admin dashboard.

**The Solution**:
Implemented a comprehensive real-time notification system using multiple technologies:

1. **Event Broadcasting**: Created `NewReservationCreated` event that implements `ShouldBroadcast` interface, broadcasting to a Pusher channel when reservations are created.

2. **Pusher Integration**: 
   - Backend: Integrated Pusher PHP SDK to broadcast events
   - Frontend: Integrated Pusher JS client to listen for events
   - Real-time updates appear instantly in the admin dashboard

3. **Notification Storage**: Created a notifications table to persist notifications, allowing staff to view notification history even if they weren't online when the reservation was created.

4. **Browser Push Notifications**: Added browser Notification API integration, so staff receive desktop notifications even when the browser tab is not active (with permission).

5. **Email Fallback**: Maintained email notifications as a backup, ensuring notifications are never missed even if real-time systems fail.

6. **Notification Panel**: Built a comprehensive notification panel component that:
   - Fetches existing notifications on page load
   - Listens for real-time updates via Pusher
   - Shows unread count badge
   - Allows marking notifications as read
   - Displays notification history

**Result**: Staff now receive instant notifications both in-app and via browser push notifications, significantly improving response time to new reservations while maintaining reliable email notifications as a backup.

## 7. Notable Technical Achievements

### 1. **Inertia.js SPA Architecture**
   - Seamless integration between Laravel backend and Vue 3 frontend
   - Server-side routing with client-side rendering
   - No API boilerplate - direct data passing from controllers to Vue components
   - Maintains Laravel's authentication and session management

### 2. **Advanced Reservation System**
   - **Day-of-Week Configuration**: Flexible operating hours and capacity settings per day
   - **Real-Time Availability Checking**: API endpoint for checking slot availability before submission
   - **Capacity Management**: Prevents overbooking by tracking reservations per time slot
   - **Operating Hours Validation**: Validates reservation times against configured hours
   - **Closed Day Detection**: Automatically prevents reservations on closed days

### 3. **Role-Based Access Control (RBAC)**
   - Comprehensive permission system using Spatie Laravel Permission
   - Three-tier role system (Admin, Manager, Staff) with granular permissions
   - Middleware-based route protection
   - Dynamic UI based on user permissions
   - User status management (active, suspended, disabled)

### 4. **Queue-Based Email System**
   - Asynchronous email processing using Laravel Queues
   - Horizon dashboard for monitoring and managing queue jobs
   - Failed job handling and retry mechanisms
   - Prevents blocking user requests during email sending

### 5. **TypeScript Integration**
   - Full TypeScript support for Vue components
   - Type-safe API responses
   - Enhanced developer experience with autocomplete and error checking
   - Strict type checking enabled

### 6. **Modern UI Component System**
   - Radix Vue for accessible, headless components
   - Tailwind CSS for utility-first styling
   - Dark mode support (via appearance middleware)
   - Responsive design throughout
   - Custom cursor component
   - Motion animations via VueUse Motion

### 7. **Comprehensive Menu Management**
   - Advanced filtering system (category, price, availability, visibility, featured status)
   - Search functionality across multiple fields
   - Image upload and management
   - Featured items and chef specials system
   - Category-based organization

### 8. **System Monitoring & Debugging**
   - Laravel Telescope integration for application debugging
   - System health dashboard
   - Queue monitoring via Horizon
   - Error tracking and logging
   - Disk usage monitoring
   - Failed job tracking

### 9. **Email Template System**
   - Custom email templates for different notification types
   - Reservation confirmation emails
   - System alert emails
   - User status change notifications
   - Professional, branded email design

### 10. **Testing Infrastructure**
   - PHPUnit test suite
   - Feature tests for authentication
   - Reservation capacity tests
   - Dashboard tests
   - Settings tests

### 11. **Developer Experience**
   - Concurrent development script running server, queue, logs, and Vite
   - Hot module replacement for instant frontend updates
   - Real-time log viewing with Laravel Pail
   - ESLint and Prettier for code quality
   - TypeScript for type safety

### 12. **API Design**
   - RESTful API endpoints for public features
   - JSON responses for frontend consumption
   - Availability checking API
   - Menu items API with filtering
   - Settings API for dynamic configuration

---

**Project Status**: Production-ready restaurant management system with comprehensive features for both customers and restaurant staff.

**Domain**: hardballsmokehouse.co.uk

**Business Type**: Caribbean Smokehouse Restaurant

