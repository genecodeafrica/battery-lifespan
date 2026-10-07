@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="page-title">
            Edit User
        </h2>

        <p class="text-muted">
            Update account information and permissions
        </p>

    </div>


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="dashboard-card">

        <form
            method="POST"
            action="{{ route(
                'admin.users.update',
                $user
            ) }}"
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

                        <option
                            value="user"
                            @selected($user->role === 'user')
                        >
                            User
                        </option>

                        <option
                            value="admin"
                            @selected($user->role === 'admin')
                        >
                            Administrator
                        </option>

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option
                            value="active"
                            @selected($user->status === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            @selected($user->status === 'inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        New Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Leave blank to keep current password"
                    >

                </div>


                <div class="col-md-6">

                    <label class="form-label">
                        Confirm New Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                    >

                </div>

            </div>


            <div class="mt-4 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="bi bi-save me-2"></i>

                    Save Changes

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