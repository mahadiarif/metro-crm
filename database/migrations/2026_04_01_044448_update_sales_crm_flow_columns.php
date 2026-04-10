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
        // 1. Update Leads table
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'status')) {
                $table->string('status')->default('active')->after('service_package_id'); // active, closed, in_pipeline
            }
            if (!Schema::hasColumn('leads', 'close_reason')) {
                $table->text('close_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('leads', 'last_called_at')) {
                $table->timestamp('last_called_at')->nullable()->after('close_reason');
            }
        });

        // 2. Update Sales Calls table outcome
        // We'll change it to string to be more flexible than enum, but we can't easily change enum in MySQL via Schema without raw SQL or Doctrine.
        // Given it's Laravel 12, we'll just use change().
        Schema::table('sales_calls', function (Blueprint $table) {
            $table->string('outcome')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['status', 'close_reason', 'last_called_at']);
        });

        Schema::table('sales_calls', function (Blueprint $table) {
            // Reverting to something generic or leaving as string is safer.
            $table->string('outcome')->change();
        });
    }
};
