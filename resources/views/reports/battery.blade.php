@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1 class="page-title">
                Advanced Battery Analytics
            </h1>

            <p class="text-muted mb-0">

                {{ $battery->battery_code }}

                @if($battery->cell_name)
                    — {{ $battery->cell_name }}
                @endif

            </p>

        </div>

        <div class="d-flex gap-2">

            <a
                href="{{ route('reports.index') }}"
                class="btn btn-outline-light">

                <i class="bi bi-arrow-left me-1"></i>

                Reports

            </a>

            <button
                onclick="window.print()"
                class="btn btn-outline-light">

                <i class="bi bi-printer me-1"></i>

                Print

            </button>

            <a
                href="{{ route('reports.battery.pdf', $battery) }}"
                class="btn btn-primary">

                <i class="bi bi-file-earmark-pdf me-1"></i>

                Generate PDF

            </a>

        </div>

    </div>


    {{-- KPI CARDS --}}
    <div class="row g-4 mb-4">

        <div class="col-md-3">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-heart-pulse"></i>
                </div>

                <div>

                    <div class="kpi-label">
                        Battery Health
                    </div>

                    <h2>

                        {{ $healthPercentage !== null
                            ? $healthPercentage . '%'
                            : 'N/A'
                        }}

                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-arrow-repeat"></i>
                </div>

                <div>

                    <div class="kpi-label">
                        Current Cycle
                    </div>

                    <h2>

                        {{ $currentCycle !== null
                            ? number_format($currentCycle)
                            : 'N/A'
                        }}

                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-graph-down"></i>
                </div>

                <div>

                    <div class="kpi-label">
                        Capacity Loss
                    </div>

                    <h2>

                        {{ $capacityLoss !== null
                            ? number_format(
                                $capacityLoss,
                                2
                            ) . ' mAh'
                            : 'N/A'
                        }}

                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="kpi-card">

                <div class="kpi-icon">
                    <i class="bi bi-cpu"></i>
                </div>

                <div>

                    <div class="kpi-label">
                        Latest RUL
                    </div>

                    <h2>

                        {{ $latestPrediction
                            ? number_format(
                                $latestPrediction
                                    ->predicted_rul_cycles,
                                0
                            )
                            : 'N/A'
                        }}

                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- HEALTH STATUS --}}
    <div class="card dashboard-card mb-4">

        <div class="p-4">

            <div class="d-flex align-items-center gap-3">

                <div class="kpi-icon">

                    <i class="bi bi-shield-check"></i>

                </div>

                <div>

                    <div class="kpi-label">
                        Battery Health Status
                    </div>

                    <h4 class="mb-0">
                        {{ $healthStatus }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- CAPACITY CHART --}}
    <div class="card dashboard-card mb-4">

        <div class="card-header-custom">

            <div>

                <h5>
                    Capacity Degradation
                </h5>

                <p>
                    Capacity change across battery cycles.
                </p>

            </div>

        </div>

        <div class="p-4">

            <div class="advanced-chart">

                <canvas id="capacityChart"></canvas>

            </div>

        </div>

    </div>


    {{-- RETENTION CHART --}}
    <div class="card dashboard-card mb-4">

        <div class="card-header-custom">

            <div>

                <h5>
                    Battery Capacity Retention
                </h5>

                <p>
                    Percentage of original capacity remaining.
                </p>

            </div>

        </div>

        <div class="p-4">

            <div class="advanced-chart">

                <canvas id="retentionChart"></canvas>

            </div>

        </div>

    </div>


    {{-- TEMPERATURE + VOLTAGE --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-6">

            <div class="card dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            Temperature Trend
                        </h5>

                        <p>
                            Average battery temperature.
                        </p>

                    </div>

                </div>

                <div class="p-4">

                    <div class="advanced-chart-small">

                        <canvas id="temperatureChart"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <div class="col-lg-6">

            <div class="card dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            Voltage Trend
                        </h5>

                        <p>
                            Start and end voltage measurements.
                        </p>

                    </div>

                </div>

                <div class="p-4">

                    <div class="advanced-chart-small">

                        <canvas id="voltageChart"></canvas>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- DEGRADATION RATE --}}
    <div class="card dashboard-card mb-4">

        <div class="card-header-custom">

            <div>

                <h5>
                    Degradation Rate
                </h5>

                <p>
                    Change in capacity per cycle.
                </p>

            </div>

        </div>

        <div class="p-4">

            <div class="advanced-chart">

                <canvas id="degradationChart"></canvas>

            </div>

        </div>

    </div>


    {{-- RUL PREDICTION CHART --}}
    <div class="card dashboard-card mb-4">

        <div class="card-header-custom">

            <div>

                <h5>
                    AI Remaining Useful Life Predictions
                </h5>

                <p>
                    RUL estimates generated by the machine learning model.
                </p>

            </div>

        </div>

        <div class="p-4">

            @if($predictions->count() > 0)

                <div class="advanced-chart">

                    <canvas id="rulChart"></canvas>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-cpu fs-1 d-block mb-3"></i>

                    <p class="text-muted mb-0">

                        No AI predictions are available yet.

                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- MODEL INFORMATION --}}
    <div class="card dashboard-card mb-4">

        <div class="card-header-custom">

            <div>

                <h5>
                    Machine Learning Model
                </h5>

                <p>
                    Model used for battery lifespan prediction.
                </p>

            </div>

        </div>

        <div class="row g-4 p-4">

            <div class="col-md-3">

                <div class="mini-stat">

                    <span>
                        Model
                    </span>

                    <strong>
                        {{ $modelInfo['name'] }}
                    </strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="mini-stat">

                    <span>
                        MAE
                    </span>

                    <strong>
                        {{ $modelInfo['mae'] }}
                    </strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="mini-stat">

                    <span>
                        RMSE
                    </span>

                    <strong>
                        {{ $modelInfo['rmse'] }}
                    </strong>

                </div>

            </div>

            <div class="col-md-3">

                <div class="mini-stat">

                    <span>
                        R²
                    </span>

                    <strong>
                        {{ $modelInfo['r2'] }}
                    </strong>

                </div>

            </div>

        </div>

    </div>


    {{-- PREDICTION HISTORY --}}
    <div class="card dashboard-card">

        <div class="card-header-custom">

            <div>

                <h5>
                    Prediction History
                </h5>

                <p>
                    Historical AI predictions for this battery.
                </p>

            </div>

        </div>

        <div class="table-responsive">

            <table class="table table-dark table-hover mb-0">

                <thead>

                    <tr>

                        <th>Date</th>

                        <th>Cycle</th>

                        <th>Predicted RUL</th>

                        <th>Estimated EOL</th>

                        <th>Model</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($predictions as $prediction)

                        <tr>

                            <td>

                                {{ $prediction->created_at
                                    ->format('d M Y H:i')
                                }}

                            </td>

                            <td>

                                {{ $prediction->measurement
                                    ? number_format(
                                        $prediction
                                            ->measurement
                                            ->cycle
                                    )
                                    : 'N/A'
                                }}

                            </td>

                            <td>

                                <strong>

                                    {{ number_format(
                                        $prediction
                                            ->predicted_rul_cycles,
                                        0
                                    ) }}

                                    cycles

                                </strong>

                            </td>

                            <td>

                                {{ $prediction
                                    ->estimated_eol_cycle !== null
                                    ? number_format(
                                        $prediction
                                            ->estimated_eol_cycle
                                    )
                                    : 'N/A'
                                }}

                            </td>

                            <td>

                                {{ $prediction->model_name }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-5">

                                No prediction history available.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


<script>

    /*
    |--------------------------------------------------------------------------
    | Chart Data
    |--------------------------------------------------------------------------
    */

    const analyticsData =
        @json($chartData);

    const predictionData =
        @json($predictionChartData);


    /*
    |--------------------------------------------------------------------------
    | Common Chart Options
    |--------------------------------------------------------------------------
    */

    const commonOptions = {

        responsive: true,

        maintainAspectRatio: false,

        interaction: {
            intersect: false,
            mode: 'index'
        },

        plugins: {

            legend: {
                display: true
            }

        },

        scales: {

            x: {

                title: {
                    display: true,
                    text: 'Cycle'
                }

            }

        }

    };


    /*
    |--------------------------------------------------------------------------
    | Capacity Chart
    |--------------------------------------------------------------------------
    */

    const capacityCanvas =
        document.getElementById(
            'capacityChart'
        );

    if (capacityCanvas) {

        new Chart(
            capacityCanvas,
            {

                type: 'line',

                data: {

                    labels: analyticsData.map(
                        item => item.cycle
                    ),

                    datasets: [

                        {

                            label: 'Capacity (mAh)',

                            data: analyticsData.map(
                                item => item.capacity
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 2

                        }

                    ]

                },

                options: {

                    ...commonOptions,

                    scales: {

                        ...commonOptions.scales,

                        y: {

                            title: {

                                display: true,

                                text: 'Capacity (mAh)'

                            }

                        }

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Retention Chart
    |--------------------------------------------------------------------------
    */

    const retentionCanvas =
        document.getElementById(
            'retentionChart'
        );

    if (retentionCanvas) {

        new Chart(
            retentionCanvas,
            {

                type: 'line',

                data: {

                    labels: analyticsData.map(
                        item => item.cycle
                    ),

                    datasets: [

                        {

                            label: 'Capacity Retention (%)',

                            data: analyticsData.map(
                                item => item.retention
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 2

                        },

                        {

                            label: 'EOL Threshold (80%)',

                            data: analyticsData.map(
                                () => 80
                            ),

                            borderDash: [
                                6,
                                6
                            ],

                            borderWidth: 2,

                            pointRadius: 0

                        }

                    ]

                },

                options: {

                    ...commonOptions,

                    scales: {

                        ...commonOptions.scales,

                        y: {

                            min: 0,

                            max: 110,

                            title: {

                                display: true,

                                text: 'Retention (%)'

                            }

                        }

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Temperature Chart
    |--------------------------------------------------------------------------
    */

    const temperatureCanvas =
        document.getElementById(
            'temperatureChart'
        );

    if (temperatureCanvas) {

        new Chart(
            temperatureCanvas,
            {

                type: 'line',

                data: {

                    labels: analyticsData.map(
                        item => item.cycle
                    ),

                    datasets: [

                        {

                            label: 'Average Temperature (°C)',

                            data: analyticsData.map(
                                item => item.temperature
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 2

                        }

                    ]

                },

                options: {

                    ...commonOptions,

                    scales: {

                        ...commonOptions.scales,

                        y: {

                            title: {

                                display: true,

                                text: 'Temperature (°C)'

                            }

                        }

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Voltage Chart
    |--------------------------------------------------------------------------
    */

    const voltageCanvas =
        document.getElementById(
            'voltageChart'
        );

    if (voltageCanvas) {

        new Chart(
            voltageCanvas,
            {

                type: 'line',

                data: {

                    labels: analyticsData.map(
                        item => item.cycle
                    ),

                    datasets: [

                        {

                            label: 'Start Voltage (V)',

                            data: analyticsData.map(
                                item => item.start_voltage
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 2

                        },

                        {

                            label: 'End Voltage (V)',

                            data: analyticsData.map(
                                item => item.end_voltage
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 2

                        }

                    ]

                },

                options: {

                    ...commonOptions,

                    scales: {

                        ...commonOptions.scales,

                        y: {

                            title: {

                                display: true,

                                text: 'Voltage (V)'

                            }

                        }

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Degradation Rate Chart
    |--------------------------------------------------------------------------
    */

    const degradationCanvas =
        document.getElementById(
            'degradationChart'
        );

    if (degradationCanvas) {

        new Chart(
            degradationCanvas,
            {

                type: 'line',

                data: {

                    labels: analyticsData.map(
                        item => item.cycle
                    ),

                    datasets: [

                        {

                            label:
                                'Degradation Rate (mAh/cycle)',

                            data: analyticsData.map(
                                item =>
                                    item.degradation_rate
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 2

                        }

                    ]

                },

                options: {

                    ...commonOptions,

                    scales: {

                        ...commonOptions.scales,

                        y: {

                            title: {

                                display: true,

                                text:
                                    'mAh per Cycle'

                            }

                        }

                    }

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | RUL Prediction Chart
    |--------------------------------------------------------------------------
    */

    const rulCanvas =
        document.getElementById(
            'rulChart'
        );

    if (
        rulCanvas &&
        predictionData.length > 0
    ) {

        new Chart(
            rulCanvas,
            {

                type: 'line',

                data: {

                    labels: predictionData.map(
                        item => item.cycle
                    ),

                    datasets: [

                        {

                            label:
                                'Predicted RUL (cycles)',

                            data: predictionData.map(
                                item => item.rul
                            ),

                            tension: 0.3,

                            borderWidth: 2,

                            pointRadius: 4

                        }

                    ]

                },

                options: {

                    ...commonOptions,

                    scales: {

                        ...commonOptions.scales,

                        y: {

                            beginAtZero: true,

                            title: {

                                display: true,

                                text:
                                    'Remaining Useful Life (cycles)'

                            }

                        }

                    }

                }

            }
        );

    }

</script>


<style>

.advanced-chart {

    position: relative;

    height: 350px;

}

.advanced-chart-small {

    position: relative;

    height: 280px;

}


@media print {

    .sidebar,
    .topbar,
    .btn,
    nav {

        display: none !important;

    }


    .main-content {

        margin-left: 0 !important;

        width: 100% !important;

    }


    body {

        background: white !important;

        color: black !important;

    }


    .card {

        background: white !important;

        border: 1px solid #ddd !important;

        color: black !important;

    }


    .advanced-chart,
    .advanced-chart-small {

        break-inside: avoid;

    }

}

</style>

@endsection