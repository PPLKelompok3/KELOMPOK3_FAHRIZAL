<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
{
    // Share notifications to recruiter layout
    View::composer('layouts.recruiter', function ($view) {
        if (Auth::check()) {
            $notifications = Notification::where('user_id', Auth::id())
                ->where('is_read', 0)
                ->latest()
                ->take(5)
                ->get();

            $unreadCount = Notification::where('user_id', Auth::id())
                ->where('is_read', 0)
                ->count();

            $view->with([
                'notifications' => $notifications,
                'unreadCount' => $unreadCount
            ]);
        }
    });
}
}