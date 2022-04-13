<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecepcionDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recepcion_detalles', function (Blueprint $table) {
            $table->id('RecepcionDetalleId');
            $table->unsignedBigInteger('FK_RecepcionCabecera');
            $table->foreign('FK_RecepcionCabecera')
                  ->references('RecepcionCabeceraId')
                  ->on('recepcion_cabeceras');
            $table->string('FK_Pedido',50);
            $table->string('TipoPrenda',50);
            $table->string('CodigoModelo',50);
            $table->string('Talla',50);
            $table->string('Color',50);
            $table->integer('CantidadSolicitada');
            $table->integer('CantidadRecibida');
            $table->integer('CantidadFaltante');
            $table->integer('CantidadSolicitadaAnterior');
            $table->integer('CantidadRecibidaAnterior');
            $table->integer('CantidadFaltanteAnterior');
            $table->string('Estado',50);
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
        Schema::dropIfExists('recepcion_detalles');
    }
}
