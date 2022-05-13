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
    public $search1;
    
    //Variables estados postPedido
    public $estado_pick = "";
    public $estado_pick1 = "";
    public $recepcion_parcial = 0;
    public $recepcion_parcial1 = 0;
    public $recepcion_finalizada = 0;
    public $recepcion_finalizada1 = 0;
    public $en_bordado = 0;
    public $en_bordado1 = 0;
    public $recibe_de_bordado = 0;
    public $recibe_de_bordado1 = 0;
    public $entregado = 0;
    public $entregado1 = 0;
    

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
    public $listaPedidos = [];
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
    public $idPrenda;
    public $estadoPrendaPersona;

    //Variable para ordenar la lista de facturas
    public $sortBy = 'FK_Pedido';
    public $sortBy1 = 'FK_Pedido';
    public $sortAsc = true;
    public $sortAsc1 = true;

    public $fecha = "";
    public $fecha1 = "";

    public $confirmingPostPedidoAdd = false;
    public $confirmingRecepcionDetalle = false;
    public $confirmingDetalleEdit = false;
    public $confirmingMostrarRecepcion = false;
    public $confirmingBordado = false;
    public $confirmingPrendaPersona = false;
    public $confirmingPrendaPersonaCliente = false;
    public $confirmingEstadoPrendaPersona = false;

    public $idRecepcionCabecera;
    public $idRecepcionDetalle;

    public $seleccionados;

    public $estadoBotonGuardarCabeceraRecepcion = 0;

    protected $queryString = [
        'search',
        'sortBy' => ['except' => 'PedidoId'],
        'sortAsc' => ['except' => true],
        'search1',
        'sortBy1' => ['except' => 'PedidoId'],
        'sortAsc1' => ['except' => true],
    ];

    public function mount() {
        $this->fecha =  date('Y-m-d');
        $this->fecha1 =  date('Y-m-d');
        $this->fechaBordado =  date('Y-m-d');
    }

    protected $rules = [
        'pedidoAsociado'                                => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], // Pedido externo asociado
        'numeroFactura'                                 => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], // Numero factura pedido externo
        'observacionfactura'                            => ['string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idPedidoAsociado'                              => ['numeric', 'exists:man_pedidos_externos,PedidoExternoId'],

        'cantidadRecibida'                              => ['required','numeric'],

        'pedidoAsociadoBordado'                         => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], // Pedido externo asociado
        'idPedidoAsociadoBordado'                       => ['string', 'exists:recepcion_detalles,RecepcionDetalleId'],

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
        'estadoPrendaPersona'                           => ['required','string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],


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

        'idPedidoAsociadoBordado.string'                => 'El id de pedido asociado debe ser alfa numérico.',
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

        'estadoPrendaPersona.required'                   => 'Debe seleccionar un estado.',
        'estadoPrendaPersona.string'                     => 'El estado de prenda asociado a la persona debe ser alfa numérico.',

    ];
   
    public function sortBy($field) {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }
   
    public function sortBy1($field) {
        if ($field == $this->sortBy1) {
            $this->sortAsc1 = !$this->sortAsc1;
        }
        $this->sortBy1 = $field;
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

            $fk = RecepcionDetalle::where('FK_DocumentoExterno', '=', $this->pedidoAsociadoBordado)->orderBy('RecepcionDetalleId', 'desc')->first();
            $fk = $fk->FK_RecepcionCabecera;

            $det = RecepcionDetalle::where('FK_RecepcionCabecera', '=', $fk)->get();
            $detalle1 = $det->where('TipoPrenda', '=', "Top");

            
            $detalle = PrendaPersona::select('prenda_personas.*', DB::raw('sum(CantidadPersona) as suma2'))
                                        ->where('FK_DocumentoExterno1', '=', $pedidoAsociadoBordado_form)
                                        ->groupBy('CodigoModeloPersona', 'TallaPersona', 'TipoPrendaPersona', 'ColorPersona');

            $detalleJoin = RecepcionDetalle::select('recepcion_detalles.*', DB::raw('sum(CantidadRecibida) as suma'), 'detalle.*')
                                        ->leftJoinSub($detalle, 'detalle', function ($join) {
                                            $join->on('recepcion_detalles.RecepcionDetalleId', '=', 'detalle.FK_RecepcionDetalle');
                                        })
                                        ->where('FK_DocumentoExterno', '=', $pedidoAsociadoBordado_form)
                                        ->groupBy('CodigoModelo', 'Talla', 'TipoPrenda', 'Color')
                                        ->get();
                                        //dd($detalleJoin);
            $detalleTipo = RecepcionDetalle::select('TipoPrenda')
                                        ->where('FK_DocumentoExterno', '=', $pedidoAsociadoBordado_form)
                                        ->groupBy('TipoPrenda')
                                        ->get();
            
            foreach ($detalleJoin as $item) {
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
            $count = 0;
            foreach ($this->listaSumatoriaBordado as $item) {
                if($item['suma'] > 0) {
                    $count++;
                }
            }
            if ($count == 0) {
                $errorCode = 'No existen prendas en stock por el momento';
                $this->dispatchBrowserEvent('abrirMsjeFallido13', ['error' => $errorCode]);
    
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
                                    //dd($persona);

        foreach ($persona as $item) {
            $cantidadPrendas = $item->cantidadPrenda;
            $nombre = $item->PedPerPrimerNombre." ".$item->PedPerSegundoNombre." ".$item->PedPerPrimerApellido." ".$item->PedPerSegundoApellido;
            //dd($nombre);
            $foo = preg_replace('/\s+/', ' ', $nombre);
            $cantidad = PrendaPersona::select('prenda_personas.*', DB::raw('sum(CantidadPersona) as suma'))
                                     ->where('TipoPrendaPersona', '=', $item->ManNombre)
                                     ->where('CodigoModeloPersona', '=', $item->ModCodigo)
                                     ->where('TallaPersona', '=', $item->TallajeTalla)
                                     ->where('ColorPersona', '=', $item->ColNombre)
                                     ->where('PersonaAsociada', '=', $foo)
                                     ->first();

            $cantidad = $cantidad->suma;
            if($cantidadPrendas > $cantidad) {
   
                $a = array();
                $a['id1'] = "";
                $a['primerNombre'] = $item->PedPerPrimerNombre;
                $a['segundoNombre'] = $item->PedPerSegundoNombre;
                $a['primerApellido'] = $item->PedPerPrimerApellido;
                $a['segundoApellido'] = $item->PedPerSegundoApellido;
                
                $this->listaPersonaBordado[] = $a;                
            }              
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

    public function updatedPersonaBordado($personaBordado_form) {
        $this->validateOnly("personaBordado");
    }

    public function updatedEstadoPrendaPersona($estadoPrendaPersona_form) {
        $this->validateOnly("estadoPrendaPersona");
    }

    public function changeEstado($estado)
    {
        $this->estado_pick = $estado;
        $this->resetPage();
    }

    public function changeEstado1($estado)
    {
        $this->estado_pick1 = $estado;
        $this->resetPage();
    }

    //Funcion para refrescar la pagina al buscar
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingSearch1()
    {
        $this->resetPage();
    }

    public function updatingFecha()
    {
        $this->resetPage();
    }
    public function updatingFecha1()
    {
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

        OpPedidos::where('PedidoId', '=', $this->FK_Pedido)->update(['PedEstado' => 'RECEPCION_PARCIAL']);        

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
            OpPedidos::where('PedidoId', '=', $this->FK_Pedido)->update(['PedEstado' => 'RECEPCION_FINALIZADA']);   
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
    public function cancelarAsignacion() {
        $this->confirmingBordado = false; 
        $this->reset([
            'listaSumatoriaBordado', 'pedidoAsociadoBordado', 'listaBordado',
        ]);
    }

    //Funcion para agregar al arreglo bordado
    public function agregarPrendaBordado(){
        $this->validateOnly('tipoBordado');
        $this->validateOnly('codigoModeloBordado');
        $this->validateOnly('tallaBordado');
        $this->validateOnly('colorBordado');
        //$this->validateOnly('personaBordado');
        $filtered_array = array_filter($this->listaSumatoriaBordado, function($val){
            return ($val['TipoPrenda']==$this->tipoBordado and $val['ModCodigo']==$this->codigoModeloBordado 
                                        and $val['TallajeTalla']==$this->tallaBordado  and $val['ColorPrenda']==$this->colorBordado);
                                        });
        foreach ( $filtered_array as $key => $item) {
            $id = $key;
            $idRecepcionDetalle = $item['id'];
        }
        $this->idPrenda = $id;
        $this->idRecepcionDetalle = $idRecepcionDetalle;
        if (($this->listaSumatoriaBordado[$this->idPrenda]['suma'] - $this->cantidadPrendaBordado)<0) {
            $errorCode = 'La cantidad seleccionada no puede ser mayor que la cantidad en stock';
            $this->dispatchBrowserEvent('abrirMsjeFallido12', ['error' => $errorCode]);
        } else {

            $this->listaSumatoriaBordado[$key]['suma'] = $this->listaSumatoriaBordado[$key]['suma'] - $this->cantidadPrendaBordado;                     
            array_push($this->listaBordado,[
                'id'                        => $id,
                'idRecepcionDetalle'        => $this->idRecepcionDetalle,
                'tipoBordado'               => $this->tipoBordado, 
                'codigoModeloBordado'       => $this->codigoModeloBordado,
                'tallaBordado'              => $this->tallaBordado,
                'colorBordado'              => $this->colorBordado, 
                'personaBordado'            => $this->personaBordado,
                'cantidadPrendaBordado'     => $this->cantidadPrendaBordado,
            ]);
        }    
            $this->reset('id', 'tipoBordado','codigoModeloBordado','tallaBordado','colorBordado','personaBordado','cantidadPrendaBordado');
    }

    public function quitarPrendaBordado($key1){
        //dd($this);
        $this->tipoBordado = $this->listaBordado[$key1]['tipoBordado'];
        $this->codigoModeloBordado = $this->listaBordado[$key1]['codigoModeloBordado'];
        $this->tallaBordado = $this->listaBordado[$key1]['tallaBordado'];
        $this->colorBordado = $this->listaBordado[$key1]['colorBordado'];

        $filtered_array = array_filter($this->listaSumatoriaBordado, function($val){
            return ($val['TipoPrenda']==$this->tipoBordado and $val['ModCodigo']==$this->codigoModeloBordado 
                                        and $val['TallajeTalla']==$this->tallaBordado  and $val['ColorPrenda']==$this->colorBordado);
                                        });
                                        //dd($filtered_array);
        foreach ($filtered_array as $key => $item) {
            $id = $key;
        }
        $this->cantidadPrendaBordado = $this->listaBordado[$key1]['cantidadPrendaBordado'];
        $this->listaSumatoriaBordado[$id]['suma'] = $this->listaSumatoriaBordado[$id]['suma'] + $this->cantidadPrendaBordado;                     
        unset($this->listaBordado[$key1]);
        $this->reset('id', 'tipoBordado','codigoModeloBordado','tallaBordado','colorBordado','personaBordado','cantidadPrendaBordado');

    }

    public function enviarAsignacion() {
        //dd($this);
        try {

        if (count($this->listaBordado) > 0) {
            foreach ($this->listaBordado as $item) {
                PrendaPersona::create([
                    'TipoPrendaPersona'                        => $item['tipoBordado'],
                    'CodigoModeloPersona'                      => $item['codigoModeloBordado'],
                    'TallaPersona'                             => $item['tallaBordado'],
                    'ColorPersona'                             => $item['colorBordado'],
                    'PersonaAsociada'                          => $item['personaBordado'],
                    'CantidadPersona'                          => $item['cantidadPrendaBordado'],
                    'FK_RecepcionDetalle'                      => $item['idRecepcionDetalle'],
                    'EstadoPersona'                            => "ASIGNADO",
                    'FK_DocumentoExterno1'                     => $this->pedidoAsociadoBordado,

                    ]);           
            }
            $this->reset([
                'pedidoAsociadoBordado', 'listaBordado', 'listaSumatoriaBordado',
            ]);
            $this->confirmingBordado = false;
        }

    } catch (QueryException $e) {
        //$this->inicializando = false;
        $errorCode = $e->errorInfo[1];
        //$this->emitUp('OtAgregado'); //refrescamos la vista padre, tabla Ot para que aparezca la ot agregada
        $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);
    }
    }

    public function confirmPrendaPersona ($factura) {
        $this->idPedidoAsociadoBordado = $factura['NumeroDocumentoExterno'];
        $this->confirmingPrendaPersona = true;
    }

    public function confirmPrendaPersonaCliente ($factura) {
        $this->idPedidoAsociadoBordado = $factura['NumeroDocumentoExterno'];
        $this->confirmingPrendaPersonaCliente = true;
    }

    public function cancelarPrendaPersona () {
        $this->confirmingPrendaPersona = false;
    }

    public function cancelarPrendaPersonaCliente () {
        $this->confirmingPrendaPersonaCliente = false;
    }

    public function confirmEstadoPrendaPersona ($key) {
        $this->confirmingEstadoPrendaPersona = true;
        $this->idPrenda = $key;
    }

    public function updateEstadoPrendaPersona () {     
        $this->validateOnly('estadoPrendaPersona');
        //dd($this);
        if ($this->estadoPrendaPersona == "ASIGNADO") {
            PrendaPersona::where('prendaPersonaId', '=', $this->idPrenda)->update(['FechaAsignado' => $this->todayDate]);

        } elseif ($this->estadoPrendaPersona == "EN BORDADO") {
            PrendaPersona::where('prendaPersonaId', '=', $this->idPrenda)->update(['FechaBordado' => $this->todayDate]);
           
        } elseif ($this->estadoPrendaPersona == "RECIBE DE BORDADO") {
            PrendaPersona::where('prendaPersonaId', '=', $this->idPrenda)->update(['FechaRecibeBordado' => $this->todayDate]);

        } elseif ($this->estadoPrendaPersona == "ENTREGADO") {
            PrendaPersona::where('prendaPersonaId', '=', $this->idPrenda)->update(['FechaEntregado' => $this->todayDate]);

        }
        PrendaPersona::where('prendaPersonaId', '=', $this->idPrenda)->update(['EstadoPersona' => $this->estadoPrendaPersona]);

        $listado = ManTallajePersona::select(['man_tallaje_personas.*',DB::raw('count(*) as total'), 'op_pedidos_personas.*', 'man_modelos.*', 'op_pedidos_modelos.*', DB::raw('sum(cantidadPrenda) as suma'), 'man_pedidos_externos.*'])
                                    ->leftjoin('op_pedidos_personas', 'man_tallaje_personas.FK_PedidoPersona', '=', 'op_pedidos_personas.PedidoPersonaId')
                                    ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                                    ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                                    ->leftjoin('man_pedidos_externos', 'op_pedidos_personas.FK_pedido', '=', 'man_pedidos_externos.FK_Pedido')
                                    ->where('man_pedidos_externos.NumPedidoExterno', '=', $this->idPedidoAsociadoBordado)
                                    ->get();
        foreach ($listado as $item) {
            $suma = $item['suma'];
        }

        $listado2 = RecepcionDetalle::select('recepcion_detalles.*', DB::raw('sum(CantidadRecibida) as suma'))
                                 ->where('FK_DocumentoExterno', '=', $this->idPedidoAsociadoBordado)
                                 ->get();
        foreach ($listado2 as $item) {
            $suma2 = $item['suma'];
        }
        $fk_pedido = ManPedidosExterno::select('FK_Pedido')
                                      ->where('NumPedidoExterno', '=', $this->idPedidoAsociadoBordado)
                                      ->get();
        foreach ($fk_pedido as $item) {
            $fk_pedido = $item->FK_Pedido;
        }
        if ($suma == $suma2) {
            $listadoBordado = PrendaPersona::select('prenda_personas.*')
                                            ->where('FK_DocumentoExterno1', '=', $this->idPedidoAsociadoBordado)
                                            ->get();

            $count = 0;
            $count2 = 0;
            $count3 = 0;
            foreach ($listadoBordado as $item) {
                if ($item['EstadoPersona'] == 'EN BORDADO') {
                    $count++;
                }
                if ($item['EstadoPersona'] == 'RECIBE DE BORDADO') {
                    $count2++;
                }
                if ($item['EstadoPersona'] == 'ENTREGADO') {
                    $count3++;
                }
            }
            
            if ($count > 0) {
                OpPedidos::where('PedidoId', '=', $fk_pedido)->update(['PedEstado' => 'EN BORDADO']);
                RecepcionCabecera::where('FK_Pedido', '=', $fk_pedido)->update(['Estado' => 'EN BORDADO']);
            } else
                if ($count2 > 0) {
                    OpPedidos::where('PedidoId', '=', $fk_pedido)->update(['PedEstado' => 'RECIBE DE BORDADO']);
                    RecepcionCabecera::where('FK_Pedido', '=', $fk_pedido)->update(['Estado' => 'RECIBE DE BORDADO']);
            } else
                if ($count3 > 0) {
                    OpPedidos::where('PedidoId', '=', $fk_pedido)->update(['PedEstado' => 'ENTREGADO']);
                    RecepcionCabecera::where('FK_Pedido', '=', $fk_pedido)->update(['Estado' => 'ENTREGADO']);
            }   
        }

        $this->confirmingEstadoPrendaPersona = false;
        $this->reset('estadoPrendaPersona');
    }

    public function cancelarEstadoPrendaPersona () {
        $this->reset('estadoPrendaPersona');
        $this->confirmingEstadoPrendaPersona = false;
    }

    public function render()
    {
        $this->todayDate = Carbon::now()->format('Y-m-d');

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
                case "RECIBE DE BORDADO":
                    $this->recibe_de_bordado = $total;
                    break;
                case "ENTREGADO":
                    $this->entregado = $total;
                    break;
            }
        }

        $facturas = $facturas->paginate(10);

        $facturasCliente = RecepcionCabecera::select('recepcion_cabeceras.*', 'op_pedidos.*', 'users.*')
                                            ->leftjoin('op_pedidos', 'recepcion_cabeceras.FK_Pedido', '=', 'op_pedidos.PedidoId')            
                                            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'users.id')  
                                            ->where('FK_user', '=', auth()->user()->id)          
                                            ->orderBy($this->sortBy, $this->sortAsc ?  'ASC' : 'DESC');  
        $fecha1 = Carbon::createFromFormat('Y-m-d', $this->fecha1); 
        $fechaFinal1 = $fecha1->subMonths(3);
        $fechaAux1 = date('m-d-Y');
        
        if ($this->fecha1 !== "") {
            $facturasCliente->whereDate('FechaRecepcion', '>=', $fechaFinal1);
            $facturasCliente->whereDate('FechaRecepcion', '<=', $this->fecha1);
        } else {
            $facturasCliente->whereDate('FechaRecepcion', 'LIKE', "%{$fechaAux1}%");
        }

        if ($this->estado_pick1 != "") {
            $facturasCliente->where('Estado', '=', $this->estado_pick1);
        }

        if ($this->search1 != "") {
            $facturasCliente->where(function ($query) {
                $query->where('NumeroDocumentoExterno', 'like', '%' . $this->search1 . '%')
                    ->orWhere('Observacion', 'like', '%' . $this->search1 . '%')
                    ->orWhere('Estado', 'like', '%' . $this->search1 . '%');
            });
        }

        $this->reset(['recepcion_parcial1', 'recepcion_finalizada1', 'en_bordado1', 'recibe_de_bordado1', 'entregado1']);
        $estados1 = $facturasCliente->get()->countBy('Estado');
        foreach ($estados1 as $estado => $total) {
            switch ($estado) {
                case "RECEPCION_PARCIAL":
                    $this->recepcion_parcial1 = $total;
                    break;
                case "RECEPCION_FINALIZADA":
                    $this->recepcion_finalizada1 = $total;
                    break;
                case "EN BORDADO":
                    $this->en_bordado1 = $total;
                    break;
                case "RECIBE DE BORDADO":
                    $this->recibe_de_bordado1 = $total;
                    break;
                case "ENTREGADO":
                    $this->entregado1 = $total;
                    break;
            }
        }

        $facturasCliente = $facturasCliente->paginate(10);  
        
        $recep = RecepcionCabecera::all();
        if ($recep->isEmpty()) {
            $pedidosAsociados = ManPedidosExterno::all();

        } else {
            $pedidosAsociados = ManPedidosExterno::select('man_pedidos_externos.*', 'recepcion_cabeceras.*')
                                                 ->leftjoin('recepcion_cabeceras', 'man_pedidos_externos.NumPedidoExterno', '=', 'recepcion_cabeceras.NumeroDocumentoExterno')
                                                 ->where('recepcion_cabeceras.Estado', '!=', 'EN BORDADO')
                                                 ->where('recepcion_cabeceras.Estado', '!=', 'RECIBE DE BORDADO')
                                                 ->where('recepcion_cabeceras.Estado', '!=', 'RECEPCION_FINALIZADA')
                                                 ->where('recepcion_cabeceras.Estado', '!=', 'ENTREGADO')
                                                 ->orwhere('recepcion_cabeceras.Estado', '=', null)
                                                 ->get();
        }
                                            
        $pedidosAsociadosBordado = RecepcionDetalle::groupBy('FK_DocumentoExterno')->get();
        foreach($pedidosAsociadosBordado as $item) {
            $cantidad = PrendaPersona::select('prenda_personas.*', DB::raw('sum(CantidadPersona) as suma'))
                                     ->where('FK_DocumentoExterno1', '=', $item->FK_DocumentoExterno)
                                     ->first();
            $cantidad = $cantidad->suma;

            $listado = ManTallajePersona::select(['man_tallaje_personas.*',DB::raw('count(*) as total'), 'op_pedidos_personas.*', 'man_modelos.*', 'op_pedidos_modelos.*', DB::raw('sum(cantidadPrenda) as suma'), 'man_pedidos_externos.*'])
                                        ->leftjoin('op_pedidos_personas', 'man_tallaje_personas.FK_PedidoPersona', '=', 'op_pedidos_personas.PedidoPersonaId')
                                        ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                                        ->leftjoin('man_pedidos_externos', 'op_pedidos_personas.FK_pedido', '=', 'man_pedidos_externos.FK_Pedido')
                                        ->where('man_pedidos_externos.NumPedidoExterno', '=', $item->FK_DocumentoExterno)
                                        ->first();
            $listado = $listado->suma;
            if ($cantidad != $listado) {
                if(array_search($item->FK_DocumentoExterno, array_column($this->listaPedidos, 'nombre')) === false) {
                    $a = array();
                    $a['nombre'] = $item->FK_DocumentoExterno;
                    
                    $this->listaPedidos[] = $a;                
                }
            }
        }
        $recepcionDetalle = RecepcionDetalle::where('FK_Pedido', '=', $this->FK_Pedido)
                                            ->orderBy('RecepcionDetalleId','desc')
                                            ->first();

        $recepcionDetalle = RecepcionDetalle::where('FK_RecepcionCabecera', '=', $this->FK_RecepcionCabecera)->get();

        $prendaPersona = PrendaPersona::where('FK_DocumentoExterno1', '=', $this->idPedidoAsociadoBordado)->get();
        
        return view('livewire.post-pedido', [
            'facturas'                      => $facturas,
            'facturasCliente'               => $facturasCliente,
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
            'listaPedidos'                  => $this->listaPedidos,
            'recepcionDetalle'              => $recepcionDetalle,
            'prendaPersona'                 => $prendaPersona,

        ]);
    }
}
