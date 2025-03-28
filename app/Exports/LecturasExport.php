<?php

namespace App\Exports;

use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LecturasExport implements FromArray, WithHeadings
{
    protected $apiBaseUrl = 'http://localhost:3000/api';
    protected $search;

    public function __construct($search = '')
    {
        $this->search = $search;
    }

    public function array(): array
    {
        $response = Http::get("{$this->apiBaseUrl}/lecturas", [
            'search' => $this->search,
            'per_page' => 1000,
        ]);

        if ($response->failed()) {
            return [];
        }

        $lecturas = $response->json()['data'] ?? [];

        return array_map(function ($lectura) {
            return [
                $lectura['id'],
                $lectura['sensor']['nombre'] ?? 'Sin sensor',
                $lectura['valor'] ?? 'N/A',
                $lectura['unidad'] ?? 'N/A',
                $lectura['fecha_hora'] ?? 'N/A',
            ];
        }, $lecturas);
    }

    public function headings(): array
    {
        return [
            'ID',
            'Sensor',
            'Valor',
            'Unidad',
            'Fecha y Hora',
        ];
    }
}