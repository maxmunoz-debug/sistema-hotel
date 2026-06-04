<?php

namespace App\Http\Controllers;

use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoHabitacionController extends Controller
{
    /**
     * Muestra la lista de tipos de habitación.
     */
    public function index()
    {
        // Forzamos a traer los datos frescos de la base de datos
        $tipos = TipoHabitacion::orderBy('id', 'desc')->get();
        
        return Inertia::render('tipos-habitacion/index', [
            'tipos' => $tipos
        ]);
    }

    /**
     * Guarda un nuevo tipo de habitación.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        TipoHabitacion::create($validated);

        // Volvemos a la ruta index de forma limpia para que Inertia recargue los props
        return redirect()->to('/tipos-habitacion');
    }

    /**
     * Actualiza un tipo de habitación existente.
     */
    public function update(Request $request, string $id)
    {
        $tipoHabitacion = TipoHabitacion::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);

        $tipoHabitacion->update($validated);

        return redirect()->to('/tipos-habitacion');
    }

    /**
     * Elimina un tipo de habitación.
     */
    public function destroy(string $id)
    {
        $tipoHabitacion = TipoHabitacion::findOrFail($id);
        $tipoHabitacion->delete();

        return redirect()->to('/tipos-habitacion');
    }
}