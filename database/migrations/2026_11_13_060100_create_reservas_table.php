<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 12)->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('tipo_habitacion_id')->constrained('tipos_habitacion');
            // La habitación física se asigna recién en el check-in
            $table->foreignId('habitacion_id')->nullable()->constrained('habitaciones');
            $table->date('fecha_entrada');
            $table->date('fecha_salida');
            $table->unsignedTinyInteger('huespedes');
            $table->unsignedTinyInteger('noches');
            $table->decimal('precio_noche', 8, 2);
            $table->decimal('total_estadia', 10, 2);
            $table->string('estado', 20)->default('Confirmada');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
