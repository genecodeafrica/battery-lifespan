<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\Prediction;
use App\Services\MachineLearningService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class PredictionController extends Controller
{
    public function index(Battery $battery)
    {
        $battery->load([
            'measurements' => function ($query) {
                $query->orderBy('cycle', 'asc');
            },
            'predictions' => function ($query) {
                $query->with('measurement')
                    ->latest();
            },
        ]);

        $latestMeasurement = $battery->measurements
            ->sortByDesc('cycle')
            ->first();

        $latestPrediction = $battery->predictions
            ->sortByDesc('created_at')
            ->first();

        $healthPercentage = null;

        if ($latestMeasurement) {
            if ($latestMeasurement->capacity_retention !== null) {
                $healthPercentage = round(
                    $latestMeasurement->capacity_retention * 100,
                    2
                );
            }
        }

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

        $chartData = $battery->measurements
            ->sortBy('cycle')
            ->values()
            ->map(function ($measurement) {
                return [
                    'id' => $measurement->id,
                    'cycle' => $measurement->cycle,
                    'capacity' => $measurement->capacity_mAh,
                    'retention' => $measurement->capacity_retention,
                    'temperature' => $measurement->avg_temp_C,
                    'start_voltage' => $measurement->start_voltage_V,
                    'end_voltage' => $measurement->end_voltage_V,
                ];
            });

        $modelInfo = [
            'name' => 'Gradient Boosting Regressor',
            'mae' => 669.736465,
            'rmse' => 875.951112,
            'r2' => 0.857924,
            'estimators' => 200,
            'learning_rate' => 0.05,
            'max_depth' => 2,
            'validation' => '8-Fold Group Cross-Validation',
        ];

        return view(
            'predictions.index',
            compact(
                'battery',
                'latestMeasurement',
                'latestPrediction',
                'healthPercentage',
                'healthStatus',
                'chartData',
                'modelInfo'
            )
        );
    }

    public function predict(
        Request $request,
        Battery $battery,
        MachineLearningService $machineLearningService,
        NotificationService $notificationService
    ) {
        $validatedRequest = $request->validate([
            'measurement_id' => [
                'required',
                'integer',
                'exists:battery_measurements,id',
            ],
        ]);

        $measurement = $battery->measurements()
            ->where(
                'id',
                $validatedRequest['measurement_id']
            )
            ->first();

        if (!$measurement) {
            return redirect()
                ->route(
                    'batteries.predictions.index',
                    $battery
                )
                ->with(
                    'error',
                    'The selected measurement does not belong to this battery.'
                );
        }

        $features = [
            'cycle' => $measurement->cycle,
            'capacity_mAh' => $measurement->capacity_mAh,
            'duration_s' => $measurement->duration_s,
            'start_voltage_V' => $measurement->start_voltage_V,
            'end_voltage_V' => $measurement->end_voltage_V,
            'avg_temp_C' => $measurement->avg_temp_C,
            'n_samples' => $measurement->n_samples,
            'initial_capacity_mAh' => $measurement->initial_capacity_mAh,
            'capacity_retention' => $measurement->capacity_retention,
            'capacity_loss_mAh' => $measurement->capacity_loss_mAh,
            'degradation_rate_mAh_per_cycle' =>
                $measurement->degradation_rate_mAh_per_cycle,
        ];

        foreach ($features as $key => $value) {
            if ($value === null) {
                return redirect()
                    ->route(
                        'batteries.predictions.index',
                        $battery
                    )
                    ->with(
                        'error',
                        "The selected measurement is missing the required value: {$key}"
                    );
            }
        }

        try {
            $result =
                $machineLearningService->predict(
                    $features
                );
        } catch (Exception $e) {
            return redirect()
                ->route(
                    'batteries.predictions.index',
                    $battery
                )
                ->with(
                    'error',
                    $e->getMessage()
                );
        }

        $predictedRul =
            $result['predicted_rul_cycles']
            ?? $result['predicted_rul']
            ?? null;

        if ($predictedRul === null) {
            return redirect()
                ->route(
                    'batteries.predictions.index',
                    $battery
                )
                ->with(
                    'error',
                    'The machine learning service did not return a valid RUL prediction.'
                );
        }

        $predictedRul = max(
            0,
            (float) $predictedRul
        );

        $estimatedEol =
            $result['estimated_eol_cycle']
            ?? round(
                $measurement->cycle +
                $predictedRul
            );

        $prediction = Prediction::create([
            'battery_id' => $battery->id,
            'measurement_id' => $measurement->id,
            'predicted_rul_cycles' => $predictedRul,
            'estimated_eol_cycle' => $estimatedEol,
            'model_name' =>
                $result['model_name']
                ?? 'Gradient Boosting Regressor',
        ]);

        /*
         * Create notification for the currently logged-in user.
         */
        $notificationService->predictionGenerated(
            Auth::user(),
            $battery,
            $prediction
        );

        return redirect()
            ->route(
                'batteries.predictions.index',
                $battery
            )
            ->with(
                'success',
                'AI lifespan prediction completed successfully using Cycle '
                . number_format($measurement->cycle)
                . '.'
            );
    }
}