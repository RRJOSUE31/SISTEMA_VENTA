<?php

namespace App\Http\Livewire\Ventas;

use App\Models\MovimientoCaja;
use App\Models\Pago_venta;
use App\Models\Producto;
use App\Models\Producto_lote;
use App\Models\Producto_venta;
use App\Models\Venta;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class VentasPorCliente extends Component
{

    use WithPagination;
    protected $paginationTheme = "bootstrap";

    public $productos,$sucursal, $collection = [];


    protected $listeners = ['render' => 'render','confirmacion' => 'confirmacion'];

    public $search;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {
        if($this->search){
            $ventas = Venta::whereHas('cliente',function(Builder $query){
                $query
                    ->where('nro_documento','LIKE', '%' . $this->search . '%')
                    ->orwhere('nombre','LIKE', '%' . $this->search . '%')
                    ->orwhere('apellido','LIKE', '%' . $this->search . '%')
                ->where('estado', 'activa')
                ->where('sucursal_id',$this->sucursal);
             })->latest('id')
             ->paginate(5);
        }
        else{
            $ventas = [];
        }
        

        return view('livewire.ventas.ventas-por-cliente',compact('ventas'));
    }

    public function delete($ventaId){
        $this->venta = $ventaId;

        $this->emit('confirm', 'Esta seguro de anular esta venta?','ventas.ventas-por-cliente','confirmacion','La venta se ha anulado.');
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
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">2-. Agregar pago de cliente: Haga click en el botón "<i class="fas fa-plus-square"></i>", ubicado al lado de cada venta, luego debe ingresar el monto abonado por el cliente y haga click en "Guardar".</p> 
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">3-.Anular venta: Haga click en el botón "<i class="fas fa-trash-alt"></i>" ubicado al lado de cada venta, la venta no se podrá restablecer, debe estar seguro de realizar esta acción.</p>');
    }

}
