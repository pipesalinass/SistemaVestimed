<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrendaPersona extends Model
{
    use HasFactory;

    protected $table = 'prenda_personas';
    protected $primaryKey = 'prendaPersonaId';


    //para metodos CRUD 
    protected $fillable = ['TipoPrendaPersona', 'CodigoModeloPersona', 'TallaPersona', 'ColorPersona', 'PersonaAsociada', 'FK_RecepcionDetalle', 'EstadoPersona', 'CantidadPersona', 'FK_DocumentoExterno1', 'FechaAsignado', 'FechaBordado', 'FechaRecibeBordado', 'FechaEntregado'];
    protected $dates = ['created_at', 'updated_at'];
}
