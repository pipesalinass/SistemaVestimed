<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManPedidosExterno extends Model
{
    use HasFactory;

    protected $table = 'man_pedidos_externos';
    protected $primaryKey = 'PedidoExternoId';


    //para metodos CRUD 
    protected $fillable = ['NumPedidoExterno', 'ObsPedidoExterno', 'FK_Pedido'];

}
