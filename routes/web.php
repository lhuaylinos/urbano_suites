<?php

use Illuminate\Support\Facades\Route;

/*
 | Páginas públicas (no requieren iniciar sesión).
 | Route::view() muestra una vista sin pasar por un controlador:
 | sirve para páginas que solo muestran información fija.
 */
Route::view('/', 'inicio')->name('inicio');
Route::view('/el-hotel', 'paginas.hotel')->name('hotel');
Route::view('/suites', 'paginas.suites')->name('suites');
Route::view('/experiencias', 'paginas.experiencias')->name('experiencias');
Route::view('/contacto', 'paginas.contacto')->name('contacto');

/*
 | Páginas que requieren iniciar sesión (middleware "auth").
 | Si alguien entra sin sesión, Laravel lo manda al login.
 */
Route::middleware('auth')->group(function () {

    // Cualquier usuario con sesión
    Route::view('/mi-perfil', 'cuenta.perfil')->name('perfil');

    // Solo huéspedes
    Route::middleware('role:huesped')->group(function () {
        Route::view('/mis-reservas', 'cuenta.mis-reservas')->name('mis-reservas');
    });

    // Solo recepción
    Route::middleware('role:recepcionista')->prefix('recepcion')->name('recepcion.')->group(function () {
        Route::view('/', 'recepcion.panel')->name('panel');
    });

    // Solo personal de limpieza
    Route::middleware('role:limpieza')->prefix('limpieza')->name('limpieza.')->group(function () {
        Route::view('/', 'limpieza.panel')->name('panel');
    });

    // Solo administrador: aquí irán los 4 CRUD
    Route::middleware('role:administrador')->prefix('admin')->name('admin.')->group(function () {
        Route::view('/', 'admin.panel')->name('panel');
    });
});
