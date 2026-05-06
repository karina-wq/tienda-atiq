<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200);
            $table->string('tipo_documento', 10)->default('DNI'); // DNI, RUC, CE
            $table->string('numero_documento', 20)->unique()->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Insertar cliente genérico para ventas rápidas
        DB::table('clientes')->insert([
            'nombre'           => 'Cliente General',
            'tipo_documento'   => 'DNI',
            'numero_documento' => '00000000',
            'activo'           => true,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
