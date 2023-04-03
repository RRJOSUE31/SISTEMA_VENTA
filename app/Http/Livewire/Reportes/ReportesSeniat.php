<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Empresa;
use Livewire\Component;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class ReportesSeniat extends Component
{
    public $isopen, $fecha_inicio,$fecha_fin,$empresa;

    protected $listeners = ['render' => 'render'];

    public function open()
    {
        $this->isopen = true;  
        $this->emitTo('reportes.reportes-seniat','render');
    }
    public function close()
    {
        $this->isopen = false;  
        $this->emit('volver');
    }

    public function render()
    {
        return view('livewire.reportes.reportes-seniat');
    }

    public function print(){

            $fecha_inicio = date("Y-m-d H:i", strtotime($this->fecha_inicio));
            $fecha_fin = date("Y-m-d H:i", strtotime($this->fecha_fin));

            $this->empresa = Empresa::first();
    
            $total_ventas = DB::select('SELECT sum(v.total_pagado_cliente) as quantity, sum(v.impuesto) as impuesto , sum(v.exento) as exento  from ventas v
            where v.created_at BETWEEN :fecha_inicioo AND :fecha_finn AND v.estado = "activa"'
            ,array('fecha_inicioo' =>$fecha_inicio, 'fecha_finn' => $fecha_fin));

           
    
            $cantidad_ventas = DB::select('SELECT COUNT(*) as cantidad from ventas v
            where v.created_at BETWEEN :fecha_inicioo AND :fecha_finn AND v.estado = "activa"'
            ,array('fecha_inicioo' =>$fecha_inicio, 'fecha_finn' => $fecha_fin));

            //dd($cantidad_ventas);
    
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
                'fecha_inicio' => date("d-m-Y H:i", strtotime($this->fecha_inicio)),
                'fecha_fin'  => date("d-m-Y H:i", strtotime($this->fecha_fin)),
            ];

            //dd($empresa_datos);
    
            $json = json_encode($total_ventas);
            $json2= json_encode($cantidad_ventas);
         //   $json3 = json_encode($pagos_metodos);
            $json4 = json_encode($empresa_datos);
    
    
            $client = new Client([
                'base_uri' => 'http://apiprint.test',
            ]);
    
            $client->request('GET', '/api/print_reportex/'.$json."/".$json2."/".$json4);
            $this->isopen = false;  
           $this->emit('volver');
        
    }
}
