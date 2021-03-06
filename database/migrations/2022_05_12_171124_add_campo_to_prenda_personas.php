<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampoToPrendaPersonas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prenda_personas', function (Blueprint $table) {
            $table->date('FechaAsignado')->nullable();
            $table->date('FechaBordado')->nullable();
            $table->date('FechaRecibeBordado')->nullable();
            $table->date('FechaEntregado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prenda_personas', function (Blueprint $table) {
            $table->dropColumn('FechaAsignado');
            $table->dropColumn('FechaBordado');
            $table->dropColumn('FechaRecibeBordado');
            $table->dropColumn('FechaEntregado');
        });
    }
}
