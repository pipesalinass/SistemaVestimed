@extends('adminlte::page')

@section('title', 'Sistema Kam')

@section('content_header')
    <h1>Visualizar usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::model($user) !!}
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="name_user">Nombre</label>
                        {!! Form::text('name', null, array('class' => 'form-control', 'readonly' => 'true')) !!}
                        @error('name')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeNameUser">{{ $message }}</span>
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="email_user">Email</label>
                        {!! Form::text('email', null,  array('class' => 'form-control', 'readonly' => 'true')) !!}
                        @error('email')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeEmailUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="NumeroContacto_user">Numero Contacto</label>
                        {!! Form::text('NumeroContacto', null,  array('class' => 'form-control', 'readonly' => 'true')) !!}
                        @error('NumeroContacto')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeNumeroContactoUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="roles_user">Rol</label>
                        {!! Form::select('roles', [null => 'Seleccionar rol'] + $roles, $userRole,  array('class' => 'form-control', 'readonly' => 'true')) !!}
                        @error('roles')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeRolesUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
            </div>
            <br>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet"  href="{{ asset('/css/admin_custom.css')}}">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
