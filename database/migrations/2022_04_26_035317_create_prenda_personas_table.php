<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrendaPersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prenda_personas', function (Blueprint $table) {
            $table->id('prendaPersonaId');
            $table->string('TipoPrendaPersona',50);
            $table->string('CodigoModeloPersona',50);
            $table->string('TallaPersona',50);
            $table->string('ColorPersona',50);
            $table->string('PersonaAsociada',50);
            $table->integer('CantidadPersona');
            $table->string('FK_RecepcionDetalle',50);
            $table->string('EstadoPersona',50);
            $table->string('FK_DocumentoExterno1',50);
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
        Schema::dropIfExists('prenda_personas');
    }
}
