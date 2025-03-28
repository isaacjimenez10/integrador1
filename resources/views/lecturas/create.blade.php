<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Lectura</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('img/lec.jpg') }}') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
            position: relative;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            width: 90%;
            max-width: 600px;
            text-align: center;
            animation: fadeIn 1s ease-in-out, scaleUp 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes scaleUp {
            from { transform: scale(0.8); }
            to { transform: scale(1); }
        }

        h1 {
            color: #ffffff;
            font-size: 26px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .btn-back {
            display: inline-block;
            margin-bottom: 15px;
            padding: 12px;
            text-decoration: none;
            background: #17a2b8;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #138496;
            transform: scale(1.05);
        }

        form {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        }

        label {
            font-size: 16px;
            color: #333;
            display: block;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        input[type="submit"] {
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        input[type="submit"]:hover {
            background: #218838;
            transform: scale(1.05);
        }

        .error-message, .success-message {
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            color: white;
        }

        .error-message {
            background: #e74c3c;
        }

        .success-message {
            background: #28a745;
        }

        .error {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Lectura</h1>

        <!-- Botón para regresar a la lista de lecturas -->
        <a href="{{ route('lecturas.index') }}" class="btn-back">Volver a la lista</a>

        <!-- Mostrar mensajes de error o éxito -->
        @if (session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <!-- Formulario para crear una lectura -->
        <form action="{{ route('lecturas.store') }}" method="POST">
            @csrf

            <!-- Selector de Sensor -->
            <label for="sensor_id">Seleccionar Sensor</label>
            <select name="sensor_id" id="sensor_id">
                <option value="">Selecciona un sensor</option>
                @foreach($sensores as $sensor)
                    <option value="{{ $sensor['id'] }}" {{ old('sensor_id') == $sensor['id'] ? 'selected' : '' }}>
                        {{ $sensor['nombre'] }}
                    </option>
                @endforeach
            </select>
            @error('sensor_id')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Campo para el valor -->
            <label for="valor">Valor</label>
            <input type="number" name="valor" id="valor" value="{{ old('valor') }}" step="any">
            @error('valor')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Campo para la unidad -->
            <label for="unidad">Unidad</label>
            <input type="text" name="unidad" id="unidad" value="{{ old('unidad') }}">
            @error('unidad')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Campo para la fecha y hora -->
            <label for="fecha_hora">Fecha y Hora</label>
            <input type="datetime-local" name="fecha_hora" id="fecha_hora" value="{{ old('fecha_hora') }}">
            @error('fecha_hora')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Botón para enviar el formulario -->
            <input type="submit" value="Crear Lectura">
        </form>
    </div>
</body>
</html>