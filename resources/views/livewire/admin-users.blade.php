<div>
    <!-- Spinner de carga -->
   
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
                            <th Colspan="3">Acción</th>
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
                                <td width="10px">
                                    <a class="btn btn-primary btn-sm" href="{{ route('accesoadmin.users.show', $user) }}">
                                    Ver
                                    </a>
                                </td>
                                <td width="10px">
                                    <a class="btn btn-secondary btn-sm"
                                        href="{{ route('accesoadmin.users.edit', $user) }}"> Editar</a>
                                </td>
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
</div>

