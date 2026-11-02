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
        Schema::create('ventilator_presets', function (Blueprint $table) {
    $table->id();
    $table->string('clinical_scenario');
    $table->string('parameter');
    $table->decimal('value_min', 10, 2)->nullable();
    $table->decimal('value_max', 10, 2)->nullable();
    $table->decimal('default_value', 10, 2)->nullable();
    $table->string('value_display')->nullable();
    $table->string('unit')->nullable();
    $table->string('monitoring')->nullable();
    $table->text('warning')->nullable();
    $table->unsignedInteger('sort_order')->default(0);
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventilator_presets');
    }
};
