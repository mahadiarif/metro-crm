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
        Schema::table('service_usages', function (Blueprint $table) {
            // First, make the existing visit ID nullable to support calls
            $table->unsignedBigInteger('sales_visit_entry_id')->nullable()->change();
            
            // Add the new call ID
            $table->unsignedBigInteger('sales_call_id')->nullable()->after('sales_visit_entry_id')->index();

            $table->foreign('sales_call_id')
                  ->references('id')
                  ->on('sales_calls')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_usages', function (Blueprint $table) {
            $table->dropForeign(['sales_call_id']);
            $table->dropColumn('sales_call_id');
            $table->unsignedBigInteger('sales_visit_entry_id')->nullable(false)->change();
        });
    }
};
