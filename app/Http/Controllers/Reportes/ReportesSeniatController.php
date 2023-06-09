<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class ReportesSeniatController extends Controller
{
    public function reportex(){

        $fecha_actual = date('Y-m-d');
        $fecha_inicio = date('d-m-Y'.' 00:00');
        $fecha_fin = date('d-m-Y H:i');

        $this->empresa = Empresa::first();

        $total_ventas = DB::select('SELECT sum(v.total_pagado_cliente) as quantity, sum(v.impuesto) as impuesto , sum(v.exento) as exento  from ventas v
        where v.fecha = :fecha_actual AND v.estado = "activa"'
        ,array('fecha_actual' => $fecha_actual));

        $cantidad_ventas = DB::select('SELECT COUNT(*) as cantidad from ventas v
        where v.fecha = :fecha_actual AND v.estado = "activa"' 
        ,array('fecha_actual' => $fecha_actual));

        /*$pagos_metodos = DB::select('SELECT sum(pv.monto) as quantity, mp.nombre as metodo_nombre from pago_ventas pv
        right join metodo_pagos mp on pv.metodo_pago_id = mp.id
        inner join ventas v on pv.venta_id=v.id  where v.created_at >= :fecha_actual
        group by mp.nombre order by sum(pv.monto) desc',array('fecha_actual' => $fecha_actual));*/

        $empresa_datos = [
            'empresa_nombre' => quitar_acentos($this->empresa->nombre),
            'empresa_tipo_documento' => $this->empresa->tipo_documento,
            'empresa_documento' => quitar_acentos($this->empresa->nro_documento),
            'empresa_direccion' => quitar_acentos($this->empresa->direccion),
            'empresa_telefono' => quitar_acentos($this->empresa->telefono),
            'empresa_email' => quitar_acentos($this->empresa->email),
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin'  => $fecha_fin,
        ];

        $json = json_encode($total_ventas);
        $json2= json_encode($cantidad_ventas);
     //   $json3 = json_encode($pagos_metodos);
        $json4 = json_encode($empresa_datos);


        $client = new Client([
            'base_uri' => 'http://apiprint.test',
        ]);

        $client->request('GET', '/api/print_reportex/'.$json."/".$json2."/".$json4);

        return redirect(request()->header('Referer'));
    }

    public function reportez(){
        $isopen = 'true';
        return view('reportes.reporte_seniat',compact('isopen'));
    }
    
}
