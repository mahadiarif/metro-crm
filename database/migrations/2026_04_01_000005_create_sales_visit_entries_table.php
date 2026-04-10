<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_visit_entries', function (Blueprint $row) {
            $row->id();
            $row->foreignId('daily_sales_visit_id')->constrained('daily_sales_visits')->cascadeOnDelete();
            
            $row->integer('visit_number'); // 1, 2, 3, 4
            $row->date('visit_date');
            $row->string('service_type'); // Internet, IPTSP, etc.
            $row->string('status'); // follow_up, service_request, not_interested
            $row->text('notes')->nullable();
            
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_visit_entries');
    }
};
