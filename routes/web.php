<?php

use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\TipoHabitacionController;
use App\Models\Habitacion;            // <-- AGREGADO: Para poder contar las habitaciones
use App\Models\TipoHabitacion;        // <-- AGREGADO: Para poder contar los tipos
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;                  // <-- AGREGADO: Necesario para renderizar con datos

Route::inertia('/', 'Welcome')->name('home');

// Agrupamos todas las rutas que requieren inicio de sesión en un solo bloque
Route::middleware(['auth', 'verified'])->group(function () {
    
    // MEJORADO: Cambiamos Route::inertia por Route::get para inyectar los datos reales
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard', [
            'totalHabitaciones' => Habitacion::count(),
            'totalTipos'        => TipoHabitacion::count(),
            'precioPromedio'     => round(Habitacion::avg('precio') ?? 0, 2)
        ]);
    })->name('dashboard');
    
    // Tus recursos del CRUD de Hotel
    Route::resource('tipos-habitacion', TipoHabitacionController::class);
    Route::resource('habitaciones', HabitacionController::class);
});

require __DIR__.'/settings.php';