<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->string('tipo', 20); // ENTRADA, SALIDA, AJUSTE
            $table->string('origen', 30); // COMPRA, VENTA, AJUSTE_MANUAL
            $table->unsignedBigInteger('origen_id')->nullable(); // id de compra o venta
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->decimal('costo_unitario', 10, 2)->default(0);
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->index('producto_id');
            $table->index('tipo');
            $table->index('origen');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos_stock');
    }
};
