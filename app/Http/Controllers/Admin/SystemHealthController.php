<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Laravel\Horizon\Horizon;

class SystemHealthController extends Controller
{
    public function index()
    {
        // Get basic system health data for overview
        $pendingJobs = DB::table('jobs')->count();
        $failedJobs = DB::table('failed_jobs')->count();
        
        // Get failed emails
        $failedEmails = DB::table('failed_jobs')
            ->where('payload', 'like', '%mail%')
            ->latest('failed_at')
            ->limit(10)
            ->get(['id', 'queue', 'failed_at', 'exception']);
            
        // Get recent errors from telescope
        $recentErrors = [];
        if (class_exists('Laravel\Telescope\Telescope')) {
            $recentErrors = DB::table('telescope_entries')
                ->where('type', 'exception')
                ->latest('created_at')
                ->limit(10)
                ->get(['uuid', 'content', 'created_at']);
        }
        
        // Get basic queue statistics
        $horizonStats = [
            'processes' => $pendingJobs > 0 ? 1 : 0,
            'jobs_per_minute' => $pendingJobs,
            'throughput' => $pendingJobs,
            'pending_jobs' => $pendingJobs,
            'failed_jobs' => $failedJobs,
        ];
        
        // Get reservation stats
        $totalReservations = \App\Models\Reservation::count();
        $todayReservations = \App\Models\Reservation::whereDate('reservation_date', today())->count();
        $failedReservations = 0; // No status column in reservations table
        
        $data = [
            'activeTab' => 'overview',
            'pendingJobs' => DB::table('jobs')->latest('created_at')->limit(10)->get(),
            'failedJobs' => DB::table('failed_jobs')->latest('failed_at')->limit(10)->get(),
            'failedEmails' => $failedEmails,
            'recentErrors' => $recentErrors,
            'horizonStats' => $horizonStats,
            'totalReservations' => $totalReservations,
            'todayReservations' => $todayReservations,
            'failedReservations' => $failedReservations,
            'recentReservations' => \App\Models\Reservation::latest()->limit(10)->get(),
            'systemLogs' => $this->getSystemLogs(),
            'telescopeEntries' => $this->getTelescopeEntries(),
        ];
        
        // Debug: Log the data being passed
        Log::info('SystemHealth data being passed:', [
            'pendingJobs_count' => count($data['pendingJobs']),
            'failedJobs_count' => count($data['failedJobs']),
            'totalReservations' => $data['totalReservations'],
            'todayReservations' => $data['todayReservations'],
            'failedEmails_count' => count($data['failedEmails']),
            'recentErrors_count' => count($data['recentErrors']),
        ]);
        
        return Inertia::render('Admin/SystemHealth', $data);
    }
    
    public function emails()
    {
        // Get email delivery status from failed_jobs and notifications
        $failedEmails = DB::table('failed_jobs')
            ->where('payload', 'like', '%mail%')
            ->latest('failed_at')
            ->limit(50)
            ->get(['id', 'queue', 'failed_at', 'exception']);
            
        $recentNotifications = DB::table('notifications')
            ->where('type', 'like', '%Mail%')
            ->latest('created_at')
            ->limit(50)
            ->get(['id', 'type', 'data', 'created_at', 'read_at']);
            
        return Inertia::render('Admin/SystemHealth', [
            'activeTab' => 'emails',
            'failedEmails' => $failedEmails,
            'recentNotifications' => $recentNotifications,
        ]);
    }
    
    public function jobs()
    {
        // Get job queue status
        $pendingJobsList = DB::table('jobs')
            ->latest('created_at')
            ->limit(50)
            ->get(['id', 'queue', 'attempts', 'created_at']);
            
        $failedJobsList = DB::table('failed_jobs')
            ->latest('failed_at')
            ->limit(50)
            ->get(['id', 'queue', 'failed_at', 'exception']);
            
        $horizonStats = [];
        try {
            // Get basic queue statistics from database
            $pendingJobsCount = DB::table('jobs')->count();
            $failedJobsCount = DB::table('failed_jobs')->count();
            
            // Check if Horizon is running by looking for recent job activity
            $recentJobs = DB::table('jobs')
                ->where('created_at', '>=', now()->subMinutes(5))
                ->count();
            
            $horizonStats = [
                'processes' => $recentJobs > 0 ? 1 : 0, // Simple indicator
                'jobs_per_minute' => $recentJobs,
                'throughput' => $pendingJobsCount,
                'pending_jobs' => $pendingJobsCount,
                'failed_jobs' => $failedJobsCount,
            ];
        } catch (\Exception $e) {
            $horizonStats = [
                'processes' => 0,
                'jobs_per_minute' => 0,
                'throughput' => 0,
                'pending_jobs' => 0,
                'failed_jobs' => 0,
            ];
        }
        
        return Inertia::render('Admin/SystemHealth', [
            'activeTab' => 'jobs',
            'pendingJobs' => $pendingJobsList,
            'failedJobs' => $failedJobsList,
            'horizonStats' => $horizonStats,
        ]);
    }
    
