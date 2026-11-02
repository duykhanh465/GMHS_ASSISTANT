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
        Schema::create('patient_calculations', function (Blueprint $table) {
    $table->id();
    $table->foreignId('patient_id')->constrained('patients')->cascadeOnUpdate()->cascadeOnDelete();
    $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
    $table->json('input_snapshot');
    $table->decimal('bmi', 5, 2)->nullable();
    $table->decimal('ibw', 5, 2)->nullable();
    $table->decimal('pbw', 5, 2)->nullable();
    $table->decimal('adjbw', 5, 2)->nullable();
    $table->decimal('dosing_weight', 5, 2)->nullable();
    $table->decimal('maintenance_fluid', 7, 2)->nullable();
    $table->decimal('mac_value', 5, 2)->nullable();
    $table->string('ett_size')->nullable();
    $table->string('lma_size')->nullable();
    $table->json('ventilator_result')->nullable();
    $table->json('drug_result')->nullable();
    $table->json('vasopressor_result')->nullable();
    $table->string('engine_version')->default('1.0.0');
    $table->timestamp('calculated_at')->useCurrent();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_calculations');
    }
};
