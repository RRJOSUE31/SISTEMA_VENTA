<?php

namespace App\Http\Livewire\Ventas;

use App\Models\MovimientoCaja;
use App\Models\Pago_venta;
use App\Models\Producto;
use App\Models\Producto_lote;
use App\Models\Producto_venta;
use App\Models\Sucursal;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class VentasContado extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";


    protected $listeners = ['render' => 'render','confirmacion' => 'confirmacion'];

    public $search,$sucursal,$buscador=0,$fecha_inicio,$fecha_fin;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {
      
        if($this->search || $this->fecha_fin){
            
           
            if($this->buscador == 0){
                $ventas = Venta::whereHas('cliente',function(Builder $query){
                    $query
                        ->where('nro_documento','LIKE', '%' . $this->search . '%')
                        ->orwhere('nombre','LIKE', '%' . $this->search . '%')
                        ->orwhere('apellido','LIKE', '%' . $this->search . '%');
                })
                ->where('estado', 'activa')
                ->where('sucursal_id',$this->sucursal)
                ->where('tipo_pago', 'Contado')
                ->latest('id')
                ->paginate(10);
            }

            else{

                $fecha_inicio = date("Y-m-d H:i", strtotime($this->fecha_inicio));
                $fecha_fin = date("Y-m-d H:i", strtotime($this->fecha_fin));

                $ventas = Venta::whereBetween('created_at',[$fecha_inicio,$fecha_fin])
                    ->where('estado', 'activa')
                    ->where('tipo_pago', 'Contado')
                    ->where('sucursal_id',$this->sucursal)
                    ->latest('id')
                    ->paginate(10);
            }
        }
        else{
            $ventas = 0;
        }

        return view('livewire.ventas.ventas-contado',compact('ventas'));
    }

    public function delete($ventaId){
        $this->venta = $ventaId;

        $this->emit('confirm', 'Esta seguro de anular esta venta?','ventas.ventas-contado','confirmacion','La venta se ha anulado.');
    }

    public function confirmacion(){
        $venta_destroy = Venta::where('id',$this->venta)->first();
        $venta_destroy->update([
            'estado' => 'anulada',
        ]);

        $movimiento = MovimientoCaja::where('venta_id',$venta_destroy->id)->first();
        $movimiento->delete();

        $pago_ventas = Pago_venta::where('venta_id',$venta_destroy->id)->first();
        $pago_ventas->delete();

        $productos_venta = Producto_venta::where('venta_id',$venta_destroy->id)->get();

        foreach($productos_venta as $producto_venta){

            //incrementando en tabla productos

            $buscar_producto = Producto::where('id',$producto_venta->producto_id)->first();
            $buscar_producto->increment('cantidad',$producto_venta->cantidad);

            //incrementando en tabla producto_lotes

            $producto_lote = Producto_lote::where('status','activo')
                ->where('producto_id',$producto_venta->producto_id)
                ->first();

            $producto_lote->increment('stock',$producto_venta->cantidad);
        }


        $this->resetPage();
    }

    public function ayuda(){
        $this->emit('ayuda','<p class="text-sm text-gray-500 m-0 p-0 text-justify">1-. Ver detalles de la venta: Haga click en el botón "<i class="fas fa-file-invoice"></i>", ubicado al lado de cada venta.</p> 
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">2-.Anular venta: Haga click en el botón "<i class="fas fa-trash-alt"></i>" ubicado al lado de cada venta, la venta no se podrá restablecer, debe estar seguro de realizar esta acción.</p>');
    }

   
}
