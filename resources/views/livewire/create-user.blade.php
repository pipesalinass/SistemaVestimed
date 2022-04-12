<div>
    <div class="form-group">
        <label for="formGroupExampleInput">Nombre completo</label>
        <input wire:model="name" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Ingresar nombre">
        @error('name')
        <div class="text-danger">
            <strong>Wow!</strong> <span id="mensajeCrudName">{{$message}}</span>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="formGroupExampleInput">Numero de contacto</label>
        <input wire:model="NumeroContacto" class="form-control" id="formGroupExampleInput2" placeholder="Ingresar numero Ej:9xxxxxxxx">
        @error('NumeroContacto')
        <div class="text-danger">
            <strong>Wow!</strong> <span id="mensajeCrudNumeroContacto">{{$message}}</span>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="formGroupExampleInput">Estado</label>
        <select wire:model="EstadoContacto" class="form-control" id="formGroupExampleInput2" placeholder="Ingresar Estado">
            <option> Activo </option>
            <option> Inactivo </option>
        </select>
        @error('EstadoContacto')
        <div class="text-danger">
            <strong>Wow!</strong> <span id="mensajeCrudEstadoContacto">{{$message}}</span>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="formGroupExampleInput2">Email</label>
        <input wire:model="email" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Ingresar correo Ej:xxx@uatsa.cl">
        @error('email')
        <div class="text-danger">
            <strong>Wow!</strong> <span id="mensajeCrudEmail">{{$message}}</span>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="formGroupExampleInput">Contraseña</label>
        <input wire:model="password" type="text" class="form-control" id="formGroupExampleInput" placeholder="Ingresar contraseña">
        @error('password')
        <div class="text-danger">
            <strong>Wow!</strong> <span id="mensajeCrudPassword">{{$message}}</span>
        </div>
        @enderror
    </div>

    <div class="form-group">
        <label for="formGroupExampleInput2">Confirmar contraseña</label>
        <input wire:model="password" type="text" class="form-control" id="formGroupExampleInput2" placeholder="Ingresar contraseña">
        @error('password')
        <div class="text-danger">
            <strong>Wow!</strong> <span id="mensajeCrudPassword">{{$message}}</span>
        </div>
        @enderror
    </div>
    <br>
    <button wire:click="save" type="submit" class="btn btn-primary">Guardar</button>
</div>
