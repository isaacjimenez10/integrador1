<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;

class SensorDataController extends Controller
{
    // Para la vista web (GET /sensordata)
    public function index()
    {
        // Obtener datos de sensores con IDs entre 1 y 100, ordenados por ID
        $sensorData = SensorData::whereBetween('id', [1, 100])->orderBy('id', 'asc')->paginate(10);
        return view('sensor-data.index', compact('sensorData'));
    }

    // Para la API (GET /api/sensordata)
    public function getData()
    {
        $sensorData = SensorData::orderBy('timestamp', 'desc')->get();
        return response()->json($sensorData);
    }

    // Para la API y el formulario web (POST /api/sensordata o POST /sensordata)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
            'soilMoisture' => 'required|integer',
        ]);

        $sensorData = SensorData::create($validated);

        // Si la solicitud es de la API (espera JSON)
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Datos guardados correctamente en sensordata',
                'data' => $sensorData
            ], 201);
        }

        // Si la solicitud es del formulario web (redirecciona)
        return redirect()->route('sensor-data.index')
            ->with('success', 'Datos de sensor guardados correctamente');
    }
}