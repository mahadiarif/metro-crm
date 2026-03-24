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
        Schema::table('visits', function (Blueprint $table) {
            $table->string('visit_stage')->default('1st Visit')->after('visit_number'); // 1st, 2nd, 3rd, 4th Visit
            $table->string('interest_summary_status')->nullable()->after('meeting_notes'); // follow_up, proposal_request, closed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn(['visit_stage', 'interest_summary_status']);
        });
    }
};
