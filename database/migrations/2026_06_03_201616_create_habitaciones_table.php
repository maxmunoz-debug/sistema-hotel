<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('habitaciones', function (Blueprint $table) {
            $table->id();
            // Clave foránea que conecta con la tabla tipos_habitacion
            $table->foreignId('tipo_habitacion_id')
                  ->constrained('tipos_habitacion')
                  ->onDelete('cascade'); // Si se borra un tipo, se borran sus habitaciones
            
            $table->string('numero'); // Ej: "101", "A-20"
            $table->decimal('precio', 8, 2); // Precio con dos decimales
            $table->string('imagen')->nullable(); // Guardará la ruta de la foto de la habitación
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habitaciones');
    }
};