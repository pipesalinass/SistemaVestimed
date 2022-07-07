@if (auth()->user()->EstadoContacto == "Activo")
@can('Ver pedidos admin')
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="m-3">
            <!-- Content Header (Page header) -->
            <div class="content-header border-bottom border-primary ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-4 mb-2">
                            <h1 class="m-0">Gestión de Pedidos</h1>
                        </div><!-- /.col -->
                        <div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input wire:model="fecha"type="date" class="form-control float-right" id="fecPrincipal">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                                <!-- Cambio, saca right -->
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    @can('Hacer pedidos')
                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect px-3"
                                        wire:click="confirmPedidoAdd">
                                        <i class=" fas fa-plus pr-2"></i><span>Nuevo</span>
                                    </button>
                                    @endcan         
                                </div>
                                <!-- /.col -->
                                <!-- Cambio, saca right -->
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    <button type="button" class="btn btn-outline-primary"
                                        wire:click="refrescar">
                                        <i class="fas fa-redo"></i>
                                    </button>   
                                </div>
                                <!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content mt-3 border-bottom border-primary">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="borradorClick"
                                wire:click="changeEstado('BORRADOR')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Borrador</span>
                                <span class="info-box-number" id="borradora">{{$borrador}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1 text-white" style="cursor:pointer"
                                id="generadoClick"
                                wire:click="changeEstado('GENERADO')"><i class="far fa-thumbs-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Generados</span>
                                <span class="info-box-number" id="generada">{{$generado}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="enTallajeClick"
                                wire:click="changeEstado('EN TALLAJE')"><i class="fas fa-tasks text-white"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">En Tallaje</span>
                                <span class="info-box-number" id="enTallaje">{{$en_tallaje}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1" style="cursor:pointer" id="CerradosClick"
                                id="gestionPedidoClick"
                                wire:click="changeEstado('GESTION DE PEDIDO')"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Gestión de Pedido</span>
                                <span class="info-box-number" id="gestionPedido">{{$gestion_pedido}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>
            </div>
        </section>

        <!-- /.content-header -->
        <section class="content mt-3">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title col-5">Pedidos Realizados</h3>
                                <div class="card-tools col-6" style="display: flex; justify-content: flex-end;">
                                    <div class="input-group input-group-sm col-10">
                                        <input type="text" name="table_search" class="form-control float-right"
                                            placeholder="Buscar" id="buscarTabla" wire:model="search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"
                                                    wire:model="search"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover responsive table-sm display text-nowrap table-striped"
                                id="tbOT">
                                <thead style="text-align: center">
                                    <tr>
                                        <th Colspan="1">Acción</th>
                                        <th>
                                            <button wire:click="sortBy('PedidoId')">
                                                Número de Pedido
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('PedTitulo')">
                                                Título
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('PedDescripcion')">
                                                Descripción
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('PedidoFechaCreacion')">
                                                Fecha
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('PedEstado')">
                                                Estado
                                            </button>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidos as $key => $pedido)
                                    @if ($pedido->PedEstado != 'INACTIVO')                

                                    <tr>
                                        <td class="rounded border px-4 py-2" style="text-align: center">
                                            <a wire:click="confirmPedidoView( {{ $pedido->PedidoId }} )" title="Visualizar Pedido" style="cursor:pointer"><i class="fas fa-eye" style="color:#0a6ed3" title="Ver Pedido"></i></a>

                                            @can('Editar pedidos')                                            
                                            @if ($pedido->PedEstado == 'GENERADO' || $pedido->PedEstado ==  'BORRADOR' || $pedido->PedEstado == 'EN TALLAJE')
                                            |
                                            <a wire:click="confirmPedidoEdit( {{ $pedido->PedidoId }} )" title="Editar Pedido" style="cursor:pointer" ><i class="fas fa-edit" style="color:#0a6ed3"></i></a> 
                                            @endif
                                            @endcan
                                            
                                            @if ($pedido->PedEstado == 'GENERADO' || $pedido->PedEstado == 'EN TALLAJE' ||$pedido->PedEstado == 'GESTION DE PEDIDO')
                                            @can('Ver modelos')                                            
                                            | 
                                            <a wire:click="confirmModeloAdd( {{ $pedido->PedidoId }} )" title="Agregar Modelo" style="cursor:pointer"><i class="fas fa-plus-circle" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                            @if ($pedido->PedEstado == 'GENERADO' || $pedido->PedEstado == 'EN TALLAJE' || $pedido->PedEstado == 'GESTION DE PEDIDO'|| $pedido->PedEstado == 'ESPERA DE PRENDAS' || $pedido->PedEstado == 'RECEPCION_PARCIAL' || $pedido->PedEstado == 'RECEPCION_FINALIZADA' || $pedido->PedEstado == 'RECIBE DE BORDADO' || $pedido->PedEstado == 'ENTREGADO' || $pedido->PedEstado == 'EN BORDADO')
                                            @can('Realizar Tallajes')   
                                            |       
                                            <a wire:click="confirmPersonaTallaje( {{ $pedido->PedidoId }} )" title="Agregar Tallaje" style="cursor:pointer"><i class="fas fa-user-tie" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                            @if ($pedido->PedEstado == 'GESTION DE PEDIDO'|| $pedido->PedEstado == 'ESPERA DE PRENDAS' || $pedido->PedEstado == 'RECEPCION_PARCIAL' || $pedido->PedEstado == 'RECEPCION_FINALIZADA' || $pedido->PedEstado == 'RECIBE DE BORDADO' || $pedido->PedEstado == 'ENTREGADO' || $pedido->PedEstado == 'EN BORDADO')
                                            @can('Realizar Tallajes')   
                                            |       
                                            <a wire:click="confirmSumatoria( {{ $pedido->PedidoId }} )" title="Ver Sumatoria" style="cursor:pointer"><i class="fas fa-cart-plus" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                            @if ($pedido->PedEstado == 'GESTION DE PEDIDO' || $pedido->PedEstado == 'ESPERA DE PRENDAS' || $pedido->PedEstado == 'RECEPCION_PARCIAL' || $pedido->PedEstado == 'RECEPCION_FINALIZADA' || $pedido->PedEstado == 'RECIBE DE BORDADO' || $pedido->PedEstado == 'ENTREGADO' || $pedido->PedEstado == 'EN BORDADO')
                                            @can('Realizar Tallajes')   
                                            |       
                                            <a wire:click="confirmAsignarPedido( {{ $pedido->PedidoId }} )" title="Asignar Pedido Externo" style="cursor:pointer"><i class="fas fa-plus-square" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                        </td>                                        
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedidoId}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedTitulo}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedDescripcion}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedidoFechaCreacion->format('d-m-Y')}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedEstado}}</td>

                                    </tr>
                                    @endif                                    
                                    @endforeach                     
                                </tbody>
                            </table>
                        </div>
                            @if ($pedidos->hasPages())
                            <div class="card-footer clearfix" style="background:#eeeeee7c ">
                                {{ $pedidos->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endcan
@can('Ver pedidos')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="m-3">
            <!-- Content Header (Page header) -->
            <div class="content-header border-bottom border-primary ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-4 mb-2">
                            <h1 class="m-0">Gestión de Pedidos</h1>
                        </div><!-- /.col -->
                        <div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="far fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input wire:model="fecha1"type="date" class="form-control float-right" id="fecPrincipal">
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                            <!-- Cambio, saca right -->
                                <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                                    @can('Hacer pedidos')
                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect px-3"
                                        wire:click="confirmPedidoAdd">
                                        <i class=" fas fa-plus pr-2"></i><span>Nuevo</span>
                                    </button>
                                    @endcan
                          
                       
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content mt-3 border-bottom border-primary">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="borradorClick1"
                                wire:click="changeEstado1('BORRADOR')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Borrador</span>
                                <span class="info-box-number" id="borradora1">{{$borrador1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1 text-white" style="cursor:pointer"
                                id="generadoClick1"
                                wire:click="changeEstado1('GENERADO')"><i class="far fa-thumbs-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Generados</span>
                                <span class="info-box-number" id="generada1">{{$generado1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="enTallajeClick1"
                                wire:click="changeEstado1('EN TALLAJE')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">En Tallaje</span>
                                <span class="info-box-number" id="enTallaje1">{{$en_tallaje1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1" style="cursor:pointer" id="CerradosClick"
                                id="gestionPedidoClick1"
                                wire:click="changeEstado1('GESTION DE PEDIDO')"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Gestión de Pedido</span>
                                <span class="info-box-number" id="gestionPedido1">{{$gestion_pedido1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>
            </div>
        </section>

        <!-- /.content-header -->
        <section class="content mt-3">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title col-5">Pedidos Realizados</h3>
                                <div class="card-tools col-6" style="display: flex; justify-content: flex-end;">
                                    <div class="input-group input-group-sm col-10">
                                        <input type="text" name="table_search1" class="form-control float-right"
                                            placeholder="Buscar" id="buscarTabla1" wire:model="search1">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"
                                                    wire:model="search1"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <table class="table table-hover responsive table-sm display text-nowrap table-striped"
                                id="tbOT">
                                <thead style="text-align: center">
                                    <tr>
                                        <th Colspan="1">Acción</th>
                                        <th>
                                            <button wire:click="sortBy1('PedTitulo')">
                                                Título
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy1('PedDescripcion')">
                                                Descripción
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy1('PedidoFechaCreacion')">
                                                Fecha
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy1('PedEstado')">
                                                Estado
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pedidosCliente as $key => $pedido)
                                    @if ($pedido->PedEstado != 'INACTIVO')   
                                    <tr>
                                        <td class="rounded border px-4 py-2" style="text-align: center">
                                            <a wire:click="confirmPedidoView( {{ $pedido->PedidoId }} )" title="Visualizar Pedido" style="cursor:pointer"><i class="fas fa-eye" style="color:#0a6ed3" title="Ver Pedido"></i></a>
                                            @if ($pedido->PedEstado == 'BORRADOR')
                                            @can('Editar pedidos')                                            
                                            |                                            
                                            <a wire:click="confirmPedidoEdit( {{ $pedido->PedidoId }} )" title="Editar Pedido" style="cursor:pointer"><i class="fas fa-edit" style="color:#0a6ed3"></i></a>        

                                            @endcan
                                            @endif

                                            @if ($pedido->PedEstado != 'TALLAJE LISTO')
                                            @can('Ver modelos')                                            
                                            | 
                                            <a wire:click="confirmModeloAdd( {{ $pedido->PedidoId }} )" title="Agregar Modelo" style="cursor:pointer"><i class="fas fa-plus-circle" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                            @if ($pedido->PedEstado == 'GENERADO' || 'EN TALLAJE')
                                            @can('Realizar Tallajes')   
                                            |       
                                            <a wire:click="confirmPersonaTallaje( {{ $pedido->PedidoId }} )" title="Agregar Tallaje" style="cursor:pointer"><i class="fas fa-user-tie" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                            @if ($pedido->PedEstado == 'GESTION DE PEDIDO')
                                            @can('Realizar Tallajes')   
                                            |       
                                            <a wire:click="confirmSumatoria( {{ $pedido->PedidoId }} )" title="Ver sumatoria" style="cursor:pointer"><i class="fas fa-user-tie" style="color:#0a6ed3"></i></a>
                                            @endcan
                                            @endif
                                        </td>                                        
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedTitulo}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedDescripcion}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedidoFechaCreacion->format('d-m-Y')}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$pedido->PedEstado}}</td>

                                    </tr>
                                    @endif    
                                @endforeach                     
                                </tbody>
                            </table>
                            @if ($pedidos->hasPages())
                            <div class="card-footer clearfix" style="background:#eeeeee7c ">
                                {{ $pedidos->links() }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endcan

<!-- Create Pedido  Modal -->
<x-dialog-modal-general wire:model="confirmingPedidoAdd" >
    <x-slot name="title" style="background: #2c3b4a; color:white">
        {{ isset($this->pedido->PedidoId) ? 'Editar Pedido' : 'Formulario Pedido' }}
        <button type="button" class="ml-2 mb-1 close" wire:click="$toggle('confirmingPedidoAdd', false)" aria-label="Close"
        style="color:white">
        <span aria-hidden="true">&times;</span>
    </x-slot>

    <x-slot name="content">
        <form>
            <!-- Información cliente -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información cliente</legend>
                <!-- Fecha Ingreso -->
                <div class="parent-grid-row-1">
                    <div class="form-group">
                        <label for="fecPrincipal">Fecha Ingreso:</label>
                        <input wire:model="fechaCreacion" type="date" class="form-control float-right" id="fecPrincipal" name="fecPrincipal"
                            readonly>
                    </div>
                    <!-- Nombre -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            wire:model="nombrePedido" placeholder="Ingrese Nombre" readonly>
                        @error('nombrePedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudNombrePedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!--Telefono -->
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input type="numeric" class="form-control" id="celular" name="celular"
                            wire:model="celularPedido" placeholder="Ingrese Celular (9 xxxx xxxx)" readonly>
                        @error('celularPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCelularPedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!-- Titulo -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                            wire:model="tituloPedido" placeholder="Ingrese Título">
                        </input>
                        @error('tituloPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudTituloPedido">{{$message}}</span>
                            </div>
                        @enderror                        
                    </div>
                    <!-- Universidad -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="universidad">Universidad:</label>
                        <select class="form-control" id="universidad" name="universidad"
                            wire:model="universidadPedido">
                            @foreach ($universidades as $key => $uni)
                                <option> {{$uni->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('universidadPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudUniversidadPedido">{{$message}}</span>
                            </div>
                        @enderror                            
                    </div>
                    <!-- Carrera -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control" id="carrera" name="carrera"
                            wire:model="carreraPedido">
                            @foreach ($carreras as $key => $car)
                                <option> {{$car->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('carreraPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCarreraPedido">{{$message}}</span>
                            </div>
                        @enderror                         
                    </div>
                </div>
                <!-- Descripcion -->
                <div class="form-row">
                    <div class="form-group col-md-6" style="display: flex; flex-direction: column;">
                        <label for="descripcion">Descripción:</label>
                        <textarea type="area" class="form-control" id="descripcion" name="descripcion"
                            wire:model="descripcionPedido"
                            placeholder="Ingrese Descripción del pedido">
                        </textarea>
                        @error('descripcionPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudDescripcionPedido">{{$message}}</span>
                            </div>
                        @enderror                          
                    </div>
                </div>
            </fieldset>

            <!--detalle PERSONAS -->
            <div class="card direct-chat direct-chat-primary mt-2" id="detalleExamen">
                <div class="card-header" id="bard-header-examenes">
                    <h3 class="card-title">Detalle Personas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="bard-header-examenes">

                    <div class="form-row ml-2 mt-2">                    
                        <div class="form-group col-md-3">
                            <label for="pNombre">Primer Nombre</label>
                            <Input type="text" class="form-control" id="pNombre" name="pNombre"
                                wire:model="pNombrePedidoPersona"  
                                placeholder="Ingrese Primer Nombre">
                            <input type="hidden" id="idExamen">
                            @error('pNombrePedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudPNombrePedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                            
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sNombre">Segundo Nombre</label>
                            <input type="text" class="form-control" id="sNombre" name="sNombre"
                                wire:model="sNombrePedidoPersona" 
                                placeholder="Ingrese Segundo Nombre">
                            @error('sNombrePedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudSNombrePedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                                  
                        </div>
                        <div class="form-group col-md-3">
                            <label for="pApellido">Primer Apellido</label>
                            <input type="text" class="form-control" id="pApellido" name="pApellido"
                                wire:model="pApellidoPedidoPersona" 
                                placeholder="Ingrese Primer Apellido">
                            @error('pApellidoPedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudPApellidoPedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                                 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="sApellido">Segundo Apellido</label>
                            <input type="text" id="ObserExam" class="form-control" id="sApellido" name="sApellido"
                                wire:model="sApellidoPedidoPersona" 
                                placeholder="Ingrese Segundo Apellido">
                            @error('sApellidoPedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudSApellidoPedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                                 
                        </div>
                        <div class="form-group col-md-3">
                            <label for="rutPersona">Rut</label>
                            <Input type="text" class="form-control" id="rutPersona" name="rutPersona"
                                wire:model="rutPedidoPersona"  
                                placeholder="Ingrese Rut de la persona">
                            <input type="hidden" id="idExamen">
                            @error('rutPedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudRutPedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                            
                        </div>                          
                        <div class="form-group col-md-3">
                            <label for="mailPersona">Mail</label>
                            <input type="email" class="form-control" id="mailPersona" name="mailPersona"
                                wire:model="mailPedidoPersona" 
                                placeholder="Ingrese Mail">
                            @error('mailPedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudMailPedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                                  
                        </div>
                        <!-- fix for small devices only -->
                        <div class="clearfix hidden-md-up"></div>
                        <div class="form-group col-md-3">
                            <label for="celularPersona">Celular</label>
                            <input type="numeric" class="form-control" id="celularPersona" name="celularPersona"
                                wire:model="celularPedidoPersona" 
                                placeholder="Ingrese Celular (9 xxxx xxxx)">
                            @error('celularPedidoPersona')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCelularPedidoPersona">{{$message}}</span>
                            </div>
                            @enderror                                   
                        </div>
                        <div class="form-group col-md- align-self-end d-flex align-items-center justify-content-center">
                            @can('Asignar Personas')
                            <button wire:click="AgregaPersonas()" type="button" class="form-control btn btn-primary"><i
                                    class="fa fa-plus" title="Agregar persona"></i></button>
                            @endcan
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                            role="alert" id="alertaCrudErrorPersonas" style="display: none;">
                            <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas"></span>
                            <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <!-- Table row -->    

                    <div class="row">
                        <div class="col-12 table-responsive">
                                
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Acciones</th>
                                            <th class="align-middle col-md-2" style="display: none;">Id User</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Nombre</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Mail</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Celular</th>
                                            @can('Ver estado')
                                            <th class="rounded border px-4 py-2" style="text-align: center">Estado</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @can('Ver estado')
                                        @foreach($listaPersona as $key => $persona)
                                            <tr >
                                                @can('Quitar personas')
                                                <td class="rounded border px-4 py-2" style="text-align: center">  
                                                    <a wire:click="quitarPersona( {{$key}} )" title="Quitar Persona" class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>
                                                    |
                                                    <a wire:click="editarPersona( {{$key}} )" title="Editar Persona" class="ml-2" style="cursor:pointer"><i class="fas fa-edit" style="color:#0a6ed3"></i></a>
                                                </td>
                                                @endcan                                                
                                                <td style="display: none;">{{$persona['rut']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['primerNombre'].' '.$persona['segundoNombre'].' '.$persona['primerApellido'].' '.$persona['segundoApellido']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['mail']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['celular']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['estado']}}</td>                                        
                                            </tr>
                                        @endforeach
                                        @endcan

                                        @can('No Ver estado')
                                        @foreach($listaPersona as $key => $persona)
                                            <tr >
                                                @can('Quitar personas')
                                                <td class="rounded border px-4 py-2" style="text-align: center">  
                                                    <a wire:click="quitarPersona( {{$key}} )" title="Quitar Persona" class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>   
                                                    |
                                                    <a wire:click="editarPersona( {{$key}} )" title="Editar Persona" class="ml-2" style="cursor:pointer"><i class="fas fa-edit" style="color:#0a6ed3"></i></a>
                                                </td>
                                                @endcan                                                
                                                <td style="display: none;">{{$persona['rut']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['primerNombre'].' '.$persona['segundoNombre'].' '.$persona['primerApellido'].' '.$persona['segundoApellido']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['mail']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['celular']}}</td>       
                                            </tr>
                                        @endforeach
                                        @endcan

                                        <div class="form-row">
                                            <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                                role="alert" id="alertaCrudErrorPersonas7" style="display: none;">
                                                <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas7"></span>
                                                <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas7">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>  
                                        <div class="form-row">
                                            <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                                role="alert" id="alertaCrudErrorPersonas" style="display: none;">
                                                <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas"></span>
                                                <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                    </tbody>
                                </table>
                            
                        </div>
                    </div>

                </div>
                @error('examenesAgregados')
                    <div style="text-align:center;" class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudExamenesAgregados">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <!--/.detalle PERSONAS -->
        </form>
    </x-slot>

    <x-slot name="footer">

        <button type="button" class="btn btn-secondary" wire:click="cancelarPedido"
        wire:loading.attr="disabled">
        {{ __('Cancelar') }}
        </button>
        @can('Generar Pedidos')            
        <button wire:click="submit($temp=1)" type="button" class="btn btn-primary" type="submit"
            wire:loading.attr="disabled">
            {{ __('Guardar Temporal ') }}
        </button>

        <button wire:click="submit($temp=2)" type="button" class="btn btn-primary" type="submit"
            wire:loading.attr="disabled">
            {{ __('Guardar') }}
        </button>
        @endcan

       
    </x-slot>
</x-dialog-modal-general>
<!-- Create Pedido  Modal -->

<!-- Edit admin Pedido  Modal -->
<x-dialog-modal-general wire:model="confirmingPedidoEditAdmin" >
    <x-slot name="title" style="background: #2c3b4a; color:white">
        {{ isset($this->pedido->PedidoId) ? 'Editar Pedido' : 'Formulario Pedido' }}
    </x-slot>

    <x-slot name="content">
        <form>
            <!-- Información cliente -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información cliente</legend>
                <!-- Fecha Ingreso -->
                <div class="parent-grid-row-1">
                    <div class="form-group">
                        <label for="fecPrincipal">Fecha Ingreso:</label>
                        <input wire:model="todayDate" type="date" class="form-control float-right" id="fecPrincipal" name="fecPrincipal"
                            readonly>
                    </div>
                    <!-- Nombre -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            wire:model="nombrePedido" placeholder="Ingrese Nombre" readonly>
                        @error('nombrePedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudNombrePedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!--Telefono -->
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input type="numeric" class="form-control" id="celular" name="celular"
                            wire:model="celularPedido" placeholder="Ingrese Celular (9 xxxx xxxx)" readonly>
                        @error('celularPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCelularPedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!-- Titulo -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                            wire:model="tituloPedido" placeholder="Ingrese Título">
                        </input>
                        @error('tituloPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudTituloPedido">{{$message}}</span>
                            </div>
                        @enderror                        
                    </div>
                    <!-- Universidad -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="universidad">Universidad:</label>
                        <select class="form-control" id="universidad" name="universidad"
                            wire:model="universidadPedido">
                            @foreach ($universidades as $key => $uni)
                                <option> {{$uni->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('universidadPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudUniversidadPedido">{{$message}}</span>
                            </div>
                        @enderror                            
                    </div>
                    <!-- Carrera -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control" id="carrera" name="carrera"
                            wire:model="carreraPedido">
                            @foreach ($carreras as $key => $car)
                                <option> {{$car->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('carreraPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCarreraPedido">{{$message}}</span>
                            </div>
                        @enderror                         
                    </div>
                </div>
                <!-- Descripcion -->
                <div class="form-row">
                    <div class="form-group col-md-6" style="display: flex; flex-direction: column;">
                        <label for="descripcion">Descripción:</label>
                        <textarea type="area" class="form-control" id="descripcion" name="descripcion"
                            wire:model="descripcionPedido"
                            placeholder="Ingrese Descripción del pedido">
                        </textarea>
                        @error('descripcionPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudDescripcionPedido">{{$message}}</span>
                            </div>
                        @enderror                          
                    </div>
                </div>
            </fieldset>

            <!--detalle PERSONAS -->
            <div class="card direct-chat direct-chat-primary mt-2" id="detalleExamen">
                <div class="card-header" id="bard-header-examenes">
                    <h3 class="card-title">Detalle Personas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="bard-header-examenes">

                    <!-- Table row -->
                        <div class="row">
                            <div class="col-12 table-responsive">
                                    
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="align-middle col-md-2" style="display: none;">Id User</th>
                                                <th class="rounded border px-4 py-2" style="text-align: center">Nombre</th>
                                                <th class="rounded border px-4 py-2" style="text-align: center">Mail</th>
                                                <th class="rounded border px-4 py-2" style="text-align: center">Celular</th>
                                                @can('Ver estado')
                                                <th class="rounded border px-4 py-2" style="text-align: center">Estado</th>
                                                @endcan
                                                <th class="rounded border px-4 py-2" style="text-align: center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @can('Ver estado')
                                            @foreach ($pedidoPersonas as $item)
                                                <tr >
                                                    <td style="display: none;">{{$item->PedPerRut}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerPrimerNombre.' '.$item->PedPerPrimerApellido.' '.$item->PedPerSegundoApellido}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerMail}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerCelular}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerEstado}}</td>
                                                    @can('Quitar personas')
                                                    <td class="rounded border px-4 py-2" style="text-align: center">  
                                                        <a wire:click="quitarPersonaAdmin({{$item->PedidoPersonaId}})" title="Quitar Persona" class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>   
                                                        |
                                                        <a wire:click="confirmPersonaEdit({{$item->PedidoPersonaId}})" title="Editar Persona" class="ml-2" style="cursor:pointer"><i class="fas fa-edit" style="color:#0a6ed3"></i></a>
                                                    </td>
                                                    @endcan
                                                </tr>
                                            @endforeach
                                            @endcan

                                            @can('No Ver estado')
                                            @foreach ($pedidoPersonas as $item)
                                                @if ($item->PedPerEstado != "INACTIVO")
                                                <tr >
                                                    <td style="display: none;">{{$item->PedPerRut}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerPrimerNombre.' '.$item->PedPerPrimerApellido.' '.$item->PedPerSegundoApellido}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerMail}}</td>
                                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$item->PedPerCelular}}</td>
                                                    @can('Quitar personas')
                                                    <td class="rounded border px-4 py-2" style="text-align: center">  
                                                        <a wire:click="quitarPersonaAdmin({{$item->PedidoPersonaId}})" title="Quitar Persona" class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>   
                                                        |
                                                        <a wire:click="confirmPersonaEdit({{$item->PedidoPersonaId}})" title="Editar Persona" class="ml-2" style="cursor:pointer"><i class="fas fa-edit" style="color:#0a6ed3"></i></a>
                                                    </td>
                                                    @endcan
                                                </tr>
                                                @endif
                                            @endforeach
                                            @endcan

                                            <div class="form-row">
                                                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                                    role="alert" id="alertaCrudErrorPersonas7" style="display: none;">
                                                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas7"></span>
                                                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas7">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>  
                                            <div class="form-row">
                                                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                                    role="alert" id="alertaCrudErrorPersonas" style="display: none;">
                                                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas"></span>
                                                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </tbody>
                                    </table>
                                
                            </div>
                        </div>

                </div>
                @error('examenesAgregados')
                    <div style="text-align:center;" class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudExamenesAgregados">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <!--/.detalle PERSONAS -->
        </form>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarPedidoAdmin"
        wire:loading.attr="disabled">
        {{ __('Volver') }}
        </button>
   
        <button type="button" class="btn btn-primary" wire:click="submitEdit()"
        wire:loading.attr="disabled">
        {{ __('Guardar') }}
        </button>   
    </x-slot>
</x-dialog-modal-general>
<!-- Edit admin Pedido  Modal -->

<!-- Visualizar Pedido  Modal -->
<x-dialog-modal-general wire:model="confirmingPedidoView" >
    <x-slot name="title" style="background: #2c3b4a; color:white">
        {{ __('Visualización Pedido') }}
        <button type="button" class="ml-2 mb-1 close" wire:click="$toggle('confirmingPedidoView', false)" aria-label="Close"
        style="color:white">
        <span aria-hidden="true">&times;</span>
    </x-slot>

    <x-slot name="content">
        <form>
            <!-- Información cliente -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información cliente</legend>
                <!-- Fecha Ingreso -->
                <div class="parent-grid-row-1">
                    <div class="form-group">
                        <label for="fecPrincipal">Fecha Ingreso:</label>
                        <input wire:model="fechaCreacion" type="date" class="form-control float-right" id="fecPrincipal" name="fecPrincipal"
                            readonly>
                    </div>
                    <!-- Nombre -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            wire:model="nombrePedido" placeholder="Ingrese Nombre" readonly>
                        @error('nombrePedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudNombrePedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!--Telefono -->
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input type="numeric" class="form-control" id="celular" name="celular"
                            wire:model="celularPedido" placeholder="Ingrese Celular (9 xxxx xxxx)" readonly>
                        @error('celularPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCelularPedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!-- Titulo -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                            wire:model="tituloPedido" placeholder="Ingrese Título" readonly>
                        </input>
                        @error('tituloPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudTituloPedido">{{$message}}</span>
                            </div>
                        @enderror                        
                    </div>
                    <!-- Universidad -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="universidad">Universidad:</label>
                        <select class="form-control" id="universidad" name="universidad"
                            wire:model="universidadPedido" disabled selected>
                            @foreach ($universidades as $key => $uni)
                                <option> {{$uni->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('universidadPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudUniversidadPedido">{{$message}}</span>
                            </div>
                        @enderror                            
                    </div>
                    <!-- Carrera -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control" id="carrera" name="carrera"
                            wire:model="carreraPedido"  disabled selected>
                            @foreach ($carreras as $key => $car)
                                <option> {{$car->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('carreraPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCarreraPedido">{{$message}}</span>
                            </div>
                        @enderror                         
                    </div>
                </div>
                <!-- Descripcion -->
                <div class="form-row">
                    <div class="form-group col-md-6" style="display: flex; flex-direction: column;">
                        <label for="descripcion">Descripción:</label>
                        <textarea type="area" class="form-control" id="descripcion" name="descripcion"
                            wire:model="descripcionPedido"
                            placeholder="Ingrese Descripción del pedido" readonly>
                        </textarea>
                        @error('descripcionPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudDescripcionPedido">{{$message}}</span>
                            </div>
                        @enderror                          
                    </div>
                </div>
            </fieldset>

            <!--detalle PERSONAS -->
            <div class="card direct-chat direct-chat-primary mt-2" id="detalleExamen">
                <div class="card-header" id="bard-header-examenes">
                    <h3 class="card-title">Detalle Personas</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" id="bard-header-examenes">
                    <div class="form-row">
                        <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                            role="alert" id="alertaCrudErrorPersonas" style="display: none;">
                            <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas"></span>
                            <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            
                                <table class="table table-striped" id="tbUserInternet">
                                    <thead>
                                        <tr>
                                            <th class="align-middle col-md-2" style="display: none;">Id User</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Nombre</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Mail</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Celular</th>
                                            <th class="rounded border px-4 py-2" style="text-align: center">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listaPersona as $key => $persona)
                                            <tr>
                                                <td style="display: none;">{{$persona['rut']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['primerNombre'].' '.$persona['segundoNombre'].' '.$persona['primerApellido'].' '.$persona['segundoApellido']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['mail']}}</td>
                                                <td class="rounded border px-4 py-2" style="text-align: center">{{$persona['celular']}}</td>
                                               
                                                <td class="rounded border px-4 py-2" style="text-align: center">
                                                    {{$persona['estado']}}
                                                </td>
                                           
                                               
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            
                        </div>
                    </div>
                </div>
                @error('examenesAgregados')
                    <div style="text-align:center;" class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudExamenesAgregados">{{ $message }}</span>
                    </div>
                @enderror
            </div>
            <!--/.detalle PERSONAS -->
        </form>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="volverPedidoView"
            wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>


    </x-slot>
</x-dialog-modal-general>
<!-- Visualizar Pedido  Modal -->

<!-- Delete Pedido Modal -->
<x-jet-dialog-modal wire:model="confirmingPedidoDeletion">
    <x-slot name="title">
        {{ __('Eliminar cotización') }}
    </x-slot>

    <x-slot name="content">
        {{ __('¿Estás seguro de que deseas eliminar la cotización?') }}
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingPedidoDeletion', false)">
            {{ __('Cancelar') }}
        </button>

        <button type="button" class="btn btn-danger" class="ml-2"
            wire:click="deletePedido ({{ $confirmingPedidoDeletion }})" wire:loading.attr="disabled">
            {{ __('Eliminar') }}
        </button>
    </x-slot>
</x-jet-dialog-modal>
<!-- Delete Pedido  Modal -->

<!-- Create Modelo Color Modal -->
<x-jet-dialog-modal wire:model="confirmingModeloAdd">
    <x-slot name="title">
        {{ __('Modelo y Color') }}
        <button type="button" class="ml-2 mb-1 close" wire:click="$toggle('confirmingModeloAdd', false)" aria-label="Close"
        style="color:navy">
        <span aria-hidden="true">&times;</span>
    </x-slot>

    <x-slot name="content">
        <!-- Información Modelo -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Modelo</legend>
            <!-- Código Modelo -->
            <div class="form-row">
                <div class="form-group col md-3" style="display: flex; flex-direction: column;">
                    <label for="codigoMod">Código:</label>
                    <input type="text" class="form-control" id="codigoMod"
                        wire:model="codigoModelo" placeholder="Ingrese Código">
                        @error('codigoModelo')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudCodigoModelo">{{$message}}</span>
                        </div>
                        @enderror  
                </div>
                <!-- Modelo -->
                <div class="form-group col md-3" style="display: flex; flex-direction: column;">
                    <label for="mod">Modelo:</label>
                    <input type="text" class="form-control" id="mod"
                        wire:model="nombreModelo" placeholder="Ingrese Modelo">
                        @error('nombreModelo')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudNombreModelo">{{$message}}</span>
                        </div>
                        @enderror  
                </div>
                <!-- Estado Modelo -->
                <div class="form-group col md-3" style="display: flex; flex-direction: column;">
                    <label for="estadoMod">Estado:</label>
                    <select class="form-control" id="estadoMod"
                        wire:model="estadoModelo">
                        <option value="Disponible">Disponible</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estadoModelo')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudEstadoModelo">{{$message}}</span>
                    </div>
                    @enderror  
                </div>
                <div class="form-group col-md-1 align-self-end d-flex align-items-center justify-content-center">
                    @can('Agregar modelos')                        
                    <button wire:click="saveModelo" type="button" class="form-control btn btn-primary"><i
                            class="fa fa-plus" title="Agregar Modelo"></i></button>
                    @endcan
                </div>
            </div>
            <!-- Table row -->
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped" id="tbDetalleOT col-md-2">
                        <thead>
                            <tr>
                                <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Código</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Modelo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($modelos as $key => $mod)
                                @if ($mod->ModEstado == 'Disponible')
                                <tr>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        @can('Anular modelos') 
                                        <a wire:click="quitarModelo( {{$mod->ModeloId}} )" title="Anular Modelo" class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>
                                        @endcan
                                        @can('Editar modelos')                                            
                                        <a wire:click="confirmModeloEdit({{ $mod->ModeloId }})" title="Editar Modelo" class="mr-2" style="cursor:pointer"><i class="fas fa-edit"
                                                style="color:   #0a6ed3 "></i></a>
                                        @endcan                                        
                                        <input class="mb-1 mr-2" type="radio" name="modelo"
                                            wire:click="seleccionados({{$mod->ModeloId}})">
                                    </td>                                    
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $mod->ModCodigo }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $mod->ModNombre }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $mod->ModEstado }} </td>

                                </tr>
                                @endif
                            @endforeach
                            <div class="form-row">
                                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                    role="alert" id="alertaCrudErrorPersonas1" style="display: none;">
                                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas1"></span>
                                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas1">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                    role="alert" id="alertaCrudErrorPersonas2" style="display: none;">
                                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas2"></span>
                                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas2">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>

        <!--detalle Color -->
        <div class="card direct-chat direct-chat-primary mt-2" id="detalleExamen">
            <div class="card-header" id="bard-header-examenes">
                <h3 class="card-title">Detalle Color</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="bard-header-examenes">
                <div class="form-row ml-2 mt-2">
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="form-group col-md-4">
                        <label>Color</label>
                        <input type="text" class="form-control" id="col" wire:model="nombreColor"
                            placeholder="Ingrese Color">
                            @error('nombreColor')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudColor">{{$message}}</span>
                            </div>
                            @enderror 
                    </div>
                    <div class="form-group col-md-4" style="display: flex; flex-direction: column;">
                        <label for="estdo">Estado:</label>
                        <select class="form-control" id="estdo" wire:model="estadoColor">
                            <option value="Disponible">Disponible</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                        @error('estadoColor')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudEstadoColor">{{$message}}</span>
                        </div>
                        @enderror 
                    </div>        
                        <div class="form-group col-md- align-self-end d-flex align-items-center justify-content-center">
                            @can('Agregar colores')                                
                            <button wire:click="saveColor()" type="button" title="Agregar Color" class="form-control btn btn-primary"
                            id="btAddExamen"><i class="fa fa-plus"></i>
                            </button>
                            @endcan
                            <div class="ml-2">
                            @can('Vincular modelo pedido')                                
                            <button wire:click="AgregaVinculacion()" type="button" title="Vincular Color a Pedido" class="form-control btn btn-primary"
                            id="btAddExamen"> Vincular
                            </button>                           
                            @endcan    
                            </div>
                        </div>
                </div>
                <div class="form-row">
                    <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                        role="alert" id="alertaCrudErrorPersonas4" style="display: none;">
                        <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas4"></span>
                        <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas4">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                        role="alert" id="alertaCrudErrorPersonas5" style="display: none;">
                        <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas5"></span>
                        <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas5">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

                <!-- Table row -->
                <div class="row">
                    <div class="table-responsive">

                        <table class="table table-striped" id="tbDetalleOT col-md-2">
                            <thead>
                                <tr>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($colores as $key => $col)
                                    @if ($col->ColEstado == 'Disponible')
                                <tr>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        @can('Anular colores')  
                                        <a wire:click="quitarColor( {{$col->ColorId}} )" title="Anular Color" class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>
                                        @endcan
                                        @can('Editar colores')                                            
                                        <a wire:click="confirmColorEdit({{ $col->ColorId }})" title="Editar Color" class="mr-2" style="cursor:pointer"><i class="fas fa-edit"
                                                style="color:   #0a6ed3 "></i></a>
                                        @endcan
                                        <input class="mb-1" type="radio" name="color" wire:click="seleccionadosColor( {{$col->ColorId}} )">  
                                 
                                    </td>                                    
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $col->ColNombre }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $col->ColEstado }} </td>

                                </tr>
                                    @endif
                                @endforeach                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Modelo y Color asociado a Pedido -->
        <div class="card direct-chat direct-chat-primary mt-2" id="detalleExamen">
            <div class="card-header" id="bard-header-examenes">
                <h3 class="card-title">Modelo y Color asociado a Pedido</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="bard-header-examenes">
                <!-- Table row -->
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-striped" id="tbDetalleOT col-md-2">
                            <thead>
                                <tr>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Código</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Modelo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center; display: none;">FK_Modelo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center; display: none;">FK_Color</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listaVinculacion as $key => $vinculacion)
                                <tr>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        @can('Quitar vinculacion modelo persona')
                                            
                                        <a wire:click="quitarVinculacion( {{$key}} )" title="Quitar Vinculación" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"></i></a>      
                                        @endcan   
                                      
                                    </td>                                    
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $vinculacion['Codigo'] }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $vinculacion['Modelo'] }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $vinculacion['Color'] }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center; display: none;"> {{ $vinculacion['FK_Modelo'] }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center; display: none;"> {{ $vinculacion['FK_Color'] }} </td>

                                </tr>
                                @endforeach                         
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="volverModelo">
            {{ __('Volver') }}
        </button>
        <button type="button" class="btn btn-primary" wire:click="saveVinculacion">
            {{ __('Guardar Vinculación') }}
        </button>
    </x-slot>
</x-jet-dialog-modal>
<!-- Create Modelo Color  Modal -->

<!-- Lista Personas Pedido Tallaje Modal -->
<x-dialog-modal-general wire:model="confirmingTallajePersonasAdd">
    <x-slot name="title">
        {{ __('Tallaje Personas') }}
        <button type="button" class="ml-2 mb-1 close" wire:click="$toggle('confirmingTallajePersonasAdd', false)" aria-label="Close"
        style="color:white">
        <span aria-hidden="true">&times;</span>
    </x-slot>

    <x-slot name="content">
        <!-- Información Modelo -->
        <div class="form-row">
            <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                role="alert" id="alertaCrudErrorPersonas8" style="display: none;">
                <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas8"></span>
                <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas8">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div> 
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Personas</legend>

            <!-- Table row -->
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped" id="tbDetalleOT col-md-2">
                        <thead>
                            <tr>
                                <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Nombre</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Mail</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Celular</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @can('Ver estado')
                            @foreach ($pedidoPersonas as $key => $persona) 
                                <tr>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        <a wire:click="confirmTallajeAdd( {{ $persona->PedidoPersonaId }} )" title="Agregar Prenda" style="cursor:pointer"><i class="fas fa-plus-circle" style="color:#0a6ed3"></i></a>
                                    </td>                                    
                                    <td style="display: none;">{{$persona->PedPerRut}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerPrimerNombre.' '.$persona->PedPerSegundoNombre.' '.$persona->PedPerPrimerApellido.' '.$persona->PedPerSegundoApellido }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerMail }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerCelular }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerEstado }} </td>

                                </tr>      
                            @endforeach
                            @endcan

                            @can('No Ver estado')
                            @foreach ($pedidoPersonas as $key => $persona) 
                                <tr>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        <a wire:click="confirmTallajeAdd( {{ $persona->PedidoPersonaId }} )" title="Agregar Prenda" style="cursor:pointer"><i class="fas fa-plus-circle" style="color:#0a6ed3"></i></a>
                                    </td>                                    
                                    <td style="display: none;">{{$persona->PedPerRut}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerPrimerNombre.' '.$persona->PedPerSegundoNombre.' '.$persona->PedPerPrimerApellido.' '.$persona->PedPerSegundoApellido }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerMail }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerCelular }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $persona->PedPerEstado }} </td>

                                </tr>      
                            @endforeach
                            @endcan

                            <div class="form-row">
                                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                    role="alert" id="alertaCrudErrorPersonas6" style="display: none;">
                                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas6"></span>
                                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas6">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                                    role="alert" id="alertaCrudErrorPersonas2" style="display: none;">
                                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas2"></span>
                                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas2">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>


    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="volverTallaje" wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>
        <button type="button" class="btn btn-primary" wire:click="saveTallaje" wire:loading.attr="disabled">
            {{ __('Guardar definitivo') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- Lista Personas Pedido Tallaje Modal -->

<!-- Prenda Modal -->
<x-dialog-modal-general wire:model="confirmingTallajeAdd">
    <x-slot name="title">
        {{ __('Tallaje Persona') }}
        <button type="button" class="ml-2 mb-1 close" wire:click="$toggle('confirmingTallajeAdd', false)" aria-label="Close"
        style="color:white">
        <span aria-hidden="true">&times;</span>
    </x-slot>

    <x-slot name="content">
        <!-- Información cliente -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Prenda</legend>
            <!-- Fecha Ingreso -->
            <div class="form-row">
                <!-- Titulo -->
                <div class="form-group col-md-4" style="display: flex; flex-direction: column;">
                    <label for="prenda">Tipo de prenda:</label>
                    <select class="form-control" id="prenda" name="prenda"
                    wire:model="tipoPrenda">
                    @foreach ($tipoPrendas as $key => $prenda)
                        <option value="none" selected  hidden>Seleccione</option>
                        <option> {{$prenda->ManNombre}} </option>
                    @endforeach
                    @error('tipoPrenda')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudTipoPrenda">{{$message}}</span>
                    </div>
                    @enderror       
                </select>
                </div>
                <!--Codigo Modelo -->
                <div class="form-group col-md-4">
                    <label for="codModPed">Código Modelo:</label>
                    <select class="form-control" id="codModPed" name="codModPed"
                    wire:model="codigoModeloPedido">
                    @foreach ($codigoModelos as $key => $codigo)
                        <option value="none" selected  hidden>Seleccione</option>
                        <option> {{$codigo->ModCodigo}} </option>
                    @endforeach
                    @error('codigoModeloPedido')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudCodigoModeloPedido">{{$message}}</span>
                    </div>
                    @enderror       
                    </select>
                </div>
                <!--Modelo -->
                <div class="form-group col-md-4">
                    <label for="mod">Modelo:</label>
                    <select class="form-control" id="mod" name="mod"
                    wire:model="modeloPedido" disabled selected>
                    @foreach ($modeloPedidos as $key => $modelo)
                        <option value="none" selected  hidden>Seleccione</option>
                        <option> {{$modelo->ModNombre}} </option>
                    @endforeach
                    @error('modeloPedido')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudModeloPedido">{{$message}}</span>
                    </div>
                    @enderror       
                </select>
                </div>
                <!-- Talla Prenda -->
                <div class="form-group col-md-4" style="display: flex; flex-direction: column;">
                    <label for="talla">Talla:</label>
                    <select class="form-control" id="talla" name="talla"
                    wire:model="tallaPrenda">
                    @foreach ($tallaPrendas as $key => $talla)
                        <option value="none" selected  hidden>Seleccione</option>
                        <option> {{$talla->ManNombre}} </option>
                    @endforeach
                    @error('tallaPrenda')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudTallaPrenda">{{$message}}</span>
                    </div>
                    @enderror       
                    </select>
                </div>
                <!-- Color Prenda -->
                <div class="form-group col-md-4" style="display: flex; flex-direction: column;">
                    <label for="vendedor">Color:</label>
                    <select class="form-control" id="color" name="color" wire:model="colorPrenda">
                        @foreach ($listaColor as $key => $col)
                        <option value="none" selected  hidden>Seleccione</option>
                        <option> {{$col}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-1" style="display: flex; flex-direction: column;">
                    <label for="cant">Cantidad:</label>
                    <select class="form-control" id="cantidad" name="cantidad" wire:model="cantidadPrenda">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        @error('cantidadPrenda')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudCantidadPrenda">{{$message}}</span>
                        </div>
                        @enderror                         
                    </select>
                </div>
                <div class="form-group col-md-1 align-self-end d-flex align-items-center justify-content-center">
                    @can('Agregar prendas')                        
                    <button wire:click="AgregaTallaje()" type="button" class="form-control btn btn-primary"><i
                            class="fa fa-plus" title="Agregar prenda"></i></button>
                    @endcan
                </div>
            </div>
        </fieldset>

        <!-- Table row -->
        <fieldset class="form-group border p-3 scheduler-border">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-striped" id="tbDetalleOT col-md-2">
                    <thead>
                        <legend class="w-auto px-2">Prendas</legend>
                        <tr>
                            <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Código Modelo</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Modelo</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listaTallaje as $key => $tallaje)
                        <tr>
                            <td class="rounded border px-4 py-2" style="text-align: center">
                                <a wire:click="editarPrenda({{ $key }})" title="Editar Prenda" class="mr-2" style="cursor:pointer"><i class="fas fa-edit"
                                    style="color:   #0a6ed3 "></i></a>
                                <a wire:click="quitarPrenda({{ $key }})" title="Quitar Prenda"class="mr-2" style="cursor:pointer"><i class="fas fa-minus-circle text-primary"
                                    style="color:   #0a6ed3 "></i></a>
                            </td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$tallaje['Tipo']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$tallaje['CodigoModelo']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$tallaje['Modelo']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$tallaje['Talla']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$tallaje['Color']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$tallaje['cantidadPrenda']}}</td>


                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        </fieldset >

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarTallajePersona">
            {{ __('Cancelar') }}
        </button>
        <button type="button" class="btn btn-primary" class="ml-2" wire:click="savePrenda($temporal=1)">
            {{ __('Guardar temporal') }}
        </button>
        <button type="button" class="btn btn-primary" class="ml-2" wire:click="savePrenda($temporal=2)">
            {{ __('Guardar definitivo') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- Create Tallaje  Modal -->

<!-- Sumatoria Pedido modal -->
<x-dialog-modal-general wire:model="confirmingSumatoria">
    <x-slot name="title">
        {{ __('Sumatoria Pedido') }}
        <button type="button" class="ml-2 mb-1 close" wire:click="$toggle('confirmingSumatoria', false)" aria-label="Close"
        style="color:white">
        <span aria-hidden="true">&times;</span>
    </x-slot>

    <x-slot name="content">
        <!-- Información Modelo -->

            <!-- Información cliente -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información Pedido</legend>
                <!-- Fecha Ingreso -->
                <div class="parent-grid-row-1">
                    <div class="form-group">
                        <label for="fecPrincipal">Fecha Ingreso:</label>
                        <input wire:model="fechaCreacion" type="date" class="form-control float-right" id="fecPrincipal" name="fecPrincipal"
                            readonly>
                    </div>
                    <!-- Nombre -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre"
                            wire:model="nombrePedido" placeholder="Ingrese Nombre" readonly>
                        @error('nombrePedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudNombrePedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!--Telefono -->
                    <div class="form-group">
                        <label for="celular">Celular:</label>
                        <input type="numeric" class="form-control" id="celular" name="celular"
                            wire:model="celularPedido" placeholder="Ingrese Celular (9 xxxx xxxx)" readonly>
                        @error('celularPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCelularPedido">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                    <!-- Titulo -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="titulo">Título:</label>
                        <input type="text" class="form-control" id="titulo" name="titulo"
                            wire:model="tituloPedido" placeholder="Ingrese Título" readonly>
                        </input>
                        @error('tituloPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudTituloPedido">{{$message}}</span>
                            </div>
                        @enderror                        
                    </div>
                    <!-- Universidad -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="universidad">Universidad:</label>
                        <select class="form-control" id="universidad" name="universidad"
                            wire:model="universidadPedido" disabled selected>
                            @foreach ($universidades as $key => $uni)
                                <option> {{$uni->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('universidadPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudUniversidadPedido">{{$message}}</span>
                            </div>
                        @enderror                            
                    </div>
                    <!-- Carrera -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="carrera">Carrera:</label>
                        <select class="form-control" id="carrera" name="carrera"
                            wire:model="carreraPedido"  disabled selected>
                            @foreach ($carreras as $key => $car)
                                <option> {{$car->ManNombre}} </option>
                            @endforeach
                        </select>
                        @error('carreraPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCarreraPedido">{{$message}}</span>
                            </div>
                        @enderror                         
                    </div>
                </div>
                <!-- Descripcion -->
                <div class="form-row">
                    <div class="form-group col-md-6" style="display: flex; flex-direction: column;">
                        <label for="descripcion">Descripción:</label>
                        <textarea type="area" class="form-control" id="descripcion" name="descripcion"
                            wire:model="descripcionPedido"
                            placeholder="Ingrese Descripción del pedido" readonly>
                        </textarea>
                        @error('descripcionPedido')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudDescripcionPedido">{{$message}}</span>
                            </div>
                        @enderror                          
                    </div>
                </div>
            </fieldset>

            <!-- Table row -->
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped" id="tbDetalleOT col-md-2">
                        <thead>
                            <legend class="w-auto px-2">Sumatoria Prendas</legend>
                            <tr>
                                <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Modelo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listaSumatoria as $key => $sumatoria)
                            <tr >
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['TipoPrenda']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ModCodigo']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ModNombre']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['TallajeTalla']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ColorPrenda']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['sumatoria']}}</td>
    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="volverSumatoria" wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/generatePDF/'.$this->idPedido) }}'">
            {{ __('Exportar a PDF') }}
        </button>

        <button type="button" class="btn btn-primary" onclick="location.href='{{ url('/generateNombres/'.$this->idPedido) }}'">
            {{ __('Exportar nombres') }}
        </button>


    </x-slot>
</x-dialog-modal-general>
<!-- Sumatoria Pedido modal -->

<!-- Edit Persona Pedido Admin Modal -->
<x-jet-dialog-modal wire:model="confirmingPersonaEditAdmin">

    <x-slot name="title">
        {{ isset($this->modelo->ModeloId) ? 'Editar Persona' : 'Editar Persona' }}
    </x-slot>

    <x-slot name="content">
        <!-- Información cliente -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Persona</legend>
            <!-- Fecha Ingreso -->

            <div class="form-row ml-2 mt-2">                    
                <div class="form-group col-md-3">
                    <label for="pNombre">Primer Nombre</label>
                    <Input type="text" class="form-control" id="pNombre" name="pNombre"
                        wire:model="pNombrePedidoPersona"  
                        placeholder="Ingrese Primer Nombre">
                    <input type="hidden" id="idExamen">
                    @error('pNombrePedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudPNombrePedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                            
                </div>
                <div class="form-group col-md-3">
                    <label for="sNombre">Segundo Nombre</label>
                    <input type="text" class="form-control" id="sNombre" name="sNombre"
                        wire:model="sNombrePedidoPersona" 
                        placeholder="Ingrese Segundo Nombre">
                    @error('sNombrePedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudSNombrePedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                                  
                </div>
                <div class="form-group col-md-3">
                    <label for="pApellido">Primer Apellido</label>
                    <input type="text" class="form-control" id="pApellido" name="pApellido"
                        wire:model="pApellidoPedidoPersona" 
                        placeholder="Ingrese Primer Apellido">
                    @error('pApellidoPedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudPApellidoPedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                                 
                </div>
                <div class="form-group col-md-3">
                    <label for="sApellido">Segundo Apellido</label>
                    <input type="text" id="ObserExam" class="form-control" id="sApellido" name="sApellido"
                        wire:model="sApellidoPedidoPersona" 
                        placeholder="Ingrese Segundo Apellido">
                    @error('sApellidoPedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudSApellidoPedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                                 
                </div>
                <div class="form-group col-md-3">
                    <label for="rutPersona">Rut</label>
                    <Input type="text" class="form-control" id="rutPersona" name="rutPersona"
                        wire:model="rutPedidoPersona"  
                        placeholder="Ingrese Rut de la persona">
                    <input type="hidden" id="idExamen">
                    @error('rutPedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudRutPedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                            
                </div>                          
                <div class="form-group col-md-3">
                    <label for="mailPersona">Mail</label>
                    <input type="email" class="form-control" id="mailPersona" name="mailPersona"
                        wire:model="mailPedidoPersona" 
                        placeholder="Ingrese Mail">
                    @error('mailPedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudMailPedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                                  
                </div>
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="form-group col-md-3">
                    <label for="celularPersona">Celular</label>
                    <input type="numeric" class="form-control" id="celularPersona" name="celularPersona"
                        wire:model="celularPedidoPersona" 
                        placeholder="Ingrese Celular (9 xxxx xxxx)">
                    @error('celularPedidoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudCelularPedidoPersona">{{$message}}</span>
                    </div>
                    @enderror                                   
                </div>
                <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                    <label for="estadoPer">Estado:</label>
                    <select class="form-control" id="estadoPer"
                        wire:model="estadoPersona">
                        <option value="Disponible">Disponible</option>
                        <option value="Inactivo">Inactivo</option>
                        <option value="LISTO">LISTO</option>
                    </select>
                    @error('estadoPersona')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudEstadoPersona">{{$message}}</span>
                    </div>
                    @enderror  
                </div>
            </div>
        </fieldset>

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingPersonaEditAdmin', false) wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" class="ml-2" wire:click="editPersonaAdmin"
            wire:loading.attr="disabled">
            {{ __('Guardar') }}
        </button>
    </x-slot>

</x-jet-dialog-modal>
<!--Edit Persona Pedido Admin Modal -->

<!-- Edit Modelo Modal -->
<x-jet-dialog-modal wire:model="confirmingModeloEdit">

    <x-slot name="title">
        {{ isset($this->modelo->ModeloId) ? 'Editar Modelo' : 'Formulario Modelo' }}
    </x-slot>

    <x-slot name="content">
        <!-- Información cliente -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Modelo</legend>
            <!-- Fecha Ingreso -->

            <div class="form-row">
                <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                    <label for="codigoMod">Código:</label>
                    <input type="text" class="form-control" id="codigoMod"
                        wire:model="codigoModelo" placeholder="Ingrese Código">
                        @error('codigoModelo')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudCodigoModelo">{{$message}}</span>
                        </div>
                        @enderror  
                </div>
                <!-- Modelo -->
                <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                    <label for="mod">Modelo:</label>
                    <input type="text" class="form-control" id="mod"
                        wire:model="nombreModelo" placeholder="Ingrese Modelo">
                        @error('nombreModelo')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudNombreModelo">{{$message}}</span>
                        </div>
                        @enderror  
                </div>
                <!-- Estado Modelo -->
                <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                    <label for="estadoMod">Estado:</label>
                    <select class="form-control" id="estadoMod"
                        wire:model="estadoModelo">
                        <option value="Disponible">Disponible</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estadoModelo')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudEstadoModelo">{{$message}}</span>
                    </div>
                    @enderror  
                </div>
            </div>
        </fieldset>

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingModeloEdit', false) wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" class="ml-2" wire:click="editModelo"
            wire:loading.attr="disabled">
            {{ __('Guardar') }}
        </button>
    </x-slot>

</x-jet-dialog-modal>
<!-- Edit Modelo Modal -->

<!-- Edit Color Modal -->
<x-jet-dialog-modal wire:model="confirmingColorEdit">

    <x-slot name="title">
        {{ isset($this->modelo->ModeloId) ? 'Editar Color' : '' }}
    </x-slot>

    <x-slot name="content">
        <!-- Información cliente -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Color</legend>

            <div class="form-row ml-2 mt-2">
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="form-group col-md-4">
                    <label>Color</label>
                    <input type="text" class="form-control" id="col" wire:model="nombreColor"
                        placeholder="Ingrese Color">
                        @error('nombreColor')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudColor">{{$message}}</span>
                        </div>
                        @enderror 
                </div>
                <div class="form-group col-md-4">
                    <label for="estdo">Estado:</label>
                    <select class="form-control" id="estdo" wire:model="estadoColor">
                        <option value="">Seleccione</option>
                        <option value="Disponible">Disponible</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                    @error('estadoColor')
                    <div class="text-danger">
                        <strong>Wow!</strong> <span id="mensajeCrudEstadoColor">{{$message}}</span>
                    </div>
                    @enderror 
                </div>
            </div>
        </fieldset>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingColorEdit', false) wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" class="ml-2" wire:click="editColor"
            wire:loading.attr="disabled">
            {{ __('Guardar') }}
        </button>
    </x-slot>

</x-jet-dialog-modal>
<!-- Edit Color  Modal -->

<!-- Asignar Pedido Externo Modal -->
<x-jet-dialog-modal wire:model="confirmingAsignarPedido">

    <x-slot name="title">
        {{ __('Asignar Pedido') }}
    </x-slot>

    <x-slot name="content">
        <!-- Información cliente -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Pedido Externo</legend>

            <div class="form-row ml-2 mt-2">
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="form-group col-md-7">
                    <label>Número de pedido</label>
                    <input type="text" class="form-control" id="numPed" wire:model="numeroPedidoExterno"
                        placeholder="Ingrese número de pedido">
                        @error('numeroPedidoExterno')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudNumeroPedidoExterno">{{$message}}</span>
                        </div>
                        @enderror 
                </div>
                <div class="form-group col-md-7">
                    <label for="observacion">Observación</label>
                    <textarea type="area" class="form-control" id="observacion" name="observacion"
                        wire:model="observacionPedidoExterno"
                        placeholder="Ingrese Observación del pedido">
                    </textarea>
                    @error('observacionPedidoExterno')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudObservacionPedidoExterno">{{$message}}</span>
                        </div>
                    @enderror 
                </div>
                <div class="form-group col-md-2 align-self-end d-flex align-items-center justify-content-center">
                    @can('Agregar modelos')                        
                    <button wire:click="asignarPedido" type="button" class="form-control btn btn-primary"><i
                            class="fa fa-plus" title="Agregar Pedido Externo"></i></button>
                    @endcan
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                    role="alert" id="alertaCrudErrorPersonas9" style="display: none;">
                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas9"></span>
                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas9">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped" id="tbDetalleOT col-md-2">
                        <thead>
                            <tr>
                                <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Número Pedido</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Observación</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pedidosExterno as $key => $pedido) 
                                <tr>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        <a wire:click="confirmAsignarPedidoEdit( {{ $pedido->PedidoExternoId }} )" title="Editar Pedido Externo" style="cursor:pointer"><i class="fas fa-edit" style="color:#0a6ed3"></i></a>
                                    </td>                                    
                                    <td style="display: none;">{{$pedido->PedidoExternoId}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $pedido->NumPedidoExterno }} </td>
                                    <td class="rounded border px-4 py-2" style="text-align: center"> {{ $pedido->ObsPedidoExterno }} </td>

                                </tr>      
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </fieldset>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="volverAsignarPedido()">
            {{ __('Volver') }}
        </button>
    </x-slot>

</x-jet-dialog-modal>
<!-- Asignar Pedido Externo Modal -->

<!-- Edit Pedido Externo Modal -->
<x-jet-dialog-modal wire:model="confirmingAsignarPedidoEdit">

    <x-slot name="title">
        {{ __('Editar Pedido Externo') }}
    </x-slot>

    <x-slot name="content">
        <!-- Información cliente -->
        <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Pedido Externo</legend>

            <div class="form-row ml-2 mt-2">
                <!-- fix for small devices only -->
                <div class="clearfix hidden-md-up"></div>
                <div class="form-group col-md-12">
                    <label>Número de pedido</label>
                    <input type="text" class="form-control" id="numPed" wire:model="numeroPedidoExterno"
                        placeholder="Ingrese número de pedido">
                        @error('numeroPedidoExterno')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudNumeroPedidoExterno">{{$message}}</span>
                        </div>
                        @enderror 
                </div>
                <div class="form-group col-md-12">
                    <label for="observacion">Observación</label>
                    <textarea type="area" class="form-control" id="observacion" name="observacion"
                        wire:model="observacionPedidoExterno"
                        placeholder="Ingrese Observación del pedido">
                    </textarea>
                    @error('observacionPedidoExterno')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudObservacionPedidoExterno">{{$message}}</span>
                        </div>
                    @enderror 
                </div>
            </div>
        </fieldset>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="$toggle('confirmingAsignarPedidoEdit', false)">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" class="ml-2" wire:click="editPedidoExterno">
            {{ __('Guardar') }}
        </button>
    </x-slot>

</x-jet-dialog-modal>
<!-- Edit Pedido Externo Modal -->

@endif