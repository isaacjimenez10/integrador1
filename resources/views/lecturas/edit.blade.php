<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Editar Lectura</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --text-color: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.4);
            --error-color: #e74c3c;
            --success-color: #28a745;
            --background-light: rgba(255, 255, 255, 0.95);
            --background-glass: rgba(255, 255, 255, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('img/lec.jpg') }}') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            background: var(--background-glass);
            backdrop-filter: blur(15px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px var(--shadow-color);
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: var(--primary-color);
            color: var(--text-color);
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            z-index: 3;
        }

        .back-button:hover {
            background: var(--secondary-color);
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        h1 {
            color: var(--text-color);
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 2px 2px 8px var(--shadow-color);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            text-align: center;
            font-weight: 500;
            animation: slideIn 0.5s ease-out;
        }

        .alert-danger {
            background: var(--error-color);
        }

        .alert-success {
            background: var(--success-color);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: var(--text-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-shadow: 1px 1px 3px var(--shadow-color);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            transition: all 0.3s ease;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: rgba(255, 255, 255, 0.9) url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="%23000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>') no-repeat right 10px center;
            background-size: 12px;
            padding-right: 30px;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.7);
            background: rgba(255, 255, 255, 1);
            transform: scale(1.02);
        }

        .form-group .error {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            color: var(--text-color);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-cancel {
            background: #6c757d;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('lecturas.index') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <div class="container">
        <h1>Editar Lectura</h1>

        <!-- Mostrar mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario de edición -->
        <form method="POST" action="{{ route('lecturas.update', $lectura['id'] ?? '') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="sensor_id">Seleccionar Sensor</label>
                <select name="sensor_id" id="sensor_id">
                    <option value="">Selecciona un sensor</option>
                    @forelse($sensores as $sensor)
                        <option value="{{ $sensor['id'] ?? '' }}" {{ old('sensor_id', $lectura['sensor_id'] ?? '') == ($sensor['id'] ?? '') ? 'selected' : '' }}>
                            {{ $sensor['nombre'] ?? 'Sensor Desconocido (ID: ' . ($sensor['id'] ?? 'N/A') . ')' }}
                        </option>
                    @empty
                        <option value="" disabled>No hay sensores disponibles</option>
                    @endforelse
                </select>
                @error('sensor_id')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="valor">Valor</label>
                <input type="number" name="valor" id="valor" value="{{ old('valor', $lectura['valor'] ?? '') }}" step="any">
                @error('valor')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="unidad">Unidad</label>
                <input type="text" name="unidad" id="unidad" value="{{ old('unidad', $lectura['unidad'] ?? '') }}">
                @error('unidad')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="fecha_hora">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_hora" id="fecha_hora" value="{{ old('fecha_hora', isset($lectura['fecha_hora']) ? \Carbon\Carbon::parse($lectura['fecha_hora'])->format('Y-m-d\TH:i') : '') }}">
                @error('fecha_hora')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Guardar
                </button>
                <a href="{{ route('lecturas.index') }}" class="btn btn-cancel">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</body>
</html>