<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManTallajePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man_tallaje_personas', function (Blueprint $table) {
            $table->id('TallajePersonaId');
            $table->string('TallajeTalla',20);
            $table->unsignedBigInteger('FK_TipoPrenda')->nullable();
            $table->foreign('FK_TipoPrenda')
                  ->references('TipoPrendaId')
                  ->on('man_tipo_prendas');
            $table->unsignedBigInteger('FK_PedidoPersona')->nullable();
            $table->foreign('FK_PedidoPersona')
                  ->references('PedidoPersonaId')
                  ->on('op_pedidos_personas');
            $table->unsignedBigInteger('FK_PedidoModelo')->nullable();
            $table->foreign('FK_PedidoModelo')
                  ->references('id')
                  ->on('op_pedidos_modelos')
                  ->onDelete('cascade');         
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
        Schema::dropIfExists('man_tallaje_personas');
    }
}
