<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Models\ManCarrera;
use App\Models\ManColor;
use App\Models\ManModelo;
use App\Models\ManModeloColor;
use App\Models\ManPedidosExterno;
use App\Models\ManUniversidad;
use App\Models\ManTipoPrenda;
use App\Models\ManTallajePersona;
use App\Models\ManTallaPrenda;
use App\Models\OpPedidos;
use App\Models\OpPedidosModelo;
use App\Models\OpPedidosPersona;
use App\Models\PrendaPersona;
use App\Models\RecepcionCabecera;
use App\Models\RecepcionDetalle;
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Redirect;
use JeroenNoten\LaravelAdminLte\Components\Form\Select;
use Illuminate\Support\Facades\DB;

class PostPedido extends Component
{

    use AuthorizesRequests;
    use WithPagination;

    public $temp;
    public $temporal;
    public $indice;


    //Variable para buscar un pedido
    public $search;
    
    //Variables estados postPedido
    public $estado_pick = "";
    public $recepcion_parcial = 0;
    public $recepcion_finalizada = 0;
    public $en_bordado = 0;
    public $recibe_de_bordado = 0;
    public $entregado = 0;
    

    /**Variables iniciales PostPedido */
    public $pedido;
    public $todayDate;
    public $idPedidoAsociado;
    public $pedidoAsociado;
    public $numeroFactura;
    public $observacionfactura;
    public $fechaPedidoExterno;
    public $listaSumatoria = [];

    public $cantidadRecibida;
    public $cantidadRecibidaActualizada=0;
    public $FK_Pedido;
    public $FK_RecepcionCabecera;

    /**Variables iniciales mostrar recepcion anterior */
    public $cantidadAnteriorSolicitada;
    public $cantidadAnteriorRecibida;
    public $cantidadAnteriorFaltante;
    public $listaAnterior = [];

    /**Variables iniciales bordado */
    public $fechaBordado;
    public $pedidoAsociadoBordado;
    public $idPedidoAsociadoBordado;
    public $listaSumatoriaBordado = [];
    public $listaTipoBordado = [];
    public $listaCodigoModeloBordado = [];
    public $listaTallaBordado = [];
    public $listaColorBordado = [];
    public $listaPersonaBordado = [];
    public $idTipoBordado;
    public $tipoBordado;
    public $idCodigoModeloBordado;
    public $codigoModeloBordado;
    public $idTallaBordado;
    public $tallaBordado;
    public $idColorBordado;
    public $colorBordado;
    public $idPersonaBordado;
    public $personaBordado;
    public $cantidadPrendaBordado;
    public $listaBordado = [];

    //Variable para ordenar la lista de facturas
    public $sortBy = 'FK_Pedido';
    public $sortAsc = true;

    public $fecha = "";

    public $confirmingPostPedidoAdd = false;
    public $confirmingRecepcionDetalle = false;
    public $confirmingDetalleEdit = false;
    public $confirmingMostrarRecepcion = false;
    public $confirmingBordado = false;
    public $confirmingPrendaPersona = false;

    public $idRecepcionCabecera;
    public $idRecepcionDetalle;

    public $seleccionados;

    public $estadoBotonGuardarCabeceraRecepcion = 0;

    protected $queryString = [
        'search',
        'sortBy' => ['except' => 'PedidoId'],
        'sortAsc' => ['except' => true],
    ];

    public function mount() {
        $this->fecha =  date('Y-m-d');
    }

