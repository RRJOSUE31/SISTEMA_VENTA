<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de totales (iva,exento y totales en venta)</title>
    <style>
        body{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px
        }

        #datos{
            text-align: left;
            float: left;
            margin-top: 0%;
            margin-left: 0%;
            margin-right: 0%;
           
        }

        #datos p{
            text-align: left;

           
        }

        .fechas{
            text-align: center;
            font-weight: bold;

           
        }

        #fo{
            text-align: center;
            font-size: 10px;
        }

        #encabezado {
            text-align: left;
            margin-left: 35%;
            margin-right: 35%;
            font-size: 15px;
        }

        #fact{
            float: right;
            text-align: center;
            margin-top: 2%;
            margin-left: 2%;
            margin-right: 2%;
            font-size: 20px;
            background: #33afff;
            border-radius: 8px;
            font-weight: bold;
        }

        #cliente{
            text-align: left;
        }

        section{
            clear: left;
        }

        #fact,
        #fv,
        #fa {
            color: #ffffff;
            font-size: 15px;            
        }

        #faproveedor {
            width: 40%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 15px;
        }


        #faproveedor thead{
            padding: 20px;
            background: #33afff;
            text-align: left;
            border-bottom: 1px solid #ffffff;

        }

        #faccomprador {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            border-spacing: 0;
            margin-bottom: 15px;
        }

        #faccomprador thead{
            padding: 20px;
            background: #33afff;
            text-align: center;
            border-bottom: 1px solid #ffffff;

        }

        #facproducto {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            text-align: center;
            border: 1px solid #000;
            margin-bottom: 15px;
        }

        #facproducto thead{
            padding: 20px;
            background: #33afff;
            text-align: center;
            border-bottom: 1px solid #ffffff;
        }

        img{
            margin-left: 0%;
            width: 115px;
        }


    </style>
</head>
<body>
    <header>
        <div>
            <img src="storage/logo/logo.png" alt="logo">
        </div>
        <div>
            <table id="datos">
                <thead>
                    <tr>
                      <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>
                            <p>
                                Fecha : {{$fecha}} <br>
                                Sucursal: {{$sucursal}} <br>
                                Usuario: {{$cajero}}<br>
     
                            </p>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>
        
    </header>
    <br>
    <br>
    <br>
    <br>

    <section>
        <div>
            <p class="fechas">Reporte de totales (iva,exento y totales en venta)</p>
        </div>

     
        <div>
            <table id="facproducto">
                <thead>
                    <tr id="fa">
                        <th class="text-center">Cantidad de ventas</th>
                        <th class="text-center">Total de ganancia</th>
                        <th class="text-center">Total IVA</th>
                        <th class="text-center">Total Exento</th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                        <td class="text-center">{{$total_cant_ventas}}</td>
                        <td class="text-center">{{ $moneda_simbolo }}  {{round($total_ventas_ganancias/ $tasa_dia),2}}</td>
                        <td class="text-center">{{ $moneda_simbolo }}  {{ round($total_iva/ $tasa_dia),2}}</td>
                        <td class="text-center">{{ $moneda_simbolo }}  {{ round($total_exento/ $tasa_dia),2}}</td>
                        </tr>
                     
                </tbody>
            </table>
        </div>

        <div>
            <p class="fechas">Totales en métodos de pago</p>
        </div>

        <div>
            <table id="facproducto">
                <thead>
                    <tr id="fa">
                        <th>Método de pago</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                @if ($array_metodos_pago)
                    @foreach ($array_metodos_pago as $value)
                        <tr>
                            <td>{{$value['metodo_nombre']}}</td>
                            <td>{{ $moneda_simbolo }}{{ round( $value['quantity']/ $tasa_dia),2}}</td>
                        </tr>
                    @endforeach
                @endif  
                </tbody>
            </table>
        </div>

    </section>
    <br>
    <br>

</body>
</html>