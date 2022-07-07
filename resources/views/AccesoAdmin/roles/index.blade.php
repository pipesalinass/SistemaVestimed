@if (auth()->user()->EstadoContacto == "Activo")
@can('Ver usuarios')
@extends('adminlte::page')

@section('title', 'Sistema Vestimed')

@section('content_header')
    <h1>Lista de roles</h1>
@stop

@section('content')

    @if (session('info'))
    <div class="alert alert-primary" role="alert">
        <strong>¡Éxito!</strong> {{session('info')}}
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{route('accesoadmin.roles.create')}}"> Crear un nuevo rol</a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th Colspan="2"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td>{{$role->id}}</td>
                            <td>{{$role->name}}</td>
                            <td width = "10px">
                                <a class="btn btn-secondary" href="{{route('accesoadmin.roles.edit', $role)}}">Editar</a>
                            </td>
                            <td width = "10px">
                                <form action="{{route('accesoadmin.roles.destroy',$role)}}" method="POST">
                                    @method('delete')
                                    @csrf
                                    <button class="btn btn-danger" type="submit">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                No hay ningun rol registrado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
@endcan
@endif