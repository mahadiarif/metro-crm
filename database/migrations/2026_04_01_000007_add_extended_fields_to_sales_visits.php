<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add legacy-compatible fields to the modern schema.
     */
    public function up(): void
    {
        Schema::table('sales_visit_entries', function (Blueprint $table) {
            $table->foreignId('marketing_exe_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('location')->nullable();
            $table->date('next_followup_at')->nullable();
            $table->string('visit_stage')->nullable(); // 1st Visit, 2nd Visit, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_visit_entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('marketing_exe_id');
            $table->dropColumn(['location', 'next_followup_at', 'visit_stage']);
        });
    }
};
