@extends('layouts.app')

@section('title', 'Measurement Details')

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h1 class="page-title">
                Measurement Details
            </h1>

            <p class="page-description">
                Cycle {{ number_format($measurement->cycle) }}
                · {{ $battery->battery_code }}
            </p>

        </div>

        <a
            href="{{ route(
                'batteries.measurements.index',
                $battery
            ) }}"
            class="btn btn-outline-light">

            <i class="bi bi-arrow-left me-1"></i>

            Measurement History

        </a>

    </div>

</div>


<div class="row g-3">

    <div class="col-md-6 col-xl-4">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Cycle
            </div>

            <div class="kpi-value">
                {{ number_format($measurement->cycle) }}
            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-4">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Capacity
            </div>

            <div class="kpi-value">

                {{ number_format(
                    $measurement->capacity_mAh,
                    2
                ) }}

            </div>

            <div class="kpi-footer">
                mAh
            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-4">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Capacity Retention
            </div>

            <div class="kpi-value">

                @if($measurement->capacity_retention !== null)

                    {{ number_format(
                        $measurement->capacity_retention * 100,
                        1
                    ) }}%

                @else
                    —
                @endif

            </div>

        </div>

    </div>


    <div class="col-12">

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <div>

                    <h2 class="dashboard-card-title">
                        Measurement Parameters
                    </h2>

                    <div class="dashboard-card-subtitle">
                        Recorded battery characteristics
                    </div>

                </div>

            </div>


            <div class="dashboard-card-body">

                <div class="row g-3">

                    <div class="col-md-4">

                        <div class="model-info">

                            <span class="model-info-label">
                                Capacity Loss
                            </span>

                            <span class="model-info-value">

                                {{ number_format(
                                    $measurement->capacity_loss_mAh,
                                    4
                                ) }}

                                mAh

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="model-info">

                            <span class="model-info-label">
                                Start Voltage
                            </span>

                            <span class="model-info-value">

                                {{ $measurement->start_voltage_V !== null
                                    ? number_format(
                                        $measurement->start_voltage_V,
                                        4
                                    ) . ' V'
                                    : '—' }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="model-info">

                            <span class="model-info-label">
                                End Voltage
                            </span>

                            <span class="model-info-value">

                                {{ $measurement->end_voltage_V !== null
                                    ? number_format(
                                        $measurement->end_voltage_V,
                                        4
                                    ) . ' V'
                                    : '—' }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="model-info">

                            <span class="model-info-label">
                                Average Temperature
                            </span>

                            <span class="model-info-value">

                                {{ $measurement->avg_temp_C !== null
                                    ? number_format(
                                        $measurement->avg_temp_C,
                                        2
                                    ) . ' °C'
                                    : '—' }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="model-info">

                            <span class="model-info-label">
                                Duration
                            </span>

                            <span class="model-info-value">

                                {{ $measurement->duration_s !== null
                                    ? number_format(
                                        $measurement->duration_s,
                                        2
                                    ) . ' s'
                                    : '—' }}

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="model-info">

                            <span class="model-info-label">
                                Samples
                            </span>

                            <span class="model-info-value">

                                {{ $measurement->n_samples !== null
                                    ? number_format(
                                        $measurement->n_samples
                                    )
                                    : '—' }}

                            </span>

                        </div>

                    </div>


                    <div class="col-12">

                        <div class="model-info">

                            <span class="model-info-label">
                                Degradation Rate
                            </span>

                            <span class="model-info-value">

                                {{ number_format(
                                    $measurement
                                        ->degradation_rate_mAh_per_cycle,
                                    8
                                ) }}

                                mAh/cycle

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection