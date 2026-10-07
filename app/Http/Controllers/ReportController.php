<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\Prediction;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Reports Dashboard
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $batteries = Battery::withCount([
            'measurements',
            'predictions'
        ])
        ->latest()
        ->get();

        $totalBatteries = Battery::count();

        $totalMeasurements = Battery::withCount('measurements')
            ->get()
            ->sum('measurements_count');

        $totalPredictions = Prediction::count();

        $latestPredictions = Prediction::with([
            'battery',
            'measurement'
        ])
        ->latest()
        ->take(10)
        ->get();

        return view(
            'reports.index',
            compact(
                'batteries',
                'totalBatteries',
                'totalMeasurements',
                'totalPredictions',
                'latestPredictions'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Individual Battery Report
    |--------------------------------------------------------------------------
    */

    public function battery(Battery $battery)
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
                    $latestMeasurement
                        ->capacity_retention * 100,
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
        | Capacity Statistics
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
        | Cycle Statistics
        |--------------------------------------------------------------------------
        */

        $currentCycle =
            $latestMeasurement?->cycle;

        $totalCycles =
            $measurements->count();


        /*
        |--------------------------------------------------------------------------
        | Advanced Chart Data
        |--------------------------------------------------------------------------
        */

        $chartData = $measurements
            ->sortBy('cycle')
            ->values()
            ->map(function ($measurement) {

                return [

                    'cycle' =>
                        (int) $measurement->cycle,

                    'capacity' =>
                        $measurement->capacity_mAh !== null
                            ? (float) $measurement->capacity_mAh
                            : null,

                    'retention' =>
                        $measurement->capacity_retention !== null
                            ? (float) $measurement->capacity_retention * 100
                            : null,

                    'temperature' =>
                        $measurement->avg_temp_C !== null
                            ? (float) $measurement->avg_temp_C
                            : null,

                    'start_voltage' =>
                        $measurement->start_voltage_V !== null
                            ? (float) $measurement->start_voltage_V
                            : null,

                    'end_voltage' =>
                        $measurement->end_voltage_V !== null
                            ? (float) $measurement->end_voltage_V
                            : null,

                    'degradation_rate' =>
                        $measurement->degradation_rate_mAh_per_cycle !== null
                            ? (float) $measurement->degradation_rate_mAh_per_cycle
                            : null,
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | Prediction Chart Data
        |--------------------------------------------------------------------------
        */

        $predictionChartData = $predictions
            ->sortBy(function ($prediction) {

                return optional(
                    $prediction->measurement
                )->cycle ?? 0;

            })
            ->values()
            ->map(function ($prediction) {

                return [

                    'cycle' =>
                        $prediction->measurement
                            ? (int) $prediction
                                ->measurement
                                ->cycle
                            : null,

                    'rul' =>
                        (float) $prediction
                            ->predicted_rul_cycles,

                    'eol' =>
                        $prediction->estimated_eol_cycle !== null
                            ? (int) $prediction
                                ->estimated_eol_cycle
                            : null,

                    'date' =>
                        $prediction->created_at
                            ->format('Y-m-d H:i'),

                ];
            })
            ->values();


        /*
        |--------------------------------------------------------------------------
        | RUL Statistics
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


        return view(
            'reports.battery',
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

                'totalCycles',

                'chartData',

                'predictionChartData',

                'averageRul',

                'minimumRul',

                'maximumRul',

                'modelInfo'
            )
        );
    }
}