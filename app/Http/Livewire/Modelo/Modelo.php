<?php

namespace App\Http\Livewire\Modelo;

use Livewire\Component;
use App\Models\Cotizacion;
use App\Models\ManModelo;
use App\Models\ManColor;

class Modelo extends Component
{

    public $search;
    public $sortBy = 'id';
    public $sortAsc = true;    

    //Variables para crear una cotizacion
    public $cotizacion;
    public $modelo;
    public $color;

    public $confirmingModeloAdd= false;
    public $confirmingColorEdit= false;

    protected $rules = [
        'modelo.ModeloId' => 'required|string',
        'modelo.Codigo' => 'required|string',
        'modelo.Modelo' => 'required|string',
        'modelo.Estado' => 'required|string',
    ];

    public function confirmModeloAdd(){
        $this->reset(['modelo']);
        $this->confirmingModeloAdd = true;
        $this->confirmingCotizacionAdd = false;
    }    

    public function confirmColorEdit(){
        $this->reset(['color']);
        $this->confirmingColorEdit = true;
    }        

    //Funciones para editar una cotizacion
    public function confirmModeloEdit(ManModelo $modelo){
        $this->modelo = $modelo;
        $this->confirmingModeloAdd = true;
    }    

    public function saveModelo() {
        if(isset ($this->modelo->ModeloId)) {
            $this->modelo->save();
        }else{     
        ManModelo::create([
            'Codigo' => $this->modelo['Codigo'],
            'Modelo' => $this->modelo['Modelo'],
            'Estado' => $this->modelo['Estado'],

            ]);
        }
        $this->confirmingModeloAdd = false;
    }    


    public function render()
    {
        $this->modelo['Estado'] = "Disponible";
        $cotizaciones = Cotizacion::where('nombre', 'like', '%'. $this->search.'%')
        ->orwhere('vendedor', 'like', '%'. $this->search.'%')
        ->orderBy( $this->sortBy, $this->sortAsc ? 'ASC' : 'DESC')
        
        ->paginate(10);

        $modelos = ManModelo::all();

        return view('livewire.modelo.modelo',compact('cotizaciones', 'modelos'));
    }
}
