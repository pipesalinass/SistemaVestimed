<div>
    <!-- Spinner de carga -->
    @can('Ver usuarios')
    <div class="card">
        <div class="card-header ">
            <a href="{{route('accesoadmin.users.create')}}"> Crear un nuevo usuario</a>
        </div>
        {{$prompt}}
        @if ($users->count())
            <div class="card-body">
                <input wire:keydown="limpiar_page" wire:model="search" class="form-control"
                placeholder="Escriba un nombre">
                <table class="table table-hover table-sm table-striped">
                    <thead class="blue-light">
                        <tr>
                            <th class="cursor-pointer" wire:click="order('id')">
                                ID
                                @if ($sort == 'id')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('name')">
                                Nombre completo
                                @if ($sort == 'name')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('email')">
                                Email
                                @if ($sort == 'email')
                                    @if ($direction == 'asc')
                                        <i class="fas fa-sort-alpha-up-alt float-right mt-1"></i>
                                    @else
                                        <i class="fas fa-sort-alpha-down-alt float-right mt-1"></i>
                                    @endif
                                @else
                                    <i class="fas fa-sort float-right mt-1"></i>
                                @endif
                            </th>
                            <th class="cursor-pointer" wire:click="order('NumeroContacto')">
                                Rol
                            </th>
                            @can('activar usuario')
                            <th class="cursor-pointer" wire:click="order('EstadoContacto')">
                                Estado
                            </th>
                            @endcan
                            <th Colspan="4">Acción</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $rolName)
                                    {{$rolName}}
                                    @endforeach
                                    @endif
                                </td>
                                @can('activar usuario')
                                <td>{{ $user->EstadoContacto }}</td>
                                @endcan
                                <td width="10px">
                                    <a class="btn btn-primary btn-sm" href="{{ route('accesoadmin.users.show', $user) }}">
                                    Ver
                                    </a>
                                </td>
                                <td width="10px">
                                    <a class="btn btn-secondary btn-sm"
                                        href="{{ route('accesoadmin.users.edit', $user) }}"> Editar</a>
                                </td>
                                @can('activar usuario')
                                @if($user->EstadoContacto == "Activo")
                                <td width="10px">
                                    <a class="btn btn-danger btn-sm" wire:click="desactivarUsuario( {{$user->id}} )"> Desactivar</a>
                                </td>
                                @endif
                                @if($user->EstadoContacto == "Inactivo")
                                <td width="10px">
                                    <a class="btn btn-success btn-sm" wire:click="activarUsuario( {{$user->id}} )"> Activar</a>
                                </td>
                                @endif
                                @endcan
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @else
            <div class="card-body">
                No existe ningún registro coincidente
            </div>
        @endif
    </div>
    @endcan
</div>

