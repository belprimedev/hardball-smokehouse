<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Notification;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Laravel\Horizon\Horizon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's reservations
        $todayReservations = Reservation::whereDate('reservation_date', today())->count();
        
        // Get failed jobs count
        $failedJobsCount = DB::table('failed_jobs')->count();
        
        // Get pending jobs count
        $pendingJobsCount = DB::table('jobs')->count();
        
        // Get recent errors (from telescope if available)
        $recentErrors = [];
        if (class_exists('Laravel\Telescope\Telescope')) {
            $recentErrors = DB::table('telescope_entries')
                ->where('type', 'exception')
                ->latest('created_at')
                ->limit(5)
                ->get(['uuid', 'content', 'created_at']);
        }
        
        // Get system uptime info
        $systemInfo = [
            'disk_usage' => $this->getDiskUsage(),
            'queue_workers' => $this->getQueueWorkersStatus(),
            'last_cron_run' => $this->getLastCronRun(),
        ];
        
        // Get recent notifications
        $recentNotifications = Notification::latest()->limit(10)->get();
        
        // Get user statistics
        $userStats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'suspended_users' => User::where('status', 'suspended')->count(),
            'recent_logins' => User::whereNotNull('last_login_at')
                ->where('last_login_at', '>=', now()->subDays(7))
                ->count(),
        ];
        
        // Get reservation statistics
        $reservationStats = [
            'total_reservations' => Reservation::count(),
            'today_reservations' => $todayReservations,
            'this_week_reservations' => Reservation::whereBetween('reservation_date', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month_reservations' => Reservation::whereMonth('reservation_date', now()->month)->count(),
        ];
        
        // Get contact statistics
        $contactStats = [
            'total_contacts' => Contact::count(),
            'new_contacts' => Contact::where('status', 'new')->count(),
            'recent_contacts' => Contact::where('created_at', '>=', now()->subDays(7))->count(),
        ];
        
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'todayReservations' => $todayReservations,
                'failedJobs' => $failedJobsCount,
                'pendingJobs' => $pendingJobsCount,
                'recentErrors' => $recentErrors,
                'systemInfo' => $systemInfo,
                'userStats' => $userStats,
                'reservationStats' => $reservationStats,
                'contactStats' => $contactStats,
            ],
            'recentNotifications' => $recentNotifications,
        ]);
    }
    
    private function getDiskUsage()
    {
        try {
            $total = disk_total_space(storage_path());
            $free = disk_free_space(storage_path());
            $used = $total - $free;
            $percentage = ($used / $total) * 100;
            
            return [
                'total' => $this->formatBytes($total),
                'used' => $this->formatBytes($used),
                'free' => $this->formatBytes($free),
                'percentage' => round($percentage, 2),
            ];
        } catch (\Exception $e) {
            return null;
        }
    }
    
    private function getQueueWorkersStatus()
    {
        try {
            // Get basic queue statistics from database
            $pendingJobs = DB::table('jobs')->count();
            $failedJobs = DB::table('failed_jobs')->count();
            
            // Check if Horizon is running by looking for recent job activity
            $recentJobs = DB::table('jobs')
                ->where('created_at', '>=', now()->subMinutes(5))
                ->count();
            
            return [
                'running' => $recentJobs > 0 ? 1 : 0, // Simple indicator
                'jobs_per_minute' => $recentJobs,
                'throughput' => $pendingJobs,
                'pending_jobs' => $pendingJobs,
                'failed_jobs' => $failedJobs,
            ];
        } catch (\Exception $e) {
            return [
                'running' => 0,
                'jobs_per_minute' => 0,
                'throughput' => 0,
                'pending_jobs' => 0,
                'failed_jobs' => 0,
            ];
        }
    }
    
    private function getLastCronRun()
    {
        // This would typically check a cache key or database record
        // For now, we'll return a placeholder
        return [
            'last_run' => now()->subMinutes(5)->toDateTimeString(),
            'status' => 'running',
        ];
    }
    
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
} 