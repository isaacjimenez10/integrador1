<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LecturaController extends Controller
{
    private $apiBaseUrl = 'http://localhost:3000/api';

    public function index(Request $request)
{
    $search = $request->get('search');
    $page = $request->input('page', 1);

    // Hacer la petición a la API, incluyendo el número de página si es necesario
    $response = Http::get("{$this->apiBaseUrl}/lecturas", [
        'search' => $search,
        'page' => $page // La API parece soportar paginación, ajusta según su documentación
    ]);

    // Verificar si la petición falló o no tiene la estructura esperada
    if ($response->failed() || !isset($response->json()['data'])) {
        Log::error('Error al obtener lecturas: ' . $response->body());
        return view('lecturas.index', [
            'lecturas' => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 3),
            'error' => 'No se pudieron obtener las lecturas desde la API.'
        ]);
    }

    // Obtener los datos de la respuesta
    $apiResponse = $response->json();
    $lecturasData = $apiResponse['data']; // Array de lecturas
    $pagination = $apiResponse['pagination'];

    // Filtrar las lecturas según el término de búsqueda
    $filteredLecturas = collect($lecturasData)->filter(function ($lectura) use ($search) {
        return !$search || (
            (isset($lectura['sensor']['nombre']) && stripos($lectura['sensor']['nombre'], $search) !== false) ||
            (isset($lectura['valor']) && stripos((string)$lectura['valor'], $search) !== false) ||
            (isset($lectura['unidad']) && stripos($lectura['unidad'], $search) !== false) ||
            (isset($lectura['fecha_hora']) && stripos($lectura['fecha_hora'], $search) !== false)
        );
    })->values();

    // Usar la paginación de la API
    $perPage = $pagination['per_page'];
    $total = $pagination['total'];
    $currentPage = $pagination['current_page'];

    // Crear el paginador con los datos filtrados
    $lecturas = new \Illuminate\Pagination\LengthAwarePaginator(
        $filteredLecturas,
        $total,
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'query' => $request->query()]
    );

    return view('lecturas.index', compact('lecturas'));
}

    public function create()
    {
        $response = Http::get("{$this->apiBaseUrl}/sensores");
        if ($response->failed()) {
            return redirect()->back()->with('error', 'Error al obtener los sensores desde la API.');
        }

        $sensores = $response->json();
        return view('lecturas.create', compact('sensores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sensor_id' => 'required|numeric',
            'valor' => 'required|numeric|min:0',
            'unidad' => 'required|string|max:20',
            'fecha_hora' => 'required|date|date_format:Y-m-d\TH:i',
        ], [
            'sensor_id.required' => 'El sensor es obligatorio.',
            'valor.required' => 'El valor es obligatorio.',
            'valor.numeric' => 'El valor debe ser un número.',
            'valor.min' => 'El valor debe ser mayor o igual a 0.',
            'unidad.required' => 'La unidad es obligatoria.',
            'unidad.string' => 'La unidad debe ser un texto.',
            'unidad.max' => 'La unidad no puede exceder los 20 caracteres.',
            'fecha_hora.required' => 'La fecha y hora son obligatorias.',
            'fecha_hora.date' => 'La fecha debe ser válida.',
            'fecha_hora.date_format' => 'El formato de fecha debe ser: Y-m-d H:i.',
        ]);

        $data = $request->only(['sensor_id', 'valor', 'unidad', 'fecha_hora']);

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->apiBaseUrl}/lecturas", $data);

        if ($response->failed()) {
            Log::error('Error al crear lectura: ' . $response->body());
            return redirect()->back()->with('error', 'Error al crear la lectura: ' . $response->body());
        }

        return redirect()->route('lecturas.index')->with('success', 'Lectura creada correctamente.');
    }

    public function show($id)
{
    $response = Http::get("{$this->apiBaseUrl}/lecturas/{$id}");
    if ($response->failed() || !isset($response->json()['data'])) {
        return redirect()->back()->with('error', 'Error al obtener la lectura desde la API.');
    }
    $lectura = $response->json()['data'];
    return view('lecturas.view', compact('lectura'));
}

