<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecepcionCabecera extends Model
{
    use HasFactory;

    protected $table = 'recepcion_cabeceras';
    protected $primaryKey = 'RecepcionCabeceraId';


    //para metodos CRUD 
    protected $fillable = ['FK_Pedido', 'FechaRecepcion', 'NumeroDocumentoExterno', 'NumeroFactura', 'FechaDocumentoExterno', 'Estado', 'Observacion'];
    protected $dates = ['created_at', 'updated_at', 'FechaRecepcion', 'FechaDocumentoExterno'];

    public function recepcionDetalle() {
        return $this->hasMany('App\Models\RecepcionDetalle');
    }

}
