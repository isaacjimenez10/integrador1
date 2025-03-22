<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Configuración</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('/img/n.jpg') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            color: #ffffff;
        }

        /* Capa translúcida para mejorar la visibilidad */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        /* Contenedor principal */
        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        /* Animaciones */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Estilos del encabezado */
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        /* Diseño del formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            color: #ffffff;
            font-weight: bold;
            font-size: 14px;
            text-align: left;
        }

        /* Estilos para inputs y select */
        input, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #28a745;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        input:focus, select:focus {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.6);
        }

        /* Estilos del botón */
        button {
            margin-top: 15px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            background: #28a745;
            color: white;
            transition: all 0.3s ease-in-out;
        }

        button:hover {
            background: #218838;
            transform: scale(1.1);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.4);
        }

        /* Estilos del enlace de volver */
        .link {
            display: block;
            margin-top: 15px;
            padding: 10px;
            text-decoration: none;
            background: rgba(40, 167, 69, 0.9);
            color: white;
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        .link:hover {
            background: rgba(40, 167, 69, 1);
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Configuración</h1>
        <form action="{{ route('configuracion.update', $configuracion->id) }}" method="POST">
            @csrf
            @method('PUT')
            <label for="sensor_id">Sensor:</label>
            <select name="sensor_id" id="sensor_id" required>
                @foreach ($sensores as $sensor)
                    <option value="{{ $sensor->id }}" {{ $configuracion->sensor_id == $sensor->id ? 'selected' : '' }}>{{ $sensor->nombre }}</option>
                @endforeach
            </select>

            <label for="minimo">Mínimo:</label>
            <input type="number" name="minimo" id="minimo" value="{{ $configuracion->minimo }}" required>

            <label for="maximo">Máximo:</label>
            <input type="number" name="maximo" id="maximo" value="{{ $configuracion->maximo }}" required>

            <button type="submit">Actualizar</button>
        </form>
        <a class="link" href="{{ route('configuracion.index') }}">Volver al Listado</a>
    </div>
</body>
</html>
