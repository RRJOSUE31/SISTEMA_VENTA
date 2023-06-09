<?php

use App\Http\Controllers\Admin\CajasController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\ClientesController;
use App\Http\Controllers\Admin\ComprasController;
use App\Http\Controllers\Admin\MarcasController;
use App\Http\Controllers\Admin\MetodosPagoController;
use App\Http\Controllers\Admin\ModelosController;
use App\Http\Controllers\Admin\ProveedoresController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SucursalesController;
use App\Http\Controllers\AjustesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Movimientos\MovimientoController;
use App\Http\Controllers\Productos\FilesController;
use App\Http\Controllers\Productos\MovimientosController;
use App\Http\Controllers\Productos\ProductosController;
use App\Http\Controllers\Proformas\ProformasController;
use App\Http\Controllers\Reportes\ReportesController;
use App\Http\Controllers\Reportes\ReportesSeniatController;
use App\Http\Controllers\tasaController;
use App\Http\Controllers\Ventas\FacturacionController;
use App\Http\Controllers\Ventas\MostrarVentasController;
use App\Http\Controllers\Ventas\VentasController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Ventas\VentasViewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

/*
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');*/

Auth::routes();

//Gestion administrativa
Route::middleware(['auth'])->group(function()
{
    Route::get('/home',[HomeController::class,'index'])->name('home');
    Route::resource('usuarios', UsuarioController::class)->only('index','create')->names('admin.usuarios')->middleware('permission:admin.usuarios.index');
    Route::resource('roles', RoleController::class)->only('index','edit','update','destroy','create','store')->names('admin.roles')->middleware('permission:productos');
    Route::resource('clientes', ClientesController::class)->only('index')->names('admin.clientes')->middleware('permission:admin.clientes.index');
    Route::resource('proveedores', ProveedoresController::class)->only('index')->names('admin.proveedores')->middleware('permission:admin.proveedores.index');
    Route::resource('sucursales', SucursalesController::class)->only('index')->names('admin.sucursales')->middleware('permission:admin.sucursales.index');

    Route::resource('categorias', CategoriasController::class)->only('index','store')->names('admin.categorias')->middleware('permission:admin.categorias.index');
    Route::resource('marcas', MarcasController::class)->only('index','store')->names('admin.marcas')->middleware('permission:admin.marcas.index');
    Route::resource('modelos', ModelosController::class)->only('index','store')->names('admin.modelos')->middleware('permission:admin.modelos.index');
    Route::resource('tasa', tasaController::class)->only('index','edit')->names('admin.tasa')->middleware('permission:admin.tasa.index');
    Route::get('cajas', [CajasController::class, 'index'])->name('admin.index.cajas')->middleware('permission:admin.index.cajas');
    Route::get('metodos_pago', [MetodosPagoController::class, 'index'])->name('admin.index.metodos')->middleware('permission:admin.index.metodos');

    Route::resource('productos', ProductosController::class)->only('index','create','edit','store')->names('productos.productos')->middleware('permission:productos.lotes.index');
    Route::get('productos_lotes',[MovimientosController::class,'productos_lotes'])->name('productos.lotes.index')->middleware('permission:productos.lotes.index');
    Route::resource('Ventas', VentasController::class)->only('create','index','update','show','edit')->names('ventas.ventas')->middleware('permission:ventas.ventas.index');
    Route::resource('Mostrar_ventas', MostrarVentasController::class)->only('create','index','edit','update','show')->names('ventas.mostrar_ventas');
    Route::get('compras',[ComprasController::class,'index'])->name('admin.compras.index')->middleware('permission:admin.compras.index');
    Route::get('ventas/{sucursal}/{proforma}',[VentasController::class,'edit'])->name('ventas.ventas.edits');
    Route::post('compras_import',[ComprasController::class,'store'])->name('admin.compras.store');

    Route::get('facturacion/{sucursal}/{proforma}',[FacturacionController::class,'facturacion'])->name('facturacion');

    Route::get('recibir_productos',[MovimientosController::class,'index_recibir'])->name('traslado_recibir.index')->middleware('permission:productos.traslado');
    Route::get('enviar_productos',[MovimientosController::class,'index_enviar'])->name('traslado_enviar.index')->middleware('permission:productos.traslado');
    Route::get('recibir_productos/{sucursal}',[MovimientosController::class,'select'])->name('productos.traslado.select')->middleware('permission:productos.traslado');
    Route::get('enviar_productos/{sucursal}',[MovimientosController::class,'select_enviar'])->name('productos.traslado.select.enviar')->middleware('permission:productos.traslado');
    Route::get('traslado/{sucursal}/{producto}',[MovimientosController::class,'select_serial'])->name('productos.traslado.serial')->middleware('permission:productos.traslado');

    //Mostrar ventas al contado y a credito
    Route::get('Mostrar_ventas/{sucursal}/{tipo}',[MovimientosController::class,'mostrar_ventas'])->name('mostrar.ventas');
    
    //Mostrar ventas por clientes
   /* Route::get('ventas_clientes',[VentasViewController::class,'index'])->name('ventas.clientes')->middleware('permission:productos');
    Route::get('ventas_clientes/{sucursal}',[VentasViewController::class,'view'])->name('ventas.clientes.view')->middleware('permission:productos');*/

   /* Route::get('devolucion',[MovimientosController::class,'devolucion'])->name('devolucion.index');
    Route::get('devolucion_registro',[MovimientosController::class,'devolucion_create'])->name('devolucion.create');

    //Proformas
    Route::get('Proforma',[ProformasController::class,'index'])->name('proformas.proformas.index');
    Route::get('Proforma_lista',[ProformasController::class,'view'])->name('proformas.view');
    Route::get('Ventas/{sucursal}/{proforma}',[ProformasController::class,'seleccio'])->name('ventas.seleccio');

    //Movimientos en caja
    Route::get('Movimientos_caja',[MovimientoController::class,'index'])->name('movimiento.caja.index');
    Route::get('Movimientos_caja_pendiente',[MovimientoController::class,'index_pendiente'])->name('movimientos.caja.index.pendiente');
    Route::get('Nuevo_movimiento_caja/{sucursal}',[MovimientoController::class,'view'])->name('movimiento.caja.view');
    Route::get('Nuevo_movimiento_pendiente_caja/{sucursal}',[MovimientoController::class,'view_pendiente'])->name('movimiento.caja_pendiente.view');*/

    //reportes de historial de productos
    //Route::get('historial_modalidad',[MovimientosController::class,'select_modalidad'])->name('movimientos.modalidad');
    //Route::post('historial_modalidad',[MovimientosController::class,'buscar'])->name('movimientos.buscar');
   // Route::get('historial_modalidad/productos_cod_barra',[MovimientosController::class,'historial'])->name('movimientos.historial');
   // Route::get('historial_modalidad/productos_serial',[MovimientosController::class,'historial_prod_serial'])->name('movimientos.historial_prod_serial');
    //Route::get('historial_modalidad/{vista}/{producto}/{fecha_inicio}/{fecha_fin}',[MovimientosController::class,'historial_detalle'])->name('movimientos.historial.detalle');

    //Productos mas vendidos
    Route::get('reporte_poducto',[ReportesController::class,'index_producto'])->name('reportes.index.productos')->middleware('permission:reportes.productos');
    Route::get('reportes_productos/{sucursal_id}/{fecha_inicio}/{fecha_fin}',[ReportesController::class,'productos'])->name('productos.reportes')->middleware('permission:reportes.productos');
    
    //Ventas
    Route::get('reporte_venta',[ReportesController::class,'index_venta'])->name('reportes.index.ventas')->middleware('permission:reportes.ventas');
    Route::get('reportes_ventas/{sucursal_id}/{fecha_inicio}/{fecha_fin}',[ReportesController::class,'ventas'])->name('ventas.reportes')->middleware('permission:reportes.ventas');
    
    //Traslados
    Route::get('reporte_traslados',[ReportesController::class,'index_traslados'])->name('reportes.index.traslados')->middleware('permission:reportes.traslado');
    Route::get('reportes_traslados/{fecha_inicio}/{fecha_fin}',[ReportesController::class,'traslados'])->name('traslados.reportes')->middleware('permission:reportes.traslado');
    
    //reporte de kardex
    Route::get('reporte_kardex',[ReportesController::class,'index_kardex'])->name('reportes.index.kardex')->middleware('permission:reportes.movimientos');
    Route::get('reportes_kardex/{fecha_inicio}/{fecha_fin}',[ReportesController::class,'kardex'])->name('kardex.reportes')->middleware('permission:reportes.movimientos');

    //reporte x y z
    Route::get('reporte_dia',[ReportesSeniatController::class,'reportex'])->name('reporte.x')->middleware('permission:reportes.reportes_seniat');
    Route::get('reportes_por_rango',[ReportesSeniatController::class,'reportez'])->name('reporte.z')->middleware('permission:reportes.reportes_seniat');
    
    //Productos por agotarse
    Route::get('productos_por_agotar',[ReportesController::class,'producto_agotar'])->name('reportes.producto_agotar')->middleware('permission:reportes.producto_agotar');
    
    //Productos por vencer
    Route::get('productos_por_vencer',[ReportesController::class,'producto_vencer'])->name('reportes.producto_vencer')->middleware('permission:reportes.producto_vencer');

    //Reporte de iva,exento,total
    Route::get('iva',[ReportesController::class,'iva_index'])->name('reportes.iva')->middleware('permission:reportes.producto_vencer');
    Route::get('reporte_iva/{sucursal_id}/{fecha_inicio}/{fecha_fin}',[ReportesController::class,'iva'])->name('reportes.iva_index')->middleware('permission:reportes.ventas');
   
    //Cargar imagen de producto
    Route::post('productos/{product}/files', [FilesController::class, 'files'])->name('productos.files');

    //Ajustes
    Route::get('cambiar_contrasena',[AjustesController::class,'ccontrasena'])->name('ajustes.ccontrasena')->middleware('permission:ajustes.contrasena');
    Route::get('sobre_empresa',[AjustesController::class,'empresa'])->name('ajustes.empresa')->middleware('permission:ajustes.empresa');
    Route::get('cambiar_moneda',[AjustesController::class,'moneda'])->name('cambiar-moneda');
    Route::get('aperturar_caja',[AjustesController::class,'aperturaCaja'])->name('apertura-caja.index')->middleware('permission:apertura-caja.index');
    Route::get('cuentas_por_pagar',[AjustesController::class,'cuentasPagar'])->name('cuentas-pagar.index')->middleware('permission:reportes.cuentas-pagar');
    Route::get('cuentas_por_cobrar',[AjustesController::class,'cuentasCobrar'])->name('cuentas-cobrar.index')->middleware('permission:preportes.cuentas-cobrar');
});

