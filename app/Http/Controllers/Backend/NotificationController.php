<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        // Return latest 10 unread, then read
        $user = Auth::guard('admin')->user();
        $notifications = $user->notifications()->limit(20)->get();
        return response()->json($notifications);
    }
    
    public function unreadCount()
    {
        $user = Auth::guard('admin')->user();
        return response()->json(['count' => $user->unreadNotifications->count()]);
    }

    public function markAsRead(Request $request)
    {
        $user = Auth::guard('admin')->user();
        if($request->id) {
            $notification = $user->notifications()->where('id', $request->id)->first();
            if($notification) {
                $notification->markAsRead();
                
                $message = $notification->data['message'] ?? 'Notification ID: ' . $notification->id;
                \App\Models\AdminLog::log('Notification Read', "Admin '{$user->name}' read notification: \"{$message}\"", [
                    'notification_id' => $notification->id,
                    'notification_data' => $notification->data
                ]);
            }
        } else {
            $user->unreadNotifications()->update(['read_at' => now()]);
            \App\Models\AdminLog::log('Notification Read', "Admin '{$user->name}' marked all notifications as read.");
        }
        return response()->json(['success' => true]);
    }
    
    public function viewAll()
    {
        $notifications = Auth::guard('admin')->user()->notifications()->paginate(20);
        return view('backend.pages.notifications.index', compact('notifications'));
    }
}
