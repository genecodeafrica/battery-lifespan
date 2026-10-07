<?php

namespace App\Http\Controllers;

use App\Models\Battery;
use App\Models\BatteryMeasurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BatteryImportController extends Controller
{
    /**
     * Show the CSV import page.
     */
    public function create()
    {
        return view('batteries.import');
    }

    /**
     * Import Oxford battery CSV data.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'csv_file' => [
                    'required',
                    'file',
                    'mimes:csv,txt',
                    'max:10240',
                ],
            ],
            [
                'csv_file.required' => 'Please select a CSV file.',
                'csv_file.file' => 'The uploaded item must be a file.',
                'csv_file.mimes' => 'The file must be a CSV file.',
                'csv_file.max' => 'The CSV file must not exceed 10 MB.',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('csv_file');

        $handle = fopen($file->getRealPath(), 'r');

        if (!$handle) {
            return back()->with('error', 'Unable to read the CSV file.');
        }

        try {
            DB::beginTransaction();

            /*
            |--------------------------------------------------------------------------
            | Read CSV header
            |--------------------------------------------------------------------------
            */

            $header = fgetcsv($handle);

            if (!$header) {
                throw new \Exception('The CSV file is empty.');
            }

            $header = array_map(
                fn ($value) => trim($value),
                $header
            );

            $requiredColumns = [
                'cell',
                'cycle',
                'test',
                'capacity_mAh',
                'duration_s',
                'start_voltage_V',
                'end_voltage_V',
                'avg_temp_C',
                'n_samples',
                'start_time',
            ];

            foreach ($requiredColumns as $column) {
                if (!in_array($column, $header)) {
                    throw new \Exception(
                        "Missing required column: {$column}"
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Convert header positions into indexes
            |--------------------------------------------------------------------------
            */

            $indexes = array_flip($header);

            $importedMeasurements = 0;
            $createdBatteries = 0;
            $updatedMeasurements = 0;

            /*
            |--------------------------------------------------------------------------
            | Keep track of previous capacity for degradation rate
            |--------------------------------------------------------------------------
            */

            $previousData = [];

            /*
            |--------------------------------------------------------------------------
            | Read every CSV row
            |--------------------------------------------------------------------------
            */

            while (($row = fgetcsv($handle)) !== false) {

                if (count($row) < count($header)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Only import C1dc discharge data
                |--------------------------------------------------------------------------
                */

                $test = trim($row[$indexes['test']]);

                if ($test !== 'C1dc') {
                    continue;
                }

                $cellName = trim($row[$indexes['cell']]);

                $cycle = (int) $row[$indexes['cycle']];

                $capacity = (float) $row[$indexes['capacity_mAh']];

                $duration = $this->nullableFloat(
                    $row[$indexes['duration_s']]
                );

                $startVoltage = $this->nullableFloat(
                    $row[$indexes['start_voltage_V']]
                );

                $endVoltage = $this->nullableFloat(
                    $row[$indexes['end_voltage_V']]
                );

                $temperature = $this->nullableFloat(
                    $row[$indexes['avg_temp_C']]
                );

                $samples = $this->nullableInt(
                    $row[$indexes['n_samples']]
                );

                /*
                |--------------------------------------------------------------------------
                | Find or create battery
                |--------------------------------------------------------------------------
                */

                $battery = Battery::firstOrCreate(
                    [
                        'battery_code' => 'OXFORD-' . strtoupper($cellName),
                    ],
                    [
                        'cell_name' => $cellName,
                        'manufacturer' => 'Oxford Battery Dataset',
                        'description' =>
                            'Battery cell imported from Oxford Battery Degradation Dataset 1.',
                    ]
                );

                if ($battery->wasRecentlyCreated) {
                    $createdBatteries++;
                }

                /*
                |--------------------------------------------------------------------------
                | Determine initial capacity
                |--------------------------------------------------------------------------
                */

                if (!$battery->initial_capacity_mAh) {

                    $firstMeasurement = BatteryMeasurement::where(
                        'battery_id',
                        $battery->id
                    )
                    ->orderBy('cycle')
                    ->first();

                    if ($firstMeasurement) {
                        $initialCapacity =
                            $firstMeasurement->capacity_mAh;
                    } else {
                        $initialCapacity = $capacity;
                    }

                    $battery->update([
                        'initial_capacity_mAh' => $initialCapacity,
                    ]);
                }

                $initialCapacity =
                    (float) $battery->initial_capacity_mAh;

                /*
                |--------------------------------------------------------------------------
                | Capacity retention
                |--------------------------------------------------------------------------
                */

                $capacityRetention = $initialCapacity > 0
                    ? $capacity / $initialCapacity
                    : 0;

                /*
                |--------------------------------------------------------------------------
                | Capacity loss
                |--------------------------------------------------------------------------
                */

                $capacityLoss =
                    $initialCapacity - $capacity;

                /*
                |--------------------------------------------------------------------------
                | Previous measurement
                |--------------------------------------------------------------------------
                */

                $previous = $previousData[$cellName] ?? null;

                if (!$previous) {

                    $previousMeasurement =
                        BatteryMeasurement::where(
                            'battery_id',
                            $battery->id
                        )
                        ->where('cycle', '<', $cycle)
                        ->orderByDesc('cycle')
                        ->first();

                    if ($previousMeasurement) {

                        $previous = [
                            'cycle' =>
                                $previousMeasurement->cycle,

                            'capacity' =>
                                (float) $previousMeasurement->capacity_mAh,
                        ];
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Degradation rate
                |--------------------------------------------------------------------------
                */

                if (
                    $previous &&
                    $cycle != $previous['cycle']
                ) {

                    $degradationRate =
                        (
                            $capacity -
                            $previous['capacity']
                        )
                        /
                        (
                            $cycle -
                            $previous['cycle']
                        );

                } else {

                    $degradationRate = 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Save measurement
                |--------------------------------------------------------------------------
                */

                $measurement = BatteryMeasurement::updateOrCreate(
                    [
                        'battery_id' => $battery->id,
                        'cycle' => $cycle,
                    ],
                    [
                        'capacity_mAh' =>
                            $capacity,

                        'duration_s' =>
                            $duration,

                        'start_voltage_V' =>
                            $startVoltage,

                        'end_voltage_V' =>
                            $endVoltage,

                        'avg_temp_C' =>
                            $temperature,

                        'n_samples' =>
                            $samples,

                        'initial_capacity_mAh' =>
                            $initialCapacity,

                        'capacity_retention' =>
                            $capacityRetention,

                        'capacity_loss_mAh' =>
                            $capacityLoss,

                        'degradation_rate_mAh_per_cycle' =>
                            $degradationRate,
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Determine whether this was newly created
                |--------------------------------------------------------------------------
                */

                if ($measurement->wasRecentlyCreated) {
                    $importedMeasurements++;
                } else {
                    $updatedMeasurements++;
                }

                /*
                |--------------------------------------------------------------------------
                | Store current row for next degradation calculation
                |--------------------------------------------------------------------------
                */

                $previousData[$cellName] = [
                    'cycle' => $cycle,
                    'capacity' => $capacity,
                ];
            }

            fclose($handle);

            DB::commit();

            return redirect()
                ->route('batteries.index')
                ->with(
                    'success',
                    "Oxford dataset imported successfully. " .
                    "{$createdBatteries} batteries created, " .
                    "{$importedMeasurements} measurements imported, " .
                    "{$updatedMeasurements} existing measurements updated."
                );

        } catch (\Throwable $e) {

            if (is_resource($handle)) {
                fclose($handle);
            }

            DB::rollBack();

            return back()->with(
                'error',
                'Import failed: ' . $e->getMessage()
            );
        }
    }

    /**
     * Convert a CSV value to float or null.
     */
    private function nullableFloat($value): ?float
    {
        $value = trim((string) $value);

        if ($value === '' || strtolower($value) === 'null') {
            return null;
        }

        return (float) $value;
    }

    /**
     * Convert a CSV value to integer or null.
     */
    private function nullableInt($value): ?int
    {
        $value = trim((string) $value);

        if ($value === '' || strtolower($value) === 'null') {
            return null;
        }

        return (int) $value;
    }
}