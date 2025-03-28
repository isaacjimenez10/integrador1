<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sensor</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --text-color: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.4);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('img/n.jpg') }}') no-repeat center center/cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        /* Capa translúcida para mejorar la visibilidad */
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

        /* Contenedor con animación de entrada */
        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px var(--shadow-color);
            width: 350px;
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
        }

        h1 {
            color: var(--text-color);
            font-size: 24px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px var(--shadow-color);
        }

        /* Diseño del formulario */
        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            color: var(--text-color);
            font-weight: bold;
            font-size: 14px;
            text-align: left;
            display: block;
        }

        /* Estilos avanzados para los inputs */
        input {
            width: 100%;
            padding: 10px;
            border: 2px solid var(--primary-color);
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.9);
            color: #333;
        }

        input:focus {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(40, 167, 69, 0.6);
        }

        /* Botón con animación al hacer hover */
        button {
            margin-top: 15px;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            background: var(--primary-color);
            color: var(--text-color);
            transition: all 0.3s ease-in-out;
            animation: pulse 2s infinite;
        }

        button:hover {
            background: var(--secondary-color);
            transform: scale(1.1);
            box-shadow: 0px 5px 15px var(--shadow-color);
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }
            50% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.8); }
            100% { box-shadow: 0 0 10px rgba(40, 167, 69, 0.5); }
        }

        /* Botón de volver */
        .link {
            display: block;
            margin-top: 15px;
            padding: 10px;
            text-decoration: none;
            background: rgba(40, 167, 69, 0.9);
            color: var(--text-color);
            border-radius: 8px;
            transition: all 0.3s ease-in-out;
        }

        .link:hover {
            background: rgba(40, 167, 69, 1);
            transform: scale(1.05);
        }

        .error-message {
            color: #e74c3c;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .error {
            color: #e74c3c;
            font-size: 12px;
            text-align: left;
            margin-top: -10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <a href="{{ route('admin.dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <div class="container">
        <h1>🌿 Editar Sensor</h1>

        <!-- Mostrar mensaje de error si existe -->
        @if (session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

        <form action="{{ route('sensores.update', $sensor['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $sensor['nombre'] ?? '') }}" required>
            @error('nombre')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="{{ old('tipo', $sensor['tipo'] ?? '') }}" required>
            @error('tipo')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" value="{{ old('ubicacion', $sensor['ubicacion'] ?? '') }}" required>
            @error('ubicacion')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">✅ Actualizar Sensor</button>
        </form>

        <a href="{{ route('sensores.index') }}" class="link">🔙 Volver a la lista</a>
    </div>
</body>
</html>