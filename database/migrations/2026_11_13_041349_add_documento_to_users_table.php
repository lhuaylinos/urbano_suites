<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('apellidos', 100)->after('name');
            $table->string('tipo_documento', 20)->default('DNI')->after('apellidos');
            $table->string('numero_documento', 20)->unique()->after('tipo_documento');
            $table->string('telefono', 20)->nullable()->after('numero_documento');
            $table->boolean('activo')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['apellidos', 'tipo_documento', 'numero_documento', 'telefono', 'activo']);
        });
    }
};
