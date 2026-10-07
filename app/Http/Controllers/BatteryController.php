<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BatteryController extends Controller
{
    /**
     * Display all batteries.
     */
    public function index()
    {
        $batteries = Battery::withCount([
            'measurements',
            'predictions'
        ])
            ->latest()
            ->paginate(10);

        return view('batteries.index', compact('batteries'));
    }


    /**
     * Show the create battery form.
     */
    public function create()
    {
        return view('batteries.create');
    }


    /**
     * Store a new battery.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'battery_code' => [
                'required',
                'string',
                'max:100',
                'unique:batteries,battery_code'
            ],

            'cell_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'initial_capacity_mAh' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:150'
            ],

            'installation_date' => [
                'nullable',
                'date'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);


        $battery = Battery::create($validated);


        return redirect()
            ->route('batteries.show', $battery)
            ->with(
                'success',
                'Battery registered successfully.'
            );
    }


    /**
     * Display a specific battery.
     */
    public function show(Battery $battery)
    {
        $battery->load([
            'measurements' => function ($query) {
                $query->latest('cycle');
            },
            'predictions' => function ($query) {
                $query->latest();
            }
        ]);


        $latestMeasurement = $battery
            ->measurements
            ->first();


        $latestPrediction = $battery
            ->predictions
            ->first();


        $healthPercentage = null;


        if (
            $latestMeasurement &&
            $latestMeasurement->capacity_retention !== null
        ) {
            $healthPercentage = round(
                $latestMeasurement->capacity_retention * 100,
                1
            );
        }


        return view(
            'batteries.show',
            compact(
                'battery',
                'latestMeasurement',
                'latestPrediction',
                'healthPercentage'
            )
        );
    }


    /**
     * Show edit form.
     */
    public function edit(Battery $battery)
    {
        return view(
            'batteries.edit',
            compact('battery')
        );
    }


    /**
     * Update battery.
     */
    public function update(
        Request $request,
        Battery $battery
    ) {

        $validated = $request->validate([

            'battery_code' => [
                'required',
                'string',
                'max:100',

                Rule::unique(
                    'batteries',
                    'battery_code'
                )->ignore($battery->id)
            ],

            'cell_name' => [
                'nullable',
                'string',
                'max:100'
            ],

            'initial_capacity_mAh' => [
                'nullable',
                'numeric',
                'min:0'
            ],

            'manufacturer' => [
                'nullable',
                'string',
                'max:150'
            ],

            'installation_date' => [
                'nullable',
                'date'
            ],

            'description' => [
                'nullable',
                'string'
            ],
        ]);


        $battery->update($validated);


        return redirect()
            ->route('batteries.show', $battery)
            ->with(
                'success',
                'Battery information updated successfully.'
            );
    }


    /**
     * Delete battery.
     */
    public function destroy(Battery $battery)
    {
        $battery->delete();


        return redirect()
            ->route('batteries.index')
            ->with(
                'success',
                'Battery deleted successfully.'
            );
    }
}