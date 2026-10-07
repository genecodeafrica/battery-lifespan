@extends('layouts.app')

@section('title', 'AI Lifespan Prediction')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="page-title">

                <i class="bi bi-cpu"></i>

                AI Lifespan Prediction

            </h1>

            <p class="text-muted mb-0">

                Machine learning analysis for

                <strong>
                    {{ $battery->battery_code }}
                </strong>

                @if($battery->cell_name)

                    <span class="ms-2">
                        — {{ $battery->cell_name }}
                    </span>

                @endif

            </p>

        </div>


        <div class="d-flex gap-2">

            <a
                href="{{ route('batteries.show', $battery) }}"
                class="btn btn-outline-light">

                <i class="bi bi-arrow-left"></i>

                Back to Battery

            </a>

        </div>

    </div>



    {{-- =========================================================
        ALERTS
    ========================================================== --}}

    @if(session('success'))

        <div class="alert alert-success">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">

            <i class="bi bi-exclamation-triangle me-2"></i>

            {{ session('error') }}

        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- =========================================================
        KPI CARDS
    ========================================================== --}}

    <div class="row g-4 mb-4">


        {{-- PREDICTED RUL --}}

        <div class="col-xl-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">

                    <i class="bi bi-hourglass-split"></i>

                </div>

                <div>

                    <div class="kpi-label">

                        Predicted Remaining Useful Life

                    </div>


                    <h2>

                        @if($latestPrediction)

                            {{ number_format(
                                $latestPrediction->predicted_rul_cycles,
                                0
                            ) }}

                            <small>
                                cycles
                            </small>

                        @else

                            --

                        @endif

                    </h2>

                </div>

            </div>

        </div>



        {{-- ESTIMATED EOL --}}

        <div class="col-xl-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">

                    <i class="bi bi-calendar-check"></i>

                </div>

                <div>

                    <div class="kpi-label">

                        Estimated End of Life

                    </div>


                    <h2>

                        @if($latestPrediction)

                            {{ number_format(
                                $latestPrediction->estimated_eol_cycle
                            ) }}

                        @else

                            --

                        @endif

                    </h2>

                </div>

            </div>

        </div>



        {{-- BATTERY HEALTH --}}

        <div class="col-xl-4 col-md-6">

            <div class="kpi-card">

                <div class="kpi-icon">

                    <i class="bi bi-heart-pulse"></i>

                </div>

                <div>

                    <div class="kpi-label">

                        Battery Health

                    </div>


                    <h2>

                        @if($healthPercentage !== null)

                            {{ number_format(
                                $healthPercentage,
                                1
                            ) }}%

                        @else

                            --

                        @endif

                    </h2>


                    @if($healthPercentage !== null)

                        <span class="health-status">

                            {{ $healthStatus }}

                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        MAIN AREA
    ========================================================== --}}

    <div class="row g-4">


        {{-- =====================================================
            DEGRADATION CHART
        ====================================================== --}}

        <div class="col-xl-8">

            <div class="dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>

                            <i class="bi bi-graph-down me-2"></i>

                            Battery Capacity Degradation

                        </h5>

                        <p>

                            Battery capacity against cycle number

                        </p>

                    </div>

                </div>


                <div class="chart-container">

                    <canvas
                        id="degradationChart">
                    </canvas>

                </div>

            </div>

        </div>



        {{-- =====================================================
            AI PREDICTION PANEL
        ====================================================== --}}

        <div class="col-xl-4">

            <div class="dashboard-card prediction-card h-100">


                <div class="card-header-custom">

                    <div>

                        <h5>

                            <i class="bi bi-robot me-2"></i>

                            Run AI Prediction

                        </h5>

                        <p>

                            Select a battery cycle to estimate
                            remaining lifespan.

                        </p>

                    </div>

                </div>


                <div class="prediction-body">


                    @if($battery->measurements->count() > 0)


                        {{-- =================================================
                            MEASUREMENT SELECTOR
                        ================================================== --}}

                        <div class="measurement-selector">


                            <label
                                for="measurement_id"
                                class="form-label">

                                <i class="bi bi-activity me-1"></i>

                                Select Battery Measurement

                            </label>


                            <select
                                name="measurement_id"
                                id="measurement_id"
                                class="form-select"
                                required>

                                <option value="">

                                    -- Choose a cycle --

                                </option>


                                @foreach(
                                    $battery->measurements
                                        ->sortByDesc('cycle')
                                    as $measurement
                                )

                                    <option
                                        value="{{ $measurement->id }}">

                                        Cycle
                                        {{ number_format(
                                            $measurement->cycle
                                        ) }}

                                        —
                                        {{ number_format(
                                            $measurement->capacity_mAh,
                                            2
                                        ) }}
                                        mAh

                                        —
                                        {{ number_format(
                                            $measurement
                                                ->capacity_retention
                                                * 100,
                                            1
                                        ) }}% health

                                    </option>

                                @endforeach

                            </select>


                            <div class="selector-help">

                                <i class="bi bi-info-circle me-1"></i>

                                Choose the cycle from which you want
                                the AI to estimate the remaining useful
                                life of the battery.

                            </div>

                        </div>



                        {{-- =================================================
                            SELECTED MEASUREMENT INFORMATION
                        ================================================== --}}

                        <div
                            id="selectedMeasurementInfo"
                            class="selected-measurement-info mt-3 d-none">


                            <div class="selected-measurement-title">

                                <i class="bi bi-check-circle me-2"></i>

                                Selected Measurement

                            </div>


                            <div class="row g-2">


                                {{-- CYCLE --}}

                                <div class="col-6">

                                    <div class="mini-stat">

                                        <span>
                                            Cycle
                                        </span>

                                        <strong
                                            id="selectedCycle">
                                            --
                                        </strong>

                                    </div>

                                </div>



                                {{-- CAPACITY --}}

                                <div class="col-6">

                                    <div class="mini-stat">

                                        <span>
                                            Capacity
                                        </span>

                                        <strong
                                            id="selectedCapacity">
                                            --
                                        </strong>

                                    </div>

                                </div>



                                {{-- HEALTH --}}

                                <div class="col-6">

                                    <div class="mini-stat">

                                        <span>
                                            Battery Health
                                        </span>

                                        <strong
                                            id="selectedRetention">
                                            --
                                        </strong>

                                    </div>

                                </div>



                                {{-- TEMPERATURE --}}

                                <div class="col-6">

                                    <div class="mini-stat">

                                        <span>
                                            Temperature
                                        </span>

                                        <strong
                                            id="selectedTemperature">
                                            --
                                        </strong>

                                    </div>

                                </div>



                                {{-- START VOLTAGE --}}

                                <div class="col-6">

                                    <div class="mini-stat">

                                        <span>
                                            Start Voltage
                                        </span>

                                        <strong
                                            id="selectedStartVoltage">
                                            --
                                        </strong>

                                    </div>

                                </div>



                                {{-- END VOLTAGE --}}

                                <div class="col-6">

                                    <div class="mini-stat">

                                        <span>
                                            End Voltage
                                        </span>

                                        <strong
                                            id="selectedEndVoltage">
                                            --
                                        </strong>

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- =================================================
                            PREDICTION FORM
                        ================================================== --}}

                        <form
                            id="predictionForm"
                            action="{{ route(
                                'batteries.predictions.predict',
                                $battery
                            ) }}"
                            method="POST"
                            class="mt-4">

                            @csrf


                            <input
                                type="hidden"
                                name="measurement_id"
                                id="predictionMeasurementId">


                            <button
                                type="submit"
                                id="predictButton"
                                class="btn btn-primary w-100"
                                disabled>

                                <i class="bi bi-cpu me-2"></i>

                                Predict Remaining Lifespan

                            </button>

                        </form>


                    @else


                        {{-- NO MEASUREMENTS --}}

                        <div class="text-center py-5">

                            <i
                                class="bi bi-database-x fs-1">
                            </i>

                            <h5 class="mt-3">

                                No Measurements Available

                            </h5>

                            <p class="text-muted">

                                Add battery measurements before
                                running an AI prediction.

                            </p>


                            <a
                                href="{{ route(
                                    'batteries.measurements.create',
                                    $battery
                                ) }}"
                                class="btn btn-primary">

                                <i class="bi bi-plus-circle me-1"></i>

                                Add Measurement

                            </a>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>



    {{-- =========================================================
        MODEL + LATEST MEASUREMENT
    ========================================================== --}}

    <div class="row g-4 mt-1">


        {{-- MODEL INFORMATION --}}

        <div class="col-xl-5">

            <div class="dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>

                            <i class="bi bi-cpu me-2"></i>

                            Machine Learning Model

                        </h5>

                        <p>

                            Model used by the prediction service

                        </p>

                    </div>

                </div>


                <div class="model-info">


                    <div class="mini-stat">

                        <span>
                            Model
                        </span>

                        <strong>
                            {{ $modelInfo['name'] }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            MAE
                        </span>

                        <strong>
                            {{ number_format(
                                $modelInfo['mae'],
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            RMSE
                        </span>

                        <strong>
                            {{ number_format(
                                $modelInfo['rmse'],
                                2
                            ) }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            R² Score
                        </span>

                        <strong>
                            {{ number_format(
                                $modelInfo['r2'],
                                4
                            ) }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            Estimators
                        </span>

                        <strong>
                            {{ $modelInfo['estimators'] }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            Learning Rate
                        </span>

                        <strong>
                            {{ $modelInfo['learning_rate'] }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            Max Depth
                        </span>

                        <strong>
                            {{ $modelInfo['max_depth'] }}
                        </strong>

                    </div>


                    <div class="mini-stat">

                        <span>
                            Validation
                        </span>

                        <strong>
                            {{ $modelInfo['validation'] }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>



        {{-- LATEST MEASUREMENT --}}

        <div class="col-xl-7">

            <div class="dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>

                            <i class="bi bi-activity me-2"></i>

                            Latest Battery Measurement

                        </h5>

                        <p>

                            Most recently recorded battery condition

                        </p>

                    </div>

                </div>


                @if($latestMeasurement)


                    <div class="row g-3">


                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Cycle
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement->cycle
                                    ) }}

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Capacity
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement->capacity_mAh,
                                        2
                                    ) }}

                                    mAh

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Capacity Retention
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement
                                            ->capacity_retention * 100,
                                        2
                                    ) }}%

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Temperature
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement->avg_temp_C,
                                        2
                                    ) }}

                                    °C

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Start Voltage
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement
                                            ->start_voltage_V,
                                        3
                                    ) }}

                                    V

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    End Voltage
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement
                                            ->end_voltage_V,
                                        3
                                    ) }}

                                    V

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Capacity Loss
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement
                                            ->capacity_loss_mAh,
                                        2
                                    ) }}

                                    mAh

                                </strong>

                            </div>

                        </div>



                        <div class="col-md-6">

                            <div class="mini-stat">

                                <span>
                                    Degradation Rate
                                </span>

                                <strong>

                                    {{ number_format(
                                        $latestMeasurement
                                            ->degradation_rate_mAh_per_cycle,
                                        6
                                    ) }}

                                </strong>

                            </div>

                        </div>

                    </div>


                @else

                    <div class="text-center py-5">

                        <p class="text-muted">

                            No measurement available.

                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>



    {{-- =========================================================
        PREDICTION HISTORY
    ========================================================== --}}

    <div class="dashboard-card mt-4">


        <div class="card-header-custom">

            <div>

                <h5>

                    <i class="bi bi-clock-history me-2"></i>

                    Prediction History

                </h5>

                <p>

                    Previous AI lifespan predictions

                </p>

            </div>

        </div>


        <div class="table-responsive">

            <table class="table table-dark table-hover align-middle">


                <thead>

                    <tr>

                        <th>
                            Date
                        </th>

                        <th>
                            Prediction Cycle
                        </th>

                        <th>
                            Battery Health
                        </th>

                        <th>
                            Predicted RUL
                        </th>

                        <th>
                            Estimated EOL
                        </th>

                        <th>
                            Model
                        </th>

                    </tr>

                </thead>


                <tbody>


                    @forelse(
                        $battery->predictions
                        as $prediction
                    )


                        <tr>


                            <td>

                                {{ $prediction->created_at
                                    ->format(
                                        'd M Y H:i'
                                    ) }}

                            </td>



                            <td>

                                @if($prediction->measurement)

                                    <span class="badge bg-secondary">

                                        Cycle

                                        {{ number_format(
                                            $prediction
                                                ->measurement
                                                ->cycle
                                        ) }}

                                    </span>

                                @else

                                    --

                                @endif

                            </td>



                            <td>

                                @if(
                                    $prediction->measurement &&
                                    $prediction->measurement
                                        ->capacity_retention
                                    !== null
                                )

                                    {{ number_format(
                                        $prediction
                                            ->measurement
                                            ->capacity_retention
                                            * 100,
                                        1
                                    ) }}%

                                @else

                                    --

                                @endif

                            </td>



                            <td>

                                <strong>

                                    {{ number_format(
                                        $prediction
                                            ->predicted_rul_cycles,
                                        0
                                    ) }}

                                </strong>

                                cycles

                            </td>



                            <td>

                                {{ number_format(
                                    $prediction
                                        ->estimated_eol_cycle
                                ) }}

                            </td>



                            <td>

                                <span
                                    class="badge bg-primary">

                                    {{ $prediction->model_name }}

                                </span>

                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-5">

                                <i
                                    class="bi bi-robot fs-2 d-block mb-2">
                                </i>

                                No predictions have been generated yet.

                            </td>

                        </tr>


                    @endforelse


                </tbody>

            </table>

        </div>

    </div>

