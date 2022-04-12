<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCantidadPrendaToManTallajePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('man_tallaje_personas', function (Blueprint $table) {
            $table->integer('cantidadPrenda');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('man_tallaje_personas', function (Blueprint $table) {
            $table->dropColumn('cantidadPrenda');
        });
    }
}
