<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="m-3">
            <!-- Content Header (Page header) -->
            <div class="content-header border-bottom border-success ">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-4 mb-2">
                            <h1 class="m-0">Inicio</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-8">
                            <div class="row">
                                
                                <div class="col-xs-12 col-sm-6 col-md-12 col-lg-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="far fa-calendar-alt"></i>
                                                </span>
                                            </div>
                                            <input wire:model="fecha_pick" type="date" class="form-control float-right" id="fecPrincipal">
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-2 col-md-12 col-lg-2">
                                    <button class="btn btn-outline-primary btn-block waves-effect px-3" id="btnRefresh">
                                        <i class="fas fa-redo pr-2 aria-hidden=true"></i>
                                    </button>
                                </div>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <section class="content mt-3">
                <div class="container-fluid">
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div wire:loading.delay wire:target="search" id="idWaiting">
                                        <div class="spinner-border text-success text-center" role="status" id="esperar">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    <h3 class="card-title col-5">Inicio</h3>
                                    <div class="card-tools col-6 d-flex justify-content-between">
                                        <!--div class="input-group input-group-sm col-2">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success float-left" title="Descarga" data-widget="chat-pane-toggle" wire:click="exportTabla()">
                                                    <i class="fas fa-file-download"></i>
                                                </button>
                                            </div>
                                        </div-->
                                        <div class="input-group input-group-sm col-10">
                                            <input type="text" name="table_search" class="form-control float-right"
                                                placeholder="Buscar" id="buscarTabla" wire:keydown.enter="render" wire:model="">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default"><i
                                                        class="fas fa-search" wire:keydown.enter="render" wire:model=""></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">

                                        <table class="table table-hover responsive table-sm display text-nowrap" id="tbOT">
                                            <thead>
                                                <tr>
                                                    <th class="align-middle">Id Simulación</th>
                                                    <th class="align-middle">Empresa</th>
                                                    <th class="align-middle">Proyecto</th>
                                                    <th class="align-middle">Fecha Simulación</th>
                                                    <th class="align-middle">Estado</th>
                                                    <th class="align-middle">Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                        <div class="card-footer clearfix">
                                         
                                        </div>
                                   
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>
        </div>
    </div>
</div>