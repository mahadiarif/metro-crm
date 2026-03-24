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
        Schema::create('quarterly_targets', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $blueprint->integer('year');
            $blueprint->integer('quarter'); // 1, 2, 3, 4
            $blueprint->decimal('target_amount', 15, 2);
            $blueprint->timestamps();

            $blueprint->unique(['user_id', 'year', 'quarter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quarterly_targets');
    }
};
