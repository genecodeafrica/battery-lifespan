@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="page-title">
            Edit Profile
        </h2>

        <p class="text-muted">
            Update your personal information
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
                    href="{{ route('profile.index') }}"
                    class="btn btn-outline-secondary"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection