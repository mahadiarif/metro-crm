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
            $table->string('contact_person')->nullable()->after('client_name');
            $table->string('designation')->nullable()->after('contact_person');
            $table->string('existing_provider')->nullable()->after('email');
            $table->text('current_usage')->nullable()->after('existing_provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['contact_person', 'designation', 'existing_provider', 'current_usage']);
        });
    }
};
