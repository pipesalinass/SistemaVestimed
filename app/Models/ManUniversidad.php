<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManUniversidad extends Model
{
    use HasFactory;

    protected $table = 'man_universidads';
    protected $primaryKey = 'UniversidadId';


    //para metodos CRUD 
    protected $fillable = ['ManNombre'];


    public function pedidos() {
        return $this->hasMany('App\Models\OpPedidos', 'PedidoId');
    }
    
}
