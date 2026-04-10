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
        Schema::table('lead_service_statuses', function (Blueprint $table) {
            $table->string('competitor_name')->after('status')->nullable();
            $table->string('current_usage')->after('competitor_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_service_statuses', function (Blueprint $table) {
            $table->dropColumn(['competitor_name', 'current_usage']);
        });
    }
};
