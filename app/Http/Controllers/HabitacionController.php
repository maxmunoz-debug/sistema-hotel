<?php

namespace App\Http\Controllers;

use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class HabitacionController extends Controller
{
    /**
     * Muestra la lista de habitaciones.
     */
    public function index()
    {
        // Traemos las habitaciones con su tipo cargado y los tipos para el SELECT
        $habitaciones = Habitacion::with('tipoHabitacion')->orderBy('id', 'desc')->get();
        $tipos = TipoHabitacion::all();

        return Inertia::render('habitaciones/index', [
            'habitaciones' => $habitaciones,
            'tipos' => $tipos
        ]);
    }

    /**
     * Guarda una nueva habitación (con imagen).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'numero' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('habitaciones', 'public');
            $validated['imagen'] = $path;
        }

        Habitacion::create($validated);

        return redirect()->to('/habitaciones');
    }

    /**
     * Actualiza una habitación existente.
     */
    public function update(Request $request, string $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        $validated = $request->validate([
            'tipo_habitacion_id' => 'required|exists:tipos_habitacion,id',
            'numero' => 'required|string|max:50',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable',
        ]);

        if ($request->hasFile('imagen')) {
            if ($habitacion->imagen) {
                Storage::disk('public')->delete($habitacion->imagen);
            }
            $path = $request->file('imagen')->store('habitaciones', 'public');
            $validated['imagen'] = $path;
        }

        $habitacion->update($validated);

        return redirect()->to('/habitaciones');
    }

    /**
     * Elimina una habitación.
     */
    public function destroy(string $id)
    {
        $habitacion = Habitacion::findOrFail($id);

        if ($habitacion->imagen) {
            Storage::disk('public')->delete($habitacion->imagen);
        }

        $habitacion->delete();

        return redirect()->to('/habitaciones');
    }
}