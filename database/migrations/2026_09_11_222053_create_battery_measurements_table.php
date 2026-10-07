<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('battery_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battery_id')->constrained()->onDelete('cascade');
            $table->integer('cycle');
            $table->float('capacity_mAh')->nullable();
            $table->float('duration_s')->nullable();
            $table->float('start_voltage_V')->nullable();
            $table->float('end_voltage_V')->nullable();
            $table->float('avg_temp_C')->nullable();
            $table->integer('n_samples')->nullable();
            $table->float('initial_capacity_mAh')->nullable();
            $table->float('capacity_retention')->nullable();
            $table->float('capacity_loss_mAh')->nullable();
            $table->float('degradation_rate_mAh_per_cycle')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battery_measurements');
    }
};
