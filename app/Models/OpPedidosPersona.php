<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpPedidosPersona extends Model
{
    use HasFactory;

    protected $table = 'op_pedidos_personas';
    protected $primaryKey = 'PedidoPersonaId';


    //para metodos CRUD 
    protected $fillable = ['PedPerRut', 'PedPerPrimerNombre', 'PedPerSegundoNombre', 'PedPerPrimerApellido','PedPerSegundoApellido','PedPerMail', 'PedPerCelular', 'FK_pedido', 'PedPerEstado'];


    public function pedidos() {
        return $this->belongsTo('App\Models\OpPedidos');
    }


}
