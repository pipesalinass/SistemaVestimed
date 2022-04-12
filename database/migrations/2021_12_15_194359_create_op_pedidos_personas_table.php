<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpPedidosPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_pedidos_personas', function (Blueprint $table) {
            $table->id('PedidoPersonaId');
            $table->string('PedPerRut',20);//->unique();
            $table->string('PedPerPrimerNombre',50);
            $table->string('PedPerSegundoNombre',50)->nullable();
            $table->string('PedPerPrimerApellido',50);
            $table->string('PedPerSegundoApellido',50);
            $table->string('PedPerMail', 250);
            $table->bigInteger('PedPerCelular');
            $table->unsignedBigInteger('FK_pedido');
            $table->foreign('FK_pedido')
                  ->references('PedidoId')
                  ->on('op_pedidos');
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
        Schema::dropIfExists('op_pedidos_personas');
    }
}
