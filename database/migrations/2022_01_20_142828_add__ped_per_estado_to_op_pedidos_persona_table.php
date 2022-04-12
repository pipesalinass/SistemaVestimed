<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPedPerEstadoToOpPedidosPersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_pedidos_personas', function (Blueprint $table) {
            $table->string('PedPerEstado');  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_pedidos_personas', function (Blueprint $table) {
            $table->dropColumn('PedPerEstado');
        });
    }
}
