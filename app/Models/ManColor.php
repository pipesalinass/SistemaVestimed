<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManColor extends Model
{
    use HasFactory;

    protected $table = 'man_colors';
    protected $primaryKey = 'ColorId';


    //para metodos CRUD 
    protected $fillable = ['ColNombre', 'ColEstado', 'FK_Modelo'];

    
    public function modelos() {
        return $this->belongsTo('App\Models\ManModelo', 'FK_Modelo', 'ModeloId');
    }
}
