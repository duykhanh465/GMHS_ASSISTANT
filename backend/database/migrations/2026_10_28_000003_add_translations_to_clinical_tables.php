<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['drugs', 'drug_categories', 'sources', 'spinal_protocols', 'emergency_protocols'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->json('translations')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['emergency_protocols', 'spinal_protocols', 'sources', 'drug_categories', 'drugs'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('translations');
            });
        }
    }
};
