<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $tipo = $request->input('tipo');
    $ubicacion = $request->input('ubicacion');

    $sensores = Sensor::when($search, function ($query, $search) {
        return $query->where('nombre', 'LIKE', "%{$search}%")
                     ->orWhere('tipo', 'LIKE', "%{$search}%")
                     ->orWhere('ubicacion', 'LIKE', "%{$search}%");
    })
    ->when($tipo, function ($query, $tipo) {
        return $query->where('tipo', $tipo);
    })
    ->when($ubicacion, function ($query, $ubicacion) {
        return $query->where('ubicacion', $ubicacion);
    })
    ->paginate(3);

    // Datos para la gráfica
    $sensorData = Sensor::selectRaw('tipo, COUNT(*) as count')
        ->groupBy('tipo')
        ->get();
    $sensorLabels = $sensorData->pluck('tipo');
    $sensorCounts = $sensorData->pluck('count');

    return view('sensores.index', compact('sensores', 'sensorLabels', 'sensorCounts'));
}


    public function create()
    {
        return view('sensores.create');
    }

    public function store(Request $request)
    {
        // Validaciones para la creación de sensor
        $request->validate([
            'nombre' => 'required|string|max:50|unique:sensores,nombre', // Validación única
            'tipo' => 'required|string|max:30',
            'ubicacion' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre del sensor es obligatorio.',
            'nombre.unique' => 'Ya existe un sensor con ese nombre.',
            'nombre.max' => 'El nombre del sensor no puede tener más de 50 caracteres.',
            'tipo.required' => 'El tipo de sensor es obligatorio.',
            'tipo.max' => 'El tipo de sensor no puede tener más de 30 caracteres.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.max' => 'La ubicación no puede tener más de 100 caracteres.',
        ]);

        // Creación del sensor
        Sensor::create($request->all());

        return redirect()->route('sensores.index')->with('success', 'Sensor creado correctamente.');
    }

    public function show($id)
    {
        $sensor = Sensor::findOrFail($id);
        return view('sensores.show', compact('sensor'));
    }

    public function edit($id)
    {
        $sensor = Sensor::findOrFail($id);
        return view('sensores.edit', compact('sensor'));
    }

    public function update(Request $request, $id)
    {
        // Validaciones para la actualización del sensor
        $sensor = Sensor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:50|unique:sensores,nombre,' . $sensor->id, // Excluye el mismo sensor en la validación
            'tipo' => 'required|string|max:30',
            'ubicacion' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre del sensor es obligatorio.',
            'nombre.unique' => 'Ya existe un sensor con ese nombre.',
            'nombre.max' => 'El nombre del sensor no puede tener más de 50 caracteres.',
            'tipo.required' => 'El tipo de sensor es obligatorio.',
            'tipo.max' => 'El tipo de sensor no puede tener más de 30 caracteres.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.max' => 'La ubicación no puede tener más de 100 caracteres.',
        ]);

        // Actualización del sensor
        $sensor->update($request->all());

        return redirect()->route('sensores.index')->with('success', 'Sensor actualizado correctamente.');
    }

    public function destroy($id)
    {
        $sensor = Sensor::findOrFail($id);
        $sensor->delete();

        return response()->json(['message' => 'Sensor eliminado con éxito']);
    }

    // Método para exportar los sensores a CSV
    public function export()
    {
        $fileName = 'sensores.csv';
        $sensores = Sensor::all();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Nombre', 'Tipo', 'Ubicación', 'Fecha de Creación']);

        foreach ($sensores as $sensor) {
            fputcsv($output, [
                $sensor->id,
                $sensor->nombre,
                $sensor->tipo,
                $sensor->ubicacion,
                $sensor->created_at->format('Y-m-d'),
            ]);
        }

        fclose($output);
        exit;
    }
}

