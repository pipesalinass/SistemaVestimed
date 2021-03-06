<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampoToRecepcionDetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recepcion_detalles', function (Blueprint $table) {
            $table->string('FK_DocumentoExterno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recepcion_detalles', function (Blueprint $table) {
            $table->dropColumn('FK_DocumentoExterno');
        });
    }
}
