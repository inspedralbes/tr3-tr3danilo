<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateButacasTable extends Migration
{
    public function up()
    {
        Schema::create('butacas', function (Blueprint $table) {
            $table->id(); // Columna para la clave primaria, se llama 'butaca_id'
            $table->string('ocupacion')->default('libre');
            $table->decimal('precio', 8, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('butacas');
    }
}

