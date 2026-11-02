<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('emergency_protocols', function (Blueprint $table) {
            $table->id();
            $table->string('situation');
            $table->string('signs')->nullable();
            $table->text('immediate_action')->nullable();
            $table->string('adult_dose')->nullable();
            $table->string('result_by_weight')->nullable();
            $table->string('monitoring')->nullable();
            $table->text('differential')->nullable();
            $table->text('note')->nullable();
            $table->string('source_ref')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_protocols');
    }
};
