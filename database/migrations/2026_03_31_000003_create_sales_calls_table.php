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
        Schema::create('sales_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('outcome'); // busy, reached, no_answer, follow_up, closed, etc.
            $table->text('notes')->nullable();
            $table->dateTime('called_at');
            $table->dateTime('next_call_at')->nullable();
            $table->timestamps();
            
            // Indexes for dashboard performance
            $table->index(['user_id', 'called_at']);
            $table->index(['user_id', 'next_call_at']);
            $table->index(['outcome']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_calls');
    }
};
