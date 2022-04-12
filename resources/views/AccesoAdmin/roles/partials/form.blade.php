<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : '') , 'placeholder' => 'Escriba un nombre']) !!}
    @error('name')
        <Span class="invalid-feedback">
            <strong>
                {{$message}}
            </strong>
        </Span>
    @enderror
</div>

<strong>
    Permisos
</strong>

@foreach ($permissions as $permission)
    <div>
        <label>
            {!! Form::checkbox('permissions[]', $permission->id, null, ['class' => 'mr-1']) !!}
            {{$permission->name}}
        </label>
    </div>
@endforeach

@error('permissions')
<small class="text-danger">
    <strong>
        {{$message}}
    </strong>
</small>
<br>
@enderror