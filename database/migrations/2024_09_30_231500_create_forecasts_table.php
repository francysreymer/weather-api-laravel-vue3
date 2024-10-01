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
        Schema::create('forecasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->datetime('date_forecast');
            $table->string('main');
            $table->string('description');
            $table->string('icon');
            $table->float('temperature');
            $table->float('feels_like');
            $table->float('min_temperature');
            $table->float('max_temperature');
            $table->float('pressure');
            $table->float('humidity');
            $table->float('wind_speed');
            $table->float('cloudiness');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forecasts');
    }
};
