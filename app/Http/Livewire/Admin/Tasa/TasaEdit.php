<?php

namespace App\Http\Livewire\Admin\Tasa;

use App\Models\Producto_lote;
use App\Models\tasa_dia;
use Livewire\Component;

class TasaEdit extends Component
{
    public function render()
    {
        return view('livewire.admin.tasa.tasa-edit');
    }

    public $tasa, $tasa_dia;
    public $isopen = false;

    protected $rules = [
        'tasa_dia' => 'required',
    ];

    public function mount(){
       $this->tasa_dia = $this->tasa->tasa;
    }

    public function open()
    {
        $this->isopen = true;  
    }
    
    public function close()
    {
        $this->isopen = false;  
    }

    public function save(){

        $rules = $this->rules;
        $this->validate($rules);

        $this->tasa->update([
            'tasa' => $this->tasa_dia,
        ]);

        $productos_lotes = Producto_lote::where('moneda_id',$this->tasa->moneda->id)->get();

        foreach ($productos_lotes as $producto_lote){

            if($producto_lote->precio_letal > 0) $precio_letal = round((($producto_lote->precio_entrada / (1 - ($producto_lote->margen_letal / 100))) * $this->tasa_dia),2); else $precio_letal = 0;
            if($producto_lote->precio_mayor > 0) $precio_mayor = round((($producto_lote->precio_entrada / (1 - ($producto_lote->margen_mayor / 100))) * $this->tasa_dia),2); else $precio_mayor = 0;
            if($producto_lote->precio_combo > 0) $precio_combo = round((($producto_lote->precio_entrada / (1 - ($producto_lote->margen_combo / 100))) * $this->tasa_dia),2); else $precio_combo = 0;
            if($producto_lote->utilidad_letal > 0) $utilidad_letal = round((($precio_letal - $producto_lote->precio_entrada)* $this->tasa_dia),2); else $utilidad_letal = 0;
            if($producto_lote->utilidad_mayor > 0) $utilidad_mayor = round((($precio_mayor - $producto_lote->precio_entrada)* $this->tasa_dia),2); else $utilidad_mayor = 0;
            if($producto_lote->utilidad_combo > 0) $utilidad_combo = round((($precio_combo - $producto_lote->precio_entrada)* $this->tasa_dia),2); else $utilidad_combo = 0;
            
            
            $producto_lote->update([
                "precio_letal" => $precio_letal,
                "precio_mayor" => $precio_mayor,
                "precio_combo" => $precio_combo,
                "utilidad_letal" => $utilidad_letal,
                "utilidad_mayor" => $utilidad_mayor,
                "utilidad_combo" => $utilidad_combo,
            ]);
    
        }

        $this->reset(['isopen']);
        $this->emitTo('admin.tasa.tasa-index','render');
        $this->emit('alert','Tasa modificada correctamente');
    }
}
