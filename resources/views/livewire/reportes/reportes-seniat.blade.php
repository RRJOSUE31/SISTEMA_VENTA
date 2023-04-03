<div>
    @if ($isopen)
        <div class="modal d-block" tabindex="-1" role="dialog" style="overflow-y: auto; display: block;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title py-0 text-lg text-gray-800"> <i class="fas fa-check-double"></i>  Imprimir reporte entre periodos de tiempo</h5>
                    </div>
                    <div class="modal-body">
                        <h2 class="text-sm ml-2 m-0 p-0 text-gray-500 font-semibold"><i class="fas fa-info-circle"></i> Seleccione las fechas requeridas y haga click en imprimir</h2> 
                        <hr>
                        <div class="lg:flex justify-items-stretch w-full ml-1">
                            <div>
                                <div wire:ignore x-data="datepicker()">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <input 
                                                type="text" 
                                                class="px-4 outline-none cursor-pointer" 
                                                x-ref="myDatepicker" 
                                                wire:model="fecha_inicio" 
                                                placeholder="Seleccione la fecha inicio">
                                
                                                
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="ml-2 mr-1 text-gray-700 font-semibold">-</p>
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
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" wire:click="close">Cerrar</button>
                        <button type="button" class="btn btn-primary" wire:click="print()">Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('js')

    <script>
        Livewire.on('volver', function(){
            window.history.back();      
        })
    </script>

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
    @endpush


</div>

