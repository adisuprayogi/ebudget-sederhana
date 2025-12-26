<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $query = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        // Filter by read status
        if ($request->filled('is_read')) {
            if ($request->is_read === 'true') {
                $query->read();
            } else {
                $query->unread();
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $notifications = $query->paginate($request->per_page ?? 15)
            ->withQueryString();

        // Get unread count
        $unreadCount = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return view('notifications.index', [
            'notifications' => $notifications,
            'filters' => $request->only(['is_read', 'type']),
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Display the specified notification.
     */
    public function show(Notification $notification)
    {
        // Check if notification belongs to user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read when viewed
        if (!$notification->is_read) {
            $notification->markAsRead();
        }

        $notification->load(['notifiable']);

        return view('notifications.show', [
            'notification' => $notification,
        ]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return back()->with('success', 'Notifikasi ditandai sebagai telah dibaca.');
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsUnread();

        return back()->with('success', 'Notifikasi ditandai sebagai belum dibaca.');
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request)
    {
        $updated = Notification::where('user_id', Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', $updated . ' notifikasi ditandai sebagai telah dibaca.');
    }

    /**
     * Mark all notifications as unread.
     */
    public function markAllAsUnread(Request $request)
    {
        $updated = Notification::where('user_id', Auth::id())
            ->read()
            ->update([
                'is_read' => false,
                'read_at' => null,
            ]);

        return back()->with('success', $updated . ' notifikasi ditandai sebagai belum dibaca.');
    }

    /**
     * Delete the specified notification.
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Delete all read notifications.
     */
    public function destroyRead(Request $request)
    {
        $deleted = Notification::where('user_id', Auth::id())
            ->read()
            ->delete();

        return back()->with('success', $deleted . ' notifikasi berhasil dihapus.');
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll(Request $request)
    {
        $deleted = Notification::where('user_id', Auth::id())->delete();

        return back()->with('success', $deleted . ' notifikasi berhasil dihapus.');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'count' => $count,
        ]);
    }

    /**
     * Get recent notifications for the authenticated user.
     */
    public function recent(Request $request)
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->with(['notifiable'])
            ->orderBy('created_at', 'desc')
            ->limit($request->limit ?? 10)
            ->get();

        $unreadCount = Notification::where('user_id', Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Create a new notification (for system use).
     */
    public function store(Request $request)
    {
        // This is typically called internally by services
        // But we can provide an endpoint for admin use

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:info,success,warning,error',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:500',
            'notifiable_type' => 'nullable|string|max:255',
            'notifiable_id' => 'nullable|integer',
        ]);

        $notification = Notification::create([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'title' => $validated['title'],
            'message' => $validated['message'],
            'link' => $validated['link'] ?? null,
            'notifiable_type' => $validated['notifiable_type'] ?? null,
            'notifiable_id' => $validated['notifiable_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Notifikasi berhasil dibuat.',
            'notification' => $notification,
        ], 201);
    }

    /**
     * Bulk create notifications for multiple users.
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'type' => 'required|in:info,success,warning,error',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'nullable|string|max:500',
            'notifiable_type' => 'nullable|string|max:255',
            'notifiable_id' => 'nullable|integer',
        ]);

        $notifications = [];
        foreach ($validated['user_ids'] as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $validated['type'],
                'title' => $validated['title'],
                'message' => $validated['message'],
                'link' => $validated['link'] ?? null,
                'notifiable_type' => $validated['notifiable_type'] ?? null,
                'notifiable_id' => $validated['notifiable_id'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Notification::insert($notifications);

        return response()->json([
            'message' => count($notifications) . ' notifikasi berhasil dibuat.',
        ], 201);
    }

    /**
     * Get notification statistics.
     */
    public function statistics()
    {
        $stats = [
            'total' => Notification::where('user_id', Auth::id())->count(),
            'unread' => Notification::where('user_id', Auth::id())->unread()->count(),
            'read' => Notification::where('user_id', Auth::id())->read()->count(),
            'by_type' => [
                'info' => Notification::where('user_id', Auth::id())->where('type', 'info')->count(),
                'success' => Notification::where('user_id', Auth::id())->where('type', 'success')->count(),
                'warning' => Notification::where('user_id', Auth::id())->where('type', 'warning')->count(),
                'error' => Notification::where('user_id', Auth::id())->where('type', 'error')->count(),
            ],
        ];

        return response()->json($stats);
    }
}
