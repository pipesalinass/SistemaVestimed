@extends('adminlte::page')

@section('title', 'Sistema Kam')

@section('content_header')
    <h1>Registrar usuario</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(array('route' => 'accesoadmin.users.store', 'method' => 'POST')) !!}
            <div class="row">
                <div class="col-8">
                    <div class="form-group">
                        <label for="name_user">Nombre</label>
                        {!! Form::text('name', null,  array('class' => 'form-control')) !!}
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
                        {!! Form::text('email', null,  array('class' => 'form-control')) !!}
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
                        <label for="password_user">Contraseña</label>
                        {!! Form::password('password',  array('class' => 'form-control')) !!}
                        @error('password')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajePasswordUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="confirm-password_user">Confirmar Contraseña</label>
                        {!! Form::password('confirm-password', array('class' => 'form-control')) !!}
                        @error('confirm-password')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeConfirmPasswordUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="NumeroContacto_user">Numero Contacto</label>
                        {!! Form::text('NumeroContacto', null,  array('class' => 'form-control')) !!}
                        @error('NumeroContacto')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeNumeroContactoUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="roles_user">Rol</label>
                        {!! Form::select('roles', [null => 'Seleccionar rol'] + $roles, [], array('class' => 'form-control')) !!}
                        @error('roles')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeRolesUser">{{ $message }}</span>
                        </div>
                    @enderror
                    </div>
                </div>
            </div>
            <br>
            <div class="action-buttons">
                <button type="submit" class="btn btn-md btn-success">Guardar</button>
            </div>
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
