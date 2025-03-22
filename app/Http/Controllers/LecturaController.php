<?php

namespace App\Http\Controllers;

use App\Models\Lectura;
use App\Models\Sensor;
use Illuminate\Http\Request;

class LecturaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');  // Obtenemos el término de búsqueda
        $lecturas = Lectura::when($search, function ($query, $search) {
            return $query->whereHas('sensor', function ($sensorQuery) use ($search) {
                $sensorQuery->where('nombre', 'like', '%'.$search.'%');  // Filtro por nombre del sensor
            })
            ->orWhere('valor', 'like', '%'.$search.'%')
            ->orWhere('unidad', 'like', '%'.$search.'%')
            ->orWhere('fecha_hora', 'like', '%'.$search.'%');
        })->paginate(3);  // Paginación de resultados

        return view('lecturas.index', compact('lecturas'));
    }

    public function create()
    {
        $sensores = Sensor::all();
        return view('lecturas.create', compact('sensores'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // Validaciones
        $request->validate([
            'sensor_id' => 'required|exists:sensores,id',  // Validar que el sensor exista
            'valor' => 'required|numeric|min:0',  // Validar que el valor sea numérico y mayor o igual a 0
            'unidad' => 'required|string|max:20',  // Validar que la unidad sea una cadena de texto y no exceda 20 caracteres
            'fecha_hora' => 'required|date|date_format:Y-m-d\TH:i',  // Validar fecha y formato (Y-m-d H:i:s)
        ], [
            // Mensajes personalizados de error
            'sensor_id.required' => 'El sensor es obligatorio.',
            'sensor_id.exists' => 'El sensor seleccionado no existe.',
            'valor.required' => 'El valor es obligatorio.',
            'valor.numeric' => 'El valor debe ser un número.',
            'valor.min' => 'El valor debe ser mayor o igual a 0.',
            'unidad.required' => 'La unidad es obligatoria.',
            'unidad.string' => 'La unidad debe ser un texto.',
            'unidad.max' => 'La unidad no puede exceder los 20 caracteres.',
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'fecha_hora.date' => 'La fecha debe ser válida.',
            'fecha_hora.date_format' => 'El formato de fecha debe ser: Y-m-d H:i:s.',
        ]);

        Lectura::create($request->all());  // Crear la lectura
        return redirect()->route('lecturas.index')->with('success', 'Lectura creada correctamente.');
    }

    public function show($id)
    {
        $lectura = Lectura::findOrFail($id);  // Buscar lectura por ID
        return view('lecturas.show', compact('lectura'));
    }

    public function edit($id)
    {
        $lectura = Lectura::findOrFail($id);  // Buscar lectura por ID
        $sensores = Sensor::all();  // Obtener todos los sensores
        return view('lecturas.edit', compact('lectura', 'sensores'));
    }

    public function update(Request $request, $id)
    {
        // Validaciones
        $request->validate([
            'sensor_id' => 'required|exists:sensores,id',  // Validar que el sensor exista
            'valor' => 'required|numeric|min:0',  // Validar que el valor sea numérico y mayor o igual a 0
            'unidad' => 'required|string|max:20',  // Validar que la unidad sea una cadena de texto y no exceda 20 caracteres
            'fecha_hora' => 'required|date|date_format:Y-m-d H:i:s',  // Validar fecha y formato (Y-m-d H:i:s)
        ], [
            // Mensajes personalizados de error
            'sensor_id.required' => 'El sensor es obligatorio.',
            'sensor_id.exists' => 'El sensor seleccionado no existe.',
            'valor.required' => 'El valor es obligatorio.',
            'valor.numeric' => 'El valor debe ser un número.',
            'valor.min' => 'El valor debe ser mayor o igual a 0.',
            'unidad.required' => 'La unidad es obligatoria.',
            'unidad.string' => 'La unidad debe ser un texto.',
            'unidad.max' => 'La unidad no puede exceder los 20 caracteres.',
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'fecha_hora.date' => 'La fecha debe ser válida.',
            'fecha_hora.date_format' => 'El formato de fecha debe ser: Y-m-d H:i:s.',
        ]);

        $lectura = Lectura::findOrFail($id);  // Buscar lectura por ID
        $lectura->update($request->all());  // Actualizar la lectura
        return redirect()->route('lecturas.index')->with('success', 'Lectura actualizada correctamente.');
    }

    public function destroy($id)
    {
        $lectura = Lectura::findOrFail($id);  // Buscar lectura por ID
        $lectura->delete();  // Eliminar la lectura
        return redirect()->route('lecturas.index')->with('success', 'Lectura eliminada correctamente.');
    }
}
