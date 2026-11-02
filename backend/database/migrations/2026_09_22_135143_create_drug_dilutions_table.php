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
        Schema::create('drug_dilutions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('drug_id')->constrained('drugs')->cascadeOnUpdate()->cascadeOnDelete();
    $table->string('label');
    $table->string('recipe_text')->nullable();
    $table->decimal('concentration_value', 10, 3)->nullable();
    $table->string('concentration_unit')->nullable();
    $table->string('rate_formula_text')->nullable();
    $table->decimal('rate_coefficient', 10, 4)->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drug_dilutions');
    }
};
