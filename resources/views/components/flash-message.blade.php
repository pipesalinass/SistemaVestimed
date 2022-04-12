
@if ($message = Session::get("success"))

<div class="alert alert-success" id="success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Éxito!</strong> Usuario identificado correctamente.
  </div>
    
@endif

@if ($message = Session::get("error"))

<div class="alert alert-danger" id="error">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong>Peligro!</strong> Usuario no identificado.
  </div>
    
@endif
