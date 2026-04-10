<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add expert-level performance indexes.
     */
    public function up(): void
    {
        Schema::table('daily_sales_visits', function (Blueprint $table) {
            $table->index('lead_id');
            $table->index('user_id');
            $table->index('company_name'); // For faster searching if needed
        });

        Schema::table('sales_visit_entries', function (Blueprint $table) {
            $table->index('daily_sales_visit_id');
            $table->index('visit_number');
            $table->index('status');
            $table->index('visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_sales_visits', function (Blueprint $table) {
            $table->dropIndex(['lead_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['company_name']);
        });

        Schema::table('sales_visit_entries', function (Blueprint $table) {
            $table->dropIndex(['daily_sales_visit_id']);
            $table->dropIndex(['visit_number']);
            $table->dropIndex(['status']);
            $table->dropIndex(['visit_date']);
        });
    }
};