    public function errors()
    {
        // Get recent errors from telescope
        $recentErrors = [];
        if (class_exists('Laravel\Telescope\Telescope')) {
            $recentErrors = DB::table('telescope_entries')
                ->where('type', 'exception')
                ->latest('created_at')
                ->limit(50)
                ->get(['uuid', 'content', 'created_at']);
        }
        
        // Get error logs from storage
        $errorLogs = [];
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $lines = explode("\n", $logContent);
                $errorLines = array_filter($lines, function($line) {
                    return strpos($line, 'ERROR') !== false || strpos($line, 'CRITICAL') !== false;
                });
                $errorLogs = array_slice(array_reverse($errorLines), 0, 50);
            }
        } catch (\Exception $e) {
            $errorLogs = [];
        }
        
        return Inertia::render('Admin/SystemHealth', [
            'activeTab' => 'errors',
            'recentErrors' => $recentErrors,
            'errorLogs' => $errorLogs,
            'systemLogs' => $this->getSystemLogs(),
        ]);
    }
    
    public function reservations()
    {
        // Get reservation system health data
        $totalReservations = Reservation::count();
        $todayReservations = Reservation::whereDate('reservation_date', today())->count();
        $failedReservations = 0; // No status column in reservations table
        
        // Get recent reservation attempts
        $recentReservations = Reservation::latest()
            ->limit(20)
            ->get(['id', 'customer_name', 'customer_email', 'reservation_date', 'reservation_time', 'created_at']);
            
        // Get reservation statistics by date (last 30 days)
        $reservationsByDate = Reservation::selectRaw('DATE(reservation_date) as date, COUNT(*) as count')
            ->where('reservation_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        return Inertia::render('Admin/SystemHealth', [
            'activeTab' => 'reservations',
            'totalReservations' => $totalReservations,
            'todayReservations' => $todayReservations,
            'failedReservations' => $failedReservations,
            'recentReservations' => $recentReservations,
            'reservationsByDate' => $reservationsByDate,
        ]);
    }
    
    public function logs()
    {
        // Get system logs
        $systemLogs = [];
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $lines = explode("\n", $logContent);
                $systemLogs = array_slice(array_reverse($lines), 0, 100);
            }
        } catch (\Exception $e) {
            $systemLogs = [];
        }
        
        // Get telescope entries for various types
        $telescopeEntries = [];
        if (class_exists('Laravel\Telescope\Telescope')) {
            $telescopeEntries = DB::table('telescope_entries')
                ->select('type', DB::raw('COUNT(*) as count'))
                ->groupBy('type')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
        }
        
        return Inertia::render('Admin/SystemHealth', [
            'activeTab' => 'logs',
            'systemLogs' => $systemLogs,
            'telescopeEntries' => $telescopeEntries,
        ]);
    }
    
    public function retryJob(Request $request, $id)
    {
        try {
            $failedJob = DB::table('failed_jobs')->find($id);
            if (!$failedJob) {
                return back()->with('error', 'Failed job not found.');
            }
            
            // Move job back to jobs table
            DB::table('jobs')->insert([
                'queue' => $failedJob->queue,
                'payload' => $failedJob->payload,
                'attempts' => 0,
                'reserved_at' => null,
                'available_at' => now()->timestamp,
                'created_at' => now()->timestamp,
                'updated_at' => now()->timestamp,
            ]);
            
            // Delete from failed_jobs
            DB::table('failed_jobs')->where('id', $id)->delete();
            
            return back()->with('success', 'Job has been retried successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retry job: ' . $e->getMessage());
        }
    }
    
    public function downloadLogs()
    {
        try {
            $logPath = storage_path('logs/laravel.log');
            if (!file_exists($logPath)) {
                return back()->with('error', 'Log file not found.');
            }
            
            return response()->download($logPath, 'laravel-' . date('Y-m-d') . '.log');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to download logs: ' . $e->getMessage());
        }
    }
    
    private function getSystemLogs()
    {
        $systemLogs = [];
        try {
            $logPath = storage_path('logs/laravel.log');
            if (file_exists($logPath)) {
                $logContent = file_get_contents($logPath);
                $lines = explode("\n", $logContent);
                $systemLogs = array_slice(array_reverse($lines), 0, 50);
            }
        } catch (\Exception $e) {
            $systemLogs = [];
        }
        return $systemLogs;
    }
    
    private function getTelescopeEntries()
    {
        $telescopeEntries = [];
        if (class_exists('Laravel\Telescope\Telescope')) {
            try {
                $telescopeEntries = DB::table('telescope_entries')
                    ->select('type', DB::raw('COUNT(*) as count'))
                    ->groupBy('type')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get();
            } catch (\Exception $e) {
                $telescopeEntries = [];
            }
        }
        return $telescopeEntries;
    }
} 