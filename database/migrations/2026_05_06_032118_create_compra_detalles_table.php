<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compra_id')->constrained('compras')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->restrictOnDelete();
            $table->string('producto_nombre', 200); // snapshot del nombre
            $table->integer('cantidad');
            $table->decimal('precio_compra', 10, 2); // precio real en el momento
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->index('compra_id');
            $table->index('producto_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compra_detalles');
    }
};