    protected $rules = [
        'pedidoAsociado'                                => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], // Pedido externo asociado
        'numeroFactura'                                 => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], // Numero factura pedido externo
        'observacionfactura'                            => ['string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idPedidoAsociado'                              => ['numeric', 'exists:man_pedidos_externos,PedidoExternoId'],

        'cantidadRecibida'                              => ['required','numeric'],

        'pedidoAsociadoBordado'                         => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], // Pedido externo asociado
        'idPedidoAsociadoBordado'                       => ['numeric', 'exists:recepcion_detalles,RecepcionDetalleId'],

        'idTipoBordado'                                 => ['numeric', 'exists:man_tipo_prendas,TipoPrendaId'],
        'tipoBordado'                                   => ['required','string', 'exists:man_tipo_prendas,ManNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idTallaBordado'                                => ['numeric', 'exists:man_talla_prendas,TallaPrendaId'],
        'tallaBordado'                                  => ['required','string', 'exists:man_talla_prendas,ManNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idCodigoModeloBordado'                         => ['numeric', 'exists:man_modelos,ModeloId'],
        'codigoModeloBordado'                           => ['required','string', 'exists:man_modelos,ModCodigo', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idColorBordado'                                => ['numeric', 'exists:man_colors,ColorId'],
        'colorBordado'                                  => ['required','string', 'exists:man_colors,ColNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idPersonaBordado'                              => ['numeric', 'exists:man_colors,ColorId'],        
        'personaBordado'                                => ['required','string', 'exists:man_modelos,ModCodigo', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],


    ];

    protected $messages = [
        'pedidoAsociado.required'                       =>  'Debe seleccionar un pedido asociado',
        'pedidoAsociado.string'                         =>  'El campo debe ser alfa numérico',
        'pedidoAsociado.regex'                          =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'numeroFactura.required'                        => 'Debe ingresar un número de factura.',
        'numeroFactura.string'                          => 'El número de factura debe ser alfa numérico.',
        'numeroFactura.regex'                           => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'observacionfactura.string'                     => 'La observación debe ser alfa numérico.',
        'observacionfactura.regex'                      => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'idPedidoAsociado.numeric'                      => 'El id de pedido asociado debe ser numérico.',
        'idPedidoAsociado.exists'                       => 'El id no existe en el pedido',

        'cantidadRecibida.numeric'                      => 'La cantidad debe ser numérica.',
        'cantidadRecibida.required'                     => 'La cantidad debe ser numérica.',

        //BORDADO
        'pedidoAsociadoBordado.required'                =>  'Debe seleccionar un pedido asociado',
        'pedidoAsociadoBordado.string'                  =>  'El campo debe ser alfa numérico',
        'pedidoAsociadoBordado.regex'                   =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'idPedidoAsociadoBordado.numeric'               => 'El id de pedido asociado debe ser numérico.',
        'idPedidoAsociadoBordado.exists'                => 'El id no existe en el pedido',



        'tipoBordado.required'                           => 'Debe seleccionar un tipo de prenda.',
        'tipoBordado.string'                             => 'El tipo de prenda asociado al pedido debe ser alfa numérico.',
        'tipoBordado.regex'                              => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'tipoBordado.exists'                             => 'El nombre no existe en el tipo de prenda',

        'idTipoBordado.numeric'                          => 'El id del tipo de prenda debe ser numérico.',
        'idTipoBordado.exists'                           => 'El id no existe en el tipo de prenda',

        'tallaBordado.required'                          => 'Debe seleccionar una talla.',
        'tallaBordado.string'                            => 'La talla de la prenda asociado al pedido debe ser alfa numérico.',
        'tallaBordado.regex'                             => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'tallaBordado.exists'                            => 'El nombre no existe en la talla de prenda',

        'idTallaBordado.numeric'                         => 'El id de la talla de prenda debe ser numérico.',
        'idTallaBordado.exists'                          => 'El id no existe en la talla de prenda',

        'codigoModeloBordado.required'                   => 'Debe seleccionar un codigo.',
        'codigoModeloBordado.string'                     => 'El código del modelo asociado al pedido debe ser alfa numérico.',
        'codigoModeloBordado.regex'                      => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'codigoModeloBordado.exists'                     => 'El nombre no existe en el modelo',

        'idCodigoModeloBordado.numeric'                  => 'El id del modelo debe ser numérico.',
        'idCodigoModeloBordado.exists'                   => 'El id no existe en el modelo',

        'colorBordado.required'                          => 'Debe seleccionar un color.',
        'colorBordado.string'                            => 'El color del tipo de prenda asociado al pedido debe ser alfa numérico.',
        'colorBordado.regex'                             => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'colorBordado.exists'                            => 'El nombre no existe en el color del tipo de prenda',


    ];
   
    public function sortBy($field) {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }

    public function seleccionados($seleccionados_form) {
        $this->seleccionados = $seleccionados_form;
    }

    public function refrescar(){
        return redirect()->route('postPedido');
    }

    public function updatedPedidoAsociado($pedidoAsociado_form) {
        $this->validateOnly("pedidoAsociado");
        $this->reset('listaSumatoria');
        $pedidoAsociado = ManPedidosExterno::where('NumPedidoExterno', '=', $pedidoAsociado_form)->first();
        if ($pedidoAsociado) {
            $pedido = $pedidoAsociado->NumPedidoExterno;
            $this->fechaPedidoExterno = $pedidoAsociado->created_at->format('Y-m-d');  
            $cabecera = RecepcionCabecera::where('NumeroDocumentoExterno', '=', $pedido)
                                        ->orderBy('RecepcionCabeceraId','desc')
                                        ->first();
            if ($cabecera == null) {
            } else {
                $cabecera = $cabecera->RecepcionCabeceraId;
                $this->idRecepcionCabecera = $cabecera;
                $detalle = RecepcionDetalle::where('FK_RecepcionCabecera', '=', $this->idRecepcionCabecera)->get();
                //dd($detalle);
                $count = 0;
                foreach ($detalle as $item) {
                    if ($item->Estado != 'RECEPCION_FINALIZADA') {
                        $count ++;
                    }
                }
                if ($count == 0) {
                    //dd("hola");
                    $this->reset([
                        'pedidoAsociado', 'numeroFactura', 'observacionfactura', 'listaSumatoria', 'fechaPedidoExterno', 
                    ]); 
                    $errorCode = 'El pedido número "' .$pedidoAsociado->NumPedidoExterno. '" ya recibió todas las facturas correspondientes';
                    $this->dispatchBrowserEvent('abrirMsjeFallido10', ['error' => $errorCode]);
                } else {
                    $this->reset(['idPedidoAsociado', 'pedidoAsociado']);
                    $this->idPedidoAsociado = $pedidoAsociado->PedidoExternoId;
                    $this->pedidoAsociado = $pedidoAsociado->NumPedidoExterno;
                    $this->fechaPedidoExterno = $pedidoAsociado->created_at->format('Y-m-d');                  
                }
            }    
        } else {
            $this->reset(['idPedidoAsociado']);
        }        
    }

    public function updatedPedidoAsociadoBordado($pedidoAsociadoBordado_form) {
        $this->validateOnly("pedidoAsociadoBordado");
        $this->reset('listaSumatoriaBordado');
        $this->reset('listaTipoBordado');
        $this->reset('listaCodigoModeloBordado');
        $this->reset('listaTallaBordado');
        $this->reset('listaColorBordado');
        $this->reset('listaPersonaBordado');
        $this->reset('tipoBordado');
        $this->reset('listaBordado');
        
        $pedidoAsociadoBordado = RecepcionDetalle::where('FK_DocumentoExterno', '=', $pedidoAsociadoBordado_form)
                                                    ->groupBy('FK_DocumentoExterno')->first();    
        if ($pedidoAsociadoBordado) {
            $pedido = $pedidoAsociadoBordado->FK_DocumentoExterno;
            $fecha = ManPedidosExterno::where('NumPedidoExterno', '=', $pedidoAsociadoBordado_form)->first();
            $this->fechaPedidoExternoBordado = $fecha->created_at->format('Y-m-d');

            $detalle = RecepcionDetalle::select('recepcion_detalles.*', DB::raw('sum(CantidadRecibida) as suma'), 'prenda_personas.*', DB::raw('sum(CantidadPersona) as suma2'))
                                        ->leftjoin('prenda_personas', 'recepcion_detalles.RecepcionDetalleId', '=', 'prenda_personas.FK_RecepcionDetalle')
                                        ->where('FK_DocumentoExterno', '=', $pedidoAsociadoBordado_form)
                                        ->groupBy('CodigoModelo', 'Talla', 'TipoPrenda', 'Color')
                                        ->get();
                                        //dd($detalle);
            $detalleTipo = RecepcionDetalle::select('TipoPrenda')
                                        ->where('FK_DocumentoExterno', '=', $pedidoAsociadoBordado_form)
                                        ->groupBy('TipoPrenda')
                                        ->get();
            
            foreach ($detalle as $item) {
                $a = array();
                $a['id1'] = "";
                $a['id'] = $item->RecepcionDetalleId;
                $a['TallajeTalla'] = $item->Talla;
                $a['ModCodigo'] = $item->CodigoModelo;
                $a['TipoPrenda'] = $item->TipoPrenda;
                $a['ColorPrenda'] = $item->Color;
                $a['suma'] = $item->suma - $item->suma2;

            $this->listaSumatoriaBordado[] = $a;
            }
            //dd($detalle);
            foreach ($detalleTipo as $item) {
                $a = array();
                $a['id1'] = "";
                $a['tipo'] = $item->TipoPrenda;

            $this->listaTipoBordado[] = $a;                
            }
        }                                                    
    }    

    public function updatedTipoBordado($tipoBordado_form) {
        $this->validateOnly("tipoBordado");
        $this->reset('listaCodigoModeloBordado');
        $this->reset('listaTallaBordado');
        $this->reset('listaColorBordado');
        $this->reset('listaPersonaBordado');
        $this->reset('codigoModeloBordado');
        $this->reset('tallaBordado');
        $this->reset('colorBordado');
        $this->reset('personaBordado');
        $this->reset('cantidadPrendaBordado');

        $this->tipoBordado = $tipoBordado_form;

        $detalleCodigoModelo = RecepcionDetalle::select('CodigoModelo')
                                        ->where('TipoPrenda', '=', $tipoBordado_form)
                                        ->where('FK_DocumentoExterno', '=', $this->pedidoAsociadoBordado)
                                        ->groupBy('CodigoModelo')
                                        ->get();

        foreach ($detalleCodigoModelo as $item) {
            $a = array();
            $a['id1'] = "";
            $a['codigoModelo'] = $item->CodigoModelo;

        $this->listaCodigoModeloBordado[] = $a;                
        }
    }

    public function updatedCodigoModeloBordado($codigoModeloBordado_form) {
        $this->validateOnly("codigoModeloBordado");
        $this->reset('listaTallaBordado');
        $this->reset('listaColorBordado'); 
        $this->reset('listaPersonaBordado');
        $this->reset('tallaBordado');
        $this->reset('colorBordado');
        $this->reset('personaBordado');

        $detalleMod = RecepcionDetalle::select('CodigoModelo')
                                        ->where('TipoPrenda', '=', $this->tipoBordado)
                                        ->where('CodigoModelo', '=', $codigoModeloBordado_form)
                                        ->where('FK_DocumentoExterno', '=', $this->pedidoAsociadoBordado)
                                        ->groupBy('CodigoModelo')
                                        ->first();

        $detalleMod = $detalleMod->CodigoModelo;
        
        $detalleTalla = RecepcionDetalle::select('Talla')
                                        ->where('TipoPrenda', '=', $this->tipoBordado)
                                        ->where('CodigoModelo', '=', $detalleMod)
                                        ->where('FK_DocumentoExterno', '=', $this->pedidoAsociadoBordado)
                                        ->groupBy('Talla')
                                        ->get();

        foreach ($detalleTalla as $item) {
            $a = array();
            $a['id1'] = "";
            $a['talla'] = $item->Talla;

        $this->listaTallaBordado[] = $a;                
        }
    }

    public function updatedTallaBordado($tallaBordado_form) {
        $this->validateOnly("tallaBordado");
        $this->reset('listaColorBordado');
        $this->reset('listaPersonaBordado');
        $this->reset('colorBordado');
        $this->reset('personaBordado');

        $detalleCol = RecepcionDetalle::select('Color')
                                        ->where('TipoPrenda', '=', $this->tipoBordado)
                                        ->where('CodigoModelo', '=', $this->codigoModeloBordado)
                                        ->where('FK_DocumentoExterno', '=', $this->pedidoAsociadoBordado)
                                        ->where('Talla', '=', $tallaBordado_form)
                                        ->groupBy('Color')
                                        ->get();

        //dd($detalleCol);
        foreach ($detalleCol as $item) {
            $a = array();
            $a['id1'] = "";
            $a['color'] = $item->Color;
    
            $this->listaColorBordado[] = $a;                
            }
    }

    public function updatedColorBordado($colorBordado_form) {
        $this->validateOnly("colorBordado");
        $this->reset('listaPersonaBordado');
        $this->reset('personaBordado');
     
        $persona = ManPedidosExterno::select('man_pedidos_externos.*', 'op_pedidos_personas.*', 'man_tallaje_personas.*', 'man_tipo_prendas.*', 'man_modelos.*', 'man_colors.*', 'op_pedidos_modelos.*')
                                    ->leftjoin('op_pedidos_personas', 'man_pedidos_externos.FK_Pedido', '=', 'op_pedidos_personas.FK_pedido')
                                    ->leftjoin('man_tallaje_personas', 'op_pedidos_personas.PedidoPersonaId', '=', 'man_tallaje_personas.FK_PedidoPersona')
                                    ->leftjoin('man_tipo_prendas', 'man_tallaje_personas.FK_TipoPrenda', '=', 'man_tipo_prendas.TipoPrendaId')
                                    ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                                    ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                                    ->leftjoin('man_colors', 'man_tallaje_personas.idColor', '=', 'man_colors.ColorId')
                                    ->where('man_pedidos_externos.NumPedidoExterno', '=', $this->pedidoAsociadoBordado)
                                    ->where('man_tipo_prendas.ManNombre', '=', $this->tipoBordado)
                                    ->where('man_tallaje_personas.TallajeTalla', '=', $this->tallaBordado)
                                    ->where('man_colors.ColNombre', '=', $this->colorBordado)
                                    ->get();

        foreach ($persona as $item) {
            $a = array();
            $a['id1'] = "";
            $a['primerNombre'] = $item->PedPerPrimerNombre;
            $a['segundoNombre'] = $item->PedPerSegundoNombre;
            $a['primerApellido'] = $item->PedPerPrimerApellido;
            $a['segundoApellido'] = $item->PedPerSegundoApellido;
            
            $this->listaPersonaBordado[] = $a;                
            }              
    }

    public function updatedNumeroFactura($numeroFactura_form) {
        $this->validateOnly("numeroFactura");
    }

    public function updatedObservacionfactura($observacionfactura_form) {
        $this->validateOnly("observacionfactura");
    }

    public function updatedCantidadRecibida($cantidadRecibida_form) {
        $this->validateOnly("cantidadRecibida");
        $this->cantidadRecibidaActualizada=1;
    }

    public function changeEstado($estado)
    {
        $this->estado_pick = $estado;
        $this->resetPage();
    }

    //Funciones para crear una Recepcion de Factura (PostPedido)
    public function confirmPostPedidoAdd() {

        $this->tituloPedido = "";
        $this->descripcionPedido = "";
        $this->reset(['pedido','estadoBotonGuardarCabeceraRecepcion']);
        $this->confirmingPostPedidoAdd = true;
    }

    //Funcion para cancelar al entrar al modal Recepcion de Factura
    public function cancelarPostPedidoAdd() {
        $this->reset([
            'pedidoAsociado', 'numeroFactura', 'observacionfactura', 'listaSumatoria', 
        ]);
        $this->confirmingPostPedidoAdd = false; 
    }

    //Funcion para cancelar al entrar al modal Recepcion de Factura
    public function confirmMostrarRecepcion($id) {
        $this->confirmingMostrarRecepcion = true;
        $this->idRecepcionDetalle = $id;

        $detalle = RecepcionDetalle::where('RecepcionDetalleId','=',$this->idRecepcionDetalle)->first();

        $this->listaAnterior['tipoPrenda'] = $detalle->TipoPrenda;
        $this->listaAnterior['modCodigo'] = $detalle->CodigoModelo;
        $this->listaAnterior['tallajeTalla'] = $detalle->Talla;
        $this->listaAnterior['colorPrenda'] = $detalle->Color;

        $this->listaAnterior['cantidadSolicitadaAnterior'] = $detalle->CantidadSolicitadaAnterior;
        $this->listaAnterior['cantidadRecibidaAnterior'] = $detalle->CantidadRecibidaAnterior;
        $this->listaAnterior['cantidadFaltanteAnterior'] = $detalle->CantidadFaltanteAnterior;

    }

    //Funcion para cancelar al entrar al modal Recepcion de Factura
    public function volverRecepcionDetalle() {
        $this->confirmingRecepcionDetalle = false; 
    }

    //Funcion para cancelar al entrar al modal Recepcion de Factura
    public function cancelarDetalleEdit() {
        $this->reset([
            'cantidadRecibida', 
        ]);
        $this->confirmingDetalleEdit = false; 
    }

    //Funcion para cancelar al entrar al modal Recepcion de Factura
    public function cancelarMostrarRecepcion() {
        $this->reset([
            'cantidadRecibida', 
        ]);
        $this->confirmingMostrarRecepcion = false; 
    }

    public function confirmRecepcionDetalle($id) {
        $this->confirmingRecepcionDetalle = true;
        $this->idRecepcionCabecera = $id;
        $fk = RecepcionCabecera::where('RecepcionCabeceraId', '=', $id)->first();
        $this->FK_Pedido = $fk->FK_Pedido;
        $this->FK_RecepcionCabecera = $fk->RecepcionCabeceraId;
        

    }

    public function confirmInsertarCantidad($id) {
        $this->resetErrorBag();
        $this->confirmingDetalleEdit = true;
        $this->idRecepcionDetalle = $id;
    }

    public function almacenarCabecera() {
        
        if ($this->pedidoAsociado == null) {
            $errorCode = 'Debe seleccionar un pedido antes de continuar';
            $this->dispatchBrowserEvent('abrirMsjeFallido10', ['error' => $errorCode]);
        } else {

        $this->reset('listaSumatoria','cantidadRecibidaActualizada');
        $this->validateOnly('pedidoAsociado');
        $this->validateOnly('numeroFactura'); 
        if($this->observacionfactura){
            $this->validateOnly('observacionfactura');
        }              

        $fk_pedido = ManPedidosExterno::select('FK_Pedido')
                                        ->where('NumPedidoExterno', '=', $this->pedidoAsociado)
                                        ->first();
        $this->FK_Pedido = $fk_pedido->FK_Pedido;                      

        $recepcionDetalle = RecepcionDetalle::where('FK_Pedido', '=',$this->FK_Pedido)->first();
        
        if ($recepcionDetalle == null) {
            $listado = ManTallajePersona::select(['man_tallaje_personas.*',DB::raw('count(*) as total'), 'op_pedidos_personas.*', 'man_modelos.*', 'man_pedidos_externos.*', 'op_pedidos_modelos.*',DB::raw('sum(cantidadPrenda) as suma')] )
                        ->leftjoin('op_pedidos_personas', 'man_tallaje_personas.FK_PedidoPersona', '=', 'op_pedidos_personas.PedidoPersonaId')
                        ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                        ->leftjoin('man_pedidos_externos', 'op_pedidos_personas.FK_pedido', '=', 'man_pedidos_externos.FK_Pedido')
                        
                        ->where('NumPedidoExterno', '=', $this->pedidoAsociado)
                        ->groupBy('ModCodigo', 'ModNombre', 'TallajeTalla', 'FK_TipoPrenda', 'idColor')
                        ->get();

            $this->reset('listaSumatoria');
            foreach ($listado as $item) {                                    
            $tipoPrenda = ManTipoPrenda::where('TipoPrendaId', '=', $item->FK_TipoPrenda)->first();    
            $colorPrenda = ManColor::where('ColorId', '=', $item->idColor)->first();    

            $a = array();
            $a['id1'] = "null";
            $a['id'] = $item->TallajePersonaId;
            $a['TallajeTalla'] = $item->TallajeTalla;
            $a['ModCodigo'] = $item->ModCodigo;
            $a['TipoPrenda'] = $tipoPrenda->ManNombre;
            $a['ColorPrenda'] = $colorPrenda->ColNombre;
            $a['sumatoria'] = $item->suma;
            $a['cantidadRecibida'] = 0;
            $a['cantidadFaltante'] = $item->suma - $a['cantidadRecibida'];

            $a['cantidadSolicitadaAnterior'] = $item->suma; 
            $a['cantidadRecibidaAnterior'] = 0;
            $a['cantidadFaltanteAnterior'] = $item->suma - $a['cantidadRecibida'];

            $this->listaSumatoria[] = $a;
            }     
        } else {
            $this->reset('listaSumatoria');
            $recepcion = RecepcionDetalle::where('FK_Pedido', '=', $this->FK_Pedido)
                                        ->orderBy('RecepcionDetalleId','desc')
                                        ->first();
            $this->FK_RecepcionCabecera = $recepcion->FK_RecepcionCabecera;
            $recepcion = RecepcionDetalle::where('FK_RecepcionCabecera', '=', $this->FK_RecepcionCabecera)->get();
            //dd($recepcion1);
            foreach ($recepcion as $item) {                                    
                $tipoPrenda = ManTipoPrenda::where('TipoPrendaId', '=', $item->FK_TipoPrenda)->first();    
                $colorPrenda = ManColor::where('ColorId', '=', $item->idColor)->first();    

                $a = array();
                $a['id1'] = "";
                $a['id'] = $item->RecepcionDetalleId;
                $a['TallajeTalla'] = $item->Talla;
                $a['ModCodigo'] = $item->CodigoModelo;
                $a['TipoPrenda'] = $item->TipoPrenda;
                $a['ColorPrenda'] = $item->Color;
                $a['sumatoria'] = $item->CantidadFaltante;
                $a['cantidadRecibida'] = 0;
                $a['cantidadFaltante'] = $item->CantidadFaltante - $a['cantidadRecibida'];

                $a['cantidadSolicitadaAnterior'] = $item->CantidadSolicitadaAnterior; 
                $a['cantidadRecibidaAnterior'] = $item->CantidadRecibidaAnterior;
                $a['cantidadFaltanteAnterior'] = $item->CantidadFaltanteAnterior;

            $this->listaSumatoria[] = $a;
                }   
                
            }
            $this->estadoBotonGuardarCabeceraRecepcion = 1;

        }
    }

    public function submitBordado() {
        dd($this);
    }

    public function submitRecepcion() {    

        if ($this->cantidadRecibidaActualizada == 0) {
            $errorCode = 'Debe agregar la cantidad recibida del o los productos para guardar';
            $this->dispatchBrowserEvent('abrirMsjeFallido10', ['error' => $errorCode]);
        } else {               
    
        $this->validateOnly('pedidoAsociado');
        $this->validateOnly('numeroFactura'); 
        if($this->observacionfactura){
            $this->validateOnly('observacionfactura');
        }
        
        RecepcionCabecera::create([
            'FK_Pedido'                         => $this->FK_Pedido,
            'FechaRecepcion'                    => $this->todayDate,
            'NumeroDocumentoExterno'            => $this->pedidoAsociado,
            'NumeroFactura'                     => $this->numeroFactura,
            'FechaDocumentoExterno'             => $this->fechaPedidoExterno,
            'Estado'                            => "BORRADOR",
            'Observacion'                       => $this->observacionfactura,
        ]);

        $recepcionDetalle = RecepcionDetalle::where('FK_Pedido', '=',$this->FK_Pedido)->first();
        
        if ($recepcionDetalle == null) {
            $FK_RecepcionCabecera = RecepcionCabecera::select('recepcion_cabeceras.*')->orderBy('RecepcionCabeceraId', 'desc')->first();
            $FK = $FK_RecepcionCabecera->RecepcionCabeceraId;
            foreach ($this->listaSumatoria as $item) {
                if ($item['sumatoria'] == $item['cantidadFaltante'] && $item['sumatoria'] > 0) {
                    RecepcionDetalle::create([
                        'FK_RecepcionCabecera'          => $FK,
                        'FK_Pedido'                     => $this->FK_Pedido,
                        'TipoPrenda'                    => $item['TipoPrenda'],
                        'CodigoModelo'                  => $item['ModCodigo'],
                        'Talla'                         => $item['TallajeTalla'],
                        'Color'                         => $item['ColorPrenda'],
                        'CantidadSolicitada'            => $item['sumatoria'],
                        'CantidadRecibida'              => $item['cantidadRecibida'],
                        'CantidadFaltante'              => $item['cantidadFaltante'],
                        'CantidadSolicitadaAnterior'    => $item['cantidadSolicitadaAnterior'],
                        'CantidadRecibidaAnterior'      => $item['cantidadRecibidaAnterior'],
                        'CantidadFaltanteAnterior'      => $item['cantidadFaltanteAnterior'],
                        'Estado'                        => "SIN RECEPCION",
                        'FK_DocumentoExterno'           => $this->pedidoAsociado,
                    ]);
                } else {
                    if ($item['sumatoria'] == $item['cantidadRecibida'] && $item['cantidadFaltante'] == 0) {
                        RecepcionDetalle::create([
                            'FK_RecepcionCabecera'          => $FK,
                            'FK_Pedido'                     => $this->FK_Pedido,
                            'TipoPrenda'                    => $item['TipoPrenda'],
                            'CodigoModelo'                  => $item['ModCodigo'],
                            'Talla'                         => $item['TallajeTalla'],
                            'Color'                         => $item['ColorPrenda'],
                            'CantidadSolicitada'            => $item['sumatoria'],
                            'CantidadRecibida'              => $item['cantidadRecibida'],
                            'CantidadFaltante'              => $item['cantidadFaltante'],
                            'CantidadSolicitadaAnterior'    => $item['cantidadSolicitadaAnterior'],
                            'CantidadRecibidaAnterior'      => $item['cantidadRecibidaAnterior'],
                            'CantidadFaltanteAnterior'      => $item['cantidadFaltanteAnterior'],
                            'Estado'                        => "RECEPCION_FINALIZADA",
                            'FK_DocumentoExterno'           => $this->pedidoAsociado,
                        ]);
                    } else {
                        if ($item['sumatoria'] != $item['cantidadFaltante'] ) {
                            RecepcionDetalle::create([
                                'FK_RecepcionCabecera'          => $FK,
                                'FK_Pedido'                     => $this->FK_Pedido,
                                'TipoPrenda'                    => $item['TipoPrenda'],
                                'CodigoModelo'                  => $item['ModCodigo'],
                                'Talla'                         => $item['TallajeTalla'],
                                'Color'                         => $item['ColorPrenda'],
                                'CantidadSolicitada'            => $item['sumatoria'],
                                'CantidadRecibida'              => $item['cantidadRecibida'],
                                'CantidadFaltante'              => $item['cantidadFaltante'],
                                'CantidadSolicitadaAnterior'    => $item['cantidadSolicitadaAnterior'],
                                'CantidadRecibidaAnterior'      => $item['cantidadRecibidaAnterior'],
                                'CantidadFaltanteAnterior'      => $item['cantidadFaltanteAnterior'],
                                'Estado'                        => "RECEPCION_PARCIAL",
                                'FK_DocumentoExterno'           => $this->pedidoAsociado,
                            ]);
                        }
                    }
                }    
            }

        } else {
            $FK_RecepcionCabecera = RecepcionCabecera::select('recepcion_cabeceras.*')->orderBy('RecepcionCabeceraId', 'desc')->first();
            $FK = $FK_RecepcionCabecera->RecepcionCabeceraId;

            foreach ($this->listaSumatoria as $item) {
                if ($item['sumatoria'] == $item['cantidadFaltante'] && $item['sumatoria'] > 0) {
                    RecepcionDetalle::create([
                        'FK_RecepcionCabecera'          => $FK,
                        'FK_Pedido'                     => $this->FK_Pedido,
                        'TipoPrenda'                    => $item['TipoPrenda'],
                        'CodigoModelo'                  => $item['ModCodigo'],
                        'Talla'                         => $item['TallajeTalla'],
                        'Color'                         => $item['ColorPrenda'],
                        'CantidadSolicitada'            => $item['sumatoria'],
                        'CantidadRecibida'              => $item['cantidadRecibida'],
                        'CantidadFaltante'              => $item['cantidadFaltante'],
                        'CantidadSolicitadaAnterior'    => $item['cantidadSolicitadaAnterior'],
                        'CantidadRecibidaAnterior'      => $item['cantidadRecibidaAnterior'],
                        'CantidadFaltanteAnterior'      => $item['cantidadFaltanteAnterior'],
                        'Estado'                        => "SIN RECEPCION",
                        'FK_DocumentoExterno'           => $this->pedidoAsociado,
                    ]);
                } else {
                    if ($item['sumatoria'] == $item['cantidadRecibida'] && $item['cantidadFaltante'] == 0) {
                        RecepcionDetalle::create([
                            'FK_RecepcionCabecera'          => $FK,
                            'FK_Pedido'                     => $this->FK_Pedido,
                            'TipoPrenda'                    => $item['TipoPrenda'],
                            'CodigoModelo'                  => $item['ModCodigo'],
                            'Talla'                         => $item['TallajeTalla'],
                            'Color'                         => $item['ColorPrenda'],
                            'CantidadSolicitada'            => $item['sumatoria'],
                            'CantidadRecibida'              => $item['cantidadRecibida'],
                            'CantidadFaltante'              => $item['cantidadFaltante'],
                            'CantidadSolicitadaAnterior'    => $item['cantidadSolicitadaAnterior'],
                            'CantidadRecibidaAnterior'      => $item['cantidadRecibidaAnterior'],
                            'CantidadFaltanteAnterior'      => $item['cantidadFaltanteAnterior'],
                            'Estado'                        => "RECEPCION_FINALIZADA",
                            'FK_DocumentoExterno'           => $this->pedidoAsociado,
                        ]);   
                    } else {
                        if ($item['sumatoria'] != $item['cantidadFaltante'] ) {
                            RecepcionDetalle::create([
                                'FK_RecepcionCabecera'          => $FK,
                                'FK_Pedido'                     => $this->FK_Pedido,
                                'TipoPrenda'                    => $item['TipoPrenda'],
                                'CodigoModelo'                  => $item['ModCodigo'],
                                'Talla'                         => $item['TallajeTalla'],
                                'Color'                         => $item['ColorPrenda'],
                                'CantidadSolicitada'            => $item['sumatoria'],
                                'CantidadRecibida'              => $item['cantidadRecibida'],
                                'CantidadFaltante'              => $item['cantidadFaltante'],
                                'CantidadSolicitadaAnterior'    => $item['cantidadSolicitadaAnterior'],
                                'CantidadRecibidaAnterior'      => $item['cantidadRecibidaAnterior'],
                                'CantidadFaltanteAnterior'      => $item['cantidadFaltanteAnterior'],
                                'Estado'                        => "RECEPCION_PARCIAL",
                                'FK_DocumentoExterno'           => $this->pedidoAsociado,
                            ]);
                        }                       
                    }
                }
            }
        }

        $FK_RecepcionCabecera = RecepcionCabecera::select('recepcion_cabeceras.*')->orderBy('RecepcionCabeceraId', 'desc')->first();
        $this->FK_RecepcionCabecera = $FK_RecepcionCabecera->RecepcionCabeceraId;
        $estado = RecepcionDetalle::where('FK_RecepcionCabecera', '=', $this->FK_RecepcionCabecera)->get();
        $count = 0;
        foreach ($estado as $item) {
            if ($item->Estado != 'RECEPCION_FINALIZADA') {
                $count ++;
            }
        }
        //dd($count);
        if ($count > 0) {
            RecepcionCabecera::where('RecepcionCabeceraId', '=', $this->FK_RecepcionCabecera)->update(['Estado' => 'RECEPCION_PARCIAL']);
        } else {
            $cabecera = RecepcionCabecera::where('FK_Pedido', '=', $this->FK_Pedido)->get();
            foreach ($cabecera as $item) {
                RecepcionCabecera::where('FK_Pedido', '=', $this->FK_Pedido)->update(['Estado' => 'RECEPCION_FINALIZADA']);
            }
        }
        
        $this->reset([
            'pedidoAsociado', 'numeroFactura', 'observacionfactura', 'listaSumatoria',
        ]);
        $this->confirmingPostPedidoAdd = false;
        }
    }

    public function detalleEdit() {
        if(($this->listaSumatoria[$this->idRecepcionDetalle]['sumatoria'] - $this->cantidadRecibida)<0){
            $errorCode = 'La cantidad recibida no puede ser mayor que la cantidad solicitada';
            $this->dispatchBrowserEvent('abrirMsjeFallido11', ['error' => $errorCode]);
        }else{
            $this->validateOnly('cantidadRecibida');
            $this->listaSumatoria[$this->idRecepcionDetalle]['cantidadRecibida'] = $this->cantidadRecibida;
            $this->listaSumatoria[$this->idRecepcionDetalle]['cantidadFaltante'] = $this->listaSumatoria[$this->idRecepcionDetalle]['sumatoria'] - $this->cantidadRecibida;

            $this->listaSumatoria[$this->idRecepcionDetalle]['cantidadSolicitadaAnterior'] = $this->listaSumatoria[$this->idRecepcionDetalle]['sumatoria'];
            $this->listaSumatoria[$this->idRecepcionDetalle]['cantidadRecibidaAnterior'] = $this->cantidadRecibida;
            $this->listaSumatoria[$this->idRecepcionDetalle]['cantidadFaltanteAnterior'] = $this->listaSumatoria[$this->idRecepcionDetalle]['sumatoria'] - $this->cantidadRecibida;

            $this->reset([
                'cantidadRecibida',
            ]);
            $this->confirmingDetalleEdit = false;
        }
    }

    public function confirmBordadoAdd() {
        $this->confirmingBordado = true;
    }
        
    //Funcion para cancelar al entrar al modal Bordado
    public function cancelarBordado() {
        $this->confirmingBordado = false; 
    }

    //Funcion para agregar al arreglo bordado
    public function agregarPrendaBordado(){
        $key = array_search($this->tipoBordado, array_column($this->listaSumatoriaBordado, 'TipoPrenda')); 
        $filtered_array = array_filter($this->listaSumatoriaBordado, function($val){
            return ($val['TipoPrenda']==$this->tipoBordado and $val['ModCodigo']==$this->codigoModeloBordado 
                                        and $val['TallajeTalla']==$this->tallaBordado  and $val['ColorPrenda']==$this->colorBordado);
                                        });
        foreach ( $filtered_array as $key => $item) {
            $id = $item['id'];
        }

                
        $this->listaSumatoriaBordado[$key]['suma'] = $this->listaSumatoriaBordado[$key]['suma'] - 1;     
            
            
            array_push($this->listaBordado,[
                'id'                        => $id,
                'tipoBordado'               => $this->tipoBordado, 
                'codigoModeloBordado'       => $this->codigoModeloBordado,
                'tallaBordado'              => $this->tallaBordado,
                'colorBordado'              => $this->colorBordado, 
                'personaBordado'            => $this->personaBordado,
                'cantidadPrendaBordado'     => $this->cantidadPrendaBordado,
            ]);
            
            $this->reset('id', 'tipoBordado','codigoModeloBordado','tallaBordado','colorBordado','personaBordado','cantidadPrendaBordado');        
        
    }

    public function quitarPrendaBordado($key){
            unset($this->listaBordado[$key]);
    }

    public function enviarBordado() {
        //dd($this);
        if (count($this->listaBordado) > 0) {
            foreach ($this->listaBordado as $item) {
                PrendaPersona::create([
                    'TipoPrendaPersona'                        => $item['tipoBordado'],
                    'CodigoModeloPersona'                      => $item['codigoModeloBordado'],
                    'TallaPersona'                             => $item['tallaBordado'],
                    'ColorPersona'                             => $item['colorBordado'],
                    'PersonaAsociada'                          => $item['personaBordado'],
                    'CantidadPersona'                          => $item['cantidadPrendaBordado'],
                    'FK_RecepcionDetalle'                      => $item['id'],
                    'EstadoPersona'                            => "EN BORDADO",
                    'FK_DocumentoExterno1'                     => $this->pedidoAsociadoBordado,

                    ]);           
            }
            $this->reset([
                'pedidoAsociadoBordado', 'listaBordado', 'listaSumatoriaBordado',
            ]);
            $this->confirmingBordado = false;
        }
    }

    public function confirmPrendaPersona ($id) {
        $this->idPedidoAsociadoBordado = $id;
        $this->confirmingPrendaPersona = true;
    }

    public function cancelarPrendaPersona () {
        $this->confirmingPrendaPersona = false;
    }

    public function render()
    {
        $this->todayDate = Carbon::now()->format('Y-m-d');
        $this->fechaBordado = Carbon::now()->format('Y-m-d');

        $facturas = RecepcionCabecera::select('recepcion_cabeceras.*')
                    ->orderBy($this->sortBy, $this->sortAsc ?  'ASC' : 'DESC');

        $fecha = Carbon::createFromFormat('Y-m-d', $this->fecha);    
        $fechaFinal = $fecha->subMonths(3);
        $fechaAux = date('m-d-Y');

        if ($this->fecha !== "") {
            $facturas->whereDate('FechaRecepcion', '>=', $fechaFinal);
            $facturas->whereDate('FechaRecepcion', '<=', $this->fecha);
        } else {
            $facturas->whereDate('FechaRecepcion', 'LIKE', "%{$fechaAux}%");
        }

        if ($this->estado_pick != "") {
            $facturas->where('Estado', '=', $this->estado_pick);
        }

        if ($this->search != "") {
            $facturas->where(function ($query) {
                $query->where('NumeroDocumentoExterno', 'like', '%' . $this->search . '%')
                    ->orWhere('Observacion', 'like', '%' . $this->search . '%')
                    ->orWhere('Estado', 'like', '%' . $this->search . '%');
            });
        }

        $this->reset(['recepcion_parcial', 'recepcion_finalizada', 'en_bordado', 'recibe_de_bordado', 'entregado']);
        $estados = $facturas->get()->countBy('Estado');
        foreach ($estados as $estado => $total) {
            switch ($estado) {
                case "RECEPCION_PARCIAL":
                    $this->recepcion_parcial = $total;
                    break;
                case "RECEPCION_FINALIZADA":
                    $this->recepcion_finalizada = $total;
                    break;
                case "EN BORDADO":
                    $this->en_bordado = $total;
                    break;
                case "RECIBE BORDADO":
                    $this->recibe_de_bordado = $total;
                    break;
                case "ENTREGADO":
                    $this->entregado = $total;
                    break;
            }
        }

        $facturas = $facturas->paginate(10);
        $recep = RecepcionCabecera::all();
        if ($recep->isEmpty()) {
            $pedidosAsociados = ManPedidosExterno::all();

        } else {
            $pedidosAsociados = ManPedidosExterno::select('man_pedidos_externos.*', 'recepcion_cabeceras.*')
                                                 ->leftjoin('recepcion_cabeceras', 'man_pedidos_externos.NumPedidoExterno', '=', 'recepcion_cabeceras.NumeroDocumentoExterno')
                                                 ->where('recepcion_cabeceras.Estado', '!=', 'RECEPCION_FINALIZADA')
                                                 ->get();
        }
                                            
        $pedidosAsociadosBordado = RecepcionDetalle::groupBy('FK_DocumentoExterno')->get();                                   
        $recepcionDetalle = RecepcionDetalle::where('FK_Pedido', '=', $this->FK_Pedido)
                                            ->orderBy('RecepcionDetalleId','desc')
                                            ->first();

        $recepcionDetalle = RecepcionDetalle::where('FK_RecepcionCabecera', '=', $this->FK_RecepcionCabecera)->get();

        $prendaPersona = PrendaPersona::where('FK_DocumentoExterno1', '=', $this->idPedidoAsociadoBordado)->get();
        

        return view('livewire.post-pedido', [
            'facturas'                      => $facturas,
            'pedidosAsociados'              => $pedidosAsociados,
            'pedidosAsociadosBordado'       => $pedidosAsociadosBordado,
            'listaSumatoria'                => $this->listaSumatoria,
            'listaSumatoriaBordado'         => $this->listaSumatoriaBordado,
            'listaAnterior'                 => $this->listaAnterior,
            'listaTipoBordado'              => $this->listaTipoBordado,
            'listaCodigoModeloBordado'      => $this->listaCodigoModeloBordado,
            'listaTallaBordado'             => $this->listaTallaBordado,
            'listaColorBordado'             => $this->listaColorBordado,
            'listaPersonaBordado'           => $this->listaPersonaBordado,
            'listaBordado'                  => $this->listaBordado,
            'recepcionDetalle'              => $recepcionDetalle,
            'prendaPersona'                 => $prendaPersona,

        ]);
    }
}
