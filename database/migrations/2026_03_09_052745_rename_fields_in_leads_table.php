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
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('contact_person', 'client_name');
            $table->renameColumn('assigned_to', 'assigned_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->renameColumn('client_name', 'contact_person');
            $table->renameColumn('assigned_user', 'assigned_to');
        });
    }
};
