<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecepcionDetalle extends Model
{
    use HasFactory;

    protected $table = 'recepcion_detalles';
    protected $primaryKey = 'RecepcionDetalleId';


    //para metodos CRUD 
    protected $fillable = ['FK_RecepcionCabecera', 'FK_Pedido', 'TipoPrenda', 'CodigoModelo', 'Talla', 'Color', 'CantidadSolicitada', 'CantidadRecibida', 'CantidadFaltante', 'Estado', 'FK_DocumentoExterno'];
    protected $dates = ['created_at', 'updated_at', 'FechaRecepcion', 'FechaDocumentoExterno'];

    public function recepcionCabeceras() {
        return $this->belongsTo('App\Models\RecepcionCabecera', 'FK_RecepcionCabecera', 'RecepcionCabeceraId');
    }
}
