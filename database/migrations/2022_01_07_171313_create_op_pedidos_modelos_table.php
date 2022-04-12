<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpPedidosModelosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('op_pedidos_modelos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('FK_Modelo');
            $table->foreign('FK_Modelo')
                  ->references('ModeloId')
                  ->on('man_modelos');
            $table->string('FK_Color');
            $table->unsignedBigInteger('FK_Pedido');
            $table->foreign('FK_Pedido')
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
        Schema::dropIfExists('op_pedidos_modelos');
    }
}
