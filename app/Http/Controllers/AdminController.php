<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Battery;
use App\Models\BatteryMeasurement;
use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();

        $activeUsers = User::where(
            'status',
            'active'
        )->count();

        $inactiveUsers = User::where(
            'status',
            'inactive'
        )->count();

        $totalAdmins = User::where(
            'role',
            'admin'
        )->count();

        $totalBatteries = Battery::count();

        $totalMeasurements =
            BatteryMeasurement::count();

        $totalPredictions =
            Prediction::count();

        $recentUsers = User::latest()
            ->take(8)
            ->get();

        $recentPredictions =
            Prediction::with('battery')
                ->latest()
                ->take(8)
                ->get();

        return view(
            'admin.dashboard',
            compact(
                'totalUsers',
                'activeUsers',
                'inactiveUsers',
                'totalAdmins',
                'totalBatteries',
                'totalMeasurements',
                'totalPredictions',
                'recentUsers',
                'recentPredictions'
            )
        );
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'email',
                    'like',
                    "%{$search}%"
                );
            });
        }

        if ($request->filled('role')) {

            $query->where(
                'role',
                $request->role
            );
        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }

        $users = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
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
                'min:8',
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'user',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],
        ]);

        User::create([

            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'role' =>
                $validated['role'],

            'status' =>
                $validated['status'],
        ]);

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'User account created successfully.'
            );
    }

    public function editUser(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }

    public function updateUser(
        Request $request,
        User $user
    ) {

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
                'unique:users,email,' . $user->id,
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'user',
                ]),
            ],

            'status' => [
                'required',
                Rule::in([
                    'active',
                    'inactive',
                ]),
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],
        ]);

        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];

        $user->role =
            $validated['role'];

        $user->status =
            $validated['status'];

        if (!empty($validated['password'])) {

            $user->password =
                Hash::make(
                    $validated['password']
                );
        }

        $user->save();

        return redirect()
            ->route('admin.users')
            ->with(
                'success',
                'User account updated successfully.'
            );
    }

    public function toggleStatus(User $user)
    {
        if ($user->id === Auth::id()) {

            return back()
                ->with(
                    'error',
                    'You cannot deactivate your own account.'
                );
        }

        $user->status =
            $user->status === 'active'
                ? 'inactive'
                : 'active';

        $user->save();

        return back()
            ->with(
                'success',
                'User status updated successfully.'
            );
    }

    public function destroyUser(User $user)
    {
        if ($user->id === Auth::id()) {

            return back()
                ->with(
                    'error',
                    'You cannot delete your own account.'
                );
        }

        $user->delete();

        return back()
            ->with(
                'success',
                'User account deleted successfully.'
            );
    }
}