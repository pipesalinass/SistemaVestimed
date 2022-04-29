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
            <div class="content-header border-bottom border-success ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-4 mb-2">
                            <h1 class="m-0">Gestión de Post Pedidos</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-8">
                            <div class="row" style="display: flex; justify-content: flex-end;">
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
                                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
                                    @can('Hacer pedidos')
                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect px-3"
                                        wire:click="confirmPostPedidoAdd">
                                        <i class=" fas fa-plus pr-2"></i><span>Nuevo</span>
                                    </button>
                                    @endcan
                                </div>
                                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
                                    @can('Hacer pedidos')
                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect px-3"
                                        wire:click="confirmBordadoAdd">
                                        <i class=" fas fa-plus pr-2"></i><span>Bordado</span>
                                    </button>
                                    @endcan
                                </div>
                                <div class="col-xs-4 col-sm-3 col-md-1 col-lg-1">
                                    <button type="button" class="btn btn-outline-primary"
                                    wire:click="refrescar">
                                    <i class="fas fa-redo"></i>
                                </button>   
                                </div>
                            </div>
                        </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content mt-3 border-bottom border-success">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row" style="display: flex;
                justify-content: space-around;">
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="parcialClick"
                                wire:click="changeEstado('RECEPCION_PARCIAL')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recepcion parcial</span>
                                <span class="info-box-number" id="parcial">{{$recepcion_parcial}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1 text-white" style="cursor:pointer"
                                id="finalizadaClick"
                                wire:click="changeEstado('RECEPCION_FINALIZADA')"><i class="far fa-thumbs-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recepcion finalizada</span>
                                <span class="info-box-number" id="finalizada">{{$recepcion_finalizada}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="enBordadoClick"
                                wire:click="changeEstado('EN BORDADO')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">En bordado</span>
                                <span class="info-box-number" id="enBordado">{{$en_bordado}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1" style="cursor:pointer" id="CerradosClick"
                                id="recibeBordadoClick"
                                wire:click="changeEstado('RECIBE BORDADO')"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recibe de bordado</span>
                                <span class="info-box-number" id="recibeBordado">{{$recibe_de_bordado}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1" style="cursor:pointer" id="CerradosClick"
                                id="entregadoClick"
                                wire:click="changeEstado('ENTREGADO')"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Entregado</span>
                                <span class="info-box-number" id="entregado">{{$entregado}}</span>
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
                            <fieldset>

                        <div class="card-body table-responsive p-0">                               
                            <table class="table table-hover responsive table-sm display text-nowrap table-striped"
                                id="tbOT">
                                <thead style="text-align: center">
                                    <tr>
                                        <th>
                                            <button wire:click="sortBy('FechaRecepcion')">
                                                Fecha Recepción
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('NumeroDocumentoExterno')">
                                                Número documento externo
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('NumeroFactura')">
                                                Número factura
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('FechaDocumentoExterno')">
                                                Fecha documento externo
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('Observacion')">
                                                Observación
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy('Estado')">
                                                Estado
                                            </button>
                                        </th>
                                        <th Colspan="3">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facturas as $key => $factura)
                                    <tr>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->FechaRecepcion->format('d-m-Y')}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->NumeroDocumentoExterno}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->NumeroFactura}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->FechaDocumentoExterno->format('d-m-Y')}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->Observacion}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->Estado}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">
                                            <a wire:click="confirmRecepcionDetalle( {{ $factura->RecepcionCabeceraId }} )" title="Visualizar Detalle" style="cursor:pointer">
                                                <i class="fas fa-eye" style="color:#0a6ed3" title="Ver Detalle"></i>
                                            </a>   
                                            <a wire:click="confirmPrendaPersona( {{ $factura }} )" title="Visualizar Personas con prendas" style="cursor:pointer">
                                                <i class="fas fa-plus" style="color:#0a6ed3" title="Visualizar Personas con prendas"></i>
                                            </a>   
                                        </td>
                                    </tr>
                                @endforeach                     
                                </tbody>
                            </table>
                        </div>
                        </fieldset>
                            @if ($facturas->hasPages())
                            <div class="card-footer clearfix" style="background:#eeeeee7c ">
                                {{ $facturas->links() }}
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
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="m-3">
            <!-- Content Header (Page header) -->
            <div class="content-header border-bottom border-success ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-4 mb-2">
                            <h1 class="m-0">Gestión de Post Pedidos</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-8">
                            <div class="row" style="display: flex; justify-content: flex-end;">
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
                                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
                                    @can('Ver pedidos admin')
                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect px-3"
                                        wire:click="confirmPostPedidoAdd">
                                        <i class=" fas fa-plus pr-2"></i><span>Nuevo</span>
                                    </button>
                                    @endcan
                                </div>
                                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3">
                                    @can('Ver pedidos admin')
                                    <button type="button" class="btn btn-outline-primary btn-block waves-effect px-3"
                                        wire:click="confirmBordadoAdd">
                                        <i class=" fas fa-plus pr-2"></i><span>Bordado</span>
                                    </button>
                                    @endcan
                                </div>
                                <div class="col-xs-4 col-sm-3 col-md-1 col-lg-1">
                                    <button type="button" class="btn btn-outline-primary"
                                    wire:click="refrescar">
                                    <i class="fas fa-redo"></i>
                                </button>   
                                </div>
                            </div>
                        </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content mt-3 border-bottom border-success">
            <div class="container-fluid">
                <!-- /.row -->
                <div class="row" style="display: flex;
                justify-content: space-around;">
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="parcialClick1"
                                wire:click="changeEstado1('RECEPCION_PARCIAL')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recepcion parcial</span>
                                <span class="info-box-number" id="parcial1">{{$recepcion_parcial1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1 text-white" style="cursor:pointer"
                                id="finalizadaClick1"
                                wire:click="changeEstado1('RECEPCION_FINALIZADA')"><i class="far fa-thumbs-up"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recepcion finalizada</span>
                                <span class="info-box-number" id="finalizada1">{{$recepcion_finalizada1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-primary elevation-1" style="cursor:pointer"
                                id="enBordadoClick1"
                                wire:click="changeEstado1('EN BORDADO')"><i class="far fa-file"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">En bordado</span>
                                <span class="info-box-number" id="enBordado1">{{$en_bordado1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1" style="cursor:pointer" id="CerradosClick"
                                id="recibeBordadoClick1"
                                wire:click="changeEstado1('RECIBE BORDADO')"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Recibe de bordado</span>
                                <span class="info-box-number" id="recibeBordado1">{{$recibe_de_bordado1}}</span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- fix for small devices only -->
                    <div class="clearfix hidden-md-up"></div>
                    <div class="">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-info elevation-1" style="cursor:pointer" id="CerradosClick"
                                id="entregadoClick1"
                                wire:click="changeEstado1('ENTREGADO')"><i class="fas fa-cog"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Entregado</span>
                                <span class="info-box-number" id="entregado1">{{$entregado1}}</span>
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
                                            placeholder="Buscar" id="buscarTabla" wire:model="search">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default"><i class="fas fa-search"
                                                    wire:model="search1"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <fieldset>

                        <div class="card-body table-responsive p-0">                               
                            <table class="table table-hover responsive table-sm display text-nowrap table-striped"
                                id="tbOT">
                                <thead style="text-align: center">
                                    <tr>
                                        <th>
                                            <button wire:click="sortBy1('PedTitulo')">
                                                Título
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy1('FechaRecepcion')">
                                                Fecha Recepción
                                            </button>
                                        </th>
                                        <th>
                                            <button wire:click="sortBy1('Estado')">
                                                Estado
                                            </button>
                                        </th>
                                        <th Colspan="3">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($facturasCliente as $key => $factura)
                                    <tr>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->PedTitulo}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->FechaRecepcion->format('d-m-Y')}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">{{$factura->Estado}}</td>
                                        <td class="rounded border px-4 py-2" style="text-align: center">
                                            <a wire:click="confirmRecepcionDetalle( {{ $factura->RecepcionCabeceraId }} )" title="Visualizar Detalle" style="cursor:pointer">
                                                <i class="fas fa-eye" style="color:#0a6ed3" title="Ver Detalle"></i>
                                            </a>  
                                            <a wire:click="confirmPrendaPersonaCliente( {{ $factura }} )" title="Visualizar Personas con prendas" style="cursor:pointer">
                                                <i class="fas fa-plus" style="color:#0a6ed3" title="Visualizar Personas con prendas"></i>
                                            </a>      
                                        </td>
                                    </tr>
                                @endforeach                     
                                </tbody>
                            </table>
                        </div>
                        </fieldset>
                            @if ($facturas->hasPages())
                            <div class="card-footer clearfix" style="background:#eeeeee7c ">
                                {{ $facturas->links() }}
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

<!-- Recepcion Facturas modal (Cabecera) -->
<x-dialog-modal-general wire:model="confirmingPostPedidoAdd">
    <x-slot name="title">
        {{ __('Recepción Documento') }}
    </x-slot>

    <x-slot name="content">
        <!-- Información Modelo -->

            <!-- Información factura -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información Recepción</legend>
                <div class="parent-grid-row-1">
                    <!-- Fecha Recepción Factura -->
                    <div class="form-group">
                        <label for="fecPrincipal">Fecha Recepción Factura:</label>
                        <input wire:model="todayDate" type="date" class="form-control float-right" id="fecPrincipal" name="fecPrincipal"
                            readonly>
                    </div>
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="pedAsociado">Pedido Externo Asociado:</label>
                        <select class="form-control" id="pedAsociado" name="pedAsociado"
                        wire:model="pedidoAsociado">
                        @foreach ($pedidosAsociados as $key => $prenda)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$prenda->NumPedidoExterno}} </option>
                        @endforeach
                        @error('pedidoAsociado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudPedidoAsociado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>
                    <!-- Fecha Pedido Externo -->
                    <div class="form-group">
                        <label for="fecPedExt">Fecha Pedido Externo Asociado:</label>
                        <input wire:model="fechaPedidoExterno" type="date" class="form-control float-right" id="fecPedExt" name="fecPedExt"
                            readonly>
                    </div>                    
                    <!-- Número Factura -->
                    <div class="form-group">
                        <label for="numFact">Número Factura:</label>
                        <input type="text" class="form-control" id="numFact" name="numFact"
                            wire:model="numeroFactura" placeholder="Ingrese número de factura">
                        @error('numeroFactura')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudNumeroFactura">{{$message}}</span>
                            </div>
                        @enderror
                    </div>
                <!-- Observación -->
                <div class="form-row">
                        <label for="observacion">Observación:</label>
                        <textarea type="area" class="form-control" id="observacion" name="observacion"
                            wire:model="observacionfactura"
                            placeholder="Ingrese Observación de la factura">
                        </textarea>
                        @error('observacionfactura')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudObservacionfactura">{{$message}}</span>
                            </div>
                        @enderror                          
                    </div>
                    <div class="form-group col-md-8 align-self-end d-flex align-items-center justify-content-center">
                        @can('Agregar modelos')                        
                        <button wire:click="almacenarCabecera" type="button" class="form-control btn btn-primary" {{ $this->estadoBotonGuardarCabeceraRecepcion ? 'disabled' : 'enabled' }}>Continuar</button>
                        @endcan
                    </div>
 
            </fieldset>
                <div class="form-row">
                    <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                        role="alert" id="alertaCrudErrorPersonas10" style="display: none;">
                        <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas10"></span>
                        <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas10">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>

            @if($this->estadoBotonGuardarCabeceraRecepcion == 1)
                <!-- Información factura -->
                <!-- Table row -->
                <div class="row">
                    <div class="card-body table-responsive p-0"">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <legend class="w-auto px-2">Prendas</legend>
                                <tr>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Cantidad Solicitada</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Cantidad Recibida</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Cantidad Faltante</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($listaSumatoria as $key => $sumatoria)
                                @if ($sumatoria != null)
                                <tr >
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['TipoPrenda']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ModCodigo']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['TallajeTalla']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ColorPrenda']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['sumatoria']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['cantidadRecibida']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['cantidadFaltante']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">
                                        <a wire:click="confirmInsertarCantidad( {{ $key }} )" title="Insertar cantidad recibida" style="cursor:pointer">
                                            <i class="fas fa-plus" style="color:#0a6ed3" title="Insertar cantidad recibida"></i>
                                        </a>
                                        @if ($sumatoria['id1'] != "null")                                        
                                        <a wire:click="confirmMostrarRecepcion( {{$sumatoria['id']}} )" title="Mostrar recepción anterior" style="cursor:pointer">
                                            <i class="fas fa-eye" style="color:#0a6ed3" title="Mostrar recepción anterior"></i>
                                        </a>   
                                        @endif   
                                    </td>          
                                </tr>
                                @endif                           
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>   
            @endif
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarPostPedidoAdd">
            {{ __('Cancelar') }}
        </button>
        @if($this->estadoBotonGuardarCabeceraRecepcion == 1)
            <button type="button" class="btn btn-primary" wire:click="submitRecepcion">
                {{ __('Guardar detalle recepción') }}
            </button>
        @endif

    </x-slot>
</x-dialog-modal-general>
<!-- Recepcion Facturas modal -->

<!-- Recepcion Detalle modal -->
<x-dialog-modal-general wire:model="confirmingRecepcionDetalle">
    <x-slot name="title">
        {{ __('Detalle Recepción') }}
    </x-slot>

    <x-slot name="content">
        <!-- Información Modelo -->

            <!-- Table row -->
           <div class="row">
                <div class="table-responsive">
                    <table class="table table-striped" id="tbDetalleOT col-md-2">
                        <thead>
                            <legend class="w-auto px-2">Prendas</legend>
                            <tr>
                                <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Cantidad Solicitada</th>
                                <th class="rounded border px-2 py-2" style="text-align: center">Cantidad Recibida</th>
                                <th class="rounded border px-2 py-2" style="text-align: center">Cantidad Faltante</th>
                                <th class="rounded border px-2 py-2" style="text-align: center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recepcionDetalle as $key => $sumatoria)
                            <tr >
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->TipoPrenda}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->CodigoModelo}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->Talla}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->Color}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->CantidadSolicitada}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->CantidadRecibida}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->CantidadFaltante}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria->Estado}}</td>                                    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>   

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="volverRecepcionDetalle">
            {{ __('Volver') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- Recepcion Detalle modal -->

<!-- Insertar Cantidad Recibida modal -->
<x-dialog-modal-general wire:model="confirmingDetalleEdit">
    <x-slot name="title">
        {{ __('Insertar Cantidad Recibida') }}
    </x-slot>

    <x-slot name="content">
                <!-- Información Cantidad Recibida -->
                <fieldset class="form-group border p-3 scheduler-border">
                    <legend class="w-auto px-2">Información Cantidad</legend>
                    <!-- Fecha Ingreso -->
                    <div class="form-row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="cantidad">Cantidad Recibida:</label>
                                <input type="number" min="0" max="999" class="form-control" id="cantidad"
                                    wire:model="cantidadRecibida">
                                    @error('cantidadRecibida')
                                    <div class="text-danger">
                                        <strong>Wow!</strong> <span id="mensajeCrudCantidadRecibida">{{$message}}</span>
                                    </div>
                                    @enderror  
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                            role="alert" id="alertaCrudErrorPersonas11" style="display: none;">
                            <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas11"></span>
                            <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas11">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </fieldset>

    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarDetalleEdit">
            {{ __('Cancelar') }}
        </button>

        <button type="button" class="btn btn-primary" wire:click="detalleEdit">
            {{ __('Guardar') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- Insertar Cantidad Recibida modal -->

<!-- Mostrar Recepcion Anterior modal -->
<x-dialog-modal-general wire:model="confirmingMostrarRecepcion">
    <x-slot name="title">
        {{ __('Recepción') }}
    </x-slot>

    <x-slot name="content">
                <!-- Información Cantidad Recibida -->
                <fieldset class="form-group border p-3 scheduler-border">
                    <legend class="w-auto px-2">Información Recepción Anterior</legend>
                    <!-- Fecha Ingreso -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <legend class="w-auto px-2">Prendas</legend>
                                <tr>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Cantidad Solicitada</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Cantidad Recibida</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Cantidad Faltante</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($listaAnterior != null)
                                <tr >
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['tipoPrenda']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['modCodigo']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['tallajeTalla']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['colorPrenda']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['cantidadSolicitadaAnterior']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['cantidadRecibidaAnterior']}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$listaAnterior['cantidadFaltanteAnterior']}}</td>                                        
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </fieldset>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarMostrarRecepcion">
            {{ __('Cerrar') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- Mostrar Recepcion Anterior modal -->

<!-- Bordado Modal -->
<x-dialog-modal-general wire:model="confirmingBordado">
    <x-slot name="title">
        {{ __('Bordado') }}
    </x-slot>

    <x-slot name="content">
        <!-- Información Modelo -->

            <!-- Información factura -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información Recepción</legend>
                <div class="parent-grid-row-1">
                    <!-- Fecha Recepción Factura -->
                    <div class="form-group">
                        <label for="fecPrincipal">Fecha envío a bordado:</label>
                        <input wire:model="fechaBordado" type="date" class="form-control float-right" id="fecPrincipal" name="fecPrincipal"
                            >
                    </div>
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="pedAsociado">Pedido Externo Asociado:</label>
                        <select class="form-control" id="pedAsociado" name="pedAsociado"
                        wire:model="pedidoAsociadoBordado">
                        @foreach ($pedidosAsociadosBordado as $key => $prenda)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$prenda->FK_DocumentoExterno}} </option>
                        @endforeach
                        @error('pedidoAsociado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudPedidoAsociadoBordado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>
                    <!-- Fecha Pedido Externo -->
                    <div class="form-group">
                        <label for="fecPedExt">Fecha Pedido Externo Asociado:</label>
                        <input wire:model="fechaPedidoExternoBordado" type="date" class="form-control float-right" id="fecPedExt" name="fecPedExt"
                            readonly>
                    </div> 
            </fieldset>
            <div class="form-row">
                <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                    role="alert" id="alertaCrudErrorPersonas10" style="display: none;">
                    <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas10"></span>
                    <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas10">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <!-- Información factura -->
            <!-- Table row -->
           <div class="row">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <legend class="w-auto px-2">Prendas</legend>
                            <tr>
                                <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                <th class="rounded border px-4 py-2" style="text-align: center">Cantidad en Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listaSumatoriaBordado as $key => $sumatoria)
                            @if ($sumatoria != null)
                            <tr >
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['TipoPrenda']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ModCodigo']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['TallajeTalla']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['ColorPrenda']}}</td>
                                <td class="rounded border px-4 py-2" style="text-align: center">{{$sumatoria['suma']}}</td>     
                            </tr>
                            @endif                           
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>   
            <br>
            <br>
            <br>
            <!-- Información factura -->
            <fieldset class="form-group border p-3 scheduler-border">
                <legend class="w-auto px-2">Información Bordado</legend>
                <div class="parent-grid-row-1">
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="tipo">Tipo:</label>
                        <select class="form-control" id="tipo" name="tipo"
                        wire:model="tipoBordado">
                        @foreach ($listaTipoBordado as $key => $prenda)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$prenda['tipo']}} </option>
                        @endforeach
                        @error('tipoBordado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudTipoBordado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="codMod">Código Modelo:</label>
                        <select class="form-control" id="codMod" name="codMod"
                        wire:model="codigoModeloBordado">
                        @foreach ($listaCodigoModeloBordado as $key => $prenda)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$prenda['codigoModelo']}} </option>
                        @endforeach
                        @error('codigoModeloBordado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudCodigoModeloBordado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="size">Talla:</label>
                        <select class="form-control" id="size" name="size"
                        wire:model="tallaBordado">
                        @foreach ($listaTallaBordado as $key => $prenda)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$prenda['talla']}} </option>
                        @endforeach
                        @error('tallaBordado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudTallaBordado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="col">Color:</label>
                        <select class="form-control" id="col" name="col"
                        wire:model="colorBordado">
                        @foreach ($listaColorBordado as $key => $prenda)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$prenda['color']}} </option>
                        @endforeach
                        @error('colorBordado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudColorBordado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>
                    <!-- Pedido Asociado -->
                    <div class="form-group" style="display: flex; flex-direction: column;">
                        <label for="pers">Persona:</label>
                        <select class="form-control" id="pers" name="pers"
                        wire:model="personaBordado">
                        @foreach ($listaPersonaBordado as $key => $persona)
                            <option value="none" selected  hidden>Seleccione</option>
                            <option> {{$persona['primerNombre'].' '.$persona['segundoNombre'].' '.$persona['primerApellido'].' '.$persona['segundoApellido']}} </option>
                        @endforeach
                        @error('personaBordado')
                        <div class="text-danger">
                            <strong>Wow!</strong> <span id="mensajeCrudPersonaBordado">{{$message}}</span>
                        </div>
                        @enderror       
                        </select>
                    </div>

                    <div class="form-group" style="display: flex; flex-direction: row;">
                        <div  class="col-7 mr-5">
                            <label for="pers">Cantidad:</label>
                            <input type="number" min="0" max="999" class="form-control" id="cantidadPrenda"
                            wire:model="cantidadPrendaBordado">
                            @error('cantidadPrendaBordado')
                            <div class="text-danger">
                                <strong>Wow!</strong> <span id="mensajeCrudCantidadPrenda">{{$message}}</span>
                            </div>
                            @enderror
                        </div>
                        <div style="margin-top:35px">
                            <button wire:click="agregarPrendaBordado()" type="button"
                            class="btn btn-primary btn-sm ms-4" id="btAddBordado"><i
                                class="fa fa-plus"></i></button>
                        </div>
                    </div>
                </fieldset>
                    <div class="form-row">
                        <div class="col-md-12 alert alert-danger alert-dismissible fade show text-center mt-2"
                            role="alert" id="alertaCrudErrorPersonas12" style="display: none;">
                            <strong>Wow!</strong> <span id="mensajeCrudErrorPersonas12"></span>
                            <button type="button" class="close" aria-label="Close" id="cerrarAlertCrudErrorPersonas12">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>

                        <!-- Table row -->
           <div class="row">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <legend class="w-auto px-2">Prendas</legend>
                        <tr>
                            <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Cantidad</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Persona</th>
                            <th class="rounded border px-4 py-2" style="text-align: center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listaBordado as $key => $list)
                        @if ($sumatoria != null)
                        <tr >
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$list['tipoBordado']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$list['codigoModeloBordado']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$list['tallaBordado']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$list['colorBordado']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$list['cantidadPrendaBordado']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">{{$list['personaBordado']}}</td>
                            <td class="rounded border px-4 py-2" style="text-align: center">
                                <a wire:click="quitarPrendaBordado( {{ $key }} )" title="Quitar prenda" style="cursor:pointer">
                                    <i class="fas fa-minus-circle" style="color:#0a6ed3"></i>
                                </a>  
                            </td>      
                        </tr>
                        @endif                           
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarBordado">
            {{ __('Cancelar') }}
        </button>

        <button type="button" class="btn btn-primary" wire:click="enviarBordado">
            {{ __('Enviar a bordado') }}
        </button>


    </x-slot>
</x-dialog-modal-general>
<!-- Bordado Modal -->

<!-- PrendaPersona modal -->
<x-dialog-modal-general wire:model="confirmingPrendaPersona">
    <x-slot name="title">
        {{ __('Información Pedido') }}
    </x-slot>

    <x-slot name="content">
                <!-- Información Cantidad Recibida -->
                <fieldset class="form-group border p-3 scheduler-border">
                    <!-- Fecha Ingreso -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <legend class="w-auto px-2">Prendas asociadas a Personas</legend>
                                <tr>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Cantidad</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Persona Asociada</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prendaPersona as $key => $list)
                                <tr >
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->TipoPrendaPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->CodigoModeloPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->TallaPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->ColorPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->CantidadPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->PersonaAsociada}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->EstadoPersona}}</td>                                        
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </fieldset>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarPrendaPersona">
            {{ __('Cerrar') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- PrendaPersona modal -->

<!-- PrendaPersona VISTA CLIENTE modal -->
<x-dialog-modal-general wire:model="confirmingPrendaPersonaCliente">
    <x-slot name="title">
        {{ __('Información Pedido') }}
    </x-slot>

    <x-slot name="content">
                <!-- Información Cantidad Recibida -->
                <fieldset class="form-group border p-3 scheduler-border">

                    <!-- Fecha Ingreso -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <legend class="w-auto px-2">Prendas asociadas a Personas</legend>
                                <tr>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Tipo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Código modelo</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Talla</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Color</th>
                                    <th class="rounded border px-4 py-2" style="text-align: center">Cantidad</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Persona Asociada</th>
                                    <th class="rounded border px-2 py-2" style="text-align: center">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prendaPersona as $key => $list)
                                <tr >
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->TipoPrendaPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->CodigoModeloPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->TallaPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->ColorPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->CantidadPersona}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->PersonaAsociada}}</td>
                                    <td class="rounded border px-4 py-2" style="text-align: center">{{$list->EstadoPersona}}</td>                                        
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </fieldset>
    </x-slot>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="cancelarPrendaPersonaCliente">
            {{ __('Cerrar') }}
        </button>
    </x-slot>
</x-dialog-modal-general>
<!-- PrendaPersona VISTA CLIENTE modal -->

