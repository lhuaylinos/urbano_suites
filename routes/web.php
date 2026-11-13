<?php

use App\Http\Controllers\Admin\HabitacionController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\ServicioAdicionalController;
use App\Http\Controllers\Admin\TarifaController;
use App\Http\Controllers\Admin\TipoHabitacionController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ExperienciaController;
use App\Http\Controllers\Huesped\ReservaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\Limpieza\HabitacionController as LimpiezaController;
use App\Http\Controllers\Recepcion\EstadiaController;
use App\Http\Controllers\SuiteController;
use Illuminate\Support\Facades\Route;

/*
 | Páginas públicas (no requieren iniciar sesión).
 | Route::view() muestra una vista sin pasar por un controlador:
 | sirve para páginas que solo muestran información fija.
 */
Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::view('/el-hotel', 'paginas.hotel')->name('hotel');
Route::get('/suites', [SuiteController::class, 'index'])->name('suites');
Route::get('/suites/{tipo}', [SuiteController::class, 'show'])->name('suites.show');
Route::get('/experiencias', [ExperienciaController::class, 'index'])->name('experiencias');
Route::view('/contacto', 'paginas.contacto')->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');
Route::get('/disponibilidad', [BusquedaController::class, 'index'])->name('buscar');

/*
 | Páginas que requieren iniciar sesión (middleware "auth").
 | Si alguien entra sin sesión, Laravel lo manda al login.
 */
Route::middleware('auth')->group(function () {

    // Cualquier usuario con sesión
    Route::view('/mi-perfil', 'cuenta.perfil')->name('perfil');

    // Solo huéspedes: proceso de reserva
    Route::middleware('role:huesped')->group(function () {
        Route::get('/mis-reservas', [ReservaController::class, 'index'])->name('mis-reservas');
        Route::get('/reservar/{tipo}', [ReservaController::class, 'resumen'])->name('reservas.resumen');
        Route::post('/reservar/{tipo}', [ReservaController::class, 'store'])->name('reservas.store');
        Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
        Route::get('/reservas/{reserva}/confirmada', [ReservaController::class, 'confirmada'])->name('reservas.confirmada');
        Route::post('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    });

    // Solo recepción: check-in, consumos y check-out
    Route::middleware('role:recepcionista')->prefix('recepcion')->name('recepcion.')->group(function () {
        Route::get('/', [EstadiaController::class, 'panel'])->name('panel');
        Route::get('/buscar', [EstadiaController::class, 'buscar'])->name('buscar');
        Route::get('/reservas/{reserva}/check-in', [EstadiaController::class, 'checkIn'])->name('check-in');
        Route::post('/reservas/{reserva}/check-in', [EstadiaController::class, 'guardarCheckIn'])->name('check-in.guardar');
        Route::get('/estadias/{reserva}', [EstadiaController::class, 'estadia'])->name('estadia');
        Route::post('/estadias/{reserva}/consumos', [EstadiaController::class, 'agregarConsumo'])->name('consumos.guardar');
        Route::get('/estadias/{reserva}/cuenta', [EstadiaController::class, 'cuenta'])->name('cuenta');
        Route::post('/estadias/{reserva}/check-out', [EstadiaController::class, 'checkOut'])->name('check-out');
        Route::get('/reservas/{reserva}/comprobante', [EstadiaController::class, 'comprobante'])->name('comprobante');
    });

    // Solo personal de limpieza
    Route::middleware('role:limpieza')->prefix('limpieza')->name('limpieza.')->group(function () {
        Route::get('/', [LimpiezaController::class, 'index'])->name('panel');
        Route::post('/habitaciones/{habitacion}/liberar', [LimpiezaController::class, 'liberar'])->name('liberar');
    });

    // Solo administrador: panel y CRUD
    Route::middleware('role:administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/', 'admin.panel')->name('panel');

        // Route::resource crea las 7 rutas del CRUD (index, create, store, edit, update, destroy...)
        // "parameters" cambia el nombre del parámetro para que coincida con la variable del controlador
        Route::resource('tipos-habitacion', TipoHabitacionController::class)
            ->parameters(['tipos-habitacion' => 'tipo'])
            ->except('show');

        Route::resource('habitaciones', HabitacionController::class)
            ->parameters(['habitaciones' => 'habitacion'])
            ->except('show');

        Route::resource('servicios-adicionales', ServicioAdicionalController::class)
            ->parameters(['servicios-adicionales' => 'servicio'])
            ->except('show');

        Route::resource('tarifas', TarifaController::class)
            ->parameters(['tarifas' => 'tarifa'])
            ->except('show');

        // Usuarios y roles no es un CRUD: no se crean ni se eliminan usuarios desde aquí
        Route::resource('usuarios', UsuarioController::class)
            ->parameters(['usuarios' => 'usuario'])
            ->only(['index', 'edit', 'update']);

        Route::get('/reporte', [ReporteController::class, 'index'])->name('reporte');
    });
});
