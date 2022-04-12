<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManTipoPrenda extends Model
{
    use HasFactory;

    protected $table = 'man_tipo_prendas';
    protected $primaryKey = 'TipoPrendaId';

    //para metodos CRUD 
    protected $fillable = ['ManNombre'];

    public function tallajePersonas() {
        return $this->hasMany('App\Models\ManTallajePersona', 'TallajePersonaId');
    }
}
