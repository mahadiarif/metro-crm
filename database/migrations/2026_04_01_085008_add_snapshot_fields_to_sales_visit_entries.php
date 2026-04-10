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
        Schema::table('sales_visit_entries', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('source')->nullable();
            $table->string('existing_provider')->nullable();
            $table->text('current_usage')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_visit_entries', function (Blueprint $table) {
            $table->dropColumn([
                'address', 
                'contact_person', 
                'designation', 
                'phone', 
                'email', 
                'source', 
                'existing_provider', 
                'current_usage'
            ]);
        });
    }
};
