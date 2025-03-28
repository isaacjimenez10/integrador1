<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConfiguracionesExport;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ConfiguracionController extends Controller
{
    // URL base de la API Node.js
    private $apiBaseUrl = 'http://localhost:3000/api';

    public function index(Request $request)
    {
        $search = $request->input('search'); // Búsqueda por nombre de sensor
        $minimo = $request->input('minimo'); // Filtro por valor mínimo
        $maximo = $request->input('maximo'); // Filtro por valor máximo

        // Hacer solicitud a la API para obtener las configuraciones
        $response = Http::get("{$this->apiBaseUrl}/configuracion");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener las configuraciones desde la API.');
        }

        $configuraciones = $response->json();

        // Obtener los sensores para mapear los sensor_id a nombres
        $sensoresResponse = Http::get("{$this->apiBaseUrl}/sensores");
        $sensores = $sensoresResponse->successful() ? $sensoresResponse->json() : [];

        // Mapear los sensor_id a nombres de sensores
        $sensorMap = collect($sensores)->keyBy('id');
        foreach ($configuraciones as &$config) {
            $config['sensor'] = isset($config['sensor_id']) && isset($sensorMap[$config['sensor_id']])
                ? $sensorMap[$config['sensor_id']]
                : ['nombre' => 'Sin sensor'];
        }

        // Filtrar configuraciones en el lado del cliente (Laravel) si es necesario
        $filteredConfiguraciones = collect($configuraciones)->filter(function ($config) use ($search, $minimo, $maximo) {
            $passesSearch = !$search || (isset($config['sensor']['nombre']) && stripos($config['sensor']['nombre'], $search) !== false);
            $passesMinimo = !$minimo || (isset($config['minimo']) && $config['minimo'] >= $minimo);
            $passesMaximo = !$maximo || (isset($config['maximo']) && $config['maximo'] <= $maximo);
            return $passesSearch && $passesMinimo && $passesMaximo;
        })->values();

        // Paginación manual
        $perPage = 5;
        $page = $request->input('page', 1);
        $total = count($filteredConfiguraciones);
        $paginatedConfiguraciones = $filteredConfiguraciones->slice(($page - 1) * $perPage, $perPage)->all();

        $configuraciones = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedConfiguraciones,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('configuracion.index', compact('configuraciones'));
    }

    public function create()
    {
        // Obtener los sensores desde la API
        $response = Http::get("{$this->apiBaseUrl}/sensores");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener los sensores desde la API.');
        }

        $sensores = $response->json();
        return view('configuracion.create', compact('sensores'));
    }

    public function store(Request $request)
{
    $request->validate([
        'sensor_id' => 'required|numeric',
        'minimo' => 'required|numeric|min:0',
        'maximo' => 'required|numeric|min:0|gt:minimo',
    ], [
        'sensor_id.required' => 'El campo sensor es obligatorio.',
        'sensor_id.numeric' => 'El sensor seleccionado no es válido.',
        'minimo.required' => 'El valor mínimo es obligatorio.',
        'minimo.numeric' => 'El valor mínimo debe ser un número.',
        'minimo.min' => 'El valor mínimo no puede ser negativo.',
        'maximo.required' => 'El valor máximo es obligatorio.',
        'maximo.numeric' => 'El valor máximo debe ser un número.',
        'maximo.min' => 'El valor máximo no puede ser negativo.',
        'maximo.gt' => 'El valor máximo debe ser mayor que el valor mínimo.',
    ]);

    // Preparar los datos para enviar a la API
    $data = [
        'sensor_id' => $request->sensor_id,
        'minimo' => $request->minimo,
        'maximo' => $request->maximo,
    ];

    // Hacer solicitud a la API para crear una configuración
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->post("{$this->apiBaseUrl}/configuracion", $data);

    if ($response->failed()) {
        // Loguear el error para depuración
        \Log::error('Error al crear configuración: ' . $response->body());
        return redirect()->back()->with('error', 'Error al crear la configuración: ' . $response->body());
    }

    return redirect()->route('configuracion.index')->with('success', 'Configuración creada correctamente.');
}
    public function show($id)
    {
        // Obtener una configuración específica desde la API
        $response = Http::get("{$this->apiBaseUrl}/configuracion/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener la configuración desde la API.');
        }

        $configuracion = $response->json();

        // Obtener el sensor asociado
        if (isset($configuracion['sensor_id'])) {
            $sensorResponse = Http::get("{$this->apiBaseUrl}/sensores/{$configuracion['sensor_id']}");
            $configuracion['sensor'] = $sensorResponse->successful()
                ? $sensorResponse->json()
                : ['nombre' => 'Sin sensor'];
        } else {
            $configuracion['sensor'] = ['nombre' => 'Sin sensor'];
        }

        return view('configuracion.view', compact('configuracion'));
    }

    public function edit($id)
{
    // Obtener la configuración desde la API
    $configResponse = Http::get("{$this->apiBaseUrl}/configuracion/{$id}");
    if ($configResponse->failed()) {
        return redirect()->back()->with('error', 'Error al obtener la configuración desde la API.');
    }

    // Obtener los sensores desde la API
    $sensorResponse = Http::get("{$this->apiBaseUrl}/sensores");
    if ($sensorResponse->failed()) {
        return redirect()->back()->with('error', 'Error al obtener los sensores desde la API.');
    }

    $configuracion = $configResponse->json();
    $sensores = $sensorResponse->json();
    return view('configuracion.edit', compact('configuracion', 'sensores'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'sensor_id' => 'required|numeric',
        'minimo' => 'required|numeric|min:0',
        'maximo' => 'required|numeric|min:0|gt:minimo',
    ], [
        'sensor_id.required' => 'El campo sensor es obligatorio.',
        'sensor_id.numeric' => 'El sensor seleccionado no es válido.',
        'minimo.required' => 'El valor mínimo es obligatorio.',
        'minimo.numeric' => 'El valor mínimo debe ser un número.',
        'minimo.min' => 'El valor mínimo no puede ser negativo.',
        'maximo.required' => 'El valor máximo es obligatorio.',
        'maximo.numeric' => 'El valor máximo debe ser un número.',
        'maximo.min' => 'El valor máximo no puede ser negativo.',
        'maximo.gt' => 'El valor máximo debe ser mayor que el valor mínimo.',
    ]);

    // Preparar los datos para enviar a la API
    $data = [
        'sensor_id' => $request->sensor_id,
        'minimo' => $request->minimo,
        'maximo' => $request->maximo,
    ];

    // Hacer solicitud a la API para actualizar la configuración
    $response = Http::withHeaders([
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ])->put("{$this->apiBaseUrl}/configuracion/{$id}", $data);

    if ($response->failed()) {
        // Loguear el error para depuración
        \Log::error('Error al actualizar configuración: ' . $response->body());
        return redirect()->back()->with('error', 'Error al actualizar la configuración: ' . $response->body());
    }

    return redirect()->route('configuracion.index')->with('success', 'Configuración actualizada correctamente.');
}

