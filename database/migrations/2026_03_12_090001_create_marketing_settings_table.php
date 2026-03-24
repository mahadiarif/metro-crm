<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_settings', function (Blueprint $col) {
            $col->id();
            $col->string('key')->unique();
            $col->text('value')->nullable();
            $col->string('group')->default('general');
            $col->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_settings');
    }
};
