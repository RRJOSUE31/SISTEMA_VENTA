<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePrecioToProductoLotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_lotes', function (Blueprint $table) {
            $table->decimal('precio_entrada',12,5)->change()->nullable();
          
            $table->decimal('precio_letal',12,5)->change()->nullable();
            $table->decimal('precio_mayor',12,5)->change()->nullable();
            $table->decimal('precio_combo',12,5)->change()->nullable();
            $table->decimal('utilidad_letal',12,5)->change()->nullable();
            $table->decimal('utilidad_mayor',12,5)->change()->nullable();
            $table->decimal('utilidad_combo',12,5)->change()->nullable();
            $table->decimal('margen_letal',12,5)->change()->nullable();
            $table->decimal('margen_mayor',12,5)->change()->nullable();
            $table->decimal('margen_combo',12,5)->change()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_lotes', function (Blueprint $table) {
            //
        });
    }
}
