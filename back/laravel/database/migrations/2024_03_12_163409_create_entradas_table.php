<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasTable extends Migration
{
    public function up()
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesion_id')->constrained('sessions');
            $table->integer('asientos');
            $table->decimal('precio', 8, 2); // Campo para el precio, se define como decimal con 8 dígitos en total y 2 decimales
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('entradas');
    }
}
