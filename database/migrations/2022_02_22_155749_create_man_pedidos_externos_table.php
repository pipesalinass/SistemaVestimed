<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManPedidosExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man_pedidos_externos', function (Blueprint $table) {
            $table->id('PedidoExternoId');
            $table->string('NumPedidoExterno',100);
            $table->string('ObsPedidoExterno',200);
            $table->unsignedBigInteger('FK_Pedido')->nullable();
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
        Schema::dropIfExists('man_pedidos_externos');
    }
}
