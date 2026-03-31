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
        // Add unsubscribed flag to leads
        Schema::table('leads', function (Blueprint $table) {
            $table->boolean('is_unsubscribed')->default(false)->after('email');
        });

        // Update campaign recipients for better tracking
        Schema::table('campaign_recipients', function (Blueprint $table) {
            if (!Schema::hasColumn('campaign_recipients', 'status')) {
                $table->string('status')->default('pending')->after('email');
            }
            if (!Schema::hasColumn('campaign_recipients', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('status');
            }
            $table->text('error_message')->nullable()->after('sent_at');
        });

        // Add soft deletes to templates
        Schema::table('marketing_templates', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('is_unsubscribed');
        });

        Schema::table('campaign_recipients', function (Blueprint $table) {
            $table->dropColumn(['error_message']);
        });

        Schema::table('marketing_templates', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
