<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle del Sensor</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

        /* Fondo con imagen */
        body {
            font-family: 'Poppins', sans-serif;
            background: url('/img/n.jpg') no-repeat center center/cover;
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
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            width: 350px;
            text-align: center;
            animation: fadeIn 1s ease-in-out, scaleUp 0.8s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes scaleUp {
            from {
                transform: scale(0.8);
            }
            to {
                transform: scale(1);
            }
        }

        h1 {
            color: #ffffff;
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
            display: block;
        }

        /* Estilos avanzados para los inputs y select */
        input, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #28a745;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.3s ease-in-out;
        }

        input:focus, select:focus {
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
            background: #28a745;
            color: white;
            transition: all 0.3s ease-in-out;
            animation: pulse 2s infinite;
        }

        button:hover {
            background: #218838;
            transform: scale(1.1);
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.4);
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(40, 167, 69, 0.8);
            }
            100% {
                box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
            }
        }

        /* Botón de volver */
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
        <h1>🌿 Detalle del Sensor</h1>
        <p><strong>ID:</strong> {{ $sensor->id }}</p>
        <p><strong>Nombre:</strong> {{ $sensor->nombre }}</p>
        <p><strong>Tipo:</strong> {{ $sensor->tipo }}</p>
        <p><strong>Ubicación:</strong> {{ $sensor->ubicacion }}</p>

        <a href="{{ url('sensores') }}" class="link">🔙 Volver a la lista</a>
    </div>

</body>
</html>
