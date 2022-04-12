<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpPedidos extends Model
{
    use HasFactory;

    protected $table = 'op_pedidos';
    protected $primaryKey = 'PedidoId';


    //para metodos CRUD 
    protected $fillable = ['PedEstado', 'PedTitulo', 'FK_Universidad', 'FK_Carrera','PedDescripcion','PedImagen', 'FK_user', 'PedidoFechaCreacion'];
    protected $dates = ['created_at', 'updated_at', 'PedidoFechaCreacion'];


    public function pedidoPersonas() {
        return $this->hasMany('App\Models\OpPedidosPersona');
    }

    public function universidades() {
        return $this->belongsTo('App\Models\ManUniversidad', 'FK_Universidad', 'UniversidadId');
    }

    public function carreras() {
        return $this->belongsTo('App\Models\ManCarrera', 'FK_Carrera', 'CarreraId');
    }

    public function pedidoModelos() {
        return $this->hasMany('App\Models\OpPedidosModelo');
    }

}
