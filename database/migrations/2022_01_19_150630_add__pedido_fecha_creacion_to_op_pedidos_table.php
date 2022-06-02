<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPedidoFechaCreacionToOpPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('op_pedidos', function (Blueprint $table) {
            $table->date('PedidoFechaCreacion');        
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('op_pedidos', function (Blueprint $table) {
            $table->dropColumn('PedidoFechaCreacion');
            
        });
    }
}
