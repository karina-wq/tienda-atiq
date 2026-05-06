<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->restrictOnDelete();
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 200);
            $table->string('descripcion', 500)->nullable();
            $table->string('unidad', 20)->default('UND'); // UND, KG, LT, MT
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Índices para búsqueda rápida
            $table->index('codigo');
            $table->index('nombre');
            $table->index('activo');
            $table->index('categoria_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
