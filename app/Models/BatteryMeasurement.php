<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatteryMeasurement extends Model
{
    protected $fillable = [
        'battery_id',
        'cycle',
        'capacity_mAh',
        'duration_s',
        'start_voltage_V',
        'end_voltage_V',
        'avg_temp_C',
        'n_samples',
        'initial_capacity_mAh',
        'capacity_retention',
        'capacity_loss_mAh',
        'degradation_rate_mAh_per_cycle',
    ];

    protected $casts = [
        'capacity_mAh' => 'float',
        'duration_s' => 'float',
        'start_voltage_V' => 'float',
        'end_voltage_V' => 'float',
        'avg_temp_C' => 'float',
        'initial_capacity_mAh' => 'float',
        'capacity_retention' => 'float',
        'capacity_loss_mAh' => 'float',
        'degradation_rate_mAh_per_cycle' => 'float',
    ];

    public function battery(): BelongsTo
    {
        return $this->belongsTo(Battery::class);
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class, 'measurement_id');
    }
}