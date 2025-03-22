<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sensor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #d4edda, #a8df8e);
            color: #2c3e50;
            text-align: center;
            padding: 135px;
        }

        h1 {
            color: #145a32;
        }

        .container {
            width: 40%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
        }

        input {
            width: 80%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            background: #ffc107;
            color: black;
            transition: 0.3s;
        }

        button:hover {
            background: #e0a800;
        }

        .link {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            text-decoration: none;
            background: #3498db;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }

        .link:hover {
            background: #217dbb;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>🌿 Editar Sensor</h1>
        <form action="{{ url('sensores/'.$sensor->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" value="{{ $sensor->nombre }}" required>

            <label for="tipo">Tipo:</label>
            <input type="text" id="tipo" name="tipo" value="{{ $sensor->tipo }}" required>

            <label for="ubicacion">Ubicación:</label>
            <input type="text" id="ubicacion" name="ubicacion" value="{{ $sensor->ubicacion }}" required>

            <button type="submit">✅ Actualizar Sensor</button>
        </form>

        <a href="{{ url('sensores') }}" class="link">🔙 Volver a la lista</a>
    </div>

</body>
</html>