public function destroy($id)
{
    // Hacer solicitud a la API para eliminar la configuración
    $response = Http::delete("{$this->apiBaseUrl}/configuracion/{$id}");

    if ($response->failed()) {
        return redirect()->route('configuracion.index')->with('error', 'Error al eliminar la configuración: ' . ($response->json()['error'] ?? 'Error desconocido'));
    }

    return redirect()->route('configuracion.index')->with('success', 'Configuración eliminada con éxito');
}

    public function export()
    {
        // Obtener las configuraciones desde la API
        $response = Http::get("{$this->apiBaseUrl}/configuracion");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener las configuraciones desde la API para exportar.');
        }

        $configuraciones = $response->json();

        // Obtener los sensores para mapear los sensor_id a nombres
        $sensoresResponse = Http::get("{$this->apiBaseUrl}/sensores");
        $sensores = $sensoresResponse->successful() ? $sensoresResponse->json() : [];
        $sensorMap = collect($sensores)->keyBy('id');

        // Mapear los sensor_id a nombres de sensores
        foreach ($configuraciones as &$config) {
            $config['sensor'] = isset($config['sensor_id']) && isset($sensorMap[$config['sensor_id']])
                ? $sensorMap[$config['sensor_id']]
                : ['nombre' => 'Sin sensor'];
        }

        $fileName = 'configuraciones.csv';
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Sensor', 'Mínimo', 'Máximo', 'Fecha de Creación']);

        foreach ($configuraciones as $config) {
            fputcsv($output, [
                $config['id'],
                $config['sensor']['nombre'] ?? 'Sin sensor',
                $config['minimo'] ?? 'N/A',
                $config['maximo'] ?? 'N/A',
                isset($config['created_at']) ? date('Y-m-d', strtotime($config['created_at'])) : 'N/A',
            ]);
        }

        fclose($output);
        exit;
    }
}