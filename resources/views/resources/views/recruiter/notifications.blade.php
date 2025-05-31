@extends('layouts.recruiter') 

@section('title', 'Notifications')

@section('content')
<section class="section-50">
    <div class="container">
        <h3 class="m-b-50 heading-line">
            Notifications <i class="fa fa-bell text-muted"></i>
        </h3>

        <div class="notification-ui_dd-content">
            @forelse ($notifications as $notification)
                <div class="notification-list {{ !$notification->is_read ? 'notification-list--unread' : '' }}">
                    <div class="notification-list_content">
                        <div class="notification-list_detail">
                            <p><b>{{ $notification->type }}</b></p>
                            <p class="text-muted">{{ $notification->content }}</p>
                            <p class="text-muted">
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-5">
                    <h5>No notifications yet 📭</h5>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $notifications->links() }}
        </div>

    </div>
</section>
@endsection
