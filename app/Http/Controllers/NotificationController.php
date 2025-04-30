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


}