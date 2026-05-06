<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->string('numero_comprobante', 50)->unique();
            $table->string('tipo_comprobante', 20)->default('BOLETA'); // BOLETA, FACTURA, TICKET
            $table->date('fecha');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('igv', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('metodo_pago', 30)->default('EFECTIVO'); // EFECTIVO, TARJETA, YAPE
            $table->decimal('monto_pagado', 10, 2)->default(0);
            $table->decimal('vuelto', 10, 2)->default(0);
            $table->string('estado', 20)->default('COMPLETADA'); // COMPLETADA, ANULADA
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->index('fecha');
            $table->index('estado');
            $table->index('cliente_id');
            $table->index('numero_comprobante');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
