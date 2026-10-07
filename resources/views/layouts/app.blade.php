<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'Dashboard') - BatteryLife AI
    </title>

    {{-- BatteryLife AI Favicon --}}
    <link
        rel="icon"
        type="image/jpeg"
        href="{{ asset('image/icon.jpg') }}"
    >

    <link
        rel="shortcut icon"
        type="image/jpeg"
        href="{{ asset('image/icon.jpg') }}"
    >

    {{-- Typography --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons used throughout the application --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- Chart.js is exposed globally so Blade chart scripts can use new Chart(...) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

    @stack('styles')

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body>

<div class="app-wrapper">


    {{-- =====================================================
        SIDEBAR OVERLAY
    ====================================================== --}}

    <div class="sidebar-overlay"></div>


    {{-- =====================================================
        SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">


        {{-- BRAND --}}

        <div class="sidebar-brand">

            <div class="brand-icon">

                <i class="bi bi-battery-charging"></i>

            </div>

            <div>

                <div class="brand-title">
                    BatteryLife AI
                </div>

                <div class="brand-subtitle">
                    Battery Intelligence
                </div>

            </div>

        </div>


        {{-- NAVIGATION --}}

        <nav class="sidebar-nav">


            {{-- MAIN --}}

            <div class="sidebar-section-title">
                MAIN
            </div>


            <a
                href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >

                <i class="bi bi-grid-1x2"></i>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="{{ route('batteries.index') }}"
                class="sidebar-link {{ request()->routeIs('batteries.*') ? 'active' : '' }}"
            >

                <i class="bi bi-battery-half"></i>

                <span>
                    Batteries
                </span>

            </a>


            {{-- DATASET IMPORT --}}

            <a
                href="{{ route('batteries.import.create') }}"
                class="sidebar-link {{ request()->routeIs('batteries.import.*') ? 'active' : '' }}"
            >

                <i class="bi bi-database-add"></i>

                <span>
                    Import Dataset
                </span>

            </a>


            {{-- AI PREDICTIONS --}}

            <div class="sidebar-section-title mt-3">
                AI PREDICTIONS
            </div>


            <a
                href="{{ route('reports.index') }}"
                class="sidebar-link {{ request()->routeIs('batteries.predictions.*') ? 'active' : '' }}"
            >

                <i class="bi bi-cpu"></i>

                <span>
                    AI Predictions
                </span>

            </a>


            <a
                href="{{ route('reports.index') }}"
                class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
            >

                <i class="bi bi-clock-history"></i>

                <span>
                    Prediction History
                </span>

            </a>


            {{-- SYSTEM --}}

            <div class="sidebar-section-title mt-3">
                SYSTEM
            </div>


            <a
                href="{{ route('reports.index') }}"
                class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
            >

                <i class="bi bi-bar-chart-line"></i>

                <span>
                    Reports
                </span>

            </a>


            <a
                href="{{ route('notifications.index') }}"
                class="sidebar-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}"
            >

                <i class="bi bi-bell"></i>

                <span>
                    Notifications
                </span>


                @php

                    $sidebarUnreadNotifications =
                        auth()->user()
                            ->notifications()
                            ->where('is_read', false)
                            ->count();

                @endphp


                @if($sidebarUnreadNotifications > 0)

                    <span class="sidebar-notification-count">

                        {{
                            $sidebarUnreadNotifications > 99
                                ? '99+'
                                : $sidebarUnreadNotifications
                        }}

                    </span>

                @endif

            </a>


            <a
                href="{{ route('settings.index') }}"
                class="sidebar-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"
            >

                <i class="bi bi-gear"></i>

                <span>
                    Settings
                </span>

            </a>


            {{-- ACCOUNT --}}

            <div class="sidebar-section-title mt-3">
                ACCOUNT
            </div>


            <a
                href="{{ route('profile.index') }}"
                class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
            >

                <i class="bi bi-person-circle"></i>

                <span>
                    My Profile
                </span>

            </a>


            {{-- ADMINISTRATION --}}

            @if(auth()->user()->role === 'admin')

                <div class="sidebar-section-title mt-3">
                    ADMINISTRATION
                </div>


                <a
                    href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                >

                    <i class="bi bi-shield-check"></i>

                    <span>
                        Admin Dashboard
                    </span>

                </a>


                <a
                    href="{{ route('admin.users') }}"
                    class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                >

                    <i class="bi bi-people"></i>

                    <span>
                        User Management
                    </span>

                </a>

            @endif

        </nav>


        {{-- SYSTEM STATUS --}}

        <div class="sidebar-status">

            <span class="sidebar-status-dot"></span>

            <span class="sidebar-status-text">
                ML Prediction Service Online
            </span>

        </div>


        {{-- SIDEBAR USER --}}

        <div class="sidebar-user">

            <div class="sidebar-user-avatar">

                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

            </div>


            <div class="sidebar-user-info">

                <strong>
                    {{ auth()->user()->name }}
                </strong>

                <small>
                    {{ ucfirst(auth()->user()->role) }}
                </small>

            </div>

        </div>


        {{-- LOGOUT --}}

        <div class="sidebar-footer">

            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf

                <button
                    type="submit"
                    class="sidebar-logout"
                >

                    <i class="bi bi-box-arrow-right"></i>

                    <span>
                        Logout
                    </span>

                </button>

            </form>

        </div>


    </aside>


    {{-- =====================================================
        MAIN AREA
    ====================================================== --}}

    <div class="main-area">


        {{-- =================================================
            TOPBAR
        ================================================== --}}

        <header class="topbar">


            <div class="d-flex align-items-center">


                {{-- MOBILE MENU --}}

                <button
                    type="button"
                    class="mobile-sidebar-toggle"
                    data-sidebar-toggle
                    aria-label="Open navigation"
                >

                    <i class="bi bi-list"></i>

                </button>


                {{-- PAGE TITLE --}}

                <div class="topbar-title">

                    @yield(
                        'topbar-title',
                        'Battery Intelligence Dashboard'
                    )

                </div>

            </div>


            <div class="topbar-actions">


                {{-- =================================================
                    NOTIFICATION BELL
                ================================================== --}}

                @php

                    $unreadNotifications =
                        auth()->user()
                            ->notifications()
                            ->where('is_read', false)
                            ->latest()
                            ->take(5)
                            ->get();

                    $unreadNotificationCount =
                        auth()->user()
                            ->notifications()
                            ->where('is_read', false)
                            ->count();

                @endphp


                <div class="notification-dropdown-wrapper">


                    <button
                        type="button"
                        class="topbar-icon-button"
                        id="notificationButton"
                        aria-label="Notifications"
                    >

                        <i class="bi bi-bell"></i>


                        @if($unreadNotificationCount > 0)

                            <span class="notification-badge">

                                {{
                                    $unreadNotificationCount > 99
                                        ? '99+'
                                        : $unreadNotificationCount
                                }}

                            </span>

                        @endif

                    </button>


                    {{-- NOTIFICATION DROPDOWN --}}

                    <div
                        class="notification-dropdown"
                        id="notificationDropdown"
                    >


                        <div class="notification-dropdown-header">

                            <div>

                                <strong>
                                    Notifications
                                </strong>

                                <small>
                                    BatteryLife AI alerts
                                </small>

                            </div>


                            @if($unreadNotificationCount > 0)

                                <span class="notification-count-label">

                                    {{ $unreadNotificationCount }}
                                    unread

                                </span>

                            @endif

                        </div>


                        <div class="notification-dropdown-body">


                            @forelse(
                                $unreadNotifications
                                as $notification
                            )


                                <form
                                    action="{{ route(
                                        'notifications.read',
                                        $notification
                                    ) }}"
                                    method="POST"
                                    class="notification-dropdown-item-form"
                                >

                                    @csrf


                                    <button
                                        type="submit"
                                        class="notification-dropdown-item"
                                    >


                                        <div
                                            class="
                                                notification-dropdown-icon
                                                notification-dropdown-{{
                                                    $notification->severity
                                                }}
                                            "
                                        >

                                            @if(
                                                $notification->severity
                                                === 'danger'
                                            )

                                                <i class="bi bi-exclamation-octagon"></i>

                                            @elseif(
                                                $notification->severity
                                                === 'warning'
                                            )

                                                <i class="bi bi-exclamation-triangle"></i>

                                            @elseif(
                                                $notification->severity
                                                === 'success'
                                            )

                                                <i class="bi bi-check-circle"></i>

                                            @else

                                                <i class="bi bi-info-circle"></i>

                                            @endif

                                        </div>


                                        <div
                                            class="notification-dropdown-content"
                                        >

                                            <strong>

                                                {{ $notification->title }}

                                            </strong>


                                            <p>

                                                {{
                                                    \Illuminate\Support\Str::limit(
                                                        $notification->message,
                                                        90
                                                    )
                                                }}

                                            </p>


                                            <small>

                                                {{
                                                    $notification
                                                        ->created_at
                                                        ->diffForHumans()
                                                }}

                                            </small>

                                        </div>


                                    </button>

                                </form>


                            @empty


                                <div class="notification-dropdown-empty">

                                    <i class="bi bi-bell-slash"></i>

                                    <span>
                                        No new notifications
                                    </span>

                                </div>


                            @endforelse


                        </div>


                        <div class="notification-dropdown-footer">

                            <a
                                href="{{ route('notifications.index') }}"
                            >

                                View All Notifications

                            </a>

                        </div>


                    </div>

                </div>


                {{-- =================================================
                    USER
                ================================================== --}}

                <a
                    href="{{ route('profile.index') }}"
                    class="topbar-user"
                >

                    <div class="topbar-user-avatar">

                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                    </div>


                    <div class="topbar-user-info">

                        <strong>
                            {{ auth()->user()->name }}
                        </strong>

                        <small>
                            {{ ucfirst(auth()->user()->role) }}
                        </small>

                    </div>

                </a>


            </div>

        </header>


        {{-- =====================================================
            PAGE CONTENT
        ====================================================== --}}

        <main class="content-area">


            {{-- SUCCESS MESSAGE --}}

            @if(session('success'))

                <div class="alert alert-success mb-3">

                    <i class="bi bi-check-circle me-2"></i>

                    {{ session('success') }}

                </div>

            @endif


            {{-- ERROR MESSAGE --}}

            @if(session('error'))

                <div class="alert alert-danger mb-3">

                    <i class="bi bi-exclamation-circle me-2"></i>

                    {{ session('error') }}

                </div>

            @endif


            @yield('content')


        </main>


        {{-- =====================================================
            FOOTER
        ====================================================== --}}

        <footer class="app-footer">

            <span>
                © {{ date('Y') }} BatteryLife AI
            </span>

            <span>
                Laravel 13 · Python ML · FastAPI
            </span>

        </footer>


    </div>

</div>


@stack('scripts')


{{-- =========================================================
    NOTIFICATION + SIDEBAR JAVASCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {


    /* =====================================================
       SIDEBAR
    ===================================================== */

    const sidebar =
        document.querySelector('.sidebar');

    const overlay =
        document.querySelector('.sidebar-overlay');

    const sidebarToggle =
        document.querySelector('[data-sidebar-toggle]');


    if (
        sidebar &&
        overlay &&
        sidebarToggle
    ) {

        sidebarToggle.addEventListener(
            'click',
            function () {

                sidebar.classList.toggle('show');

                overlay.classList.toggle('show');

            }
        );


        overlay.addEventListener(
            'click',
            function () {

                sidebar.classList.remove('show');

                overlay.classList.remove('show');

            }
        );


        document
            .querySelectorAll('.sidebar-link')
            .forEach(function (link) {

                link.addEventListener(
                    'click',
                    function () {

                        if (
                            window.innerWidth <= 991
                        ) {

                            sidebar.classList.remove('show');

                            overlay.classList.remove('show');

                        }

                    }
                );

            });

    }


    /* =====================================================
       NOTIFICATIONS
    ===================================================== */

    const notificationButton =
        document.getElementById(
            'notificationButton'
        );

    const notificationDropdown =
        document.getElementById(
            'notificationDropdown'
        );


    if (
        notificationButton &&
        notificationDropdown
    ) {


        notificationButton.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();

                notificationDropdown.classList.toggle(
                    'show'
                );

            }
        );


        document.addEventListener(
            'click',
            function () {

                notificationDropdown.classList.remove(
                    'show'
                );

            }
        );


        notificationDropdown.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();

            }
        );

    }

});

</script>


</body>

</html>