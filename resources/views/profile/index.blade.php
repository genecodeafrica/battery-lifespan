@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="page-title">
            My Profile
        </h2>

        <p class="text-muted mb-0">
            Manage your BatteryLife AI account and security
        </p>

    </div>


    @if(session('success'))

        <div class="alert alert-success">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle me-2"></i>

            Please correct the errors below.

        </div>

    @endif


    <div class="row g-4">


        {{-- Profile Summary --}}

        <div class="col-xl-4">

            <div class="dashboard-card profile-summary">

                <div class="profile-avatar-large">

                    {{ strtoupper(
                        substr($user->name, 0, 1)
                    ) }}

                </div>


                <h4>
                    {{ $user->name }}
                </h4>


                <p class="text-muted">
                    {{ $user->email }}
                </p>


                <div class="profile-role">

                    @if($user->role === 'admin')

                        <span class="badge bg-primary">
                            <i class="bi bi-shield-lock me-1"></i>
                            Administrator
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            <i class="bi bi-person me-1"></i>
                            User
                        </span>

                    @endif

                </div>


                <div class="profile-account-status">

                    @if($user->status === 'active')

                        <span class="status-dot active"></span>

                        <span>
                            Active Account
                        </span>

                    @else

                        <span class="status-dot inactive"></span>

                        <span>
                            Inactive Account
                        </span>

                    @endif

                </div>


                <hr>


                <div class="profile-meta">

                    <div>

                        <span>
                            Member Since
                        </span>

                        <strong>
                            {{ $user->created_at->format('d M Y') }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Account ID
                        </span>

                        <strong>
                            #{{ $user->id }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- Profile Information --}}

        <div class="col-xl-8">

            <div class="dashboard-card mb-4">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            Profile Information
                        </h5>

                        <span>
                            Update your personal account information
                        </span>

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                >

                    @csrf

                    @method('PUT')


                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old(
                                    'name',
                                    $user->name
                                ) }}"
                                required
                            >

                            @error('name')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old(
                                    'email',
                                    $user->email
                                ) }}"
                                required
                            >

                            @error('email')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>

                    </div>


                    <div class="mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-save me-2"></i>

                            Save Profile

                        </button>

                    </div>

                </form>

            </div>


            {{-- Password --}}

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            Change Password
                        </h5>

                        <span>
                            Keep your account secure with a strong password
                        </span>

                    </div>

                    <i class="bi bi-shield-lock fs-3 text-primary"></i>

                </div>


                <form
                    method="POST"
                    action="{{ route('profile.password') }}"
                >

                    @csrf

                    @method('PUT')


                    <div class="row g-4">

                        <div class="col-12">

                            <label class="form-label">
                                Current Password
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                class="form-control"
                                required
                            >

                            @error('current_password')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required
                            >

                            @error('password')

                                <div class="text-danger small mt-1">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required
                            >

                        </div>

                    </div>


                    <div class="mt-4">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-key me-2"></i>

                            Change Password

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection