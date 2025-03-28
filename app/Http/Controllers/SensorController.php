<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    // URL base de la API Node.js
    private $apiBaseUrl = 'http://localhost:3000/api';

    public function index(Request $request)
    {
        $search = $request->input('search');
        $tipo = $request->input('tipo');
        $ubicacion = $request->input('ubicacion');

        // Hacer solicitud a la API para obtener los sensores
        $response = Http::get("{$this->apiBaseUrl}/sensores");

        if ($response->failed()) {
            return view('sensores.index', [
                'sensores' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 3),
                'sensorLabels' => [],
                'sensorCounts' => [],
                'error' => 'Error al obtener los sensores desde la API.'
            ]);
        }

        $sensores = $response->json();

        // Filtrar sensores en el lado del cliente (Laravel) si es necesario
        $filteredSensores = collect($sensores)->filter(function ($sensor) use ($search, $tipo, $ubicacion) {
            $passesSearch = !$search || (
                (isset($sensor['nombre']) && stripos($sensor['nombre'], $search) !== false) ||
                (isset($sensor['tipo']) && stripos($sensor['tipo'], $search) !== false) ||
                (isset($sensor['ubicacion']) && stripos($sensor['ubicacion'], $search) !== false)
            );
            $passesTipo = !$tipo || (isset($sensor['tipo']) && $sensor['tipo'] === $tipo);
            $passesUbicacion = !$ubicacion || (isset($sensor['ubicacion']) && $sensor['ubicacion'] === $ubicacion);
            return $passesSearch && $passesTipo && $passesUbicacion;
        })->values();

        // Paginación manual
        $perPage = 3;
        $page = $request->input('page', 1);
        $total = count($filteredSensores);
        $paginatedSensores = $filteredSensores->slice(($page - 1) * $perPage, $perPage)->all();

        $sensores = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedSensores,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Datos para la gráfica
        $sensorData = collect($filteredSensores)->groupBy('tipo')->map->count();
        $sensorLabels = $sensorData->keys();
        $sensorCounts = $sensorData->values();

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
            'nombre' => 'required|string|max:50',
            'tipo' => 'required|string|max:30',
            'ubicacion' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre del sensor es obligatorio.',
            'nombre.max' => 'El nombre del sensor no puede tener más de 50 caracteres.',
            'tipo.required' => 'El tipo de sensor es obligatorio.',
            'tipo.max' => 'El tipo de sensor no puede tener más de 30 caracteres.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.max' => 'La ubicación no puede tener más de 100 caracteres.',
        ]);

        // Preparar los datos para enviar a la API
        $data = [
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'ubicacion' => $request->ubicacion,
        ];

        // Hacer solicitud a la API para crear un sensor
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->apiBaseUrl}/sensores", $data);

        if ($response->failed()) {
            \Log::error('Error al crear sensor: ' . $response->body());
            return redirect()->back()->with('error', 'Error al crear el sensor: ' . $response->body());
        }

        return redirect()->route('sensores.index')->with('success', 'Sensor creado correctamente.');
    }

    public function show($id)
    {
        // Obtener un sensor específico desde la API
        $response = Http::get("{$this->apiBaseUrl}/sensores/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener el sensor desde la API.');
        }

        $sensor = $response->json();
        return view('sensores.show', compact('sensor'));
    }

    public function edit($id)
    {
        // Obtener un sensor específico desde la API
        $response = Http::get("{$this->apiBaseUrl}/sensores/{$id}");

        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener el sensor desde la API.');
        }

        $sensor = $response->json();
        return view('sensores.edit', compact('sensor'));
    }

    public function update(Request $request, $id)
    {
        // Validaciones para la actualización del sensor
        $request->validate([
            'nombre' => 'required|string|max:50',
            'tipo' => 'required|string|max:30',
            'ubicacion' => 'required|string|max:100',
        ], [
            'nombre.required' => 'El nombre del sensor es obligatorio.',
            'nombre.max' => 'El nombre del sensor no puede tener más de 50 caracteres.',
            'tipo.required' => 'El tipo de sensor es obligatorio.',
            'tipo.max' => 'El tipo de sensor no puede tener más de 30 caracteres.',
            'ubicacion.required' => 'La ubicación es obligatoria.',
            'ubicacion.max' => 'La ubicación no puede tener más de 100 caracteres.',
        ]);

        // Preparar los datos para enviar a la API
        $data = [
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'ubicacion' => $request->ubicacion,
        ];

        // Hacer solicitud a la API para actualizar el sensor
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->put("{$this->apiBaseUrl}/sensores/{$id}", $data);

        if ($response->failed()) {
            \Log::error('Error al actualizar sensor: ' . $response->body());
            return redirect()->back()->with('error', 'Error al actualizar el sensor: ' . $response->body());
        }

        return redirect()->route('sensores.index')->with('success', 'Sensor actualizado correctamente.');
    }

    public function destroy($id)
    {
        // Hacer solicitud a la API para eliminar el sensor
        $response = Http::delete("{$this->apiBaseUrl}/sensores/{$id}");

        if ($response->failed()) {
            return response()->json(['error' => 'Error al eliminar el sensor'], 500);
        }

        return response()->json(['message' => 'Sensor eliminado con éxito']);
    }

    public function export()
{
    // Obtener los sensores desde la API
    $response = Http::get("{$this->apiBaseUrl}/sensores");

    if ($response->failed()) {
        return redirect()->back()->with('error', 'Error al obtener los sensores desde la API para exportar.');
    }

    $sensores = $response->json();

    // Crear una nueva hoja de cálculo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Encabezados
    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Nombre');
    $sheet->setCellValue('C1', 'Tipo');
    $sheet->setCellValue('D1', 'Ubicación');
    $sheet->setCellValue('E1', 'Fecha de Creación');

    // Llenar datos
    $row = 2;
    foreach ($sensores as $sensor) {
        $sheet->setCellValue('A' . $row, $sensor['id']);
        $sheet->setCellValue('B' . $row, $sensor['nombre']);
        $sheet->setCellValue('C' . $row, $sensor['tipo']);
        $sheet->setCellValue('D' . $row, $sensor['ubicacion']);
        $sheet->setCellValue('E' . $row, isset($sensor['created_at']) ? date('Y-m-d', strtotime($sensor['created_at'])) : 'N/A');
        $row++;
    }

    // Configurar el archivo para descarga
    $fileName = 'sensores.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
}