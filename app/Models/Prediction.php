<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    protected $fillable = [
        'battery_id',
        'measurement_id',
        'predicted_rul_cycles',
        'estimated_eol_cycle',
        'model_name',
    ];

    protected $casts = [
        'predicted_rul_cycles' => 'float',
        'estimated_eol_cycle' => 'integer',
    ];

    public function battery(): BelongsTo
    {
        return $this->belongsTo(Battery::class);
    }

    public function measurement(): BelongsTo
    {
        return $this->belongsTo(
            BatteryMeasurement::class,
            'measurement_id'
        );
    }
}