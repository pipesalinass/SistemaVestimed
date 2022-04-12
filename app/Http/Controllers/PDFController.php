<?php

namespace App\Http\Controllers;

use App\Models\ManCurso;
use App\Models\ManCliente;
use App\Models\ManContactoCliente;
use App\Models\OpDetalleCotizacion;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\OpCotizacion;
use App\Models\OpPedidos;
use Illuminate\Support\Facades\Crypt;
use App\Models\ManTallajePersona;
use Illuminate\Support\Facades\DB;
use App\Models\ManColor;
use App\Models\ManTipoPrenda;
use App\Models\ManUniversidad;
use App\Models\ManCarrera;
use Illuminate\Support\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

class PDFController extends Controller
{
    public $listaSumatoria = [];
    public $listaNombres = [];
    public $nombrePedido;
    public $celularPedido;
    public $tituloPedido; 
    public $universidadPedido;
    public $carreraPedido;
    public $descripcionPedido;
    public $fecha;



    public function generatePDF($id)
    {
        $persona = OpPedidos::select('op_pedidos.*', 'users.*')
                            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'users.id')
                            ->where('op_pedidos.PedidoId', '=', $id)
                            ->first();
               
        $pedido = OpPedidos::where('PedidoId', '=', $id)->first();
        //dd($pedido);
        $universidad = ManUniversidad::findOrFail($pedido->FK_Universidad);
        if ($universidad) {
            //$this->reset(['idUniversidad', 'universidadPedido']);
            $this->universidadPedido = $universidad->ManNombre;
            $this->idUniversidad = $universidad->UniversidadId;
        } else {
            $this->reset(['idUniversidad']);
        }

        $carrera = ManCarrera::findOrFail($pedido->FK_Carrera);
        if ($carrera) {
            //$this->reset(['idCarrera', 'carreraPedido']);
            $this->carreraPedido = $carrera->ManNombre;
            $this->idCarrera = $carrera->CarreraId;
        } else {
            $this->reset(['idCarrera']);
        }

        $this->pedido = $pedido;
        $this->fecha = date('Y-m-d');;
        $this->nombrePedido = $persona->name;
        $this->celularPedido = $persona->NumeroContacto;
        $this->tituloPedido = $pedido->PedTitulo;
        $this->descripcionPedido = $pedido->PedDescripcion;
        //$this->fecha = $pedido->created_at;


        //$this->tituloPedido = $pedido->PedTitulo;
        //$this->descripcionPedido = $pedido->PedDescripcion;
        $listado = ManTallajePersona::select(['man_tallaje_personas.*',DB::raw('count(*) as total'), 'op_pedidos_personas.*', 'man_modelos.*', 'op_pedidos_modelos.*',DB::raw('sum(cantidadPrenda) as suma')] )
                                    ->leftjoin('op_pedidos_personas', 'man_tallaje_personas.FK_PedidoPersona', '=', 'op_pedidos_personas.PedidoPersonaId')
                                    ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                                    ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                                    ->where('op_pedidos_personas.FK_pedido', '=', $id)
                                    ->groupBy('ModCodigo', 'ModNombre', 'TallajeTalla', 'FK_TipoPrenda', 'idColor')
                                    ->get();

        //$this->reset('listaSumatoria');
        foreach ($listado as $item) {                                    
        $tipoPrenda = ManTipoPrenda::where('TipoPrendaId', '=', $item->FK_TipoPrenda)->first();    
        $colorPrenda = ManColor::where('ColorId', '=', $item->idColor)->first();    

        $a = array();
        $a['TallajeTalla'] = $item->TallajeTalla;
        $a['ModCodigo'] = $item->ModCodigo;
        $a['ModNombre'] = $item->ModNombre;
        $a['TipoPrenda'] = $tipoPrenda->ManNombre;
        $a['ColorPrenda'] = "$colorPrenda->ColNombre";
        $a['sumatoria'] = $item->suma;

        $this->listaSumatoria[] = $a;
        }
        
        $datos = [
            'fecha' => date('m/d/Y'),
            'nombrePedido'              => $this->nombrePedido,
            'celularPedido'             => $this->celularPedido,
            'tituloPedido'              => $this->tituloPedido,
            'universidadPedido'         => $this->universidadPedido,
            'carreraPedido'             => $this->carreraPedido,
            'descripcionPedido'         => $this->descripcionPedido,

        ];

        $pdf = PDF::loadView('generadorPDFPedido', $datos, [
            'listaSumatoria'            => $this->listaSumatoria,


        ]);
        return $pdf->stream('Carta-de-pedido.pdf');


    }

    public function generateNombres($id)
    {
               
        $listado = ManTallajePersona::select(['man_tallaje_personas.*', 'op_pedidos_personas.*', 'man_modelos.*', 'op_pedidos_modelos.*', 'man_tipo_prendas.*', 'man_colors.*'] )
                                    ->leftjoin('op_pedidos_personas', 'man_tallaje_personas.FK_PedidoPersona', '=', 'op_pedidos_personas.PedidoPersonaId')
                                    ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                                    ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                                    ->leftjoin('man_tipo_prendas', 'man_tallaje_personas.FK_TipoPrenda', '=', 'man_tipo_prendas.TipoPrendaId')
                                    ->leftjoin('man_colors', 'man_tallaje_personas.idColor', '=', 'man_colors.ColorId')
                                    ->where('op_pedidos_personas.FK_pedido', '=', $id)
                                    //->groupBy('PedPerPrimerNombre')
                                    ->get();
        //dd($listado);
        foreach ($listado as $item) {                                    
            for ($i = 0; $i < $item->cantidadPrenda; $i++ ) {
                $a = array();
                $a['Nombre'] = $item->PedPerPrimerNombre. " ". $item->PedPerSegundoNombre. " ". $item->PedPerPrimerApellido. " ". $item->PedPerSegundoApellido;
                $a['Prenda'] = $item->ModCodigo. " ". $item->ManNombre. " ". $item->TallajeTalla. " ". $item->ColNombre;

                $this->listaNombres[] = $a;
                }
        }
    
        $pdf = PDF::loadView('generadorPDFNombres', [
            'listaNombres'            => $this->listaNombres,

        ]);
        return $pdf->stream('Carta-de-pedido.pdf');

    }
}
