<!DOCTYPE html>
<html>

<head>
    <style>
        
        /** Define the margins of your page **/  
        @font-face {
            font-family: 'Roboto', sans-serif;
            src: url('https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap');
        }

        div {
            font-size:20px;
            font-family: 'Roboto', sans-serif;
        }
    
    /* Añado la declaración de font-family, para usar la fuente de Google Fonts en este PDF */
        .column {
        position: relative;

        display: inline-block;
        overflow: hidden;
        vertical-align: top;
        }
        .content {
            width: 2.3in;
        }      

    </style>
</head>
 
        <body>
           
                @foreach($listaNombres as $key => $sumatoria)
                <div class="column">
                    <div class="content">
                        {{$sumatoria['Nombre']}}</td>
                        
                    </div>
                    <div class="content">
                       
                        {{$sumatoria['Prenda']}}</td>
                    </div>
                    <br>
                </div>
                @endforeach
           
        </body>

</html>
