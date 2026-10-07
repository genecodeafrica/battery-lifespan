<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {

            if (Auth::user()->status !== 'active') {

                Auth::logout();

                return back()
                    ->withErrors([
                        'email' =>
                            'Your account has been deactivated. Please contact an administrator.',
                    ])
                    ->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()
                ->intended(route('dashboard'))
                ->with(
                    'success',
                    'Welcome back to BatteryLife AI.'
                );
        }

        return back()
            ->withErrors([
                'email' =>
                    'The email address or password is incorrect.',
            ])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        $user = User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make($validated['password']),

            'role' => 'user',
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Your BatteryLife AI account has been created successfully.'
            );
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect to the BatteryLife AI landing page after logout
        return redirect('/')
            ->with(
                'success',
                'You have been logged out successfully.'
            );
    }
}
