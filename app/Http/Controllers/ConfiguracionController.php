<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Sensor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConfiguracionesExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConfiguracionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // Búsqueda por nombre de sensor
        $minimo = $request->input('minimo'); // Filtro por valor mínimo
        $maximo = $request->input('maximo'); // Filtro por valor máximo

        $configuraciones = Configuracion::with('sensor')
            ->when($search, function ($query, $search) {
                return $query->whereHas('sensor', function ($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%");
                });
            })
            ->when($minimo, function ($query) use ($minimo) {
                return $query->where('minimo', '>=', $minimo);
            })
            ->when($maximo, function ($query) use ($maximo) {
                return $query->where('maximo', '<=', $maximo);
            })
            ->paginate(5);

        return view('configuracion.index', compact('configuraciones'));
    }

    public function create()
    {
        $sensores = Sensor::all();
        return view('configuracion.create', compact('sensores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensores,id',
            'minimo' => 'required|numeric|min:0',
            'maximo' => 'required|numeric|min:0|gt:minimo', // El valor máximo debe ser mayor que el mínimo
        ], [
            'sensor_id.required' => 'El campo sensor es obligatorio.',
            'sensor_id.exists' => 'El sensor seleccionado no existe.',
            'minimo.required' => 'El valor mínimo es obligatorio.',
            'minimo.numeric' => 'El valor mínimo debe ser un número.',
            'minimo.min' => 'El valor mínimo no puede ser negativo.',
            'maximo.required' => 'El valor máximo es obligatorio.',
            'maximo.numeric' => 'El valor máximo debe ser un número.',
            'maximo.min' => 'El valor máximo no puede ser negativo.',
            'maximo.gt' => 'El valor máximo debe ser mayor que el valor mínimo.',
        ]);

        // Crear configuración
        Configuracion::create($request->all());
        return redirect()->route('configuracion.index')->with('success', 'Configuración creada correctamente.');
    }

    public function show($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        return view('configuracion.show', compact('configuracion'));
    }

    public function edit($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        $sensores = Sensor::all();
        return view('configuracion.edit', compact('configuracion', 'sensores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sensor_id' => 'required|exists:sensores,id',
            'minimo' => 'required|numeric|min:0',
            'maximo' => 'required|numeric|min:0|gt:minimo', // El valor máximo debe ser mayor que el mínimo
        ], [
            'sensor_id.required' => 'El campo sensor es obligatorio.',
            'sensor_id.exists' => 'El sensor seleccionado no existe.',
            'minimo.required' => 'El valor mínimo es obligatorio.',
            'minimo.numeric' => 'El valor mínimo debe ser un número.',
            'minimo.min' => 'El valor mínimo no puede ser negativo.',
            'maximo.required' => 'El valor máximo es obligatorio.',
            'maximo.numeric' => 'El valor máximo debe ser un número.',
            'maximo.min' => 'El valor máximo no puede ser negativo.',
            'maximo.gt' => 'El valor máximo debe ser mayor que el valor mínimo.',
        ]);

        $configuracion = Configuracion::findOrFail($id);
        $configuracion->update($request->all());

        return redirect()->route('configuracion.index')->with('success', 'Configuración actualizada correctamente.');
    }

    public function destroy($id)
    {
        $configuracion = Configuracion::findOrFail($id);
        $configuracion->delete();

        return response()->json(['message' => 'Configuración eliminada con éxito']);
    }

    // Método para exportar las configuraciones a Excel
    public function export()
    {
        $fileName = 'configuraciones.csv';
        $configuraciones = Configuracion::with('sensor')->get();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Sensor', 'Mínimo', 'Máximo', 'Fecha de Creación']);

        foreach ($configuraciones as $config) {
            fputcsv($output, [
                $config->id,
                $config->sensor->nombre ?? 'Sin sensor',
                $config->minimo,
                $config->maximo,
                $config->created_at->format('Y-m-d'),
            ]);
        }

        fclose($output);
        exit;
    }
}

