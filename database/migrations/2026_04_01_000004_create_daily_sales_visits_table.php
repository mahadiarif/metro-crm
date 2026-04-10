<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_sales_visits', function (Blueprint $row) {
            $row->id();
            $row->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $row->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Client snapshot (auto-filled from lead)
            $row->string('company_name');
            $row->text('address')->nullable();
            $row->string('contact_person');
            $row->string('designation')->nullable();
            $row->string('contact_number');
            $row->string('email')->nullable();
            $row->string('lead_source')->nullable();
            $row->string('competitor_provider')->nullable();
            $row->text('current_service_usage')->nullable();
            
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_sales_visits');
    }
};
