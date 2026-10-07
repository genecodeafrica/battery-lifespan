<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\BatteryMeasurement;
use Illuminate\Http\Request;

class BatteryMeasurementController extends Controller
{
    /**
     * Show measurement form.
     */
    public function create(Battery $battery)
    {
        return view(
            'measurements.create',
            compact('battery')
        );
    }


    /**
     * Store measurement.
     */
    public function store(
        Request $request,
        Battery $battery
    ) {

        $validated = $request->validate([

            'cycle' => [
                'required',
                'integer',
                'min:0'
            ],

            'capacity_mAh' => [
                'required',
                'numeric',
                'min:0'
            ],

            'duration_s' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'start_voltage_V' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'end_voltage_V' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'avg_temp_C' => [
                'nullable',
                'numeric'
            ],

            'n_samples' => [
                'nullable',
                'integer',
                'min:0'
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Determine initial capacity
        |--------------------------------------------------------------------------
        */

        $initialCapacity =
            $battery->initial_capacity_mAh;


        if (!$initialCapacity) {

            $firstMeasurement =
                $battery->measurements()
                    ->orderBy('cycle')
                    ->first();


            if ($firstMeasurement) {

                $initialCapacity =
                    $firstMeasurement->capacity_mAh;

            } else {

                $initialCapacity =
                    $validated['capacity_mAh'];

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Capacity calculations
        |--------------------------------------------------------------------------
        */

        $capacity =
            $validated['capacity_mAh'];


        $capacityRetention =
            $initialCapacity > 0
                ? $capacity / $initialCapacity
                : null;


        $capacityLoss =
            $initialCapacity - $capacity;


        /*
        |--------------------------------------------------------------------------
        | Previous measurement
        |--------------------------------------------------------------------------
        */

        $previousMeasurement =
            $battery->measurements()
                ->orderByDesc('cycle')
                ->first();


        $degradationRate = 0;


        if (
            $previousMeasurement &&
            $validated['cycle'] >
            $previousMeasurement->cycle
        ) {

            $capacityDifference =
                $capacity -
                $previousMeasurement->capacity_mAh;


            $cycleDifference =
                $validated['cycle'] -
                $previousMeasurement->cycle;


            $degradationRate =
                $capacityDifference /
                $cycleDifference;
        }


        /*
        |--------------------------------------------------------------------------
        | Save measurement
        |--------------------------------------------------------------------------
        */

        $measurement =
            $battery->measurements()->create([

                'cycle' =>
                    $validated['cycle'],

                'capacity_mAh' =>
                    $capacity,

                'duration_s' =>
                    $validated['duration_s'] ?? null,

                'start_voltage_V' =>
                    $validated['start_voltage_V'] ?? null,

                'end_voltage_V' =>
                    $validated['end_voltage_V'] ?? null,

                'avg_temp_C' =>
                    $validated['avg_temp_C'] ?? null,

                'n_samples' =>
                    $validated['n_samples'] ?? null,

                'initial_capacity_mAh' =>
                    $initialCapacity,

                'capacity_retention' =>
                    $capacityRetention,

                'capacity_loss_mAh' =>
                    $capacityLoss,

                'degradation_rate_mAh_per_cycle' =>
                    $degradationRate,
            ]);


        /*
        |--------------------------------------------------------------------------
        | Update battery initial capacity if needed
        |--------------------------------------------------------------------------
        */

        if (!$battery->initial_capacity_mAh) {

            $battery->update([
                'initial_capacity_mAh' =>
                    $initialCapacity
            ]);
        }


        return redirect()
            ->route('batteries.show', $battery)
            ->with(
                'success',
                'Battery measurement added successfully.'
            );
    }
}