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
        Schema::table('daily_sales_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('daily_sales_visits', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('address');
            }
            if (!Schema::hasColumn('daily_sales_visits', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('daily_sales_visits', 'visit_image')) {
                $table->string('visit_image')->nullable()->after('current_service_usage');
            }
            if (!Schema::hasColumn('daily_sales_visits', 'lead_temperature')) {
                $table->string('lead_temperature')->default('warm')->after('visit_image');
            }
            if (!Schema::hasColumn('daily_sales_visits', 'pain_points')) {
                $table->json('pain_points')->nullable()->after('lead_temperature');
            }
        });

        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'lead_temperature')) {
                $table->string('lead_temperature')->default('warm')->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_sales_visits', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'visit_image', 'lead_temperature', 'pain_points']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lead_temperature');
        });
    }
};
