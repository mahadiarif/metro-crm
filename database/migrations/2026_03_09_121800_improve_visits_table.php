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
            $table->integer('visit_number')->after('user_id')->default(1);
            $table->foreignId('service_id')->nullable()->after('visit_number')->constrained('services');
            $table->timestamp('next_followup_date')->nullable()->after('notes');
            // 'notes' is already present as 'notes' in the existing table, but the user requested 'meeting_notes'.
            // I will rename 'notes' to 'meeting_notes' to match the request exactly.
            $table->renameColumn('notes', 'meeting_notes');
            $table->renameColumn('visited_at', 'visit_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->renameColumn('meeting_notes', 'notes');
            $table->renameColumn('visit_date', 'visited_at');
            $table->dropConstrainedForeignId('service_id');
            $table->dropColumn(['visit_number', 'next_followup_date']);
        });
    }
};