</div>



{{-- =========================================================
    JAVASCRIPT
========================================================== --}}

<script>
document.addEventListener('DOMContentLoaded', function () {
    /*
    |--------------------------------------------------------------------------
    | Measurement Data
    |--------------------------------------------------------------------------
    */

    const measurements = @json($chartData ?? []);

    /*
    |--------------------------------------------------------------------------
    | Measurement Selector
    |--------------------------------------------------------------------------
    */

    const measurementSelector = document.getElementById('measurement_id');
    const selectedMeasurementInfo = document.getElementById('selectedMeasurementInfo');
    const predictionButton = document.getElementById('predictButton');
    const predictionMeasurementId = document.getElementById('predictionMeasurementId');


    if (measurementSelector) {

        measurementSelector.addEventListener('change', function () {

            const selectedId = Number(this.value);

            const measurement = measurements.find(
                item => Number(item.id) === selectedId
            );


            if (!measurement) {

                if (selectedMeasurementInfo) {
                    selectedMeasurementInfo.style.display = 'none';
                }

                if (predictionButton) {
                    predictionButton.disabled = true;
                }

                if (predictionMeasurementId) {
                    predictionMeasurementId.value = '';
                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Show Selected Measurement
            |--------------------------------------------------------------------------
            */

            if (selectedMeasurementInfo) {

                selectedMeasurementInfo.style.display = 'block';


                const cycleElement =
                    document.getElementById('selectedCycle');

                const capacityElement =
                    document.getElementById('selectedCapacity');

                const healthElement =
                    document.getElementById('selectedHealth');

                const temperatureElement =
                    document.getElementById('selectedTemperature');

                const startVoltageElement =
                    document.getElementById('selectedStartVoltage');

                const endVoltageElement =
                    document.getElementById('selectedEndVoltage');


                if (cycleElement) {
                    cycleElement.textContent =
                        Number(measurement.cycle).toLocaleString();
                }


                if (capacityElement) {
                    capacityElement.textContent =
                        Number(measurement.capacity).toFixed(2)
                        + ' mAh';
                }


                if (healthElement) {

                    const health =
                        Number(measurement.retention) * 100;

                    healthElement.textContent =
                        health.toFixed(1) + '%';
                }


                if (temperatureElement) {

                    if (
                        measurement.temperature !== null &&
                        measurement.temperature !== undefined
                    ) {

                        temperatureElement.textContent =
                            Number(measurement.temperature).toFixed(2)
                            + ' °C';

                    } else {

                        temperatureElement.textContent = 'N/A';
                    }
                }


                if (startVoltageElement) {

                    if (
                        measurement.start_voltage !== null &&
                        measurement.start_voltage !== undefined
                    ) {

                        startVoltageElement.textContent =
                            Number(measurement.start_voltage).toFixed(3)
                            + ' V';

                    } else {

                        startVoltageElement.textContent = 'N/A';
                    }
                }


                if (endVoltageElement) {

                    if (
                        measurement.end_voltage !== null &&
                        measurement.end_voltage !== undefined
                    ) {

                        endVoltageElement.textContent =
                            Number(measurement.end_voltage).toFixed(3)
                            + ' V';

                    } else {

                        endVoltageElement.textContent = 'N/A';
                    }
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Enable Prediction Button
            |--------------------------------------------------------------------------
            */

            if (predictionButton) {
                predictionButton.disabled = false;
            }


            if (predictionMeasurementId) {
                predictionMeasurementId.value =
                    measurement.id;
            }

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Prediction Form
    |--------------------------------------------------------------------------
    */

    const predictionForm =
        document.getElementById('predictionForm');


    if (predictionForm) {
        predictionForm.addEventListener('submit', function () {
            if (predictionButton) {
                predictionButton.disabled = true;
                predictionButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-2"></span>' +
                    'Running AI Prediction...';
            }
        });
    }
});
</script>

@endsection