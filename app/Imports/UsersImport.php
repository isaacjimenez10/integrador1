<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnFailure
{
    use SkipsFailures;

    public function model(array $row)
    {
        try {
            // Verifica que name, email y password estén presentes y no vacíos
            if (empty($row['name']) || empty($row['email']) || empty($row['password'])) {
                \Log::warning('Fila omitida por datos incompletos (name, email o password faltante): ' . json_encode($row));
                return null;
            }

            return new User([
                'name' => $row['name'],
                'email' => $row['email'],
                'password' => bcrypt($row['password']), // Encripta la contraseña del Excel
                'role' => $row['role'] ?? 'user',       // Usa 'user' por defecto si no hay rol
                'status' => 'active',                   // Asegura un estado por defecto
            ]);
        } catch (\Exception $e) {
            \Log::error('Error importing row: ' . $e->getMessage() . ' - Row: ' . json_encode($row));
            return null;
        }
    }
}