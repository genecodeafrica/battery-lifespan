@extends('layouts.app')

@section('title', 'Batteries')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h1 class="page-title">
                Batteries
            </h1>

            <p class="page-description">
                Manage registered lithium-ion batteries
                and their monitoring data.
            </p>

        </div>


        <a
            href="{{ route('batteries.create') }}"
            class="btn btn-success">

            <i class="bi bi-plus-lg me-1"></i>

            Add Battery

        </a>

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

    </div>

@endif


<div class="dashboard-card">

    <div class="dashboard-card-header">

        <div>

            <h2 class="dashboard-card-title">
                Registered Batteries
            </h2>

            <div class="dashboard-card-subtitle">
                Battery monitoring inventory
            </div>

        </div>

        <span class="badge-healthy">
            {{ $batteries->total() }} Total
        </span>

    </div>


    @if($batteries->count())

        <div class="table-responsive">

            <table class="table table-dark-custom">

                <thead>

                    <tr>

                        <th>
                            Battery
                        </th>

                        <th>
                            Cell
                        </th>

                        <th>
                            Manufacturer
                        </th>

                        <th>
                            Measurements
                        </th>

                        <th>
                            Predictions
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                @foreach($batteries as $battery)

                    <tr>

                        <td>

                            <strong>
                                {{ $battery->battery_code }}
                            </strong>

                        </td>


                        <td>
                            {{ $battery->cell_name ?? '—' }}
                        </td>


                        <td>
                            {{ $battery->manufacturer ?? '—' }}
                        </td>


                        <td>
                            {{ $battery->measurements_count }}
                        </td>


                        <td>
                            {{ $battery->predictions_count }}
                        </td>


                        <td>

                            <a
                                href="{{ route(
                                    'batteries.show',
                                    $battery
                                ) }}"
                                class="btn btn-sm btn-outline-light">

                                <i class="bi bi-eye"></i>

                                View

                            </a>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>


        <div class="p-3">

            {{ $batteries->links() }}

        </div>

    @else

        <div class="empty-state">

            <i class="bi bi-battery"></i>

            <div class="empty-state-title">
                No batteries registered
            </div>

            <p class="empty-state-text">
                Register your first battery to begin
                monitoring and prediction.
            </p>

            <a
                href="{{ route('batteries.create') }}"
                class="btn btn-success mt-3">

                <i class="bi bi-plus-lg me-1"></i>

                Register Battery

            </a>

        </div>

    @endif

</div>

@endsection