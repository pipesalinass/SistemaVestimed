<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('man_colors', function (Blueprint $table) {
            $table->id('ColorId');
            $table->string('ColNombre',50);
            $table->string('ColEstado',20);
            $table->unsignedBigInteger('FK_Modelo');
            $table->foreign('FK_Modelo')
                  ->references('ModeloId')
                  ->on('man_modelos');
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
        Schema::dropIfExists('man_colors');
    }
}
