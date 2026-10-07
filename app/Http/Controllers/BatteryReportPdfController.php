<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use Barryvdh\DomPDF\Facade\Pdf;

class BatteryReportPdfController extends Controller
{
    public function generate(Battery $battery)
    {
        $battery->load([
            'measurements' => function ($query) {
                $query->orderBy('cycle', 'asc');
            },

            'predictions' => function ($query) {
                $query->with('measurement')
                    ->latest();
            }
        ]);

        $measurements = $battery->measurements;
        $predictions = $battery->predictions;

        /*
        |--------------------------------------------------------------------------
        | Latest Measurement
        |--------------------------------------------------------------------------
        */

        $latestMeasurement = $measurements
            ->sortByDesc('cycle')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Latest Prediction
        |--------------------------------------------------------------------------
        */

        $latestPrediction = $predictions
            ->sortByDesc('created_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Battery Health
        |--------------------------------------------------------------------------
        */

        $healthPercentage = null;

        if ($latestMeasurement) {
            if (
                $latestMeasurement->capacity_retention !== null
            ) {
                $healthPercentage = round(
                    $latestMeasurement->capacity_retention * 100,
                    2
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Health Status
        |--------------------------------------------------------------------------
        */

        $healthStatus = 'Unknown';

        if ($healthPercentage !== null) {

            if ($healthPercentage >= 90) {
                $healthStatus = 'Healthy';

            } elseif ($healthPercentage >= 80) {
                $healthStatus = 'Warning';

            } else {
                $healthStatus = 'Critical';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Capacity
        |--------------------------------------------------------------------------
        */

        $initialCapacity =
            $battery->initial_capacity_mAh;

        $currentCapacity =
            $latestMeasurement?->capacity_mAh;

        $capacityLoss = null;

        if (
            $initialCapacity !== null &&
            $currentCapacity !== null
        ) {
            $capacityLoss =
                $initialCapacity -
                $currentCapacity;
        }

        /*
        |--------------------------------------------------------------------------
        | Cycle
        |--------------------------------------------------------------------------
        */

        $currentCycle =
            $latestMeasurement?->cycle;

        /*
        |--------------------------------------------------------------------------
        | Prediction Statistics
        |--------------------------------------------------------------------------
        */

        $averageRul =
            $predictions->count() > 0
                ? round(
                    $predictions->avg(
                        'predicted_rul_cycles'
                    ),
                    2
                )
                : null;

        $minimumRul =
            $predictions->count() > 0
                ? round(
                    $predictions->min(
                        'predicted_rul_cycles'
                    ),
                    2
                )
                : null;

        $maximumRul =
            $predictions->count() > 0
                ? round(
                    $predictions->max(
                        'predicted_rul_cycles'
                    ),
                    2
                )
                : null;

        /*
        |--------------------------------------------------------------------------
        | Model Information
        |--------------------------------------------------------------------------
        */

        $modelInfo = [

            'name' =>
                'Gradient Boosting Regressor',

            'mae' =>
                669.736465,

            'rmse' =>
                875.951112,

            'r2' =>
                0.857924,

            'estimators' =>
                200,

            'learning_rate' =>
                0.05,

            'max_depth' =>
                2,

            'validation' =>
                '8-Fold Group Cross-Validation',
        ];

        /*
        |--------------------------------------------------------------------------
        | Chart Data
        |--------------------------------------------------------------------------
        */

        $chartData = $measurements
            ->sortBy('cycle')
            ->values()
            ->map(function ($measurement) {

                return [

                    'cycle' =>
                        $measurement->cycle,

                    'capacity' =>
                        $measurement->capacity_mAh,

                    'retention' =>
                        $measurement->capacity_retention !== null
                            ? $measurement->capacity_retention * 100
                            : null,

                    'temperature' =>
                        $measurement->avg_temp_C,

                    'start_voltage' =>
                        $measurement->start_voltage_V,

                    'end_voltage' =>
                        $measurement->end_voltage_V,

                    'degradation_rate' =>
                        $measurement
                            ->degradation_rate_mAh_per_cycle,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'reports.pdf',
            compact(
                'battery',
                'measurements',
                'predictions',
                'latestMeasurement',
                'latestPrediction',
                'healthPercentage',
                'healthStatus',
                'initialCapacity',
                'currentCapacity',
                'capacityLoss',
                'currentCycle',
                'averageRul',
                'minimumRul',
                'maximumRul',
                'modelInfo',
                'chartData'
            )
        );

        $pdf->setPaper('a4', 'portrait');

        return $pdf->download(
            'battery-report-' .
            $battery->battery_code .
            '.pdf'
        );
    }
}