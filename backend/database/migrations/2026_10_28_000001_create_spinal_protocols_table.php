<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spinal_protocols', function (Blueprint $table) {
            $table->id();
            $table->string('technique');
            $table->string('adult_dose')->nullable();
            $table->string('reduced_dose')->nullable();
            $table->string('suggested_result')->nullable();
            $table->string('preparation')->nullable();
            $table->string('monitoring')->nullable();
            $table->text('event_management')->nullable();
            $table->text('warning')->nullable();
            $table->string('source_ref')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spinal_protocols');
    }
};
