<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Battery extends Model
{
    protected $fillable = [
        'battery_code',
        'cell_name',
        'initial_capacity_mAh',
        'manufacturer',
        'installation_date',
        'description',
    ];

    protected $casts = [
        'initial_capacity_mAh' => 'float',
        'installation_date' => 'date',
    ];

    public function measurements(): HasMany
    {
        return $this->hasMany(BatteryMeasurement::class);
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(Prediction::class);
    }
}