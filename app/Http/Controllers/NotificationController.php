<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Get all notifications
     */
    public function index(): JsonResponse
    {
        $notifications = Notification::latest()
            ->limit(50)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::unread()->count(),
        ]);
    }

    /**
     * Get unread notifications
     */
    public function unread(): JsonResponse
    {
        $notifications = Notification::latest()
            ->limit(10)
            ->get();

        $unreadCount = Notification::unread()->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request): JsonResponse
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        $notification = Notification::findOrFail($request->notification_id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => Notification::unread()->count(),
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): JsonResponse
    {
        Notification::unread()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Create a notification (for testing or manual creation)
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|string',
            'title' => 'required|string',
            'message' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $notification = Notification::create([
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'data' => $request->data,
        ]);

        return response()->json([
            'success' => true,
            'notification' => $notification,
        ]);
    }
}