public function edit($id)
{
    $response = Http::get("{$this->apiBaseUrl}/lecturas/{$id}");

    // Verificar si la petición falló o no tiene la estructura esperada
    if ($response->failed() || !isset($response->json()['data'])) {
        Log::error('Error al obtener la lectura: ' . $response->body());
        return redirect()->route('lecturas.index')->with('error', 'No se pudo encontrar la lectura para editar.');
    }

    // Obtener la lectura desde la clave 'data'
    $lectura = $response->json()['data'];

    // Obtener los sensores
    $sensoresResponse = Http::get("{$this->apiBaseUrl}/sensores");
    $sensoresData = $sensoresResponse->json();

    // Depurar la respuesta de /sensores
    Log::info('Respuesta de /sensores: ' . json_encode($sensoresData));

    // Ajustar según la estructura real de la API
    $sensores = [];
    if ($sensoresResponse->successful()) {
        if (isset($sensoresData['data'])) {
            $sensores = $sensoresData['data'];
        } elseif (is_array($sensoresData)) {
            // Si los sensores están directamente en la raíz
            $sensores = $sensoresData;
        }
    }

    // Depurar los sensores procesados
    Log::info('Sensores procesados: ' . json_encode($sensores));

    return view('lecturas.edit', compact('lectura', 'sensores'));
}

public function update(Request $request, $id)
{
    // Validar los datos del formulario
    $validated = $request->validate([
        'sensor_id' => 'required|integer',
        'valor' => 'required|numeric',
        'unidad' => 'required|string|max:10',
        'fecha_hora' => 'required|date',
    ]);

    // Preparar los datos para enviar a la API
    $data = [
        'sensor_id' => $validated['sensor_id'],
        'valor' => $validated['valor'],
        'unidad' => $validated['unidad'],
        'fecha_hora' => \Carbon\Carbon::parse($validated['fecha_hora'])->toISOString(),
    ];

    // Hacer la petición PUT a la API
    $response = Http::put("{$this->apiBaseUrl}/lecturas/{$id}", $data);

    if ($response->failed()) {
        Log::error('Error al actualizar la lectura: ' . $response->body());
        return redirect()->back()->with('error', 'No se pudo actualizar la lectura.');
    }

    return redirect()->route('lecturas.index')->with('success', 'Lectura actualizada correctamente.');
}

    public function destroy($id)
    {
        $response = Http::delete("{$this->apiBaseUrl}/lecturas/{$id}");
        if ($response->failed()) {
            Log::error('Error al eliminar lectura: ' . $response->body());
            return redirect()->back()->with('error', 'Error al eliminar la lectura: ' . $response->body());
        }

        return redirect()->route('lecturas.index')->with('success', 'Lectura eliminada correctamente.');
    }

    public function export()
{
    $response = Http::get("{$this->apiBaseUrl}/lecturas");
    if ($response->failed() || !isset($response->json()['data'])) {
        return redirect()->back()->with('error', 'Error al obtener las lecturas desde la API para exportar.');
    }

    $lecturas = $response->json()['data'];

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'ID');
    $sheet->setCellValue('B1', 'Sensor');
    $sheet->setCellValue('C1', 'Valor');
    $sheet->setCellValue('D1', 'Unidad');
    $sheet->setCellValue('E1', 'Fecha y Hora');

    $row = 2;
    foreach ($lecturas as $lectura) {
        $sheet->setCellValue('A' . $row, $lectura['id']);
        $sheet->setCellValue('B' . $row, $lectura['sensor']['nombre']);
        $sheet->setCellValue('C' . $row, $lectura['valor']);
        $sheet->setCellValue('D' . $row, $lectura['unidad']);
        $sheet->setCellValue('E' . $row, $lectura['fecha_hora']);
        $row++;
    }

    $fileName = 'lecturas.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
}