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
        if (!Schema::hasColumn('leads', 'stage_id')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->foreignId('stage_id')->nullable()->constrained('pipeline_stages')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['stage_id']);
            $table->dropColumn('stage_id');
        });
    }
};
