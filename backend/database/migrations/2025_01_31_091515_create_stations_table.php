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
        Schema::create('stations', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('name');
            $table->string('location');
            $table->double('weighting')->default(1);
            $table->boolean('moreIsBetter');
            $table->integer('typeId');
            $table->integer('userId')->nullable(); 
            $table->integer('competitionId');
            $table->foreign('typeId')->references('id')->on('result_types');
            $table->foreign('userId')->references('id')->on('users');
            $table->foreign('competitionId')->references('id')->on('competitions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
