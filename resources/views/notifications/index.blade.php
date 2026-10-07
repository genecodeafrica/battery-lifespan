@extends('layouts.app')

@section('title', 'Notifications')

@section('content')

<div class="page-header mb-4">
    <div>
        <h1 class="page-title">
            <i class="bi bi-bell me-2"></i>
            Notifications
        </h1>

        <p class="page-subtitle">
            Battery alerts, AI predictions and system notifications.
        </p>
    </div>

    @if($notifications->where('is_read', false)->count() > 0)
        <form
            action="{{ route('notifications.read-all') }}"
            method="POST"
        >
            @csrf

            <button class="btn btn-outline-light">
                <i class="bi bi-check2-all me-1"></i>
                Mark All as Read
            </button>
        </form>
    @endif
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>
@endif

<div class="card dashboard-card">

    <div class="card-body p-0">

        @forelse($notifications as $notification)

            <div class="notification-item
                {{ $notification->is_read ? 'notification-read' : 'notification-unread' }}">

                <div class="notification-icon
                    notification-{{ $notification->severity }}">

                    @if($notification->severity === 'danger')
                        <i class="bi bi-exclamation-octagon"></i>
                    @elseif($notification->severity === 'warning')
                        <i class="bi bi-exclamation-triangle"></i>
                    @elseif($notification->severity === 'success')
                        <i class="bi bi-check-circle"></i>
                    @else
                        <i class="bi bi-info-circle"></i>
                    @endif

                </div>

                <div class="notification-content">

                    <div class="d-flex justify-content-between gap-3">

                        <div>
                            <h5 class="notification-title">
                                {{ $notification->title }}
                            </h5>

                            <p class="notification-message">
                                {{ $notification->message }}
                            </p>
                        </div>

                        @if(!$notification->is_read)

                            <span class="notification-dot"></span>

                        @endif

                    </div>

                    <div class="notification-meta">

                        <span>
                            <i class="bi bi-clock me-1"></i>
                            {{ $notification->created_at->diffForHumans() }}
                        </span>

                        @if($notification->battery)

                            <span>
                                <i class="bi bi-battery-half me-1"></i>
                                {{ $notification->battery->battery_code }}
                            </span>

                        @endif

                        @if(!$notification->is_read)

                            <form
                                action="{{ route('notifications.read', $notification) }}"
                                method="POST"
                                class="ms-auto"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-sm btn-outline-light"
                                >
                                    <i class="bi bi-check2 me-1"></i>
                                    Mark as Read
                                </button>
                            </form>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="empty-state">

                <div class="empty-state-icon">
                    <i class="bi bi-bell-slash"></i>
                </div>

                <h4>No notifications</h4>

                <p>
                    You are all caught up. BatteryLife AI will display
                    important battery and prediction alerts here.
                </p>

            </div>

        @endforelse

    </div>

</div>

@if($notifications->hasPages())

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>

@endif

@endsection