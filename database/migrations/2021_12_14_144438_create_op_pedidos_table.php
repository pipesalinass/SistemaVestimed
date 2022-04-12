<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_pedidos', function (Blueprint $table) {
            $table->id('PedidoId');
            $table->string('PedEstado',20);
            $table->string('PedTitulo',50);
            $table->unsignedBigInteger('FK_Universidad')->nullable();
            $table->foreign('FK_Universidad')
                  ->references('UniversidadId')
                  ->on('man_universidads');
            $table->unsignedBigInteger('FK_Carrera')->nullable();
            $table->foreign('FK_Carrera')
                  ->references('CarreraId')
                  ->on('man_carreras');
            $table->text('PedDescripcion', 250)->nullable();
            $table->string('PedImagen', 400)->nullable();
            $table->unsignedBigInteger('FK_user');
            $table->foreign('FK_user')
                  ->references('id')
                  ->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('op_pedidos');
    }

}
