<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Configuración</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('img/n.jpg') }}') no-repeat center center/cover;
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

        .error {
            color: #e74c3c;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Crear Nueva Configuración</h1>

        <!-- Botón para regresar a la lista de configuraciones -->
       

        <!-- Formulario para crear una configuración -->
        <form action="{{ route('configuracion.store') }}" method="POST">
            @csrf

            <!-- Selector de Sensor -->
            <label for="sensor_id">Seleccionar Sensor</label>
            <select name="sensor_id" id="sensor_id">
                <option value="">Selecciona un sensor</option>
                @foreach($sensores as $sensor)
                    <option value="{{ $sensor->id }}" {{ old('sensor_id') == $sensor->id ? 'selected' : '' }}>
                        {{ $sensor->nombre }}
                    </option>
                @endforeach
            </select>
            @error('sensor_id')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Campo para el valor mínimo -->
            <label for="minimo">Valor Mínimo</label>
            <input type="number" name="minimo" id="minimo" value="{{ old('minimo') }}" step="0.01">
            @error('minimo')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Campo para el valor máximo -->
            <label for="maximo">Valor Máximo</label>
            <input type="number" name="maximo" id="maximo" value="{{ old('maximo') }}" step="0.01">
            @error('maximo')
                <div class="error">{{ $message }}</div>
            @enderror

            <!-- Botón para enviar el formulario -->
            <input type="submit" value="Crear Configuración">
            <a href="{{ route('configuracion.index') }}" class="btn-back">Volver a la lista</a>
        </form>
    </div>
</body>
</html>
