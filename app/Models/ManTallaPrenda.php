<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManTallaPrenda extends Model
{
    use HasFactory;

    protected $table = 'man_talla_prendas';
    protected $primaryKey = 'TallaPrendaId';

    //para metodos CRUD 
    protected $fillable = ['ManNombre'];

    public function tallajePersonas() {
        return $this->hasMany('App\Models\ManTallajePersona', 'TallajePersonaId');
    }
}
