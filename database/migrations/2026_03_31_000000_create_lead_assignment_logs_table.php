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
        Schema::create('lead_assignment_logs', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('lead_id')->constrained()->onDelete('cascade');
            $blueprint->foreignId('assigned_from')->nullable()->constrained('users')->onDelete('set null');
            $blueprint->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_assignment_logs');
    }
};
