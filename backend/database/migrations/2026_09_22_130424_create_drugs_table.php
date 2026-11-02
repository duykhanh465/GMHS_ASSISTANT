<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drugs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drug_category_id')->constrained('drug_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->string('unit')->nullable();
            $table->string('dose_text')->nullable();
            $table->decimal('dose_min_per_kg', 10, 3)->nullable();
            $table->decimal('dose_max_per_kg', 10, 3)->nullable();
            $table->enum('weight_basis', ['actual', 'pbw', 'ibw', 'adjbw', 'fixed'])->default('actual');
            $table->string('preparation')->nullable();
            $table->string('indication')->nullable();
            $table->string('timing')->nullable();
            $table->string('monitoring')->nullable();
            $table->text('warning')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drugs');
    }
};
