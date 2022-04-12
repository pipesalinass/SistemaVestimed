<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecepcionCabecerasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recepcion_cabeceras', function (Blueprint $table) {
            $table->id('RecepcionCabeceraId');
            $table->unsignedBigInteger('FK_Pedido');
            $table->foreign('FK_Pedido')
                  ->references('PedidoId')
                  ->on('op_pedidos');
            $table->date('FechaRecepcion');
            $table->string('NumeroDocumentoExterno',70);
            $table->string('NumeroFactura',70);
            $table->date('FechaDocumentoExterno');
            $table->string('Estado',30);
            $table->string('Observacion',70)->nullable();
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
        Schema::dropIfExists('recepcion_cabeceras');
    }
}
