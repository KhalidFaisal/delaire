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
            }
        } else {
            $user->unreadNotifications->markAsRead();
        }
        return response()->json(['success' => true]);
    }
    
    public function viewAll()
    {
        $notifications = Auth::guard('admin')->user()->notifications()->paginate(20);
        return view('backend.pages.notifications.index', compact('notifications'));
    }
}
