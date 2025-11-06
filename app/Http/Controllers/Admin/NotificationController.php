<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ✅ List all notifications for the current logged-in user
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $notifications = $user->notifications()->latest()->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    // ✅ Mark all unread notifications as read
    public function markAllRead(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'All notifications marked as read.');
    }

    // ✅ Mark single notification as read and redirect to its link
    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must log in first.');
        }

        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect($notification->data['url'] ?? route('notifications.index'));
    }
}
