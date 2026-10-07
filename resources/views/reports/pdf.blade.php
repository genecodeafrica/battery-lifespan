<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Battery Lifespan Analysis Report
    </title>

    <style>

        @page {
            margin: 35px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
            line-height: 1.5;
        }

        h1,
        h2,
        h3 {
            margin-top: 0;
        }

        .header {
            border-bottom: 3px solid #0A192F;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .brand {
            font-size: 25px;
            font-weight: bold;
            color: #0A192F;
        }

        .subtitle {
            color: #64748b;
            font-size: 12px;
            margin-top: 4px;
        }

        .report-title {
            margin-top: 20px;
            font-size: 20px;
            color: #0A192F;
        }

        .section {
            margin-top: 22px;
            margin-bottom: 15px;
        }

        .section-title {
            background: #0A192F;
            color: white;
            padding: 8px 10px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .grid {
            width: 100%;
        }

        .grid td {
            width: 50%;
            padding: 5px;
            vertical-align: top;
        }

        .info-box {
            border: 1px solid #dbe2ea;
            padding: 10px;
            min-height: 55px;
        }

        .label {
            color: #64748b;
            font-size: 8px;
            text-transform: uppercase;
        }

        .value {
            font-size: 12px;
            font-weight: bold;
            margin-top: 3px;
        }

        .kpi-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kpi-table td {
            width: 25%;
            border: 1px solid #dbe2ea;
            padding: 10px;
            text-align: center;
        }

        .kpi-number {
            font-size: 16px;
            font-weight: bold;
            color: #0A192F;
        }

        .kpi-label {
            color: #64748b;
            font-size: 8px;
            margin-top: 4px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #0A192F;
            color: white;
            padding: 7px;
            text-align: left;
            font-size: 9px;
        }

        table.data td {
            border: 1px solid #dbe2ea;
            padding: 6px;
            font-size: 9px;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border: 1px solid #cbd5e1;
            font-weight: bold;
        }

        .note {
            background: #f8fafc;
            border-left: 4px solid #0A192F;
            padding: 10px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #dbe2ea;
            padding-top: 10px;
            color: #64748b;
            font-size: 8px;
        }

        .page-break {
            page-break-before: always;
        }

    </style>

</head>


<body>


{{-- HEADER --}}

<div class="header">

    <div class="brand">
        BatteryLife AI
    </div>

    <div class="subtitle">
        Lithium-Ion Battery Lifespan Prediction System
    </div>

    <div class="report-title">
        Battery Lifespan Analysis Report
    </div>

</div>


{{-- BATTERY INFORMATION --}}

<div class="section">

    <div class="section-title">
        1. Battery Information
    </div>

    <table class="grid">

        <tr>

            <td>

                <div class="info-box">

                    <div class="label">
                        Battery Code
                    </div>

                    <div class="value">
                        {{ $battery->battery_code }}
                    </div>

                </div>

            </td>


            <td>

                <div class="info-box">

                    <div class="label">
                        Cell
                    </div>

                    <div class="value">
                        {{ $battery->cell_name ?? 'N/A' }}
                    </div>

                </div>

            </td>

        </tr>


        <tr>

            <td>

                <div class="info-box">

                    <div class="label">
                        Manufacturer
                    </div>

                    <div class="value">
                        {{ $battery->manufacturer ?? 'N/A' }}
                    </div>

                </div>

            </td>


            <td>

                <div class="info-box">

                    <div class="label">
                        Initial Capacity
                    </div>

                    <div class="value">

                        {{ $initialCapacity !== null
                            ? number_format(
                                $initialCapacity,
                                2
                            ) . ' mAh'
                            : 'N/A'
                        }}

                    </div>

                </div>

            </td>

        </tr>

    </table>

</div>


{{-- SUMMARY --}}

<div class="section">

    <div class="section-title">
        2. Battery Health Summary
    </div>

    <table class="kpi-table">

        <tr>

            <td>

                <div class="kpi-number">

                    {{ $healthPercentage !== null
                        ? $healthPercentage . '%'
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Battery Health
                </div>

            </td>


            <td>

                <div class="kpi-number">

                    {{ $currentCycle !== null
                        ? number_format($currentCycle)
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Current Cycle
                </div>

            </td>


            <td>

                <div class="kpi-number">

                    {{ $capacityLoss !== null
                        ? number_format(
                            $capacityLoss,
                            2
                        )
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Capacity Loss (mAh)
                </div>

            </td>


            <td>

                <div class="kpi-number">

                    {{ $latestPrediction
                        ? number_format(
                            $latestPrediction
                                ->predicted_rul_cycles,
                            0
                        )
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Latest RUL
                </div>

            </td>

        </tr>

    </table>


    <div class="note">

        <strong>
            Health Status:
        </strong>

        <span class="status">
            {{ $healthStatus }}
        </span>

    </div>

</div>


{{-- LATEST MEASUREMENT --}}

<div class="section">

    <div class="section-title">
        3. Latest Battery Measurement
    </div>

    @if($latestMeasurement)

        <table class="data">

            <tr>

                <th>
                    Parameter
                </th>

                <th>
                    Value
                </th>

            </tr>

            <tr>

                <td>
                    Cycle
                </td>

                <td>
                    {{ number_format(
                        $latestMeasurement->cycle
                    ) }}
                </td>

            </tr>

            <tr>

                <td>
                    Capacity
                </td>

                <td>
                    {{ number_format(
                        $latestMeasurement->capacity_mAh,
                        4
                    ) }} mAh
                </td>

            </tr>

            <tr>

                <td>
                    Capacity Retention
                </td>

                <td>

                    {{ $latestMeasurement
                        ->capacity_retention !== null
                        ? number_format(
                            $latestMeasurement
                                ->capacity_retention * 100,
                            2
                        ) . '%'
                        : 'N/A'
                    }}

                </td>

            </tr>

            <tr>

                <td>
                    Average Temperature
                </td>

                <td>

                    {{ $latestMeasurement
                        ->avg_temp_C !== null
                        ? number_format(
                            $latestMeasurement->avg_temp_C,
                            2
                        ) . ' °C'
                        : 'N/A'
                    }}

                </td>

            </tr>

            <tr>

                <td>
                    Start Voltage
                </td>

                <td>

                    {{ $latestMeasurement
                        ->start_voltage_V !== null
                        ? number_format(
                            $latestMeasurement->start_voltage_V,
                            3
                        ) . ' V'
                        : 'N/A'
                    }}

                </td>

            </tr>

            <tr>

                <td>
                    End Voltage
                </td>

                <td>

                    {{ $latestMeasurement
                        ->end_voltage_V !== null
                        ? number_format(
                            $latestMeasurement->end_voltage_V,
                            3
                        ) . ' V'
                        : 'N/A'
                    }}

                </td>

            </tr>

        </table>

    @else

        <p>
            No measurement data is available.
        </p>

    @endif

</div>


{{-- AI MODEL --}}

<div class="section">

    <div class="section-title">
        4. Machine Learning Model
    </div>

    <table class="data">

        <tr>
            <th>Parameter</th>
            <th>Value</th>
        </tr>

        <tr>
            <td>Model</td>
            <td>{{ $modelInfo['name'] }}</td>
        </tr>

        <tr>
            <td>MAE</td>
            <td>{{ $modelInfo['mae'] }}</td>
        </tr>

        <tr>
            <td>RMSE</td>
            <td>{{ $modelInfo['rmse'] }}</td>
        </tr>

        <tr>
            <td>R²</td>
            <td>{{ $modelInfo['r2'] }}</td>
        </tr>

        <tr>
            <td>Estimators</td>
            <td>{{ $modelInfo['estimators'] }}</td>
        </tr>

        <tr>
            <td>Learning Rate</td>
            <td>{{ $modelInfo['learning_rate'] }}</td>
        </tr>

        <tr>
            <td>Maximum Depth</td>
            <td>{{ $modelInfo['max_depth'] }}</td>
        </tr>

        <tr>
            <td>Validation</td>
            <td>{{ $modelInfo['validation'] }}</td>
        </tr>

    </table>

</div>


{{-- RUL SUMMARY --}}

<div class="section">

    <div class="section-title">
        5. AI Prediction Summary
    </div>

    <table class="kpi-table">

        <tr>

            <td>

                <div class="kpi-number">

                    {{ $averageRul !== null
                        ? number_format(
                            $averageRul,
                            0
                        )
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Average RUL
                </div>

            </td>


            <td>

                <div class="kpi-number">

                    {{ $minimumRul !== null
                        ? number_format(
                            $minimumRul,
                            0
                        )
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Minimum RUL
                </div>

            </td>


            <td>

                <div class="kpi-number">

                    {{ $maximumRul !== null
                        ? number_format(
                            $maximumRul,
                            0
                        )
                        : 'N/A'
                    }}

                </div>

                <div class="kpi-label">
                    Maximum RUL
                </div>

            </td>


            <td>

                <div class="kpi-number">

                    {{ $predictions->count() }}

                </div>

                <div class="kpi-label">
                    Predictions
                </div>

            </td>

        </tr>

    </table>

</div>


{{-- PREDICTION HISTORY --}}

<div class="section">

    <div class="section-title">
        6. Prediction History
    </div>

    @if($predictions->count() > 0)

        <table class="data">

            <tr>

                <th>
                    Date
                </th>

                <th>
                    Cycle
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


            @foreach($predictions as $prediction)

                <tr>

                    <td>

                        {{ $prediction
                            ->created_at
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

                        {{ number_format(
                            $prediction
                                ->predicted_rul_cycles,
                            0
                        ) }}

                        cycles

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

            @endforeach

        </table>

    @else

        <p>
            No AI predictions have been generated.
        </p>

    @endif

</div>


{{-- DATA SUMMARY --}}

<div class="section page-break">

    <div class="section-title">
        7. Measurement Data Summary
    </div>

    <table class="data">

        <tr>

            <th>
                Cycle
            </th>

            <th>
                Capacity
            </th>

            <th>
                Retention
            </th>

            <th>
                Temperature
            </th>

            <th>
                Start V
            </th>

            <th>
                End V
            </th>

        </tr>


        @foreach($measurements as $measurement)

            <tr>

                <td>
                    {{ number_format(
                        $measurement->cycle
                    ) }}
                </td>

                <td>

                    {{ number_format(
                        $measurement->capacity_mAh,
                        2
                    ) }}

                </td>

                <td>

                    {{ $measurement
                        ->capacity_retention !== null
                        ? number_format(
                            $measurement
                                ->capacity_retention * 100,
                            2
                        ) . '%'
                        : 'N/A'
                    }}

                </td>

                <td>

                    {{ $measurement
                        ->avg_temp_C !== null
                        ? number_format(
                            $measurement->avg_temp_C,
                            2
                        )
                        : 'N/A'
                    }}

                </td>

                <td>

                    {{ $measurement
                        ->start_voltage_V !== null
                        ? number_format(
                            $measurement->start_voltage_V,
                            3
                        )
                        : 'N/A'
                    }}

                </td>

                <td>

                    {{ $measurement
                        ->end_voltage_V !== null
                        ? number_format(
                            $measurement->end_voltage_V,
                            3
                        )
                        : 'N/A'
                    }}

                </td>

            </tr>

        @endforeach

    </table>

</div>


{{-- CONCLUSION --}}

<div class="section">

    <div class="section-title">
        8. Report Summary
    </div>

    <div class="note">

        This report summarizes the observed battery measurements,
        capacity degradation, battery health and machine learning
        predictions generated by the BatteryLife AI system.

        The lifespan prediction is generated using the
        Gradient Boosting Regressor model trained on the
        battery degradation dataset.

        The reported prediction should be interpreted as a
        model-based estimate derived from the available
        measurement data.

    </div>

</div>


<div class="footer">

    <strong>
        BatteryLife AI
    </strong>

    &nbsp; | &nbsp;

    Lithium-Ion Battery Lifespan Prediction System

    <br>

    Report generated:
    {{ now()->format('d M Y H:i:s') }}

</div>


</body>

</html>