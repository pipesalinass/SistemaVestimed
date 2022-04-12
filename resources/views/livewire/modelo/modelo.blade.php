<div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="m-3">
                <!-- Content Header (Page header) -->
                <div class="content-header border-bottom border-success ">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-4 mb-2">
                                <h1 class="m-0">Gestión de Modelos</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-8 allign right"> <!-- Cambio, saca right -->
                                <div class="row" style="display: flex; justify-content: flex-end;""> 
                                    <div class="col-xs-12 col-sm-4 col-md-12 col-lg-4 mb-3  "> 
                                        <button type="button"
                                                class="btn btn-outline-primary btn-block waves-effect px-3"
                                                wire:click="confirmModeloAdd">
                                                <i class=" fas fa-plus pr-2"></i><span>Nuevo Modelo</span>
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
                                        <h3 class="card-title col-5">Modelos</h3>
                                        <div class="card-tools col-6" style="display: flex; justify-content: flex-end;">
                                            <div class="input-group input-group-sm col-10">
                                                <input type="text" name="table_search" class="form-control float-right"
                                                    placeholder="Buscar" id="buscarTabla" wire:model="search">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default"><i
                                                            class="fas fa-search" wire:model="search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-header -->
                                    <table class="table table-hover responsive table-sm display text-nowrap table-striped" id="tbOT">
                                        <thead style="text-align: center">
                                            <tr>
                                                <th>
                                                <button wire:click="sortBy('id')"> 
                                                    Código
                                                </button> 
                                                </th>
                                                <th>
                                                <button wire:click="sortBy('nombre')"> 
                                                    Modelo
                                                </button> 
                                                </th>
                                                <th>
                                                <button wire:click="sortBy('telefono')"> 
                                                    Estado
                                                </button> 
                                                </th>
                                                <th Colspan="2">Acción</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($modelos as $key => $mod )
                                            <tr style="text-align: center">
                                                <td class="rounded border px-4 py-2"> {{ $mod->Codigo}} </td>
                                                <td class="rounded border px-4 py-2"> {{ $mod->Modelo}} </td>
                                                <td class="rounded border px-4 py-2"> {{ $mod->Estado}} </td>
                                                <td>
                                                    <a
                                                        wire:click="confirmColorEdit ({{ $mod->ModeloId }})"><i
                                                            class="fas fa-eye"
                                                            style="color:   #0a6ed3 " title="Ver Colores"></i></a>
                                                    |
                                                    <a wire:click="confirmModeloEdit({{ $mod->ModeloId }})"><i 
                                                            class="fas fa-edit"
                                                            style="color:   #0a6ed3 " title="Editar Modelo"></i></a>
                                                    
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                               </table>
                               @if ($cotizaciones->hasPages())
                               <div class="card-footer clearfix" style="background:#eeeeee7c ">
                                   {{ $cotizaciones->links() }}
                               </div>
                           @endif
                                </div>
                            </div>
                        </div>
                    </div>              
                </section>
            </div>
        </div>
    </div>

  <!-- Create Modelo Color Modal -->
  <x-jet-dialog-modal wire:model="confirmingModeloAdd" >
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
                 <label for="Estado">Estado:</label>
                <select class="form-select"  id="modelo.Estado" aria-label="modelo.Estado" wire:model="modelo.Estado">
                    <option>Seleccione</option>
                    <option value="Disponible">Disponible</option>
                    <option value="No Disponible">No Disponible</option>
                </select>
            </div>            
            <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                <label for="Codigo">Código:</label>
                <input type="text"  class="form-select" id="modelo.Codigo" wire:model="modelo.Codigo" aria-label="modelo.Codigo" placeholder="Ingrese Código">
            </div>
            <!-- Modelo -->
            <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                <label for="Modelo">Modelo:</label>
                <input type="text"  class="form-select" id="modelo.Modelo" wire:model="modelo.Modelo" aria-label="modelo.Modelo" placeholder="Ingrese Modelo">
            </div>
            <div
                    class="form-group col-md-2 align-self-end d-flex align-items-center justify-content-center">
                    <button wire:click="saveModelo" type="button" class="btn btn-primary btn-sm ms-4"><i
                            class="fa fa-plus" ></i></button>
            </div>
            </div>   
         </fieldset>
           
</x-slot>

<x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="confirmModeloAdd"
            wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" class="ml-2"
        wire:click="saveModelo" wire:loading.attr="disabled">
            {{ __('Guardar') }}
        </button>
</x-slot>
</x-jet-dialog-modal>
  <!-- Create Modelo Modal -->    


  <!-- Create Color Modal -->
  <x-jet-dialog-modal wire:model="confirmingColorEdit" >
    <x-slot name="title">
        {{ isset($this->modelo->ModeloId) ? 'Editar Color' : '' }}
    </x-slot>

<x-slot name="content">
         <!-- Información cliente -->
         <fieldset class="form-group border p-3 scheduler-border">
            <legend class="w-auto px-2">Información Color</legend>
           
            <div class="form-row">          
            <div class="form-group col md-6" style="display: flex; flex-direction: column;">
                <label for="Codigo">Color:</label>
                <input type="text"  class="form-select" id="modelo.Codigo" wire:model.defer="modelo.Codigo" aria-label="modelo.Codigo" placeholder="Ingrese Color">
            </div>
            <div
                    class="form-group col-md-2 align-self-end d-flex align-items-center justify-content-center">
                    <button wire:click="saveModelo" type="button" class="btn btn-primary btn-sm ms-4"><i
                            class="fa fa-plus" ></i></button>
            </div>
            </div>   
         </fieldset>     
</x-slot>

<x-slot name="footer">
        <button type="button" class="btn btn-secondary" wire:click="confirmModeloAdd"
            wire:loading.attr="disabled">
            {{ __('Volver') }}
        </button>

        <button type="button" class="btn btn-primary" class="ml-2"
        wire:click="saveModelo" wire:loading.attr="disabled">
            {{ __('Guardar') }}
        </button>
</x-slot>
</x-jet-dialog-modal>
  <!-- Create Color  Modal -->      

</div>
