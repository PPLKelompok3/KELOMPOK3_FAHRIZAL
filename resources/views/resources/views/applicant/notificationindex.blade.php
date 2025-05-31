@extends('layouts.applicant') 

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
                    <div class="notification-list_feature-img">
                        @if ($notification->company_logo_url)
                            <img src="{{ asset('storage/' . $notification->company_logo_url) }}" alt="Company Logo" style="object-fit: cover; width: 100px; height: 100px;">
                        @else
                            <img src="{{ asset('assets/img/icons/default-company.png') }}" alt="Default Company Logo" style="object-fit: cover; width: 100px; height: 100px;">
                        @endif
                    </div>
                    
                </div>
            @empty
                <div class="text-center p-5">
                    <h5>No notifications yet 📭</h5>
                </div>
            @endforelse
        </div>

        {{-- Optional pagination (if many notifications) --}}
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>

    </div>
</section>

@endsection

