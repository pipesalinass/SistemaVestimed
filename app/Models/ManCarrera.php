<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManCarrera extends Model
{
    use HasFactory;

    protected $table = 'man_carreras';
    protected $primaryKey = 'CarreraId';


    //para metodos CRUD 
    protected $fillable = ['ManNombre'];


    public function pedidos() {
        return $this->hasMany('App\Models\OpPedidos', 'PedidoId');
    }

}
