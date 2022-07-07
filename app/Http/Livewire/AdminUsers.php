<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;



use Livewire\WithPagination;

class AdminUsers extends Component
{
    use WithPagination;

    protected $paginationTheme = "bootstrap";
    protected $lesteners = ['refreshParent'];

    public $user;
    public $idUser;

    public $search;
    public $sort = 'id';
    public $direction ='desc';

    public $prompt;

    public $name, $email, $NumeroContacto, $password, $EstadoContacto;

    protected $rules = [
        'name'                                      => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉáÍíÓóÚú]+$/'],//nombrePersonaPedido viene desde user creado
        'NumeroContacto'                            => ['required', 'numeric', 'regex:/^[0-9]+$/'],
        'EstadoContacto'                            => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉáÍíÓóÚú]+$/'],//nombrePersonaPedido viene desde user creado
        'email'                                     => ['required', 'email'],
        'password'                                  => ['required', 'string', 'regex:/^[A-Za-z0-9Ññ\s\.\ÁáÉáÍíÓóÚú]+$/'],//nombrePersonaPedido viene desde user creado

    ];

    protected $messages = [
        'name.required'                             =>  'Debe ingresar un nombre para la persona.',
        'name.string'                               =>  'El campo debe ser alfa numérico',
        'name.regex'                                =>  'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'NumeroContacto.required'                   => 'Debe ingresar un número de contacto asoaciado a la persona.',
        'NumeroContacto.numeric'                    => 'El número de contacto debe ser alfa numérico.',
        'NumeroContacto.regex'                      => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'EstadoContacto.required'                   => 'Debe ingresar un estado (Activo / Inactivo).',
        'EstadoContacto.string'                     => 'El nombre del estado debe ser alfa numérico.',
        'EstadoContacto.regex'                      => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

        'email.required'                            => 'Debe ingresar un email válido.',
        'email.string'                              => 'El nombre del codigo debe ser alfa numérico.',
        
        'password.required'                         => 'Debe ingresar un password.',
        'password.string'                           => 'El password debe ser alfa numérico.',
        'password.regex'                            => 'El campo solamente acepta mayúsculas, minúsculas, espacios, "."',

    ];

    public function updatedName($name_form) {
        $this->validateOnly("name");
    }

    public function updatedEmail($email_form) {
        $this->validateOnly("email");
    }

    public function updatedNumeroContacto($numeroContacto_form) {
        $this->validateOnly("NumeroContacto");
    }

    public function updatePassword($password_form) {
        $this->validateOnly("password");
    }

    public function updatedEstadoContacto($estadoContacto_form) {
        $this->validateOnly("EstadoContacto");
    }

    public function save(){
        
        try {

            $this->validateOnly('name');
            $this->validateOnly('NumeroContacto');
            $this->validateOnly('EstadoContacto');
            $this->validateOnly('email');
            $this->validateOnly('password');

            if($this->idUser > 0) {
                User::where('id', '=', $this->idUser)->update(['name'=>$this->name]);
                User::where('id', '=', $this->idUser)->update(['NumeroContacto'=>$this->NumeroContacto]);
                User::where('id', '=', $this->idUser)->update(['EstadoContacto'=>$this->EstadoContacto]);
                User::where('id', '=', $this->idUser)->update(['email'=>$this->email]);
                User::where('id', '=', $this->idUser)->update(['password'=>Hash::make($this->password)]);

            }else {
            User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'NumeroContacto' => $this->NumeroContacto,
            'EstadoContacto' => $this->EstadoContacto,

        ]);

        $this->emit('render');
    }
        
        $this->reset('name', 'NumeroContacto', 'EstadoContacto', 'email', 'password');

        }catch (QueryException $e) {
            //$this->inicializando = false;
            $errorCode = $e->errorInfo[1];
            $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);         
            }
    }

    public function refreshParent(){
        $this->prompt = "The parent has be refresh"; 
    }

    public function limpiar_page(){
        $this->reset('page');
    }

    public function order($sort){
        if ($this->sort == $sort) {
            if ($this->direction== 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }   
        } else {
            $this->sort = $sort;
            $this->direction = 'asc';
        }
        
    }

    public function desactivarUsuario ($id) {
        User::where('id', '=', $id)->update(['EstadoContacto'=>'Inactivo']);
    }

    public function activarUsuario ($id) {
        User::where('id', '=', $id)->update(['EstadoContacto'=>'Activo']);
    }

    public function editPersona(User $user) {
        //dd($user);
        $this->user = $user;
        $this->idUser = $user->id;
        $usuario = User::where('id', '=', $user->id)->first();

        if(isset($this->user->id)) {
        
        $this->name = $usuario->name;
        $this->NumeroContacto = $usuario->NumeroContacto;
        $this->EstadoContacto = $usuario->EstadoContacto;
        $this->email = $usuario->email;

        
        }
 
    }

    public function render()
    {
        $user = User::where('id', auth()->user()->id)->with('roles')->first();
        $roles = $user->getRoleNames()->first();
        if($roles == "SuperAdministrador") {
            $users = User::where('name', 'LIKE', '%'. $this->search . '%')
            ->orWhere('email', 'LIKE', '%'. $this->search . '%')
            ->orderBy($this->sort, $this->direction)
            ->paginate(5);
        } else {
            $users = User::where('EstadoContacto', '=', 'Activo')
            ->orderBy($this->sort, $this->direction)
            ->paginate(5);
        }
        return view('livewire.admin-users',compact('users'));
    }

}
