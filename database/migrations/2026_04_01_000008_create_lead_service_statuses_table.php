<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations to normalize Lead-Service relationships.
     */
    public function up(): void
    {
        Schema::create('lead_service_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('follow_up'); // follow_up, service_request, not_interested
            $table->timestamps();
            
            $table->unique(['lead_id', 'service_id']);
        });

        // Data Migration: Move existing service_id from leads to new table
        $leads = DB::table('leads')->whereNotNull('service_id')->get();
        foreach ($leads as $lead) {
            DB::table('lead_service_statuses')->insert([
                'lead_id'    => $lead->id,
                'service_id' => $lead->service_id,
                'status'     => $lead->status === 'in_pipeline' ? 'service_request' : 'follow_up',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_service_statuses');
    }
};
