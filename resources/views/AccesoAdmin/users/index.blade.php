@if (auth()->user()->EstadoContacto == "Activo")
@can('Ver usuarios')
@extends('adminlte::page')

@section('title', 'Sistema Vestimed')

@section('content_header')
    <h1>Lista de usuarios</h1>
@stop

@section('content')
    @livewire('admin-users')
@stop


@section('css')
    <link rel="stylesheet"  href="{{ asset('/css/admin_custom.css')}}">
    <link rel="stylesheet" href="{{asset('css/my_css.css')}}">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
@endcan
@endif