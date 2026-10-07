<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\BatteryMeasurement;

class MeasurementController extends Controller
{
    /**
     * Display measurements for a battery.
     */
    public function index(Battery $battery)
    {
        $measurements = $battery->measurements()
            ->orderByDesc('cycle')
            ->paginate(20);

        return view(
            'measurements.index',
            compact(
                'battery',
                'measurements'
            )
        );
    }


    /**
     * Show a single measurement.
     */
    public function show(
        Battery $battery,
        BatteryMeasurement $measurement
    ) {
        abort_unless(
            $measurement->battery_id === $battery->id,
            404
        );

        return view(
            'measurements.show',
            compact(
                'battery',
                'measurement'
            )
        );
    }
}