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
        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('battery_id')->constrained()->onDelete('cascade');
            $table->foreignId('measurement_id')->nullable()->constrained('battery_measurements')->onDelete('set null');
            $table->float('predicted_rul_cycles')->nullable();
            $table->integer('estimated_eol_cycle')->nullable();
            $table->string('model_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predictions');
    }
};
