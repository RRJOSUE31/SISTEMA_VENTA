<div  x-data="{buscador: @entangle('buscador')}">
    <div class="card">
        <div class="card-header flex items-center justify-between">
           
            <div class="flex-1">
                <div class="flex">
                    <div class="w-1/4">
                        <select title="Categoría de la busqueda" wire:model="buscador" id="buscador" class="form-control text-m" name="buscador">
                                <option value="0">Por cliente</option>
                                <option value="1">Por fechas</option>
                            </select>
                        <x-input-error for="buscador" />
                    </div>

                    <div class="flex-1 mr-2" :class="{'hidden' : buscador == '1'}">
                        <input wire:model="search" placeholder="Ingrese el nombre o nro de documento del cliente a buscar" class="form-control ml-2">
                    </div>
                    <div :class="{'hidden' : buscador == '0'}">
                        <div class="lg:flex justify-items-stretch w-full ml-4">
                            <div>
                                <div wire:ignore x-data="datepicker()">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="text" 
                                                class="px-4 outline-none cursor-pointer" 
                                                x-ref="myDatepicker" 
                                                wire:model="fecha_inicio" 
                                                placeholder="Seleccione la fecha fin">
                                            <span class="cursor-pointer underline" x-on:click="reset()">
                                            <svg class="h-6 w-5 text-gray-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C5.44772 2 5 2.44772 5 3V4H4C2.89543 4 2 4.89543 2 6V16C2 17.1046 2.89543 18 4 18H16C17.1046 18 18 17.1046 18 16V6C18 4.89543 17.1046 4 16 4H15V3C15 2.44772 14.5523 2 14 2C13.4477 2 13 2.44772 13 3V4H7V3C7 2.44772 6.55228 2 6 2ZM6 7C5.44772 7 5 7.44772 5 8C5 8.55228 5.44772 9 6 9H14C14.5523 9 15 8.55228 15 8C15 7.44772 14.5523 7 14 7H6Z"/>
                                            </svg>
                                                
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="ml-2 mr-2 text-gray-700 font-semibold">-</p>
                            <div>
                                <div wire:ignore x-data="datepicker()" class=" ml-2">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="text" 
                                                class="px-4 outline-none cursor-pointer" 
                                                x-ref="myDatepicker" 
                                                wire:model="fecha_fin" 
                                                placeholder="Seleccione la fecha fin">
                                            <span class="cursor-pointer underline" x-on:click="reset()">
                                            <svg class="h-6 w-5 text-gray-400 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2C5.44772 2 5 2.44772 5 3V4H4C2.89543 4 2 4.89543 2 6V16C2 17.1046 2.89543 18 4 18H16C17.1046 18 18 17.1046 18 16V6C18 4.89543 17.1046 4 16 4H15V3C15 2.44772 14.5523 2 14 2C13.4477 2 13 2.44772 13 3V4H7V3C7 2.44772 6.55228 2 6 2ZM6 7C5.44772 7 5 7.44772 5 8C5 8.55228 5.44772 9 6 9H14C14.5523 9 15 8.55228 15 8C15 7.44772 14.5523 7 14 7H6Z"/>
                                            </svg>
                                                
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ml-2">
                <button
                    title="Ayuda a usuario"
                    class="btn btn-success btn-sm" 
                    wire:click="ayuda"><i class="fas fa-info"></i>
                    Guía rápida
                </button>
            </div>
        </div>
        @if ($ventas != '0' && $ventas->count())
            <div class="card-body mt-0">
                <table class="table table-striped table-responsive-lg table-responsive-md table-responsive-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Cliente</th>
                            <th class="text-center">Cliente - Documento</th>
                            <th class="text-center">Estado de entrega</th>
                            <th class="text-center">Total de venta</th>
                            <th colspan="2"></th>  
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                          
                                <td class="text-center">{{  \Carbon\Carbon::parse($venta->created_at)->format('d-m-Y H:i') }}</td>
                                <td class="text-center">{{$venta->cliente->nombre}} {{$venta->cliente->apellido}}</td>
                                <td class="text-center">{{$venta->cliente->nro_documento}}</td>
                                <td class="text-center">{{$venta->estado_entrega}}</td>
                                <td class="text-center">{{$venta->total}}</td>
                                <td width="10px">
                                    @livewire('ventas.ventas-view', ['venta' => $venta],key('0'.' '.$venta->id)) 
                                </td>
                                <td width="10px">
                                    <button
                                        class="btn btn-danger btn-sm" 
                                        wire:click="delete('{{$venta->id}}')"
                                        title="Anular venta">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
                <div class="card-footer">
                    {{$ventas->links()}}
                </div>
        @else
             <div class="card-body">
                <strong>No hay registros</strong>
            </div>
        @endif
            
    </div>

    <script>
    document.addEventListener('alpine:init',()=>{
        Alpine.data('datepicker',()=>({
            fecha_inicio:null,
            fecha_fin:null,
            init(){
                flatpickr(this.$refs.myDatepicker, {dateFormat:'Y-m-d H:i', altInput:true, enableTime:true, altFormat: 'F j, Y h:i K',})
            },
            reset(){
                this.fecha_inicio= null;
                this.fecha_fin= null;
            }
        }))
    })
</script>
</div>