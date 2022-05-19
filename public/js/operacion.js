$(document).ready(function () {
    window.addEventListener('abrirCreacionSimulacion', event => {
        $("#nuevaSimulacion").modal('show');
    });

    $("#nuevaSimulacion").on("hidden.bs.modal", function () {
        livewire.emit("limpiarFormulario");
    });

    window.addEventListener('cerrarModalSimulacion', event => {
        $("#nuevaSimulacion").modal('hide');
    });
});

$(document).ready(function () {
    window.addEventListener('abrirCreacionSimulacion1', event => {
        $("#nuevaSimulacion1").modal('show');
    });

    $("#nuevaSimulacion1").on("hidden.bs.modal", function () {
        livewire.emit("limpiarFormulario1");
    });

    window.addEventListener('cerrarModalSimulacion1', event => {
        $("#nuevaSimulacion1").modal('hide');
    });
    window.addEventListener('redireccionar', event => {
        alert('hola');
        window.location.href = "http://sistemavestimed.test/login";

    });
});

$(document).ready(function(){
    $("#success").delay(5000).slideUp(300);
});

$(document).ready(function(){
    $("#error").delay(5000).slideUp(300);
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido', event => {
        document.getElementById("mensajeCrudErrorPersonas").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido1', event => {
        document.getElementById("mensajeCrudErrorPersonas1").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas1").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas1', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas1").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas1").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido2', event => {
        document.getElementById("mensajeCrudErrorPersonas2").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas2").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas2', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas2").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas2").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido3', event => {
        document.getElementById("mensajeCrudErrorPersonas3").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas3").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas3', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas3").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas3").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido4', event => {
        document.getElementById("mensajeCrudErrorPersonas4").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas4").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas4', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas4").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas4").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido5', event => {
        document.getElementById("mensajeCrudErrorPersonas5").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas5").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas5', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas5").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas5").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido6', event => {
        document.getElementById("mensajeCrudErrorPersonas6").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas6").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas6', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas6").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas6").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido7', event => {
        document.getElementById("mensajeCrudErrorPersonas7").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas7").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas7', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas7").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas7").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido8', event => {
        document.getElementById("mensajeCrudErrorPersonas8").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas8").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas8', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas8").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas8").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido9', event => {
        document.getElementById("mensajeCrudErrorPersonas9").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas9").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas9', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas9").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas9").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido10', event => {
        document.getElementById("mensajeCrudErrorPersonas10").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas10").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas10', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas10").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas10").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido11', event => {
        document.getElementById("mensajeCrudErrorPersonas11").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas11").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas11', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas11").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas11").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido12', event => {
        document.getElementById("mensajeCrudErrorPersonas12").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas12").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas12', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas12").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas12").style.display = "none";
    });
});

$(document).ready(function(){
    window.addEventListener('abrirMsjeFallido13', event => {
        document.getElementById("mensajeCrudErrorPersonas13").textContent  = event.detail.error; 
        document.getElementById("alertaCrudErrorPersonas13").style.display = "block";
    })
    $(document).on('click', '#cerrarAlertCrudErrorPersonas13', function(e){
        e.preventDefault();
        document.getElementById("mensajeCrudErrorPersonas13").textContent  = ''; 
        document.getElementById("alertaCrudErrorPersonas13").style.display = "none";
    });
});


$(document).ready(function () {
   $('[data-toggle="mensaje"]').tooltip()
});


// get elements
var parent = document.querySelector('.parent');
var column = document.querySelector('.column');
var content = document.querySelector('.content');

// calculate height values of column and it's content
var columnHeight = column.offsetHeight;
var contentHeight = content.offsetHeight;

// create an array of offset values
var offsetValues = [];
for (var i = columnHeight; i < contentHeight; i+= columnHeight) {
    offsetValues.push(i);
}

// create a new column for each offset value
offsetValues.forEach( function(offsetValue, i) {

    // init clone and add classes
    var cloneColumn = document.createElement('div');
    var cloneContent = document.createElement('div');
    cloneColumn.classList.add('column');
    cloneContent.classList.add('content');

    // populate the DOM
    cloneContent.innerHTML = content.innerHTML;
    cloneColumn.appendChild(cloneContent);
    parent.appendChild(cloneColumn); 

    // apply position and offset styles
    cloneContent.style.position = 'relative';
    cloneContent.style.top = '-' + offsetValue + 'px';
});


$(document).ready(function() {

    // ...
  
    // Enable Bootstrap tooltips on page load
    $('[data-toggle="tooltip"]').tooltip();
  
    // Ensure Livewire updates re-instantiate tooltips
    if (typeof window.Livewire !== 'undefined') {
      window.Livewire.hook('message.processed', (message, component) => {
          $('[data-toggle="tooltip"]').tooltip('dispose').tooltip();
      });
    }
  
  });