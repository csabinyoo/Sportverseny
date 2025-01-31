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
        Schema::create('team_at_stations', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('teamId');
            $table->integer('stationId');
            $table->foreign('teamId')->references('id')->on('teams');
            $table->foreign('stationId')->references('id')->on('stations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_at_stations');
    }
};
