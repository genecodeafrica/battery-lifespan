@extends('layouts.app')

@section('title', $battery->battery_code)

@section('content')

<div class="page-header">

    <div class="d-flex justify-content-between align-items-center">

        <div>

            <h1 class="page-title">
                {{ $battery->battery_code }}
            </h1>

            <p class="page-description">
                Battery monitoring and performance information.
            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route(
                    'batteries.measurements.create',
                    $battery
                ) }}"
                class="btn btn-success">

                <i class="bi bi-plus-lg me-1"></i>

                Add Measurement

            </a>


            <a
                href="{{ route(
                    'batteries.edit',
                    $battery
                ) }}"
                class="btn btn-outline-light">

                <i class="bi bi-pencil me-1"></i>

                Edit

            </a>

            <a
                href="{{ route(
                    'batteries.predictions.index',
                    $battery
                ) }}"
                class="btn btn-primary">

                <i class="bi bi-cpu"></i>

                AI Lifespan Prediction

            </a>

        </div>

    </div>

</div>


@if(session('success'))

    <div class="alert alert-success">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

    </div>

@endif


{{-- BATTERY SUMMARY --}}
<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Battery Code
            </div>

            <div class="kpi-value"
                 style="font-size: 21px;">

                {{ $battery->battery_code }}

            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-3">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Initial Capacity
            </div>

            <div class="kpi-value">

                {{ $battery->initial_capacity_mAh
                    ? number_format(
                        $battery->initial_capacity_mAh,
                        1
                    )
                    : '—' }}

            </div>

            <div class="kpi-footer">
                mAh
            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-3">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Current Health
            </div>

            <div class="kpi-value">

                {{ $healthPercentage !== null
                    ? $healthPercentage . '%'
                    : '—' }}

            </div>

        </div>

    </div>


    <div class="col-md-6 col-xl-3">

        <div class="kpi-card">

            <div class="kpi-label mb-3">
                Latest RUL
            </div>

            <div class="kpi-value">

                {{ $latestPrediction
                    ? number_format(
                        $latestPrediction
                            ->predicted_rul_cycles,
                        0
                    )
                    : '—' }}

            </div>

            <div class="kpi-footer">
                cycles
            </div>

        </div>

    </div>

</div>


<div class="row g-3">

    {{-- BATTERY INFORMATION --}}
    <div class="col-12 col-xl-4">

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <div>

                    <h2 class="dashboard-card-title">
                        Battery Information
                    </h2>

                </div>

            </div>


            <div class="dashboard-card-body">

                <div class="model-info">

                    <span class="model-info-label">
                        Cell
                    </span>

                    <span class="model-info-value">
                        {{ $battery->cell_name ?? '—' }}
                    </span>

                </div>


                <div class="model-info">

                    <span class="model-info-label">
                        Manufacturer
                    </span>

                    <span class="model-info-value">
                        {{ $battery->manufacturer ?? '—' }}
                    </span>

                </div>


                <div class="model-info">

                    <span class="model-info-label">
                        Installation
                    </span>

                    <span class="model-info-value">

                        {{ $battery->installation_date
                            ? $battery
                                ->installation_date
                                ->format('d M Y')
                            : '—' }}

                    </span>

                </div>


                <div class="model-info">

                    <span class="model-info-label">
                        Measurements
                    </span>

                    <span class="model-info-value">

                        {{ $battery->measurements->count() }}

                    </span>

                </div>


                @if($battery->description)

                    <div class="mt-3">

                        <div class="model-info-label mb-2">
                            Description
                        </div>

                        <p
                            style="
                                color:#8a9ab0;
                                font-size:11px;
                                line-height:1.7;
                            ">

                            {{ $battery->description }}

                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>


    {{-- LATEST MEASUREMENT --}}
    <div class="col-12 col-xl-8">

        <div class="dashboard-card">

            <div class="dashboard-card-header">

                <div>

                    <h2 class="dashboard-card-title">
                        Latest Measurement
                    </h2>

                    <div class="dashboard-card-subtitle">
                        Most recent battery performance
                    </div>

                </div>


                <a
                    href="{{ route(
                        'batteries.measurements.index',
                        $battery
                    ) }}"
                    class="btn btn-sm btn-outline-light">

                    View History

                </a>

            </div>


            @if($latestMeasurement)

                <div class="dashboard-card-body">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <div class="model-info">

                                <span class="model-info-label">
                                    Cycle
                                </span>

                                <span class="model-info-value">

                                    {{ number_format(
                                        $latestMeasurement->cycle
                                    ) }}

                                </span>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="model-info">

                                <span class="model-info-label">
                                    Capacity
                                </span>

                                <span class="model-info-value">

                                    {{ number_format(
                                        $latestMeasurement
                                            ->capacity_mAh,
                                        2
                                    ) }}
                                    mAh

                                </span>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="model-info">

                                <span class="model-info-label">
                                    Retention
                                </span>

                                <span class="model-info-value">

                                    {{ number_format(
                                        $latestMeasurement
                                            ->capacity_retention * 100,
                                        1
                                    ) }}%

                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            @else

                <div class="empty-state">

                    <i class="bi bi-activity"></i>

                    <div class="empty-state-title">
                        No measurements
                    </div>

                    <p class="empty-state-text">
                        Add the first measurement for this battery.
                    </p>

                    <a
                        href="{{ route(
                            'batteries.measurements.create',
                            $battery
                        ) }}"
                        class="btn btn-success mt-3">

                        Add Measurement

                    </a>

                </div>

            @endif

        </div>

    </div>

</div>


{{-- DELETE --}}
<div class="mt-4">

    <div class="dashboard-card">

        <div class="dashboard-card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <div
                        style="
                            color:#fff;
                            font-size:12px;
                            font-weight:600;
                        ">

                        Delete Battery

                    </div>

                    <div
                        style="
                            color:#708198;
                            font-size:10px;
                            margin-top:4px;
                        ">

                        This will also remove its measurements
                        and predictions.

                    </div>

                </div>


                <form
                    method="POST"
                    action="{{ route(
                        'batteries.destroy',
                        $battery
                    ) }}"
                    onsubmit="
                        return confirm(
                            'Are you sure you want to delete this battery?'
                        );
                    ">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="btn btn-sm btn-outline-danger">

                        <i class="bi bi-trash me-1"></i>

                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection