@extends('layouts.app')

@section('title', 'System Settings')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="page-title">
            System Settings
        </h2>

        <p class="text-muted mb-0">
            BatteryLife AI configuration and machine learning information
        </p>

    </div>


    <div class="row g-4">


        {{-- System Information --}}

        <div class="col-xl-6">

            <div class="dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            System Information
                        </h5>

                        <span>
                            Application technology stack
                        </span>

                    </div>

                    <i class="bi bi-cpu fs-3 text-primary"></i>

                </div>


                <div class="settings-list">

                    <div>

                        <span>
                            Application
                        </span>

                        <strong>
                            {{ $systemInfo['application'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Version
                        </span>

                        <strong>
                            {{ $systemInfo['version'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Framework
                        </span>

                        <strong>
                            {{ $systemInfo['framework'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Frontend
                        </span>

                        <strong>
                            {{ $systemInfo['frontend'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Database
                        </span>

                        <strong>
                            {{ $systemInfo['database'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Machine Learning
                        </span>

                        <strong>
                            {{ $systemInfo['machine_learning'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            API
                        </span>

                        <strong>
                            {{ $systemInfo['api'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Prediction Model
                        </span>

                        <strong>
                            {{ $systemInfo['prediction_model'] }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- ML Model --}}

        <div class="col-xl-6">

            <div class="dashboard-card h-100">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            Machine Learning Model
                        </h5>

                        <span>
                            Current production model
                        </span>

                    </div>

                    <span class="badge bg-success">
                        Active
                    </span>

                </div>


                <div class="model-name-box">

                    <i class="bi bi-robot"></i>

                    <div>

                        <strong>
                            {{ $modelInfo['name'] }}
                        </strong>

                        <small>
                            {{ $modelInfo['purpose'] }}
                        </small>

                    </div>

                </div>


                <div class="row g-3 mt-2">

                    <div class="col-md-4">

                        <div class="model-metric">

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

                    </div>


                    <div class="col-md-4">

                        <div class="model-metric">

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

                    </div>


                    <div class="col-md-4">

                        <div class="model-metric">

                            <span>
                                R²
                            </span>

                            <strong>
                                {{ number_format(
                                    $modelInfo['r2'],
                                    4
                                ) }}
                            </strong>

                        </div>

                    </div>

                </div>


                <div class="settings-list mt-3">

                    <div>

                        <span>
                            Model Type
                        </span>

                        <strong>
                            {{ $modelInfo['type'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Estimators
                        </span>

                        <strong>
                            {{ $modelInfo['estimators'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Learning Rate
                        </span>

                        <strong>
                            {{ $modelInfo['learning_rate'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Maximum Depth
                        </span>

                        <strong>
                            {{ $modelInfo['max_depth'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            Validation
                        </span>

                        <strong>
                            {{ $modelInfo['validation'] }}
                        </strong>

                    </div>


                    <div>

                        <span>
                            EOL Threshold
                        </span>

                        <strong>
                            {{ $modelInfo['eol_threshold'] }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>


        {{-- About --}}

        <div class="col-12">

            <div class="dashboard-card">

                <div class="card-header-custom">

                    <div>

                        <h5>
                            About BatteryLife AI
                        </h5>

                        <span>
                            Lithium-Ion battery lifespan prediction platform
                        </span>

                    </div>

                    <i class="bi bi-info-circle fs-3 text-primary"></i>

                </div>


                <div class="about-system">

                    <div class="about-icon">

                        <i class="bi bi-battery-charging"></i>

                    </div>


                    <div>

                        <h5>
                            BatteryLife AI
                        </h5>

                        <p>
                            BatteryLife AI is a web-based battery monitoring
                            and lifespan prediction system designed to analyze
                            lithium-ion battery measurements and estimate
                            Remaining Useful Life using machine learning.
                        </p>

                        <p class="mb-0">
                            The platform combines Laravel 13 for application
                            management with a Python-based machine learning
                            service for battery lifespan prediction.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection