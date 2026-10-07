<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();

        return view(
            'profile.index',
            compact('user')
        );
    }

    /**
     * Update profile information.
     */
    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

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
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],
        ]);

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->save();

        return redirect()
            ->route('profile.index')
            ->with(
                'success',
                'Your profile information has been updated successfully.'
            );
    }

    /**
     * Change the user's password.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8),
            ],
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (
            !Hash::check(
                $validated['current_password'],
                $user->password
            )
        ) {
            return back()
                ->withErrors([
                    'current_password' =>
                        'The current password is incorrect.',
                ])
                ->withInput();
        }

        $user->password =
            Hash::make(
                $validated['password']
            );

        $user->save();

        return redirect()
            ->route('profile.index')
            ->with(
                'success',
                'Your password has been changed successfully.'
            );
    }
}