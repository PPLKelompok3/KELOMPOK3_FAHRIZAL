<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
public function index()
{
    $notifications = Notification::where('user_id', Auth::id())
                    ->latest()
                    ->paginate(10); // paginate for cleaner long list

    return view('applicant.notificationindex', compact('notifications'));
}
public function markAllRead()
{
    $user = Auth::user();
    /** @var \App\Models\User $user */
    $user->customNotifications()
    ->where('is_read', 0) // 
    ->update(['is_read' => 1]); // 

    return redirect()->route('applicantnotification')->with('success', 'All notifications marked as read.');
}
public function recruiterIndex()
{
    $user = Auth::user();

    // Mark all as read for this user
    Notification::where('user_id', $user->id)
        ->where('is_read', 0)
        ->update(['is_read' => 1]);

    // Fetch all (latest first)
    $notifications = Notification::where('user_id', $user->id)
    ->orderBy('updated_at', 'desc')
        ->paginate(10); // Add pagination if needed

    return view('recruiter.notifications', compact('notifications'));
}


}