<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManModelo extends Model
{
    use HasFactory;

    protected $table = 'man_modelos';
    protected $primaryKey = 'ModeloId';


    //para metodos CRUD 
    protected $fillable = ['ModCodigo', 'ModNombre', 'ModEstado'];

    public function colores() {
        return $this->hasMany('App\Models\ManColor');
    }

    public function pedidoModelos() {
        return $this->hasMany('App\Models\OpPedidosModelo');
    }
}
