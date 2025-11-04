<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('prestamos', function (Blueprint $table) {
            $table->id();

            
            $table->foreignId('libro_id')->constrained('libros');
            $table->foreignId('usuario_id')->constrained('usuarios');
            $table->foreignId('empleado_id')->constrained('empleados');

            $table->dateTime('fecha_prestamo');
            $table->dateTime('fecha_devolucion_estimada');
            $table->dateTime('fecha_devolucion_real')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
