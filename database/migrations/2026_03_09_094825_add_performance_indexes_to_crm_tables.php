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
        Schema::table('leads', function (Blueprint $table) {
            $table->index(['status', 'created_at']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->index('closed_at');
            $table->index(['user_id', 'closed_at']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->index('visited_at');
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->index(['scheduled_at', 'completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['closed_at']);
            $table->dropIndex(['user_id', 'closed_at']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['visited_at']);
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropIndex(['scheduled_at', 'completed_at']);
        });
    }
};
