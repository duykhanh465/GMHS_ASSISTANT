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
       Schema::create('patients', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
    $table->string('code');
    $table->string('full_name')->nullable();
    $table->unsignedTinyInteger('age');
    $table->enum('gender', ['male', 'female', 'other']);
    $table->decimal('height_cm', 5, 1);
    $table->decimal('weight_kg', 5, 1);
    $table->enum('asa_class', ['I', 'II', 'III', 'IV', 'V']);
    $table->boolean('is_pregnant')->default(false);
    $table->enum('respiratory_condition', ['routine', 'asthma_copd', 'ards', 'co2_endoscopy'])->default('routine');
    $table->text('surgery_note')->nullable();
    $table->timestamps();
    $table->softDeletes();
    $table->unique(['user_id', 'code']);
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
