<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManModeloColor extends Model
{
    use HasFactory;

    protected $table = 'man_modelo_colors';
    protected $primaryKey = 'ModeloColorId';


    //para metodos CRUD 
    protected $fillable = ['FK_Modelo', 'FK_Color'];
}
