<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpPedidosModelo extends Model
{
    use HasFactory;

    protected $table = 'op_pedidos_modelos';
    
    //para metodos CRUD 
    protected $fillable = ['FK_Modelo', 'FK_Color', 'FK_Pedido'];


    public function pedidos() {
        return $this->belongsTo('App\Models\OpPedidos');
    }

    public function modelos() {
        return $this->belongsTo('App\Models\ManModelos');
    }

}
