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
use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Redirect;
use JeroenNoten\LaravelAdminLte\Components\Form\Select;
use Illuminate\Support\Facades\DB;

class Pedidos extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $temp;
    public $temporal;
    public $indice;


    //Variable para buscar un pedido
    public $search;
    public $search1;

    //Seleccionados en radio button modal Modelo
    public $seleccionados;
    public $seleccionadosColor;

    public $estado_pick = "";
    public $estado_pick1 = "";
    public $borrador = 0;
    public $borrador1 = 0;
    public $generado = 0;
    public $generado1 = 0;
    public $en_tallaje = 0;
    public $en_tallaje1 = 0;
    public $gestion_pedido = 0;
    public $gestion_pedido1 = 0;

    //Variable para ordenar la lista de pedidos
    public $sortBy = 'PedidoId';
    public $sortAsc = true;
    public $sortBy1 = 'PedidoId';
    public $sortAsc1 = true;

    /**Variables iniciales Pedido */
    public $todayDate;
    public $idUser;
    public $nombrePedido = "";
    public $celularPedido;
    public $tituloPedido = "";
    public $idUniversidad = 1;
    public $universidadPedido = "No Aplica";
    public $idCarrera = 1;
    public $carreraPedido = "No Aplica";
    public $descripcionPedido = "";
    public $fechaCreacion = "";

    /**Variables iniciales PedidoPersona */
    public $rutPedidoPersona = "";
    public $pNombrePedidoPersona = "";
    public $sNombrePedidoPersona = "";
    public $pApellidoPedidoPersona = "";
    public $sApellidoPedidoPersona = "";
    public $mailPedidoPersona = "";
    public $celularPedidoPersona;
    public $estadoPersona = "Disponible";

    //Variables iniciales Modelo
    public $personaPedido;
    public $modelo;
    public $codigoModelo;
    public $nombreModelo;
    public $estadoModelo = "Disponible";

    //Variables iniciales color
    public $color;
    public $nombreColor;
    public $estadoColor = "Disponible";
    public $idColor;

    //Variables iniciales tallaje
    public $idPedidoPersona = "";
    public $idTipoPrenda = "";
    public $tipoPrenda = "";
    public $idModeloPedido = "";
    public $modeloPedido = "";
    public $idTallaPrenda = "";
    public $tallaPrenda = "";
    public $codigoModeloPedido = "";
    public $idCodigoModeloPedido = "";
    public $colorPrenda = "";
    public $idColorPrenda = "";
    public $cantidadPrenda = 1;


    //Variables para crear una 
    public $cotizacion;
    public $pedido;
    public $idPedido;
    public $col;
    public $mod;
    public $pedidoPersona;
    public $estado = "Disponible";

    // Variables para asignar pedido externo
    public $numeroPedidoExterno;
    public $observacionPedidoExterno;
    public $pedidoExterno;

    public $confirmingPedidoAdd = false;
    public $confirmingTallajeAdd = false;
    public $confirmingModeloAdd = false;
    public $confirmingModeloEdit = false;
    public $confirmingColorEdit = false;
    public $confirmingPedidoView = false;
    public $confirmingTallajePersonasAdd = false;
    public $confirmingSumatoria = false;
    public $confirmingPedidoEditAdmin = false;
    public $confirmingPersonaEditAdmin = false;
    public $confirmingAsignarPedido = false;
    public $confirmingAsignarPedidoEdit = false;

    public $palabra;
    public $listaPersona = [];
    public $listaPersonaPedido = [];
    public $listaVinculacion = [];
    public $listaModeloPedido = [];
    public $listaTallaje = [];
    public $listaColor = [];
    public $listaSumatoria = [];

    public $fecha = "";
    public $fecha1 = "";

    //Variables para eliminar una 
    public $confirmingPedidoDeletion = false;

    //variable para visualizar una 
    public $confirmingCotizacionShow = false;

    protected $queryString = [
        'search',
        'sortBy' => ['except' => 'PedidoId'],
        'sortAsc' => ['except' => true],
        'search1',
        'sortBy1' => ['except' => 'PedidoId'],
        'sortAsc1' => ['except' => true],
    ];

    public function mount()
    {
        
        $this->fecha =  date('Y-m-d');
        $this->fecha1 =  date('Y-m-d');
        //$this->dispatchBrowserEvent('redireccionar');  
        $usuario = User::where('id', '=', auth()->user()->id)->first();
        if ($usuario) {
            $this->usuario = $usuario->id;
            $this->nombrePedido = $usuario->name;
            $this->celularPedido = $usuario->NumeroContacto;
            //Auth::guard('web')->logout();

            //dd(url()->current());

        }
    }

    public function limpiarVariables()
    {
        $this->reset([
            'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'pNombrePedidoPersona',
            'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona', 'celularPedidoPersona'
        ]);
    }

    

    protected $rules = [

        'nombrePedido'                                  => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'], //nombrePersonaPedido viene desde user creado
        'celularPedido'                                 => ['required', 'numeric', 'regex:/^[0-9]+$/'], //nombrePersonaPedido viene desde user creado
        'tituloPedido'                                  => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idUniversidad'                                 => ['numeric', 'exists:man_universidads,UniversidadId'],
        'universidadPedido'                             => ['string', 'exists:man_universidads,ManNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idCarrera'                                     => ['numeric', 'exists:man_carreras,CarreraId'],
        'carreraPedido'                                 => ['string', 'exists:man_carreras,ManNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'descripcionPedido'                             => ['string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'rutPedidoPersona'                              => ['regex:/^[0-9]+[-|‐]{1}[0-9kK]{1}$/'],
        'pNombrePedidoPersona'                          => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'sNombrePedidoPersona'                          => ['string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'pApellidoPedidoPersona'                        => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'sApellidoPedidoPersona'                        => ['string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'mailPedidoPersona'                             => ['email'],
        'celularPedidoPersona'                          => ['numeric', 'regex:/^[0-9]+$/'],

        'codigoModelo'                                  => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'nombreModelo'                                  => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'estadoModelo'                                  => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],

        'nombreColor'                                   => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'estadoColor'                                   => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],

        'idTipoPrenda'                                  => ['numeric', 'exists:man_tipo_prendas,TipoPrendaId'],
        'tipoPrenda'                                    => ['required','string', 'exists:man_tipo_prendas,ManNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idModeloPedido'                                => ['numeric', 'exists:man_modelos,ModeloId'],
        'modeloPedido'                                  => ['required','string', 'exists:man_modelos,ModNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idTallaPrenda'                                 => ['numeric', 'exists:man_talla_prendas,TallaPrendaId'],
        'tallaPrenda'                                   => ['required','string', 'exists:man_talla_prendas,ManNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idCodigoModeloPedido'                          => ['numeric', 'exists:man_modelos,ModeloId'],
        'codigoModeloPedido'                            => ['required','string', 'exists:man_modelos,ModCodigo', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'idColorPrenda'                                 => ['numeric', 'exists:man_colors,ColorId'],
        'colorPrenda'                                   => ['required','string', 'exists:man_colors,ColNombre', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'cantidadPrenda'                                => ['required','numeric' ],

        'estado'                                        => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'estadoPersona'                                 => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],

        'numeroPedidoExterno'                           => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],
        'observacionPedidoExterno'                      => ['string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉéÍíÓóÚú]+$/'],

    ];

    protected $messages = [
        'nombreColor.required'                          =>  'Debe ingresar un color para el modelo seleccionado',
        'nombreColor.string'                            =>  'El campo debe ser alfa numérico',
        'nombreColor.regex'                             =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'estadoColor.required'                          => 'Debe ingresar un codigo al modelo.',
        'estadoColor.string'                            => 'El nombre del codigo debe ser alfa numérico.',
        'estadoColor.regex'                             => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'codigoModelo.required'                         => 'Debe ingresar un codigo al modelo.',
        'codigoModelo.string'                           => 'El nombre del codigo debe ser alfa numérico.',
        'codigoModelo.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'nombreModelo.required'                         => 'Debe ingresar un nombre al modelo.',
        'nombreModelo.string'                           => 'El nombre del modelo debe ser alfa numérico.',
        'nombreModelo.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'estadoModelo.required'                         => 'Debe ingresar un estado al modelo.',
        'estadoModelo.string'                           => 'El nombre del estado debe ser alfa numérico.',
        'estadoModelo.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',


        //PEDIDO

        'nombrePedido.required'                         => 'Debe ingresar un nombre al pedido.',
        'nombrePedido.string'                           => 'El nombre del pedido debe ser alfa numérico.',
        'nombrePedido.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'celularPedido.required'                        => 'Debe ingresar un celular asociado al pedido.',
        'celularPedido.numeric'                         => 'El celular del pedido debe ser numérico.',
        'celularPedido.regex'                           => 'El campo solamente acepta números.',

        'tituloPedido.required'                         => 'Debe ingresar un título al pedido.',
        'tituloPedido.string'                           => 'El titulo del pedido debe ser alfa numérico.',
        'tituloPedido.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'descripcionPedido.text'                        => 'El titulo del pedido debe ser alfa numérico.',
        'descripcionPedido.regex'                       => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'universidadPedido.string'                      => 'La universidad asociada al pedido debe ser alfa numérico.',
        'universidadPedido.regex'                       => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'universidadPedido.exists'                      => 'El nombre no existe en la universidad',

        'idUniversidad.numeric'                         => 'El id de universidad debe ser numérico.',
        'idUniversidad.exists'                          => 'El id no existe en la universidad',

        'carreraPedido.string'                          => 'La carrera de la universidad debe ser alfa numérico.',
        'carreraPedido.regex'                           => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'carreraPedido.exists'                          => 'El nombre no existe en la carrera',

        'idCarrera.numeric'                             => 'El id de carrera debe ser numérico.',
        'idCarrera.exists'                              => 'El id no existe en la carrera',

        'rutPedidoPersona.required'                     => 'Debe ingresar un rut para la persona',
        'rutPedidoPersona.regex'                        =>  'El formato de rut es 11222333-4',

        'pNombrePedidoPersona.required'                 => 'Debe ingresar un primer nombre a la persona.',
        'pNombrePedidoPersona.string'                   => 'El nombre de la persona debe ser alfa numérico.',
        'pNombrePedidoPersona.regex'                    => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'sNombrePedidoPersona.string'                   => 'El nombre del pedido debe ser alfa numérico.',
        'sNombrePedidoPersona.regex'                    => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'pApellidoPedidoPersona.required'               => 'Debe ingresar un primer apellido a la persona.',
        'pApellidoPedidoPersona.string'                 => 'El apellido de la persona debe ser alfa numérico.',
        'pApellidoPedidoPersona.regex'                  => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'sApellidoPedidoPersona.required'               => 'Debe ingresar un segundo apellido a la persona.',
        'sApellidoPedidoPersona.string'                 => 'El apellido de la persona debe ser alfa numérico.',
        'sApellidoPedidoPersona.regex'                  => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'mailPedidoPersona.required'                    => 'Debe ingresar un mail asociado a la persona.',
        'mailPedidoPersona.string'                      => 'El nombre del pedido debe ser alfa numérico.',
        'mailPedidoPersona.regex'                       => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'celularPedidoPersona.numeric'                  => 'El celular de la persona debe ser numérico.',
        'celularPedidoPersona.regex'                    => 'El campo solamente acepta números.',

        'estado.required'                               =>  'Debe ingresar un estado para el modelo',
        'estado.string'                                 =>  'El campo debe ser alfa numérico',
        'estado.regex'                                  =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'estadoPersona.required'                        =>  'Debe ingresar un estado para el modelo',
        'estadoPersona.string'                          =>  'El campo debe ser alfa numérico',
        'estadoPersona.regex'                           =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        //TALLAJE PARTICULAR
        'tipoPrenda.required'                           => 'Debe seleccionar un tipo de prenda.',
        'tipoPrenda.string'                             => 'El tipo de prenda asociado al pedido debe ser alfa numérico.',
        'tipoPrenda.regex'                              => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'tipoPrenda.exists'                             => 'El nombre no existe en el tipo de prenda',

        'idTipoPrenda.numeric'                          => 'El id del tipo de prenda debe ser numérico.',
        'idTipoPrenda.exists'                           => 'El id no existe en el tipo de prenda',

        'modeloPedido.required'                         => 'Debe seleccionar un tipo de modelo.',
        'modeloPedido.string'                           => 'El modelo asociado al pedido debe ser alfa numérico.',
        'modeloPedido.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'modeloPedido.exists'                           => 'El nombre no existe en el modelo',

        'idModeloPedido.numeric'                        => 'El id del modelo debe ser numérico.',
        'idModeloPedido.exists'                         => 'El id no existe en el modelo',

        'tallaPrenda.required'                          => 'Debe seleccionar una talla.',
        'tallaPrenda.string'                            => 'La talla de la prenda asociado al pedido debe ser alfa numérico.',
        'tallaPrenda.regex'                             => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'tallaPrenda.exists'                            => 'El nombre no existe en la talla de prenda',

        'idTallaPrenda.numeric'                         => 'El id de la talla de prenda debe ser numérico.',
        'idTallaPrenda.exists'                          => 'El id no existe en la talla de prenda',

        'codigoModeloPedido.required'                   => 'Debe seleccionar un codigo.',
        'codigoModeloPedido.string'                     => 'El código del modelo asociado al pedido debe ser alfa numérico.',
        'codigoModeloPedido.regex'                      => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'codigoModeloPedido.exists'                     => 'El nombre no existe en el modelo',

        'idCodigoModeloPedido.numeric'                  => 'El id del modelo debe ser numérico.',
        'idCodigoModeloPedido.exists'                   => 'El id no existe en el modelo',

        'colorPrenda.required'                          => 'Debe seleccionar un color.',
        'colorPrenda.string'                            => 'El color del tipo de prenda asociado al pedido debe ser alfa numérico.',
        'colorPrenda.regex'                             => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',
        'colorPrenda.exists'                            => 'El nombre no existe en el color del tipo de prenda',

        'cantidadPrenda.required'                       => 'Debe seleccionar una cantidad.',
        'cantidadPrenda.numeric'                        => 'La cantidad de prenda debe ser numérico.',

        'numeroPedidoExterno.required'                  =>  'Debe ingresar un numero para el pedido externo',
        'numeroPedidoExterno.string'                    =>  'El campo debe ser alfa numérico',
        'numeroPedidoExterno.regex'                     =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'observacionPedidoExterno.text'                 =>  'El campo debe ser alfa numérico',
        'observacionPedidoExterno.regex'                =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',


    ];

    public function sortBy($field)
    {
        if ($field == $this->sortBy) {
            $this->sortAsc = !$this->sortAsc;
        }
        $this->sortBy = $field;
    }
    public function sortBy1($field)
    {
        if ($field == $this->sortBy1) {
            $this->sortAsc1 = !$this->sortAsc1;
        }
        $this->sortBy1 = $field;
    }

    public function refrescar(){
        return redirect()->route('pedido');

    }

    public function updatedColor($color_form)
    {
        $this->validateOnly("color");
    }
    public function updatedEstadoColor($estadoColor_forn)
    {
        $this->validateOnly("estadoColor");
    }

    public function updatedNombreColor($nombreColor_forn)
    {
        $this->validateOnly("nombreColor");
    }

    public function updatedCodigoModelo($codigoModelo_forn)
    {
        $this->validateOnly("codigoModelo");
    }
    public function updatedNombreModelo($nombreModelo_forn)
    {
        $this->validateOnly("nombreModelo");
    }
    public function updatedEstadoModelo($estadoModelo_forn)
    {
        $this->validateOnly("estadoModelo");
    }

    // Evento change para pedido principal
    public function updatedTituloPedido($tituloPedido_forn)
    {
        $this->validateOnly("tituloPedido");
    }

    public function updatedDescripcionPedido($descripcionPedido_forn)
    {
        $this->validateOnly("descripcionPedido");
    }

    public function updatedUniversidadPedido($universidadPedido_form)
    {
        $this->validateOnly("universidadPedido");
        $universidad = ManUniversidad::where('ManNombre', '=', $universidadPedido_form)->first();
        if ($universidad) {
            $this->reset(['idUniversidad', 'universidadPedido']);
            $this->idUniversidad = $universidad->UniversidadId;
            $this->universidadPedido = $universidad->ManNombre;
        } else {
            $this->reset(['idUniversidad']);
        }
    }

    public function updatedCarreraPedido($carreraPedido_form)
    {
        $this->validateOnly("carreraPedido");
        $carrera = ManCarrera::where('ManNombre', '=', $carreraPedido_form)->first();
        //dd($carrera);
        if ($carrera) {
            $this->reset(['idCarrera', 'carreraPedido']);
            $this->idCarrera = $carrera->CarreraId;
            $this->carreraPedido = $carrera->ManNombre;
        } else {
            $this->reset(['idCarrera']);
        }

    }

    public function updatedTipoPrenda($tipoPrenda_form)
    {
        $this->validateOnly("tipoPrenda");
        $tipoPrenda = ManTipoPrenda::where('ManNombre', '=', $tipoPrenda_form)->first();
        if ($tipoPrenda) {
            $this->reset(['idTipoPrenda', 'tipoPrenda']);
            $this->idTipoPrenda = $tipoPrenda->TipoPrendaId;
            $this->tipoPrenda = $tipoPrenda->ManNombre;
        } else {
            $this->reset(['idTipoPrenda']);
        }
    }

    public function updatedCodigoModeloPedido($codigoModeloPedido_form)
    {
                
        $this->validateOnly("codigoModeloPedido");
        $codigoModeloPedidoTallaje = OpPedidosModelo::select('op_pedidos_modelos.*', 'man_modelos.*')
                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'ModeloId')
                        ->where('FK_Pedido', '=', $this->idPedido)
                        ->where('ModCodigo', '=', $codigoModeloPedido_form)
                        ->first();

        $colores = Mancolor::select('man_colors.*', 'op_pedidos_modelos.*', 'man_modelos.*')
                            ->leftjoin('op_pedidos_modelos', 'man_colors.ColorId', '=', 'op_pedidos_modelos.FK_Color')
                            ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                            ->where('op_pedidos_modelos.FK_Pedido', '=', $this->idPedido)
                            ->where('ModCodigo', '=', $codigoModeloPedido_form)
                            ->get();
        //dd($codigoModeloPedidoTallaje);
        $this->reset(['listaColor']);
        foreach($colores as $col) {
            $this->listaColor[] = $col->ColNombre;
        }
        //dd($this->listaColor);
        if ($codigoModeloPedidoTallaje) {
        $this->reset(['idCodigoModeloPedido', 'codigoModeloPedido']);
        $this->idCodigoModeloPedido = $codigoModeloPedidoTallaje->id;
        $this->codigoModeloPedido = $codigoModeloPedidoTallaje->ModCodigo;
        $this->modeloPedido = $codigoModeloPedidoTallaje->ModNombre;
  
        } else {
            $this->reset(['idCodigoModeloPedido']);
        }
        //dd($codigoModeloPedidoTallaje);
    }

    public function updatedModeloPedido($modeloPedido_form)
    {
        
        $this->validateOnly("modeloPedido");
        $modeloPedidoTallaje = OpPedidosModelo::select('op_pedidos_modelos.*', 'man_modelos.*')
                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'ModeloId')
                        ->where('FK_Pedido', '=', $this->idPedido)
                        ->where('ModNombre', '=', $modeloPedido_form)
                        ->first();
        //dd($modeloPedidoTallaje);
        if ($modeloPedidoTallaje) {
        $this->reset(['idModeloPedido', 'modeloPedido']);
        $this->idModeloPedido = $modeloPedidoTallaje->FK_Modelo;
        $this->modeloPedido = $modeloPedidoTallaje->ModNombre;
        
        } else {
            $this->reset(['idModeloPedido']);
        }
      
    }

    public function updatedTallaPrenda($tallaPrenda_form)
    {
        $this->validateOnly("tallaPrenda");
        $tallaPrenda = ManTallaPrenda::where('ManNombre', '=', $tallaPrenda_form)->first();
        if ($tallaPrenda) {
            $this->reset(['idTallaPrenda', 'tallaPrenda']);
            $this->idTallaPrenda = $tallaPrenda->TallaPrendaId;
            $this->tallaPrenda = $tallaPrenda->ManNombre;
        } else {
            $this->reset(['idTallaPrenda']);
        }
    }

    public function updatedColorPrenda($colorPrenda_form)
    {
        $this->validateOnly("colorPrenda");
        $colorPrenda = ManColor::where('ColNombre', '=', $colorPrenda_form)->first();
        if ($colorPrenda) {
            $this->reset(['idColorPrenda', 'colorPrenda']);
            $this->idColorPrenda = $colorPrenda->ColorId;
            $this->colorPrenda = $colorPrenda->ColNombre;
        } else {
            $this->reset(['idColorPrenda']);
        }
    }

    public function updatedCantidadPrenda($cantidadPrenda_form)
    {
        $this->validateOnly("cantidadPrenda");
    }

    // Evento change para pedido Personas
    public function updatedRutPedidoPersona($rutPedidoPersona_form)
    {
        $this->validateOnly("rutPedidoPersona");
    }

    public function updatedPNombrePedidoPersona($pNombrePedidoPersona_form)
    {
        $this->validateOnly("pNombrePedidoPersona");
    }

    public function updatedSNombrePedidoPersona($sNombrePedidoPersona_form)
    {
        $this->validateOnly("sNombrePedidoPersona");
    }

    public function updatedPApellidoPedidoPersona($pApellidoPedidoPersona_form)
    {
        $this->validateOnly("pApellidoPedidoPersona");
    }

    public function updatedSApellidoPedidoPersona($sApellidoPedidoPersona_form)
    {
        $this->validateOnly("sApellidoPedidoPersona");
    }

    public function updatedMailPedidoPersona($mailPedidoPersona_form)
    {
        $this->validateOnly("mailPedidoPersona");
    }

    public function updatedCelularPedidoPersona($celularPedidoPersona_form)
    {
        $this->validateOnly("celularPedidoPersona");
    }

    public function updatedEstadoPersona($estadoPersona_form)
    {
        $this->validateOnly("estadoPersona");
    }

    public function updatedEstado($estado_form)
    {
        $this->validateOnly("estado");
    }

    public function updatedNumeroPedidoExterno($numeroPedidoExterno_form)
    {
        $this->validateOnly("numeroPedidoExterno");
    }

    public function updatedObservacionPedidoExterno($observacionPedidoExterno_form)
    {
        $this->validateOnly("observacionPedidoExterno");
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

    //Funcion para refrescar modal pedido al cancelar 
    public function cancelarPedido()
    {
        $this->reset([
            'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona',
            'pNombrePedidoPersona', 'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona',
            'celularPedidoPersona', 'listaPersona'
        ]);

        $this->confirmingPedidoAdd = false;
    }
    public function cancelarPedidoAdmin()
    {
        $this->reset([
            'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona',
            'pNombrePedidoPersona', 'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona',
            'celularPedidoPersona'
        ]);

        $this->confirmingPedidoEditAdmin = false;
    }

    public function volverPedidoView()
    {
        $this->reset([
            'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona',
            'pNombrePedidoPersona', 'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona',
            'celularPedidoPersona', 'listaPersona'
        ]);

        $this->confirmingPedidoView = false;
    }

    public function volverModelo()
    {
        $this->reset([
            'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona',
            'pNombrePedidoPersona', 'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona',
            'celularPedidoPersona', 'listaPersona'
        ]);

        $this->confirmingModeloAdd = false;
    }

    public function volverTallaje()
    {
        $this->confirmingTallajePersonasAdd = false;
    }

    public function volverSumatoria()
    {
        $this->confirmingSumatoria = false;
    }

    public function cancelarTallajePersona()
    {
        $this->reset([
            'tipoPrenda', 'codigoModeloPedido', 'modeloPedido', 'tallaPrenda', 'colorPrenda', 'cantidadPrenda'
        ]);

        $this->confirmingTallajeAdd = false;
    }

    //Funciones para crear un Pedido
    public function confirmPedidoAdd()
    {
        
        $this->reset('listaPersona');
        $this->nombrePedido = auth()->user()->name;
        $this->celularPedido = auth()->user()->NumeroContacto;
        $this->tituloPedido = "";
        $this->descripcionPedido = "";
        $this->reset(['pedido']);
        $this->confirmingPedidoAdd = true;
        $this->confirmingTallajeAdd = false;
        $this->confirmingModeloAdd = false;
    }

    //Funcion para editar una Pedido
    public function confirmPedidoEdit(OpPedidos $pedido)
    {
        $persona = OpPedidos::select('op_pedidos.*', 'users.*')
                            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'users.id')
                            ->where('users.id', '=', $pedido->FK_user)
                            ->first();

        $universidad = ManUniversidad::findOrFail($pedido->FK_Universidad);
        if ($universidad) {
            $this->reset(['idUniversidad', 'universidadPedido']);
            $this->universidadPedido = $universidad->ManNombre;
            $this->idUniversidad = $universidad->UniversidadId;
        } else {
            $this->reset(['idUniversidad']);
        }

        $carrera = ManCarrera::findOrFail($pedido->FK_Carrera);
        if ($carrera) {
            $this->reset(['idCarrera', 'carreraPedido']);
            $this->carreraPedido = $carrera->ManNombre;
            $this->idCarrera = $carrera->CarreraId;
        } else {
            $this->reset(['idCarrera']);
        }

        $this->pedido = $pedido;
        $this->confirmingPedidoAdd = true;
        $this->nombrePedido = $persona->name;
        $this->celularPedido = $persona->NumeroContacto;
        $this->tituloPedido = $pedido->PedTitulo;
        $this->descripcionPedido = $pedido->PedDescripcion;
        $this->idPedido = $pedido->PedidoId;

        $listado = OpPedidosPersona::where('FK_pedido', '=', $pedido->PedidoId)->get();
        //dd($listado);
        $this->reset('listaPersona');
        foreach ($listado as $item) {

            $a = array();
            $a['id'] = $item->PedidoPersonaId;
            $a['rut'] = $item->PedPerRut;
            $a['primerNombre'] = $item->PedPerPrimerNombre;
            $a['segundoNombre'] = $item->PedPerSegundoNombre;
            $a['primerApellido'] = $item->PedPerPrimerApellido;
            $a['segundoApellido'] = $item->PedPerSegundoApellido;
            $a['mail'] = $item->PedPerMail;
            $a['celular'] = $item->PedPerCelular;
            $a['estado'] = $item->PedPerEstado;

            $this->listaPersona[] = $a;
        }

    }
    public function confirmPedidoEditAdmin(OpPedidos $pedido)
    {
        $persona = OpPedidos::select('op_pedidos.*', 'users.*')
                            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'users.id')
                            ->where('users.id', '=', $pedido->FK_user)
                            ->first();

        $universidad = ManUniversidad::findOrFail($pedido->FK_Universidad);
        if ($universidad) {
            $this->reset(['idUniversidad', 'universidadPedido']);
            $this->universidadPedido = $universidad->ManNombre;
            $this->idUniversidad = $universidad->UniversidadId;
        } else {
            $this->reset(['idUniversidad']);
        }

        $carrera = ManCarrera::findOrFail($pedido->FK_Carrera);
        if ($carrera) {
            $this->reset(['idCarrera', 'carreraPedido']);
            $this->carreraPedido = $carrera->ManNombre;
            $this->idCarrera = $carrera->CarreraId;
        } else {
            $this->reset(['idCarrera']);
        }

        $this->pedido = $pedido;
        $this->confirmingPedidoEditAdmin = true;
        $this->nombrePedido = $persona->name;
        $this->celularPedido = $persona->NumeroContacto;
        $this->tituloPedido = $pedido->PedTitulo;
        $this->descripcionPedido = $pedido->PedDescripcion;
        $this->idPedido = $pedido->PedidoId;

    }

    //Funcion para editar una Pedido
    public function confirmPedidoView(OpPedidos $pedido)
    {
        $persona = OpPedidos::select('op_pedidos.*', 'users.*')
                            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'users.id')
                            ->where('users.id', '=', $pedido->FK_user)
                            ->first();

        $universidad = ManUniversidad::findOrFail($pedido->FK_Universidad);
        if ($universidad) {
            $this->reset(['idUniversidad', 'universidadPedido']);
            $this->universidadPedido = $universidad->ManNombre;
            $this->idUniversidad = $universidad->UniversidadId;
        } else {
            $this->reset(['idUniversidad']);
        }

        $carrera = ManCarrera::findOrFail($pedido->FK_Carrera);
        if ($carrera) {
            $this->reset(['idCarrera', 'carreraPedido']);
            $this->carreraPedido = $carrera->ManNombre;
            $this->idCarrera = $carrera->CarreraId;
        } else {
            $this->reset(['idCarrera']);
        }

        $this->pedido = $pedido;
        $this->confirmingPedidoView = true;
        $this->nombrePedido = $persona->name;
        $this->celularPedido = $persona->NumeroContacto;
        $this->tituloPedido = $pedido->PedTitulo;
        $this->descripcionPedido = $pedido->PedDescripcion;
        $this->fechaCreacion = $pedido->PedidoFechaCreacion->format('Y-m-d');

        $listado = OpPedidosPersona::where('FK_pedido', '=', $pedido->PedidoId)->get();
        //dd($listado);
        $this->reset('listaPersona');
        foreach ($listado as $item) {

            $a = array();
            $a['id'] = $item->PedidoPersonaId;
            $a['rut'] = $item->PedPerRut;
            $a['primerNombre'] = $item->PedPerPrimerNombre;
            $a['segundoNombre'] = $item->PedPerSegundoNombre;
            $a['primerApellido'] = $item->PedPerPrimerApellido;
            $a['segundoApellido'] = $item->PedPerSegundoApellido;
            $a['mail'] = $item->PedPerMail;
            $a['celular'] = $item->PedPerCelular;
            $a['estado'] = $item->PedPerEstado;


            $this->listaPersona[] = $a;
        }
    }

    public function quitarPersona($id)
    {
           
        unset($this->listaPersona[$id]);

    }
    public function quitarPersonaAdmin($id)
    {
        //dd($this);
        OpPedidosPersona::where('PedidoPersonaId', '=', $id)->update(['PedPerEstado' => 'INACTIVO']);
        ManTallajePersona::where('FK_PedidoPersona', '=', $id)->delete();

    }

    public function quitarVinculacion($id)
    {

        unset($this->listaVinculacion[$id]);
    }

    public function quitarPrenda($id)
    {

        unset($this->listaTallaje[$id]);
    }

    public function quitarModelo($id)
    {

        $color = ManColor::where('FK_Modelo', '=', $id)->first();
        if ($color == null) {
            ManModelo::where('ModeloId', '=', $id)->update(['ModEstado' => 'Inactivo']);
        } else {

            $errorCode = 'No se puede eliminar un Modelo que tenga colores asociados';
            $this->dispatchBrowserEvent('abrirMsjeFallido1', ['error' => $errorCode]);
        }
    }

    public function quitarColor($id)
    {

        ManColor::where('ColorId', '=', $id)->update(['ColEstado' => 'Inactivo']);
    }

    public function editarPersona($id)
    {
        $this->pNombrePedidoPersona = $this->listaPersona[$id]['primerNombre'];
        $this->sNombrePedidoPersona = $this->listaPersona[$id]['segundoNombre'];
        $this->pApellidoPedidoPersona = $this->listaPersona[$id]['primerApellido'];
        $this->sApellidoPedidoPersona = $this->listaPersona[$id]['segundoApellido'];
        $this->rutPedidoPersona = $this->listaPersona[$id]['rut'];
        $this->mailPedidoPersona = $this->listaPersona[$id]['mail'];
        $this->celularPedidoPersona = $this->listaPersona[$id]['celular'];

        unset($this->listaPersona[$id]);
    }

    public function editarPrenda($id)
    {

        $this->tipoPrenda = $this->listaTallaje[$id]['Tipo'];
        $this->codigoModeloPedido = $this->listaTallaje[$id]['CodigoModelo'];
        $this->modeloPedido = $this->listaTallaje[$id]['Modelo'];
        $this->tallaPrenda = $this->listaTallaje[$id]['Talla'];
        $this->colorPrenda = $this->listaTallaje[$id]['Color'];
        $this->idColorPrenda = $this->listaTallaje[$id]['FK_Color'];
        $this->idTipoPrenda = $this->listaTallaje[$id]['FK_TipoPrenda'];
        $this->idModeloPedido = $this->listaTallaje[$id]['FK_PedidoModelo'];
        $this->cantidadPrenda = $this->listaTallaje[$id]['cantidadPrenda'];

        unset($this->listaTallaje[$id]);
    }

    //Funciones para eliminar una Pedido
    public function confirmPedidoDeletion($id)
    {
        //$cotizacion->delete();
        $this->confirmingPedidoDeletion = $id;
    }

    //Funciones para mostrar lista personas listas para tallaje
    public function confirmPersonaTallaje(OpPedidos $pedido)
    {    
        $personas = OpPedidosPersona::where('FK_pedido', '=', $pedido->PedidoId)->get();
            foreach ($personas as $p) {
                $prendas = ManTallajePersona::where('FK_PedidoPersona', '=', $p->PedidoPersonaId)->first();
                if ($prendas == null) {
                    OpPedidosPersona::where('PedidoPersonaId', '=', $p->PedidoPersonaId)->update(['PedPerEstado' => 'SIN TALLAJE']);
                }
            }

        $this->confirmingTallajePersonasAdd = true;
        $modelos = OpPedidosModelo::where('FK_Pedido', '=', $pedido->PedidoId)->first();
        if ($modelos == null) {
            $errorCode = 'Debe vincular modelos al pedido para proceder a Tallaje';
            $this->dispatchBrowserEvent('abrirMsjeFallido8', ['error' => $errorCode]);
        }
        $listado = OpPedidosPersona::where('FK_pedido', '=', $pedido->PedidoId)->get();
        $this->idPedido = $pedido->PedidoId;

    }

    public function confirmSumatoria(OpPedidos $pedido) {
        $persona = OpPedidos::select('op_pedidos.*', 'users.*')
                            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'users.id')
                            ->where('users.id', '=', $pedido->FK_user)
                            ->first();

        $this->idPedido = $pedido->PedidoId;
        $universidad = ManUniversidad::findOrFail($pedido->FK_Universidad);
        if ($universidad) {
            $this->reset(['idUniversidad', 'universidadPedido']);
            $this->universidadPedido = $universidad->ManNombre;
            $this->idUniversidad = $universidad->UniversidadId;
        } else {
            $this->reset(['idUniversidad']);
        }

        $carrera = ManCarrera::findOrFail($pedido->FK_Carrera);
        if ($carrera) {
            $this->reset(['idCarrera', 'carreraPedido']);
            $this->carreraPedido = $carrera->ManNombre;
            $this->idCarrera = $carrera->CarreraId;
        } else {
            $this->reset(['idCarrera']);
        }

        $this->pedido = $pedido;
        $this->confirmingSumatoria = true;
        $this->nombrePedido = $persona->name;
        $this->celularPedido = $persona->NumeroContacto;
        $this->tituloPedido = $pedido->PedTitulo;
        $this->descripcionPedido = $pedido->PedDescripcion;

        $listado = ManTallajePersona::select(['man_tallaje_personas.*',DB::raw('count(*) as total'), 'op_pedidos_personas.*', 'man_modelos.*', 'op_pedidos_modelos.*',DB::raw('sum(cantidadPrenda) as suma')] )
                                    ->leftjoin('op_pedidos_personas', 'man_tallaje_personas.FK_PedidoPersona', '=', 'op_pedidos_personas.PedidoPersonaId')
                                    ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                                    ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                                    ->where('op_pedidos_personas.FK_pedido', '=', $this->idPedido)
                                    ->groupBy('ModCodigo', 'ModNombre', 'TallajeTalla', 'FK_TipoPrenda', 'idColor')
                                    ->get();
                          
        $this->reset('listaSumatoria');
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
                   
    }

    public function confirmAsignarPedido($id) {
        $this->confirmingAsignarPedido = true;
        $this->idPedido = $id;
        
    }

    public function asignarPedido() {
        //dd($this);
        $this->validateOnly('numeroPedidoExterno');
        $this->validateOnly('observacionPedidoExterno');

        $pedExt = ManPedidosExterno::where('FK_Pedido', '=', $this->idPedido)->first();
        if($pedExt == null) {

            if ($this->idPedido > 0) {
            ManPedidosExterno::create([
                'NumPedidoExterno'                   => $this->numeroPedidoExterno,
                'ObsPedidoExterno'                   => $this->observacionPedidoExterno,
                'FK_Pedido'                          => $this->idPedido,
                ]);
                OpPedidos::where('PedidoId', '=', $this->idPedido)->update(['PedEstado' => 'ESPERA DE PRENDAS']);

            }
        } else {
            $errorCode = 'Ya existe un Pedido Externo Asignado';
            $this->reset([
                'numeroPedidoExterno', 'observacionPedidoExterno'
            ]);
            $this->dispatchBrowserEvent('abrirMsjeFallido9', ['error' => $errorCode]);
        }

    }

    public function volverAsignarPedido() {
        $this->reset([
            'numeroPedidoExterno', 'observacionPedidoExterno', 'idPedido'
        ]);
        $this->confirmingAsignarPedido = false;
    }


    public function confirmTallajeAdd($id) {
        $this->reset('listaModeloPedido'); 
        $this->idPedidoPersona = $id;

        $listado = ManTallajePersona::where('FK_PedidoPersona', '=', $this->idPedidoPersona)->get();
        $this->reset('listaTallaje');
        foreach ($listado as $item) {
            $tipo = ManTipoPrenda::where('TipoPrendaId', '=', $item->FK_TipoPrenda)->first();
            $codigoModelo = ManTallajePersona::select('man_modelos.*', 'man_tallaje_personas.*', 'op_pedidos_modelos.*')
                            ->leftjoin('op_pedidos_modelos', 'man_tallaje_personas.FK_PedidoModelo', '=', 'op_pedidos_modelos.id')
                            ->leftjoin('man_modelos', 'man_modelos.ModeloId', '=', 'op_pedidos_modelos.FK_Modelo')
                            ->where('man_tallaje_personas.FK_PedidoModelo', '=', $item->FK_PedidoModelo)->first();
            //dd($codigoModelo);
            $color = ManColor::where('ColorId', '=', $item->idColor)->first();
            //dd($this);
            $a = array();
            $a['Tipo'] = $tipo->ManNombre;
            $a['CodigoModelo'] = $codigoModelo->ModCodigo;
            $a['Modelo'] = $codigoModelo->ModNombre;
            $a['Talla'] = $item->TallajeTalla;
            $a['Color'] = "$color->ColNombre";
            $a['FK_TipoPrenda'] = $item->FK_TipoPrenda;
            $a['FK_PedidoModelo'] = $item->FK_PedidoModelo;
            $a['FK_Color'] = $item->idColor;
            $a['cantidadPrenda'] = $item->cantidadPrenda;

            $this->listaTallaje[] = $a;
        }
        $modeloPedido = OpPedidosModelo::select('op_pedidos_modelos.*', 'man_modelos.*')
                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'ModeloId')
                        ->where('FK_Pedido', '=', $this->idPedido)
                        ->get();

        
        foreach($modeloPedido as $item) {
            if(array_search($item->ModNombre, array_column($this->listaModeloPedido, 'modelo')) === false) {
                $a = array();
                $a['modelo'] = $item->ModNombre;
                $a['codigoModelo'] = $item->ModCodigo;
                $a['FK_Modelo'] = $item->FK_Modelo;
                $a['FK_Color'] = $item->FK_Color;
                $this->listaModeloPedido[] = $a;
            }
        }

        $this->confirmingTallajeAdd = true;        
    }

    //Funciones para crear un modelo
    public function confirmModeloAdd($pedido)
    {
        $this->idPedido = $pedido;
        $listado = OpPedidosModelo::where('FK_Pedido', '=', $this->idPedido)->get();
        //dd($listado);
        $this->reset('listaVinculacion');
        foreach ($listado as $item) {
            $color = $item->FK_Color;
            $color1 = ManColor::where('ColorId', '=', $color)->first();
            $nombreModelo = $item->FK_Modelo;
            $nombreModelo1 = ManModelo::where('ModeloId', '=', $nombreModelo)->first();


            //dd($color1);

            $a = array();
            $a['id'] = $item->id;
            $a['Color'] = $color1->ColNombre;
            $a['Codigo'] = $nombreModelo1->ModCodigo;
            $a['Modelo'] = $nombreModelo1->ModNombre;
            $a['FK_Modelo'] = $item->FK_Modelo;
            $a['FK_Color'] = $item->FK_Color;

            $this->listaVinculacion[] = $a;
        }
        //dd($this->listaVinculacion);
        //$this->reset(['modelo']);
        //$this->reset(['Pedido']);
        
        $this->confirmingModeloAdd = true;
        $this->confirmingPedidoAdd = false;
        $this->confirmingModeloEdit = false;
    }

    //Funciones para editar un Modelo
    public function confirmModeloEdit(ManModelo $modelo)
    {
        $this->confirmingModeloEdit = true;

        $this->modelo = $modelo;
        //dd($this->modelo);
        $this->codigoModelo = $modelo->ModCodigo;
        $this->nombreModelo = $modelo->ModNombre;
        $this->estadoModelo = $modelo->ModEstado;
    }

    //Funciones para editar un Modelo
    public function editModelo()
    {
        $this->validateOnly('codigoModelo');
        $this->validateOnly('nombreModelo');
        $this->validateOnly('estadoModelo');

        if (isset($this->modelo->ModeloId)) {

            $this->modelo->ModCodigo = $this->codigoModelo;
            $this->modelo->ModNombre = $this->nombreModelo;
            $this->modelo->ModEstado = $this->estadoModelo;
            $this->modelo->save();
        }
        $this->confirmingModeloEdit = false;
    }

    //Funciones para editar un Color
    public function confirmColorEdit(ManColor $color)
    {
        $this->confirmingColorEdit = true;

        $this->color = $color;
        //dd($this->modelo);
        $this->nombreColor = $color->ColNombre;
        $this->estadoColor = $color->ColEstado;
    }

    //Funciones para editar un Color
    public function editColor()
    {
        $this->validateOnly('nombreColor');
        $this->validateOnly('estadoColor');

        if (isset($this->color->ColorId)) {

            $this->color->ColNombre = $this->nombreColor;
            $this->color->ColEstado = $this->estadoColor;
            $this->color->save();
        }
        $this->reset(['codigoModelo', 'nombreModelo', 'estadoModelo', 'nombreColor', 'estadoColor']);
        $this->confirmingColorEdit = false;
    }

        //Funciones para editar un Color
        public function confirmAsignarPedidoEdit(ManPedidosExterno $pedidoExterno)
        {
            $this->confirmingAsignarPedidoEdit = true;
    
            $this->pedidoExterno = $pedidoExterno;
            //dd($this->modelo);
            $this->numeroPedidoExterno = $pedidoExterno->NumPedidoExterno;
            $this->observacionPedidoExterno = $pedidoExterno->ObsPedidoExterno;
        }
    
        //Funciones para editar un Color
        public function editPedidoExterno()
        {
            $this->validateOnly('numeroPedidoExterno');
            $this->validateOnly('observacionPedidoExterno');
    
            if (isset($this->pedidoExterno->PedidoExternoId)) {
    
                $this->pedidoExterno->NumPedidoExterno = $this->numeroPedidoExterno;
                $this->pedidoExterno->ObsPedidoExterno = $this->observacionPedidoExterno;
                $this->pedidoExterno->save();
            }
            $this->reset(['numeroPedidoExterno', 'observacionPedidoExterno']);
            $this->confirmingAsignarPedidoEdit = false;
        }

        //Funciones para editar un Modelo
        public function confirmPersonaEdit(OpPedidosPersona $pedidoPersona)
        {
            
            $this->confirmingPersonaEditAdmin = true;
    
            $this->personaPedido = $pedidoPersona;
            //dd($this->modelo);
            $this->pNombrePedidoPersona = $pedidoPersona->PedPerPrimerNombre;
            $this->sNombrePedidoPersona = $pedidoPersona->PedPerSegundoNombre;
            $this->pApellidoPedidoPersona = $pedidoPersona->PedPerPrimerApellido;
            $this->sApellidoPedidoPersona = $pedidoPersona->PedPerSegundoApellido;
            $this->rutPedidoPersona = $pedidoPersona->PedPerRut;
            $this->mailPedidoPersona = $pedidoPersona->PedPerMail;
            $this->celularPedidoPersona = $pedidoPersona->PedPerCelular;
            $this->estadoPersona = $pedidoPersona->PedPerEstado;
        }
    
        //Funciones para editar un Modelo
        public function editPersonaAdmin()
        {
            $this->validateOnly('pNombrePedidoPersona');
            $this->validateOnly('sNombrePedidoPersona');
            $this->validateOnly('pApellidoPedidoPersona');
            $this->validateOnly('sApellidoPedidoPersona');
            $this->validateOnly('rutPedidoPersona');
            $this->validateOnly('mailPedidoPersona');
            $this->validateOnly('celularPedidoPersona');
            $this->validateOnly('estadoPersona');
            
            if (isset($this->personaPedido->PedidoPersonaId)) {
    
                $this->personaPedido->PedPerPrimerNombre = $this->pNombrePedidoPersona;
                $this->personaPedido->PedPerSegundoNombre = $this->sNombrePedidoPersona;
                $this->personaPedido->PedPerPrimerApellido = $this->pApellidoPedidoPersona;
                $this->personaPedido->PedPerSegundoApellido = $this->sApellidoPedidoPersona;
                $this->personaPedido->PedPerRut = $this->rutPedidoPersona;
                $this->personaPedido->PedPerMail = $this->mailPedidoPersona;
                $this->personaPedido->PedPerCelular = $this->celularPedidoPersona;
                $this->personaPedido->PedPerEstado = $this->estadoPersona;
                $this->personaPedido->save();
            }
            $this->confirmingPersonaEditAdmin = false;
        }

    public function AgregaPersonas()
    {
        /**Validamos que existan los elementos básicos del pedido */
        $this->validateOnly('nombrePedido');
        $this->validateOnly('celularPedido');
        $this->validateOnly('tituloPedido');
        //$this->validateOnly('universidadPedido');
        //$this->validateOnly('carreraPedido');
        $this->validateOnly('descripcionPedido');
        if ($this->universidadPedido == null) {
            $this->idUniversidad = "1";
        } 
        if($this->carreraPedido == null) {
            $this->idCarrera = "1";
        }

        /**Validamos que existan los elementos básicos de la persona */
        $this->validateOnly('rutPedidoPersona');
        $this->validateOnly('pNombrePedidoPersona');
        $this->validateOnly('sNombrePedidoPersona');
        $this->validateOnly('pApellidoPedidoPersona');
        $this->validateOnly('sApellidoPedidoPersona');
        $this->validateOnly('mailPedidoPersona');
        //$this->validateOnly('celularPedidoPersona');
        if ($this->celularPedidoPersona == null) {
            $this->celularPedidoPersona = 0;
        }
        
            array_push($this->listaPersona, [
                'id'                              => "",
                'rut'                             => $this->rutPedidoPersona,
                'primerNombre'                    => $this->pNombrePedidoPersona,
                'segundoNombre'                   => $this->sNombrePedidoPersona,
                'primerApellido'                  => $this->pApellidoPedidoPersona,
                'segundoApellido'                 => $this->sApellidoPedidoPersona,
                'mail'                            => $this->mailPedidoPersona,
                'celular'                         => $this->celularPedidoPersona,
                'estado'                         => "SIN TALLAJE",
            ]);
                
            $this->reset([
                'rutPedidoPersona', 'pNombrePedidoPersona', 'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona',
                'celularPedidoPersona'
            ]);
            
    }

    public function submit($temp)
    {

            $this->validateOnly('nombrePedido');
            $this->validateOnly('celularPedido');
            $this->validateOnly('tituloPedido');
            $this->validateOnly('universidadPedido');
            $this->validateOnly('carreraPedido');
            $this->validateOnly('descripcionPedido');

            if ($this->universidadPedido == null) {
                $this->idUniversidad = "1";
            } 
            if($this->carreraPedido == null) {
                $this->idCarrera = "1";
            }
            if (isset($this->pedido->PedidoId)) {
                $this->pedido->PedTitulo = strtoupper($this->tituloPedido);
                $this->pedido->PedDescripcion = $this->descripcionPedido;
                $this->pedido->FK_Universidad = $this->idUniversidad;
                $this->pedido->FK_Carrera = $this->idCarrera;
                if ($temp == 1) {
                    $this->pedido->PedEstado = "BORRADOR";
                } 
                if ($temp == 2 && count($this->listaPersona) > 0) {
                    $this->pedido->PedEstado = "GENERADO";
                }
                $this->pedido->save();
                //OpPedidosPersona::where('FK_pedido', '=', $this->pedido->PedidoId)->delete();
                $listado = OpPedidosPersona::where('FK_pedido', '=', $this->idPedido)->get();
                //dd($this->listaPersona);
                if (count($this->listaPersona) > 0) {
                    foreach ($this->listaPersona as $key => $persona) {
                        if($persona['id'] == "") {              
                        //Creamos persona
                        OpPedidosPersona::create([
                            'PedPerRut'                         => $persona['rut'],
                            'PedPerPrimerNombre'                => $persona['primerNombre'],
                            'PedPerSegundoNombre'               => $persona['segundoNombre'],
                            'PedPerPrimerApellido'              => $persona['primerApellido'],
                            'PedPerSegundoApellido'             => $persona['segundoApellido'],
                            'PedPerMail'                        => $persona['mail'],
                            'PedPerCelular'                     => $persona['celular'],
                            'FK_pedido'                         => $this->pedido->PedidoId,
                            'PedPerEstado'                      => "SIN TALLAJE",

                            ]);
                        } else {
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerRut'=>$persona['rut']]);
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerPrimerNombre'=>$persona['primerNombre']]);
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerSegundoNombre'=>$persona['segundoNombre']]);
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerPrimerApellido'=>$persona['primerApellido']]);
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerSegundoApellido'=>$persona['segundoApellido']]);
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerMail'=>$persona['mail']]);
                            OpPedidosPersona::where('PedidoPersonaId', '=', $persona['id'])->update(['PedPerCelular'=>$persona['celular']]);
                        }
                    }
                            $count = 0;
                            foreach ($listado as $key => $lista) {
                                foreach ($this->listaPersona as $key => $persona) {
                                    if ($lista->PedidoPersonaId == $persona['id']) {
                                        $count++;
                                    }
                                }
                                    if ($count < 1) {
                                        ManTallajePersona::where('FK_PedidoPersona', '=', $lista->PedidoPersonaId)->delete();
                                        OpPedidosPersona::where('PedidoPersonaId', '=', $lista->PedidoPersonaId)->delete();
                                    }
                                    $count = 0;
                            }                                                             
                }
                $this->reset([
                    'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona', 'pNombrePedidoPersona', 'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona',
                    'celularPedidoPersona', 
                ]);
                $this->confirmingPedidoAdd = false;

            } else {
                if ($temp == 1) {
                    $pedido = OpPedidos::create([
                        'PedTitulo'                         => strtoupper($this->tituloPedido),
                        'FK_Universidad'                    => $this->idUniversidad,
                        'FK_Carrera'                        => $this->idCarrera,
                        'PedDescripcion'                    => $this->descripcionPedido,
                        'PedImagen'                         => 1,
                        'PedEstado'                         => 'BORRADOR',
                        'FK_user'                           => auth()->user()->id,
                        'PedidoFechaCreacion'               => $this->todayDate,

                    ]);

                    if (count($this->listaPersona) > 0 && $pedido->PedidoId > 0) {
                        foreach ($this->listaPersona as $key => $persona) {
                            //Creamos persona
                            OpPedidosPersona::create([
                                'PedPerRut'                         => $persona['rut'],
                                'PedPerPrimerNombre'                => $persona['primerNombre'],
                                'PedPerSegundoNombre'               => $persona['segundoNombre'],
                                'PedPerPrimerApellido'              => $persona['primerApellido'],
                                'PedPerSegundoApellido'             => $persona['segundoApellido'],
                                'PedPerMail'                        => $persona['mail'],
                                'PedPerCelular'                     => $persona['celular'],
                                'FK_pedido'                         => $pedido->PedidoId,
                                'PedPerEstado'                      => "SIN TALLAJE",
                            ]);
                        }
                    }
                    $this->reset([
                        'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona', 'pNombrePedidoPersona',
                        'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona', 'celularPedidoPersona', 'listaPersona'
                    ]);
                    $this->confirmingPedidoAdd = false;

                } elseif ($temp == 2 && count($this->listaPersona) > 0) {
                    $pedido = OpPedidos::create([
                        'PedTitulo'                         => strtoupper($this->tituloPedido),
                        'FK_Universidad'                    => $this->idUniversidad,
                        'FK_Carrera'                        => $this->idCarrera,
                        'PedDescripcion'                    => $this->descripcionPedido,
                        'PedImagen'                         => 1,
                        'PedEstado'                         => 'GENERADO',
                        'FK_user'                           => auth()->user()->id,
                        'PedidoFechaCreacion'               => $this->todayDate,

                    ]);
                    
                                    if (count($this->listaPersona) > 0 && $pedido->PedidoId > 0) {
                                        foreach ($this->listaPersona as $key => $persona) {
                                            //Creamos persona
                                            OpPedidosPersona::create([
                                                'PedPerRut'                         => $persona['rut'],
                                                'PedPerPrimerNombre'                => $persona['primerNombre'],
                                                'PedPerSegundoNombre'               => $persona['segundoNombre'],
                                                'PedPerPrimerApellido'              => $persona['primerApellido'],
                                                'PedPerSegundoApellido'             => $persona['segundoApellido'],
                                                'PedPerMail'                        => $persona['mail'],
                                                'PedPerCelular'                     => $persona['celular'],
                                                'FK_pedido'                         => $pedido->PedidoId,
                                                'PedPerEstado'                      => "SIN TALLAJE",
                                            ]);
                                        }
                                    }
                $this->reset([
                    'nombrePedido', 'celularPedido', 'tituloPedido', 'universidadPedido', 'carreraPedido', 'descripcionPedido', 'rutPedidoPersona', 'pNombrePedidoPersona',
                    'sNombrePedidoPersona', 'pApellidoPedidoPersona', 'sApellidoPedidoPersona', 'mailPedidoPersona', 'celularPedidoPersona', 'listaPersona'
                ]);
                $this->confirmingPedidoAdd = false;

                } else {
                    $errorCode = 'No puede Guardar si no existen personas asociadas al pedido';
                    $this->dispatchBrowserEvent('abrirMsjeFallido7', ['error' => $errorCode]);
                }

            }     
    }

    public function submitEdit() {

            $this->validateOnly('nombrePedido');
            $this->validateOnly('celularPedido');
            $this->validateOnly('tituloPedido');
            $this->validateOnly('universidadPedido');
            $this->validateOnly('carreraPedido');
            $this->validateOnly('descripcionPedido');

            if ($this->universidadPedido == null) {
                $this->idUniversidad = "1";
            } 
            if($this->carreraPedido == null) {
                $this->idCarrera = "1";
            }
            if (isset($this->pedido->PedidoId)) {
                $this->pedido->PedTitulo = strtoupper($this->tituloPedido);
                $this->pedido->PedDescripcion = $this->descripcionPedido;
                $this->pedido->FK_Universidad = $this->idUniversidad;
                $this->pedido->FK_Carrera = $this->idCarrera;

                $this->pedido->save();
                $this->confirmingPedidoEditAdmin = false;
            }
    }

    public function AgregaVinculacion()
    {
        $count = 0;
        $modelo = ManModelo::where('ModeloId', '=', $this->seleccionados)->first();
        $color = ManColor::where('ColorId', '=', $this->seleccionadosColor)->first();
        $nombreModelo = $modelo->ModNombre;
        $codigo = $modelo->ModCodigo;
        $nombreColor = $color->ColNombre;
        foreach ($this->listaVinculacion as $item) {
            if ($this->seleccionados == $item['FK_Modelo']) {
                if ($this->seleccionadosColor == $item['FK_Color']) {
                    $count++;
                }
            }
        }
        if ($count == 0) {
            if ($modelo && $color && $this->seleccionados > 0 && $this->seleccionadosColor > 0) {
                array_push($this->listaVinculacion, [
                    'id'                                => "",
                    'Codigo'                             => $codigo,
                    'Modelo'                             => $nombreModelo,
                    'Color'                              => $nombreColor,
                    'FK_Modelo'                          => $this->seleccionados,
                    'FK_Color'                           => $this->seleccionadosColor,
                ]);
            }
        } else {
            $errorCode = 'No se puede vincular un Modelo con su respectivo Color a un pedido que ya contiene aquella Vinculación';
            $this->dispatchBrowserEvent('abrirMsjeFallido4', ['error' => $errorCode]);
        }
    }

    public function saveVinculacion()
    {      
        try {

            $listado = OpPedidosModelo::where('FK_Pedido', '=', $this->idPedido)->get();
        //OpPedidosModelo::where('FK_pedido', '=', $this->idPedido)->delete();
            if (count($this->listaVinculacion) > 0) {
                //dd($this->listaVinculacion);
                foreach ($this->listaVinculacion as $key => $vinculacion) {
                    if($vinculacion['id'] == "") {  
                    //Creamos persona
                    OpPedidosModelo::create([
                        'FK_Modelo'                         => $vinculacion['FK_Modelo'],
                        'FK_Color'                          => $vinculacion['FK_Color'],
                        'FK_Pedido'                         => $this->idPedido,

                        ]);
                    }
                }
                $count = 0;
                foreach ($listado as $key => $lista) {
                    foreach ($this->listaVinculacion as $key => $vinculacion) {
                        if ($lista->id == $vinculacion['id']) {
                            $count++;
                        }
                    }
                        if ($count < 1) {
                            OpPedidosModelo::where('id', '=', $lista->id)->delete();
                        }
                        $count = 0;
                }                
            }
            $this->confirmingModeloAdd = false;
            //session()->flash('success', 'jasdjasdj');
        } catch (QueryException $e) {
            //$this->inicializando = false;
            $errorCode = $e->errorInfo[1];
            //$this->emitUp('OtAgregado'); //refrescamos la vista padre, tabla Ot para que aparezca la ot agregada
            $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);
        }

    }

    public function saveModelo()
    {
        try {

            $this->validateOnly('codigoModelo');
            $this->validateOnly('nombreModelo');
            $this->validateOnly('estadoModelo');

            if (isset($this->mod->ModeloId)) {
                $this->modelo->save();
            } else {
                $listadoCodigo = ManModelo::where('ModCodigo', '=', $this->codigoModelo)->first();
                if ($listadoCodigo == null) {
                    ManModelo::create([
                        'ModCodigo' => $this->codigoModelo,
                        'ModNombre' => $this->nombreModelo,
                        'ModEstado' => $this->estadoModelo,
                    ]);
                } else {
                    $errorCode = 'No se puede agregar un Modelo que tenga un código ya existente';
                    $this->dispatchBrowserEvent('abrirMsjeFallido2', ['error' => $errorCode]);
                }
            }
            $this->reset(['codigoModelo', 'nombreModelo', 'estadoModelo']);
            $this->confirmingModeloEdit = false;
        } catch (QueryException $e) {
            //$this->inicializando = false;
            $errorCode = $e->errorInfo[1];
            //$this->emitUp('OtAgregado'); //refrescamos la vista padre, tabla Ot para que aparezca la ot agregada
            $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);
        }
    }

    public function saveColor()
    {
        $this->validateOnly('nombreColor');
        $this->validateOnly('estadoColor');

        try {

            if ($this->seleccionados > 0) {
                if (isset($this->col->ColorId)) {
                    $this->col->save();
                } else {

                    $color = ManColor::where('FK_Modelo', '=',$this->seleccionados)
                                        ->where('ColNombre', '=', $this->nombreColor)->first();
                    if ($color == null) {
                        ManColor::create([
                            'ColNombre' => $this->nombreColor,
                            'ColEstado' => $this->estadoColor,
                            'FK_Modelo' => $this->seleccionados,
                        ]);
                    } else {
                        $errorCode = 'No se puede agregar un Color ya asociado a un Modelo';
                        $this->dispatchBrowserEvent('abrirMsjeFallido5', ['error' => $errorCode]);
                    }

                    $color = ManColor::select('ColorId')->orderBy('ColorId', 'desc')->first();
                    if ($color) {
                        $this->reset(['idColor', 'color']);
                        $this->color = $color->ManNombre;
                        $this->idColor = $color->ColorId;
                    } else {
                        $this->reset(['idColor']);
                    }

                    ManModeloColor::create([
                        'FK_Modelo' => $this->seleccionados,
                        'FK_Color' => $this->idColor,
                    ]);
                }
            }
            $this->reset(['codigoModelo', 'nombreModelo', 'estadoModelo', 'nombreColor', 'estadoColor']);
            $this->confirmingModeloEdit = false;
        } catch (QueryException $e) {
            //$this->inicializando = false;
            $errorCode = $e->errorInfo[1];
            //$this->emitUp('OtAgregado'); //refrescamos la vista padre, tabla Ot para que aparezca la ot agregada
            $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);
        }
    }

    public function savePrenda($temporal) {
        if (count($this->listaTallaje) > 0) {
            ManTallajePersona::where('FK_PedidoPersona', '=', $this->idPedidoPersona)->delete();
            foreach ($this->listaTallaje as $key => $prenda) {
                //Creamos persona
                ManTallajePersona::create([
                    'TallajeTalla'                 => $prenda['Talla'],
                    'FK_TipoPrenda'                => $prenda['FK_TipoPrenda'],
                    'FK_PedidoPersona'             => $this->idPedidoPersona,
                    'idColor'                      => $prenda['FK_Color'],
                    'FK_PedidoModelo'              => $prenda['FK_PedidoModelo'],
                    'cantidadPrenda'               => $prenda['cantidadPrenda'],

                ]);
            }
            if ($temporal == 1) {

                OpPedidosPersona::where('PedidoPersonaId', '=', $this->idPedidoPersona)->update(['PedPerEstado' => 'EN PROCESO']);
                $this->confirmingTallajeAdd = false;

            } elseif ($temporal == 2) {

                OpPedidosPersona::where('PedidoPersonaId', '=', $this->idPedidoPersona)->update(['PedPerEstado' => 'ESPERA DE PRENDA']);
                $this->confirmingTallajeAdd = false;
            }
            
        }
        //Si hay alguna persona en estado EN PROCESO o ESPERA DE PRENDA, el estado del pedido pasa de GENERADO a EN TALLAJE
        $pedidoPersonas = OpPedidosPersona::where('FK_pedido', '=', $this->idPedido)->get();
        //dd($pedidoPersonas);
        $count = 0;
        foreach ($pedidoPersonas as $item) {
            if($item->PedPerEstado == "EN PROCESO" || $item->PedPerEstado == "ESPERA DE PRENDA") {
                $count++;
            }
        }
        if ($count > 0) {
            OpPedidos::where('PedidoId', '=', $this->idPedido)->update(['PedEstado' => 'EN TALLAJE']);
        }
    }

    public function saveTallaje() {
        try {
        $count = 0;
        $tallaje = OpPedidosPersona::where('FK_pedido', '=', $this->idPedido)->get();
        foreach($tallaje as $item) {
            if ($item->PedPerEstado != "ESPERA DE PRENDA" && $item->PedPerEstado != "INACTIVO") {
                $count++;
            }

        }
        if ($count == 0) {
            OpPedidos::where('PedidoId', '=', $this->idPedido)->update(['PedEstado' => 'GESTION DE PEDIDO']);
            $this->confirmingTallajePersonasAdd = false;

        } else {
            $errorCode = 'No se puede guardar, todas las personas deben tener estado "ESPERA DE PRENDA"';
            $this->dispatchBrowserEvent('abrirMsjeFallido6', ['error' => $errorCode]);
        }
    } catch (QueryException $e) {
        //$this->inicializando = false;
        $errorCode = $e->errorInfo[1];
        //$this->emitUp('OtAgregado'); //refrescamos la vista padre, tabla Ot para que aparezca la ot agregada
        $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);
    }

    }

    public function AgregaTallaje()
    {
        
        $count = 2;
        $this->validateOnly('tipoPrenda');
        $this->validateOnly('codigoModeloPedido');
        $this->validateOnly('modeloPedido');
        $this->validateOnly('tallaPrenda');
        $this->validateOnly('colorPrenda');
        $this->validateOnly('cantidadPrenda');
 
            if ($count > 0) {
                array_push($this->listaTallaje, [
                    'Tipo'                                      => $this->tipoPrenda,
                    'CodigoModelo'                              => $this->codigoModeloPedido,
                    'Modelo'                                    => $this->modeloPedido,
                    'Talla'                                     => $this->tallaPrenda,
                    'Color'                                     => $this->colorPrenda,
                    'FK_TipoPrenda'                             => $this->idTipoPrenda,
                    'FK_PedidoModelo'                           => $this->idCodigoModeloPedido,
                    'FK_Color'                                  => $this->idColorPrenda,
                    'cantidadPrenda'                            => $this->cantidadPrenda,
                ]);
            //dd($this->listaTallaje);
        } else {
            $errorCode = 'No se puede agregar tallaje ya hecho';
            $this->dispatchBrowserEvent('abrirMsjeFallido4', ['error' => $errorCode]);
            $this->reset([
                'tipoPrenda', 'codigoModeloPedido', 'modeloPedido', 'tallaPrenda', 'colorPrenda', 'cantidadPrenda'
            ]);
        }
        $this->reset([
            'tipoPrenda', 'codigoModeloPedido', 'modeloPedido', 'tallaPrenda', 'colorPrenda', 'cantidadPrenda'
        ]);
    }

    public function seleccionados($seleccionados_form)
    {
        $this->seleccionados = $seleccionados_form;
    }

    public function seleccionadosColor($seleccionadosColor_form)
    {
        $this->seleccionadosColor = $seleccionadosColor_form;
    }

    public function render()
    {
        $this->todayDate = Carbon::now()->format('Y-m-d');

        $pedidos = OpPedidos::select('op_pedidos.*', 'users.*')
            ->leftjoin('users', 'op_pedidos.FK_user', '=', 'id')
            ->orderBy($this->sortBy, $this->sortAsc ?  'ASC' : 'DESC');


        $fecha = Carbon::createFromFormat('Y-m-d', $this->fecha);    
        $fechaFinal = $fecha->subMonths(3);
        $fechaAux = date('d-m-Y');

        if ($this->fecha !== "") {
            $pedidos->whereDate('PedidoFechaCreacion', '>=', $fechaFinal);
            $pedidos->whereDate('PedidoFechaCreacion', '<=', $this->fecha);

        } else {
            $pedidos->whereDate('PedidoFechaCreacion', 'LIKE', "%{$fechaAux}%");
            
        }

        if ($this->estado_pick != "") {
            $pedidos->where('PedEstado', '=', $this->estado_pick);
        }

        if ($this->search != "") {
            $pedidos->where(function ($query) {
                $query->where('PedTitulo', 'like', '%' . $this->search . '%')
                    ->orWhere('PedDescripcion', 'like', '%' . $this->search . '%')
                    ->orWhere('PedEstado', 'like', '%' . $this->search . '%');
            });
        }

        $this->reset(['borrador', 'generado', 'en_tallaje', 'gestion_pedido']);
        $estados = $pedidos->get()->countBy('PedEstado');
        foreach ($estados as $estado => $total) {
            switch ($estado) {
                case "BORRADOR":
                    $this->borrador = $total;
                    break;
                case "GENERADO":
                    $this->generado = $total;
                    break;
                case "EN TALLAJE":
                    $this->en_tallaje = $total;
                    break;
                case "GESTION DE PEDIDO":
                    $this->gestion_pedido = $total;
                    break;
            }
        }

        $pedidos = $pedidos->paginate(10);
        

    $pedidosCliente = OpPedidos::select('op_pedidos.*', 'users.*')
        ->leftjoin('users', 'op_pedidos.FK_user', '=', 'id')
        ->where('FK_user', '=', auth()->user()->id)
        ->orderBy($this->sortBy1, $this->sortAsc1 ?  'ASC' : 'DESC');



    $fecha1 = Carbon::createFromFormat('Y-m-d', $this->fecha);    
    $fechaFinal1 = $fecha1->subMonths(3);
 
    $fechaAux1 = date('Y-m-d');

    if ($this->fecha1 !== "") {
        $pedidosCliente->whereDate('PedidoFechaCreacion', '>=', $fechaFinal1);
        $pedidosCliente->whereDate('PedidoFechaCreacion', '<=', $this->fecha1);
    } else {
        $pedidosCliente->whereDate('PedidoFechaCreacion', 'LIKE', "%{$fechaAux1}%");
    }

    if ($this->estado_pick1 != "") {
        $pedidosCliente->where('PedEstado', '=', $this->estado_pick1);
    }

    if ($this->search1 != "") {
        $pedidosCliente->where(function ($query) {
            $query->where('PedTitulo', 'like', '%' . $this->search1 . '%')
                ->orWhere('PedDescripcion', 'like', '%' . $this->search1 . '%')
                ->orWhere('PedEstado', 'like', '%' . $this->search1 . '%');
        });
    }

    $this->reset(['borrador1', 'generado1', 'en_tallaje1', 'gestion_pedido1']);
    $estados1 = $pedidosCliente->get()->countBy('PedEstado');
    foreach ($estados1 as $estado => $total) {
        switch ($estado) {
            case "BORRADOR":
                $this->borrador1 = $total;
                break;
            case "GENERADO":
                $this->generado1 = $total;
                break;
            case "EN TALLAJE":
                $this->en_tallaje1 = $total;
                break;
            case "GESTION DE PEDIDO":
                $this->gestion_pedido1 = $total;
                break;
        }
    }

    $pedidosCliente = $pedidosCliente->paginate(10);
    //dd($pedidosCliente);


        //$pedidosAdmin = OpPedidos::all()->orderBy($this->sortBy, $this->sortAsc ?  'ASC' : 'DESC');

        $universidades = ManUniversidad::all();
        $tipoPrendas = ManTipoPrenda::all();
        $tallaPrendas = ManTallaPrenda::all();
        $modeloPedidos = OpPedidosModelo::select('op_pedidos_modelos.*', 'man_modelos.*')
                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'man_modelos.ModeloId')
                        ->where('FK_Pedido', '=', $this->idPedido)
                        ->get();

        $codigoModelos = OpPedidosModelo::select('op_pedidos_modelos.*', 'man_modelos.*')
                        ->leftjoin('man_modelos', 'op_pedidos_modelos.FK_Modelo', '=', 'ModeloId')
                        ->where('FK_Pedido', '=', $this->idPedido)
                        ->groupBy('man_modelos.ModCodigo')
                        ->orderBy('man_modelos.ModCodigo', 'desc')->get();
        $pedidoPersonas = OpPedidosPersona::where('FK_pedido', '=', $this->idPedido)->get();
        $pedidosExterno = ManPedidosExterno::where('FK_pedido', '=', $this->idPedido)->get();
        $pedido1 = OpPedidos::where('PedidoId', '=', $this->idPedido)->get();
        //dd($pedido1);
        $carreras = ManCarrera::all();
        $modelos = ManModelo::all();
        $colores = ManColor::where('FK_Modelo', '=', $this->seleccionados)->get();

        //$modeloColores = ManModeloColor::select('man_colors.Color', 'man_colors.Estado')
        //->join('man_colors', 'man_modelo_colors.FK_Color', '=', 'man_colors.ColorId')
        //->where('FK_Modelo', '=', $this->seleccionados)->get();


        return view('livewire.pedidos', [
            'pedidos'                   => $pedidos,
            'pedidosCliente'            => $pedidosCliente,
            'modelos'                   => $modelos,
            'colores'                   => $colores,
            'universidades'             => $universidades,
            'tipoPrendas'               => $tipoPrendas,
            'tallaPrendas'              => $tallaPrendas,
            'carreras'                  => $carreras,
            //'modeloColores'           => $modeloColores,
            'listaPersona'              => $this->listaPersona,
            'listaVinculacion'          => $this->listaVinculacion,
            'listaModeloPedido'         => $this->listaModeloPedido, 
            'modeloPedidos'             => $modeloPedidos,
            'codigoModelos'             => $codigoModelos,
            'pedidoPersonas'            => $pedidoPersonas,
            'pedido1'                   => $pedido1,
            'listaTallaje'              => $this->listaTallaje,
            'listaColor'                => $this->listaColor,
            'listaSumatoria'            => $this->listaSumatoria,
            'pedidosExterno'            => $pedidosExterno,

        ]);
    }
}
