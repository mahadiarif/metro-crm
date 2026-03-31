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
            $table->index('assigned_user');
            $table->index('stage_id');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('service_id');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['assigned_user']);
            $table->dropIndex(['stage_id']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['service_id']);
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });
    }
};
