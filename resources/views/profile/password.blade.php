@extends('layouts.app')

@section('title', 'Change Password')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="page-title">
            Change Password
        </h2>

        <p class="text-muted">
            Update your account password
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


    <div class="row">

        <div class="col-xl-7">

            <div class="dashboard-card">

                <form
                    method="POST"
                    action="{{ route(
                        'profile.password.update'
                    ) }}"
                >

                    @csrf

                    @method('PUT')


                    <div class="mb-4">

                        <label class="form-label">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-4">

                        <label class="form-label">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-4">

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


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-shield-check me-2"></i>

                        Change Password

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection