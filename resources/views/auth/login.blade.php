<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | BatteryLife AI</title>

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
                Lithium-Ion Battery Lifespan Prediction
            </p>

        </div>


        @if(session('success'))

            <div class="alert alert-success">
                {{ session('success') }}
            </div>

        @endif


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
            action="{{ route('login') }}"
        >

            @csrf


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
                    autofocus
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
                    placeholder="Enter your password"
                    required
                >

            </div>


            <div class="d-flex justify-content-between align-items-center mb-4">

                <div class="form-check">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        id="remember"
                    >

                    <label
                        class="form-check-label"
                        for="remember"
                    >
                        Remember me
                    </label>

                </div>

            </div>


            <button
                type="submit"
                class="btn btn-primary w-100"
            >

                <i class="bi bi-box-arrow-in-right me-2"></i>

                Sign In

            </button>

        </form>


        <div class="auth-footer">

            <span>
                Don't have an account?
            </span>

            <a href="{{ route('register') }}">
                Create Account
            </a>

        </div>

    </div>

</div>

</body>

</html>