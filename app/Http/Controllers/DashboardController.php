<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\BatteryMeasurement;
use App\Models\Prediction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBatteries = Battery::count();

        $totalMeasurements = BatteryMeasurement::count();

        $totalPredictions = Prediction::count();

        $averageRul = Prediction::avg('predicted_rul_cycles');

        /*
        |--------------------------------------------------------------------------
        | Latest measurement
        |--------------------------------------------------------------------------
        */

        $latestMeasurement = BatteryMeasurement::with('battery')
            ->latest('cycle')
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Battery health
        |--------------------------------------------------------------------------
        */

        $healthPercentage = null;

        if ($latestMeasurement) {

            if ($latestMeasurement->capacity_retention !== null) {

                $healthPercentage =
                    round(
                        $latestMeasurement->capacity_retention * 100,
                        1
                    );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Capacity chart
        |--------------------------------------------------------------------------
        */

        $chartLabels = [];

        $chartValues = [];

        if ($latestMeasurement) {

            $chartMeasurements = BatteryMeasurement::where(
                'battery_id',
                $latestMeasurement->battery_id
            )
                ->orderBy('cycle')
                ->limit(100)
                ->get([
                    'cycle',
                    'capacity_mAh'
                ]);

            foreach ($chartMeasurements as $measurement) {

                $chartLabels[] = $measurement->cycle;

                $chartValues[] = $measurement->capacity_mAh;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Recent predictions
        |--------------------------------------------------------------------------
        */

        $recentPredictions = Prediction::with([
            'battery',
            'measurement'
        ])
            ->latest()
            ->limit(8)
            ->get();


        return view('dashboard', compact(
            'totalBatteries',
            'totalMeasurements',
            'totalPredictions',
            'averageRul',
            'latestMeasurement',
            'healthPercentage',
            'chartLabels',
            'chartValues',
            'recentPredictions'
        ));
    }
}