<?php

namespace App\Http\Livewire\Productos;

use App\Models\Moneda;
use App\Models\Producto;
use App\Models\Producto_lote;
use App\Models\Producto_sucursal;
use App\Models\tasa_dia;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class ProductosLote extends Component
{
    use WithPagination;
    protected $paginationTheme = "bootstrap";

    protected $listeners = ['render' => 'render','confirmacion' => 'confirmacion'];


    public $buscador="0",$search, $producto,$item_buscar,$tasa_dia,$moneda_nombre,$moneda_simbolo,$producto_lote;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {

        if($this->search){
            if ($this->buscador == "0"){
                $lotes = Producto_lote::whereHas('producto',function(Builder $query){
                    $query->where('cod_barra', 'LIKE', '%' . $this->search . '%')
                            ->orwhere('nombre', 'LIKE', '%' . $this->search . '%');
                    //->where('estado','Habilitado');
                })
                    //->where('status','activo')
                    ->latest('id')
                    ->paginate(20);
            }
            elseif($this->buscador == '1'){
                $lotes = Producto_lote::whereHas('producto.categoria',function(Builder $query){
                    $query->where('nombre', 'LIKE', '%' . $this->search . '%');
                   // ->where('estado','Habilitado');
                })
                    //->where('status','activo')
                    ->latest('id')
                    ->paginate(20);
            }
            elseif($this->buscador == '2'){
                $lotes = Producto_lote::whereHas('producto.marca',function(Builder $query){
                    $query->where('nombre', 'LIKE', '%' . $this->search . '%');
                    //->where('estado','Habilitado');
                })
                    //->where('status','activo')
                    ->latest('id')
                    ->paginate(20);
            }
            elseif($this->buscador == '3'){
                $lotes = Producto_lote::whereHas('producto.modelo',function(Builder $query){
                    $query->where('nombre', 'LIKE', '%' . $this->search . '%');
                   // ->where('estado','Habilitado');
                })
                   // ->where('status','activo')
                    ->latest('id')
                    ->paginate(20);
            }
            if ($this->buscador == "4"){
                $lotes = Producto_lote::whereHas('producto',function(Builder $query){
                    $query->where('nombre', 'LIKE', '%' . $this->search . '%');
                   // ->where('estado','Habilitado');
                })
                   // ->where('status','activo')
                    ->latest('id')
                    ->paginate(20);
            }


        }
        else{
            $lotes = 0;
        }

        if(session()->has('moneda')){
            $this->moneda = Moneda::where('nombre',session('moneda'))->first();
            $this->moneda_nombre = session('moneda');
            $this->moneda_simbolo = session('simbolo_moneda');
            if(session('moneda') == "Bolivar") $this->tasa_dia = 1;
            else $this->tasa_dia = tasa_dia::where('moneda_id',$this->moneda->id)->first()->tasa;
        } 
        else{
            $this->moneda = Moneda::where('nombre','Bolivar')->first();
            $this->moneda_nombre = 'Bolivar';
            $this->moneda_simbolo = 'Bs';
            $this->tasa_dia = 1;
        } 


        
        return view('livewire.productos.productos-lote',compact('lotes'));
    }

    public function delete($loteId){

        $this->producto_lote = $loteId;

        $this->emit('confirm', '¿Esta seguro de eliminar este lote?','productos.productos-lote','confirmacion','El lote se ha eliminado.');
    }

    public function confirmacion(){

        $lote_destroy = Producto_lote::where('id',$this->producto_lote)->first();
        $lote_destroy->update([
            'status' => 'inactivo',
        ]);

        Producto_sucursal::where('producto_id',$lote_destroy->producto_id)
            ->where('lote',$lote_destroy->lote)->update([
            'status' => 'inactivo'
        ]);

        $producto = Producto::where('id',$lote_destroy->producto_id)->first();
        $cantidad_nueva = $producto->cantidad - $lote_destroy->stock;
        $producto->update([
            'cantidad' => $cantidad_nueva
        ]);
    }


    public function ayuda(){
        $this->emit('ayuda','<p class="text-sm text-gray-500 m-0 p-0 text-justify">1-. Registro de equipos: Haga click en el botón "<i class="fas fa-plus-square"></i> Nuevo equipo", ubicado en la zona superior derecha y complete el formulario.</p> 
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">2-.Exportar inventario: Haga click en el botón "<i class="far fa-file-excel"></i> Exportar inventario" ubicado en la zona superior derecha, complete el formulario y haga click en Exportar.</p>
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">3-.Agregar unidades a un tipo de equipo: Haga click en el botón "<i class="fas fa-plus-square"></i>" ubicado eal lado de cada equipo registrado y complete el formulario.</p>
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">4-.Editar información de equipos: Haga click en el botón "<i class="far fa-edit"></i>" ubicado al lado de cada equipo, complete el formulario y haga click en Guardar.</p>
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">5-.Ver stock de equipos por almacen: Haga click en el botón "<i class="fas fa-warehouse"></i>" ubicado al lado del stock de cada equipo y le aparecerá la información detallada por sucursal.</p>
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">6-.Imrpimir códigos de barra: Haga click en el botón "<i class="far fa-file-excel"></i> Exportar inventario" ubicado en la zona superior derecha, complete el formulario y haga click en Exportar.</p>
        <p class="text-sm text-gray-500 m-0 p-0 text-justify">7-.Eliminar equipo: Haga click en el botón "<i class="fas fa-trash-alt"></i>", si el equipo esta asociado a alguna venta no podrá eliminarlo, de lo contrario el sistema solicitará confirmación.</p>');
    }
}
