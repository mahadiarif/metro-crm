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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained();
            $table->foreignId('user_id')->constrained(); // Prepared by
            $table->foreignId('service_id')->constrained();
            $table->foreignId('service_package_id')->nullable()->constrained();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('status')->default('draft'); // draft, sent, accepted, rejected
            $table->string('pdf_path')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
