<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compras', function (Blueprint $table) {
    $table->id();
    $table->foreignId('proveedor_id')->constrained();
    $table->string('tipo_comprobante', 20); // FACTURA, BOLETA, etc.
    $table->string('num_comprobante', 50);
    $table->date('fecha');
    $table->decimal('subtotal', 10, 2);
    $table->decimal('igv', 10, 2)->default(0); // Lo dejamos pero en 0
    $table->decimal('total', 10, 2);
    $table->text('observaciones')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
