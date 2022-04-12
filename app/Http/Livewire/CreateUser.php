<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\Database\QueryException;

class CreateUser extends Component
{
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

            if (isset($this->user->id)) {
                $this->user->save();
            } else {
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'NumeroContacto' => $this->NumeroContacto,
            'EstadoContacto' => $this->EstadoContacto,

        ]);

        $this->emit('render');
    }

        }catch (QueryException $e) {
            //$this->inicializando = false;
            $errorCode = $e->errorInfo[1];
            $this->dispatchBrowserEvent('abrirMsjeFallido', ['error' => $errorCode]);         
            }
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}
