<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Account | BatteryLife AI</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="auth-page">

<div class="auth-wrapper">

    <div class="auth-card">

        <div class="auth-brand">

            <div class="auth-logo">
                <i class="bi bi-battery-charging"></i>
            </div>

            <h1>BatteryLife AI</h1>

            <p>
                Create your account
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


        <form
            method="POST"
            action="{{ route('register') }}"
        >

            @csrf


            <div class="mb-3">

                <label class="form-label">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                >

            </div>


            <div class="mb-3">

                <label class="form-label">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Minimum 8 characters"
                    required
                >

            </div>


            <div class="mb-4">

                <label class="form-label">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Repeat your password"
                    required
                >

            </div>


            <button
                type="submit"
                class="btn btn-primary w-100"
            >

                <i class="bi bi-person-plus me-2"></i>

                Create Account

            </button>

        </form>


        <div class="auth-footer">

            <span>
                Already have an account?
            </span>

            <a href="{{ route('login') }}">
                Sign In
            </a>

        </div>

    </div>

</div>

</body>

</html>