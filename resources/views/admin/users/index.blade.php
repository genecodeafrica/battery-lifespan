@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="page-title">
                User Management
            </h2>

            <p class="text-muted mb-0">
                Manage BatteryLife AI accounts
            </p>

        </div>

        <a
            href="{{ route('admin.users.create') }}"
            class="btn btn-primary"
        >

            <i class="bi bi-person-plus me-2"></i>

            Add User

        </a>

    </div>


    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    <div class="dashboard-card mb-4">

        <form
            method="GET"
            action="{{ route('admin.users') }}"
        >

            <div class="row g-3">

                <div class="col-lg-5">

                    <label class="form-label">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Search name or email..."
                    >

                </div>


                <div class="col-lg-2">

                    <label class="form-label">
                        Role
                    </label>

                    <select
                        name="role"
                        class="form-select"
                    >

                        <option value="">
                            All Roles
                        </option>

                        <option
                            value="admin"
                            @selected(request('role') === 'admin')
                        >
                            Admin
                        </option>

                        <option
                            value="user"
                            @selected(request('role') === 'user')
                        >
                            User
                        </option>

                    </select>

                </div>


                <div class="col-lg-2">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="active"
                            @selected(request('status') === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="inactive"
                            @selected(request('status') === 'inactive')
                        >
                            Inactive
                        </option>

                    </select>

                </div>


                <div class="col-lg-3 d-flex align-items-end gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-search me-1"></i>

                        Search

                    </button>

                    <a
                        href="{{ route('admin.users') }}"
                        class="btn btn-outline-secondary"
                    >
                        Reset
                    </a>

                </div>

            </div>

        </form>

    </div>


    <div class="dashboard-card">

        <div class="table-responsive">

            <table class="table dashboard-table align-middle">

                <thead>

                    <tr>

                        <th>
                            User
                        </th>

                        <th>
                            Role
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Registered
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center gap-3">

                                    <div class="user-avatar">

                                        {{ strtoupper(
                                            substr($user->name, 0, 1)
                                        ) }}

                                    </div>

                                    <div>

                                        <strong>
                                            {{ $user->name }}
                                        </strong>

                                        <small class="d-block text-muted">
                                            {{ $user->email }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            <td>

                                @if($user->role === 'admin')

                                    <span class="badge bg-primary">
                                        Administrator
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        User
                                    </span>

                                @endif

                            </td>


                            <td>

                                @if($user->status === 'active')

                                    <span class="badge bg-success">
                                        Active
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Inactive
                                    </span>

                                @endif

                            </td>


                            <td>
                                {{ $user->created_at->format('d M Y') }}
                            </td>


                            <td class="text-end">

                                <div class="d-flex justify-content-end gap-1">

                                    <a
                                        href="{{ route(
                                            'admin.users.edit',
                                            $user
                                        ) }}"
                                        class="btn btn-sm btn-outline-primary"
                                        title="Edit"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    @if($user->id !== auth()->id())

                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.users.toggle-status',
                                                $user
                                            ) }}"
                                        >

                                            @csrf

                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Toggle status"
                                            >

                                                <i class="bi bi-power"></i>

                                            </button>

                                        </form>


                                        <form
                                            method="POST"
                                            action="{{ route(
                                                'admin.users.destroy',
                                                $user
                                            ) }}"
                                            onsubmit="return confirm(
                                                'Are you sure you want to delete this user?'
                                            );"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete"
                                            >

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5"
                            >

                                <i class="bi bi-people fs-1 text-muted"></i>

                                <p class="mt-2 mb-0">
                                    No users found.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        <div class="mt-3">

            {{ $users->links() }}

        </div>

    </div>

</div>

@endsection