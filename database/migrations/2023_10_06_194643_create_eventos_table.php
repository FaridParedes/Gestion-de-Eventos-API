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
        Schema::create('eventos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->boolean('allDay');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('session');
            $table->string('ubication');
            $table->string('enlace');
            $table->bigInteger('organizadorId')->unsigned();
            $table->foreign('organizadorId')->references('id')->on('users');
            $table->bigInteger('categoriaId')->unsigned();
            $table->foreign('categoriaId')->references('id')->on('categorias');
            $table->bigInteger('estadoId')->unsigned();
            $table->foreign('estadoId')->references('id')->on('estados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
