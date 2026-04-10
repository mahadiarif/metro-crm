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
            $table->string('visit_status')->nullable()->after('lead_source');
            $table->date('next_followup_date')->nullable()->after('visit_status');
            $table->string('followup_type')->nullable()->after('next_followup_date');
            $table->text('notes')->nullable()->after('followup_type');
        });

        Schema::table('sales_calls', function (Blueprint $table) {
            $table->string('call_status')->nullable()->after('outcome');
            $table->date('next_followup_date')->nullable()->after('next_call_at');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->string('lead_status')->nullable()->default('qualified')->after('status');
            $table->integer('visit_count')->default(1)->after('lead_status');
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->string('source_type')->nullable()->after('user_id');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
            $table->string('status')->default('pending')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_sales_visits', function (Blueprint $table) {
            $table->dropColumn(['visit_status', 'next_followup_date', 'followup_type', 'notes']);
        });

        Schema::table('sales_calls', function (Blueprint $table) {
            $table->dropColumn(['call_status', 'next_followup_date']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['lead_status', 'visit_count']);
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropColumn(['source_type', 'source_id', 'status']);
        });
    }
};
