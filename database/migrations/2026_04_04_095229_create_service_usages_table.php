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
        Schema::create('service_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_visit_entry_id')->index();
            $table->string('service_type'); // internet, m365, ip_phone, sms, cloud
            $table->string('competitor')->nullable();
            $table->json('details'); // Stores dynamic data like Mbps, Licenses, etc.
            $table->timestamps();

            // Link to the specific visit event
            $table->foreign('sales_visit_entry_id')
                  ->references('id')
                  ->on('sales_visit_entries')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_usages');
    }
};
