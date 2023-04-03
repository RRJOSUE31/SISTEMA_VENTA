<?php

namespace App\Http\Livewire\Ventas;

use App\Models\Producto_lote;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto_sucursal as Pivot;
use App\Models\tasa_dia;
use App\Models\User;
use Livewire\Component;

class VentasSeleccionCantidades extends Component
{

    protected $listeners = ['render' => 'render'];

    public $quantity,$producto, $sucursal, $precios = 1, $usuario, $change_price, $precio_manual;
    public $qty = 1;

    public $options = [
        'exento' => null,
        'modelo' => null,
    ];

    protected $rule_precio_manual = [
        'precio_manual' => 'required',
    ];

    public function decrement(){
        $this->qty = $this->qty - 1;
    }

    public function increment(){
        $this->qty = $this->qty + 1;
    }

    public function mount(){
        $product=$this->producto->producto_id;
        $sucur=$this->sucursal;
        $producto_lote = Producto_lote::where('id',$this->producto->id)
            ->first();

    $this->quantity = qty_available($product,$sucur,$producto_lote);
    }


    public function addItem(){

        $this->tasa_metodo = tasa_dia::where('moneda_id',2)->first()->tasa;

        if($this->precios == '3'){
            $rule_precio_manual = $this->rule_precio_manual;
            $this->validate($rule_precio_manual);
        }

        $producto_lote = Producto_lote::where('id',$this->producto->id)
            ->first();

        if($this->precios == 1){
            $precio_venta = $producto_lote->precio_letal;
            $precio_dolares = round((($producto_lote->precio_entrada / (1 - ($producto_lote->margen_letal / 100)))),2);

        } 
        elseif($this->precios == 2){
            $precio_venta = $producto_lote->precio_mayor;
            $precio_dolares = round((($producto_lote->precio_entrada / (1 - ($producto_lote->margen_mayor / 100)))),2);

        } 
        elseif($this->precios == 3){
            $precio_venta = $this->precio_manual;
            $precio_dolares = $this->precio_manual / $this->tasa_metodo;

        } 
        elseif($this->precios == 4){
            $precio_venta = $producto_lote->precio_combo;
            $precio_dolares = round((($producto_lote->precio_entrada / (1 - ($producto_lote->margen_combo / 100)))),2);
        } 
            
        $this->options['exento'] = $this->producto->producto->exento;
        $this->options['moneda_compra'] = $producto_lote->moneda_id;
        $this->options['precio_dolares'] = $precio_dolares;
        $this->options['descuento'] = $this->producto->producto->descuento;
        $this->options['modelo'] = quitar_acentos($this->producto->producto->modelo->nombre);
        $this->options['lote'] = $producto_lote->id;


        Cart::add([ 'id' => $this->producto->producto_id, 
            'name' => quitar_acentos($this->producto->producto->nombre), 
            'qty' => $this->qty, 
            'price' => $precio_venta, 
            'weight' => 0,
            'options' => $this->options,
        ]);

        $this->quantity = qty_available($this->producto->producto_id,$this->sucursal,$producto_lote);
        $this->reset('precios','qty');

        $this->emitTo('ventas.ventas-cart','render');
    
    }

    public function render()
    {

        $usuario_aut = User::where('id',$this->usuario)->first();

        if($usuario_aut->changePrice == 'si') $this->change_price = 'si';
        elseif ($usuario_aut->changePrice == 'no') $this->change_price = 'no';
        return view('livewire.ventas.ventas-seleccion-cantidades');
    }
}
