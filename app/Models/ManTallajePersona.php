<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManTallajePersona extends Model
{
    use HasFactory;

    protected $table = 'man_tallaje_personas';
    protected $primaryKey = 'TallajePersonaId';

    //para metodos CRUD 
    protected $fillable = ['TallajeTalla', 'FK_TipoPrenda', 'FK_PedidoPersona', 'FK_PedidoModelo', 'idColor', 'cantidadPrenda'];

    public function pedidosPersonas() {
        return $this->belongsTo('App\Models\OpPedidosPersona', 'FK_PedidoPersona', 'PedidoPersonaId');
    }

    public function pedidosModelos() {
        return $this->belongsTo('App\Models\OpPedidosModelo', 'FK_PedidoModelo', 'id');
    }

    public function tipoPrendas() {
        return $this->belongsTo('App\Models\ManTipoPrenda', 'FK_TipoPrenda', 'TipoPrendaId');
    }

    public function tallaPrendas() {
        return $this->belongsTo('App\Models\ManTallaPrenda', 'TallajeTalla', 'TallaPrendaId');
    }
}
