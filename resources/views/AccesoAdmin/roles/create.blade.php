@extends('adminlte::page')

@section('title', 'Sistema Otec')

@section('content_header')
    <h1>Crear nuevo rol</h1>
@stop

@section('content')
   <div class="card">
       <div class="card-body">
            {!! Form::open(['route' => 'accesoadmin.roles.store'])!!}  
                @include('AccesoAdmin.roles.partials.form')
            {!! Form::submit('Crear Rol', ['class' => 'btn btn-primary mt-2']) !!}
            {!! Form::close()!!}  
       </div>
   </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop