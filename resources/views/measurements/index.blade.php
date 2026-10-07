@extends('layouts.app')

@section('title', 'Measurement History')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h1 class="page-title">
                Measurement History
            </h1>

            <p class="page-description">
                Recorded performance data for
                {{ $battery->battery_code }}.
            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('batteries.show', $battery) }}"
                class="btn btn-outline-light">

                <i class="bi bi-arrow-left me-1"></i>

                Battery

            </a>

            <a
                href="{{ route(
                    'batteries.measurements.create',
                    $battery
                ) }}"
                class="btn btn-success">

                <i class="bi bi-plus-lg me-1"></i>

                Add Measurement

            </a>

        </div>

    </div>

</div>


<div class="dashboard-card">

    <div class="dashboard-card-header">

        <div>

            <h2 class="dashboard-card-title">
                {{ $battery->battery_code }}
            </h2>

            <div class="dashboard-card-subtitle">
                Cycle-by-cycle measurement records
            </div>

        </div>

        <span class="badge-healthy">
            {{ $measurements->total() }} Records
        </span>

    </div>


    @if($measurements->count())

        <div class="table-responsive">

            <table class="table table-dark-custom">

                <thead>

                    <tr>

                        <th>Cycle</th>
                        <th>Capacity</th>
                        <th>Retention</th>
                        <th>Capacity Loss</th>
                        <th>Start Voltage</th>
                        <th>End Voltage</th>
                        <th>Temperature</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                @foreach($measurements as $measurement)

                    <tr>

                        <td>
                            <strong>
                                {{ number_format($measurement->cycle) }}
                            </strong>
                        </td>

                        <td>
                            {{ number_format(
                                $measurement->capacity_mAh,
                                2
                            ) }}
                            mAh
                        </td>

                        <td>

                            @if($measurement->capacity_retention !== null)

                                {{ number_format(
                                    $measurement->capacity_retention * 100,
                                    1
                                ) }}%

                            @else
                                —
                            @endif

                        </td>

                        <td>

                            {{ number_format(
                                $measurement->capacity_loss_mAh,
                                2
                            ) }}

                            mAh

                        </td>

                        <td>
                            {{ $measurement->start_voltage_V !== null
                                ? number_format(
                                    $measurement->start_voltage_V,
                                    3
                                ) . ' V'
                                : '—' }}
                        </td>

                        <td>
                            {{ $measurement->end_voltage_V !== null
                                ? number_format(
                                    $measurement->end_voltage_V,
                                    3
                                ) . ' V'
                                : '—' }}
                        </td>

                        <td>
                            {{ $measurement->avg_temp_C !== null
                                ? number_format(
                                    $measurement->avg_temp_C,
                                    2
                                ) . ' °C'
                                : '—' }}
                        </td>

                        <td>

                            <a
                                href="{{ route(
                                    'batteries.measurements.show',
                                    [
                                        $battery,
                                        $measurement
                                    ]
                                ) }}"
                                class="btn btn-sm btn-outline-light">

                                <i class="bi bi-eye"></i>

                            </a>

                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>


        <div class="p-3">

           {{ $measurements->onEachSide(1)->links('pagination::bootstrap-5') }}

        </div>

    @else

        <div class="empty-state">

            <i class="bi bi-activity"></i>

            <div class="empty-state-title">
                No measurements found
            </div>

            <p class="empty-state-text">
                This battery does not have any measurement
                records yet.
            </p>

        </div>

    @endif

</div>

@endsection