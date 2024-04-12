<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compras', function (Blueprint $table) {
            $table->unsignedBigInteger('sesion_id');
            $table->foreign('sesion_id')->references('id')->on('sessions');
            $table->foreignId('id_user')->nullable()->constrained('users');
            $table->string('butaca');
            $table->decimal('precio', 8, 2);
            $table->string('ocupacion');
            $table->timestamps();

            // Definir la clave primaria compuesta
            $table->primary(['sesion_id', 'butaca']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compras');
    }
};
