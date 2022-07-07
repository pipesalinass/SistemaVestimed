<!DOCTYPE html>
<html>

<head>
    <style>
        /** Define the margins of your page **/

        header {
            position: fixed;
            top: 2px;

        }

        body {
            margin-top: 2cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }

        footer {
            position: fixed;
            bottom: 2px;
        }

        div.page_break {
            page-break-after: always;
            position: relative;

        }

    </style>
</head>

<body>
    <header>
        <table class="headerTable">
            <tr>
                <td style="font-size: 12px;color:#A3A7B3">VESTIMED</td>

            </tr>
            <tr>
                <td style="margin: 0;font-size: 12px;color:#A3A7B3">Eduardo Orchard 502. Antofagasta.</td>
            </tr>
            <tr>
                <td style="margin: 0;font-size: 12px;color:#A3A7B3">Antofagasta</td>
            </tr>
        </table>
        <hr style="border: 0.5px solid #01559641">
    </header>
    <footer>
        <hr style="border: 0.5px solid #01559641; width:420">
        <em style="font-size: 12px; color:#A3A7B3">
            VESTIMED
        </em>
    </footer>
    <main>
        <br>
        <h2 style="color:#015696">Información Pedido</h2>
        <br>
        <table>
            <tr>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Fecha ingreso:</td>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Nombre </td>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Celular </td>
            </tr>
            <tr>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{date('d-m-Y', strtotime($fecha))}}</strong></td>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{$nombrePedido}}</strong>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{$celularPedido}}</strong>
                </td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>

            <tr>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Título </td>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Universidad</td>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Carrera</td>
            </tr>
            <tr>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{$tituloPedido}}</strong></td>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{$universidadPedido}}</strong></td>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{$carreraPedido}}</strong></td>
            </tr>
            <tr>
                <td style="height: 20px;"></td>
            </tr>
            <tr>
                <td style="width: 200px;color:#015696;font: normal 11pt century-gothic, sans-serif;">Descripción </td>

            </tr>
            <tr>
                <td style="width: 200px;font: normal 10pt century-gothic, sans-serif;"><strong>{{$descripcionPedido}}</strong></td>

            </tr>

        </table>

        <br>
        <br>

        <table style="border-collapse:collapse;border-spacing:0;table-layout: fixed; width: 500px"
            class="tg">
            <tr>
                <td style="background-color:#0e5990;border-color:#000000;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;font-weight:normal;overflow:hidden;text-align:center;vertical-align:top;word-break:normal"
                    colspan="6">Prendas pedido</td>
            </tr>
            <tr>
                <td style="width: 550px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal"
                    colspan="6"></td>
            </tr>
            <tr>
                <td
                    style="width: 135px;background-color:#0e5990;border-color:black;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;text-align:center;vertical-align:top;word-break:normal">
                    Tipo</td>
                <td
                    style="width: 135px;background-color:#0e5990;border-color:black;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;text-align:center;vertical-align:top;word-break:normal">
                    Código Modelo</td>
                <td
                    style="width: 135px;background-color:#0e5990;border-color:black;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;text-align:center;vertical-align:top;word-break:normal">
                    Modelo</td>
                <td
                    style="width: 135px;background-color:#0e5990;border-color:black;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;text-align:center;vertical-align:top;word-break:normal">
                    Talla</td>
                <td
                    style="width: 135px;background-color:#0e5990;border-color:black;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;text-align:center;vertical-align:top;word-break:normal">
                    Color</td>
                <td
                    style="width: 135px;background-color:#0e5990;border-color:black;border-style:solid;border-width:1px;color:#ffffff;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;text-align:center;vertical-align:top;word-break:normal">
                    Cantidad</td>
            </tr>
            @foreach($listaSumatoria as $key => $sumatoria)
            <tr>
                <td
                    style="width: 135px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal">
                    {{$sumatoria['TipoPrenda']}}</td>
                <td
                    style="width: 135px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal">
                    {{$sumatoria['ModCodigo']}}</td>
                <td
                    style="width: 135px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal">
                    {{$sumatoria['ModNombre']}}</td>
                <td
                    style="width: 135px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal">
                    {{$sumatoria['TallajeTalla']}}</td>
                <td
                    style="width: 135px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal">
                    {{$sumatoria['ColorPrenda']}}</td>
                <td
                    style="width: 135px;border-color:#000000;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;overflow:hidden;padding:10px 5px;text-align:center;vertical-align:top;word-break:normal">
                    {{$sumatoria['sumatoria']}}</td>
            </tr>
            @endforeach

        </table>

       

    </main>
</body>

</html>
