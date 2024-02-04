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
        Schema::create('asistentes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastName');
            $table->string('email');
            $table->date('registro');
            $table->bigInteger('userId')->unsigned()->nullable();
            $table->foreign('userId')->references('id')->on('users');
            $table->bigInteger('eventoId')->unsigned();
            $table->foreign('eventoId')->references('id')->on('eventos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistentes');
    }
};
