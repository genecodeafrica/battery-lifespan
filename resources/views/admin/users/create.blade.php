@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="page-title">
            Create User
        </h2>

        <p class="text-muted">
            Create a new BatteryLife AI account
        </p>

    </div>


    <div class="dashboard-card">

        <form
            method="POST"
            action="{{ route('admin.users.store') }}"
        >

            @csrf

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="form-label">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
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
                        value="{{ old('email') }}"
                        required
                    >

                    @error('email')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Role
                    </label>

                    <select
                        name="role"
                        class="form-select"
                        required
                    >

                        <option value="user">
                            User
                        </option>

                        <option value="admin">
                            Administrator
                        </option>

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Account Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option value="active">
                            Active
                        </option>

                        <option value="inactive">
                            Inactive
                        </option>

                    </select>

                </div>

            </div>


            <div class="mt-4 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-person-plus me-2"></i>

                    Create User

                </button>


                <a
                    href="{{ route('admin.users') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection