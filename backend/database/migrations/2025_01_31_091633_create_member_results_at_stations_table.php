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
        Schema::create('member_results_at_stations', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('teamAtStationId');
            $table->integer('teamMemberId');
            $table->integer('result')->nullable();
            $table->time('resultTime')->nullable();
            $table->foreign('teamAtStationId')->references('id')->on('team_at_stations');
            $table->foreign('teamMemberId')->references('id')->on('team_members');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_results_at_stations');
    }
};
