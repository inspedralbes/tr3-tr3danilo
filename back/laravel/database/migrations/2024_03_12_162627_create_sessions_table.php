<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelicula_id')->constrained('pelicules');
            $table->date('fecha');
            $table->time('hora');
            $table->timestamps();
        });

        // Agregar restricción de clave externa con eliminación en cascada
        Schema::table('compras', function (Blueprint $table) {
            $table->foreign('sesion_id')->references('id')->on('sessions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('compras', function (Blueprint $table) {
            // Eliminar la restricción de clave externa antes de eliminar la tabla
            $table->dropForeign(['sesion_id']);
        });

        Schema::dropIfExists('sessions');
    }
}
