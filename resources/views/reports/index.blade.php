@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="page-title">
                Reports & Analytics
            </h1>

            <p class="text-muted mb-0">
                Analyze battery performance, degradation and AI predictions.
            </p>
        </div>

    </div>


    {{-- KPI CARDS --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-battery-half"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        Total Batteries
                    </div>

                    <h2>
                        {{ number_format($totalBatteries) }}
                    </h2>
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-activity"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        Measurements
                    </div>

                    <h2>
                        {{ number_format($totalMeasurements) }}
                    </h2>
                </div>

            </div>

        </div>


        <div class="col-md-4">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-cpu"></i>
                </div>

                <div>
                    <div class="kpi-label">
                        AI Predictions
                    </div>

                    <h2>
                        {{ number_format($totalPredictions) }}
                    </h2>
                </div>

            </div>

        </div>

    </div>


    {{-- BATTERY REPORTS --}}
    <div class="card dashboard-card mb-4">

        <div class="card-header-custom">

            <div>

                <h5>
                    Battery Reports
                </h5>

                <p>
                    Select a battery to view its complete analytical report.
                </p>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle mb-0">

                <thead>

                    <tr>

                        <th>Battery</th>

                        <th>Cell</th>

                        <th>Measurements</th>

                        <th>Predictions</th>

                        <th>Manufacturer</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($batteries as $battery)

                        <tr>

                            <td>

                                <strong>
                                    {{ $battery->battery_code }}
                                </strong>

                            </td>


                            <td>
                                {{ $battery->cell_name ?? 'N/A' }}
                            </td>


                            <td>
                                {{ number_format($battery->measurements_count) }}
                            </td>


                            <td>
                                {{ number_format($battery->predictions_count) }}
                            </td>


                            <td>
                                {{ $battery->manufacturer ?? 'N/A' }}
                            </td>


                            <td>

                                <a
                                    href="{{ route('reports.battery', $battery) }}"
                                    class="btn btn-primary btn-sm">

                                    <i class="bi bi-bar-chart-line me-1"></i>

                                    View Report

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5">

                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>

                                No batteries available.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- RECENT PREDICTIONS --}}
    <div class="card dashboard-card">

        <div class="card-header-custom">

            <div>

                <h5>
                    Recent AI Predictions
                </h5>

                <p>
                    Latest remaining useful life predictions.
                </p>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-dark table-hover mb-0">

                <thead>

                    <tr>

                        <th>Battery</th>

                        <th>Cycle</th>

                        <th>Predicted RUL</th>

                        <th>Estimated EOL</th>

                        <th>Model</th>

                        <th>Date</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($latestPredictions as $prediction)

                        <tr>

                            <td>
                                {{ $prediction->battery->battery_code }}
                            </td>


                            <td>

                                {{ $prediction->measurement
                                    ? number_format(
                                        $prediction->measurement->cycle
                                    )
                                    : 'N/A'
                                }}

                            </td>


                            <td>

                                <strong>

                                    {{ number_format(
                                        $prediction->predicted_rul_cycles,
                                        0
                                    ) }}

                                    cycles

                                </strong>

                            </td>


                            <td>

                                {{ $prediction->estimated_eol_cycle !== null
                                    ? number_format(
                                        $prediction->estimated_eol_cycle
                                    )
                                    : 'N/A'
                                }}

                            </td>


                            <td>
                                {{ $prediction->model_name }}
                            </td>


                            <td>

                                {{ $prediction->created_at
                                    ->format('d M Y H:i')
                                }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-5">

                                No predictions have been generated yet.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection