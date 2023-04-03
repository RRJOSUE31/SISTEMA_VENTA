<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de cierre de caja</title>
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

        #f{
            padding: 10px;
            background: #33afff;
            text-align: center;
            border-bottom: 1px solid #000;
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
                                Reporte emitido por: {{$usuario_nombre}} {{$usuario_apellido}}<br>
                                Fecha: {{$fecha}}<br>
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
            <p class="fechas">CUENTAS POR PAGAR</p>
        </div>


        <div>
            <table id="facproducto">
                <thead>
                    <tr id="fa">
                        <th>Fecha</th>
                        <th>Compra Nro</th>
                        <th>Sucursal de compra</th>
                        <th>Proveedor</th>
                        <th>Deuda</th>
                    </tr>
                </thead>
                <tbody>
                @if ($cuentas)
                    @foreach ($cuentas as $cuenta)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($cuenta->fecha)->format('d-m-Y')}}</td>
                            <td>{{$cuenta->id}}</td>
                            <td>{{$cuenta->sucursal->nombre}}</td>
                            <td>{{$cuenta->proveedor->nombre_proveedor}} </td>
                            <td>{{ $moneda_simbolo }} {{ round(($cuenta->deuda_a_proveedor/ $tasa_dia),2)}} </td>
                        </tr>
                    @endforeach
                @endif  
                
                      
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <p align="center"></p>
                        </td>
                        
                        <td>
                            <p align="center"></p>
                        </td>
                        <td>
                            <p align="center"></p>
                        </td>
                        <td>
                            <p align="center"></p>
                        </td>
                      
                        <td>
                            <p align="center">{{ $moneda_simbolo }} {{round(($total/ $tasa_dia),2)}}</p>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </section>
    <br>
    <br>

</body>
</html>