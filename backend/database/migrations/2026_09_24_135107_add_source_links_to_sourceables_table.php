<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sourceables', function (Blueprint $table) {
            $table->foreignId('source_id')
                ->constrained('sources')
                ->cascadeOnDelete();

            $table->morphs('sourceable');
            $table->string('priority')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('sourceables', function (Blueprint $table) {
            $table->dropMorphs('sourceable');
            $table->dropConstrainedForeignId('source_id');
            $table->dropColumn('priority');
        });
    }
};
