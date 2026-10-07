@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="dashboard-page">

    {{-- PAGE HEADER --}}
    <div class="page-header dashboard-page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-description">
            Monitor battery performance and predict remaining useful life using machine learning.
        </p>
    </div>

    {{-- FOUR KPI CARDS --}}
    <div class="dashboard-kpi-grid">

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-label">Registered Batteries</div>
                <div class="kpi-icon"><i class="bi bi-battery-full"></i></div>
            </div>
            <div class="kpi-value">{{ number_format($totalBatteries) }}</div>
            <div class="kpi-footer">Batteries being monitored</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-label">Measurements</div>
                <div class="kpi-icon"><i class="bi bi-activity"></i></div>
            </div>
            <div class="kpi-value">{{ number_format($totalMeasurements) }}</div>
            <div class="kpi-footer">Battery measurement records</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-label">AI Predictions</div>
                <div class="kpi-icon"><i class="bi bi-cpu"></i></div>
            </div>
            <div class="kpi-value">{{ number_format($totalPredictions) }}</div>
            <div class="kpi-footer">Lifespan predictions generated</div>
        </div>

        <div class="kpi-card">
            <div class="kpi-top">
                <div class="kpi-label">Average RUL</div>
                <div class="kpi-icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
            <div class="kpi-value">
                @if($averageRul !== null)
                    {{ number_format($averageRul, 0) }}
                @else
                    —
                @endif
            </div>
            <div class="kpi-footer">Remaining useful life · cycles</div>
        </div>

    </div>

    {{-- CAPACITY + HEALTH --}}
    <div class="dashboard-two-column-grid">

        <div class="dashboard-card dashboard-capacity-card">
            <div class="dashboard-card-header">
                <div>
                    <h2 class="dashboard-card-title">Capacity Degradation</h2>
                    <div class="dashboard-card-subtitle">Latest monitored battery</div>
                </div>

                @if($latestMeasurement)
                    <span class="badge-healthy">Monitoring</span>
                @endif
            </div>

            <div class="dashboard-card-body">
                @if(count($chartValues) > 0)
                    <div class="chart-container dashboard-capacity-chart">
                        <canvas
                            id="capacityChart"
                            data-labels='@json($chartLabels)'
                            data-values='@json($chartValues)'>
                        </canvas>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-bar-chart-line"></i>
                        <div class="empty-state-title">No battery measurements yet</div>
                        <p class="empty-state-text">
                            Add battery measurement data to display the degradation curve.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <div class="dashboard-card dashboard-health-card">
            <div class="dashboard-card-header">
                <div>
                    <h2 class="dashboard-card-title">Battery Health</h2>
                    <div class="dashboard-card-subtitle">Current capacity retention</div>
                </div>
            </div>

            <div class="dashboard-card-body">
                <div
                    class="health-card"
                    style="--health-angle: {{ $healthPercentage !== null ? ($healthPercentage * 3.6) . 'deg' : '0deg' }};">

                    @if($healthPercentage !== null)
                        <div class="health-ring">
                            <div class="health-value">
                                <span class="health-number">{{ $healthPercentage }}%</span>
                                <span class="health-label">Capacity</span>
                            </div>
                        </div>

                        @if($healthPercentage >= 80)
                            <div class="health-status">Healthy</div>
                        @else
                            <div class="health-status" style="background: var(--bl-red-soft); color: var(--bl-red);">
                                Attention Required
                            </div>
                        @endif
                    @else
                        <div class="empty-state">
                            <i class="bi bi-battery"></i>
                            <div class="empty-state-title">No health data</div>
                            <p class="empty-state-text">
                                Battery measurement data will appear here.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- RECENT PREDICTIONS + AI MODEL --}}
    <div class="dashboard-two-column-grid dashboard-lower-grid">

        <div class="dashboard-card dashboard-predictions-card">
            <div class="dashboard-card-header">
                <div>
                    <h2 class="dashboard-card-title">Recent AI Predictions</h2>
                    <div class="dashboard-card-subtitle">Latest battery lifespan predictions</div>
                </div>
            </div>

            @if($recentPredictions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-dark-custom dashboard-predictions-table">
                        <thead>
                            <tr>
                                <th>Battery</th>
                                <th>RUL</th>
                                <th>EOL Cycle</th>
                                <th>Model</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($recentPredictions as $prediction)
                            <tr>
                                <td>
                                    <strong>{{ $prediction->battery->battery_code ?? '—' }}</strong>
                                </td>
                                <td>
                                    {{ number_format($prediction->predicted_rul_cycles, 0) }} cycles
                                </td>
                                <td>{{ $prediction->estimated_eol_cycle ?? '—' }}</td>
                                <td>{{ $prediction->model_name }}</td>
                                <td>
                                    @if($prediction->predicted_rul_cycles > 1000)
                                        <span class="badge-healthy">Healthy</span>
                                    @else
                                        <span class="badge-warning">Attention</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="bi bi-cpu"></i>
                    <div class="empty-state-title">No predictions available</div>
                    <p class="empty-state-text">
                        AI prediction results will appear here after the first prediction is performed.
                    </p>
                </div>
            @endif
        </div>

        <div class="dashboard-card dashboard-model-card">
            <div class="dashboard-card-header">
                <div>
                    <h2 class="dashboard-card-title">AI Model</h2>
                    <div class="dashboard-card-subtitle">Current prediction engine</div>
                </div>
            </div>

            <div class="dashboard-card-body">
                <div class="ai-status">
                    <div class="ai-status-header">
                        <i class="bi bi-check-circle-fill ai-status-icon"></i>
                        <span class="ai-status-title">Prediction Service Online</span>
                    </div>
                    <p class="ai-status-text">
                        The machine learning service is ready to receive battery data and generate lifespan predictions.
                    </p>
                </div>

                <div class="mt-3">
                    <div class="model-info">
                        <span class="model-info-label">Algorithm</span>
                        <span class="model-info-value">Gradient Boosting</span>
                    </div>
                    <div class="model-info">
                        <span class="model-info-label">Target</span>
                        <span class="model-info-value">RUL Cycles</span>
                    </div>
                    <div class="model-info">
                        <span class="model-info-label">EOL Threshold</span>
                        <span class="model-info-value">80%</span>
                    </div>
                    <div class="model-info">
                        <span class="model-info-label">R²</span>
                        <span class="model-info-value">0.858</span>
                    </div>
                    <div class="model-info">
                        <span class="model-info-label">RMSE</span>
                        <span class="model-info-value">875.95</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

{{-- Dashboard-only layout rules. Existing chart logic is untouched. --}}
@push('styles')
<style>
    .dashboard-page { width: 100%; max-width: 100%; }
    .dashboard-page-header { margin-bottom: 22px; }

    .dashboard-kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 16px;
    }

    .dashboard-two-column-grid {
        display: grid;
        grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr);
        gap: 14px;
        margin-bottom: 16px;
        align-items: stretch;
    }

    .dashboard-lower-grid { margin-bottom: 0; }

    .dashboard-capacity-chart {
        position: relative;
        width: 100%;
        min-height: 300px;
    }

    .dashboard-capacity-chart canvas {
        display: block;
        width: 100% !important;
        height: 300px !important;
    }

    .dashboard-capacity-card,
    .dashboard-health-card,
    .dashboard-predictions-card,
    .dashboard-model-card {
        min-width: 0;
    }

    .dashboard-predictions-table { margin-bottom: 0; }

    @media (max-width: 1199.98px) {
        .dashboard-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dashboard-two-column-grid {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-kpi-grid,
        .dashboard-two-column-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-capacity-chart { min-height: 260px; }

        .dashboard-capacity-chart canvas {
            height: 260px !important;
        }

        .dashboard-predictions-table { min-width: 700px; }
    }
</style>
@endpush

@endsection
