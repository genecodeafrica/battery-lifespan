@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="page-title">
                Administration
            </h2>

            <p class="text-muted mb-0">
                BatteryLife AI system administration
            </p>

        </div>

        <a
            href="{{ route('admin.users.create') }}"
            class="btn btn-primary"
        >

            <i class="bi bi-person-plus me-2"></i>

            Add User

        </a>

    </div>


    <div class="row g-4 mb-4">

        <div class="col-xl-3 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>

                    <span class="kpi-label">
                        Total Users
                    </span>

                    <h3>
                        {{ number_format($totalUsers) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <div>

                    <span class="kpi-label">
                        Active Users
                    </span>

                    <h3>
                        {{ number_format($activeUsers) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-battery-charging"></i>
                </div>

                <div>

                    <span class="kpi-label">
                        Batteries
                    </span>

                    <h3>
                        {{ number_format($totalBatteries) }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-xl-3 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-cpu"></i>
                </div>

                <div>

                    <span class="kpi-label">
                        AI Predictions
                    </span>

                    <h3>
                        {{ number_format($totalPredictions) }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    <div class="row g-4">

        <div class="col-xl-7">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            Recent Users
                        </h5>

                        <span>
                            Latest registered accounts
                        </span>

                    </div>

                    <a
                        href="{{ route('admin.users') }}"
                        class="btn btn-sm btn-outline-primary"
                    >
                        View All
                    </a>

                </div>


                <div class="table-responsive">

                    <table class="table dashboard-table">

                        <thead>

                            <tr>

                                <th>
                                    User
                                </th>

                                <th>
                                    Role
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Registered
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($recentUsers as $user)

                                <tr>

                                    <td>

                                        <div class="d-flex align-items-center gap-2">

                                            <div class="user-avatar">

                                                {{ strtoupper(
                                                    substr($user->name, 0, 1)
                                                ) }}

                                            </div>

                                            <div>

                                                <strong>
                                                    {{ $user->name }}
                                                </strong>

                                                <small class="d-block text-muted">
                                                    {{ $user->email }}
                                                </small>

                                            </div>

                                        </div>

                                    </td>

                                    <td>

                                        <span class="badge bg-primary">

                                            {{ ucfirst($user->role) }}

                                        </span>

                                    </td>

                                    <td>

                                        @if($user->status === 'active')

                                            <span class="badge bg-success">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>

                                        @endif

                                    </td>

                                    <td>
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center"
                                    >
                                        No users found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="col-xl-5">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            System Overview
                        </h5>

                        <span>
                            Current platform statistics
                        </span>

                    </div>

                </div>


                <div class="admin-stat-list">

                    <div>

                        <span>
                            Administrators
                        </span>

                        <strong>
                            {{ number_format($totalAdmins) }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Inactive Users
                        </span>

                        <strong>
                            {{ number_format($inactiveUsers) }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Measurements
                        </span>

                        <strong>
                            {{ number_format($totalMeasurements) }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            AI Predictions
                        </span>

                        <strong>
                            {{ number_format($totalPredictions) }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection