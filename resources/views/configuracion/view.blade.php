<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Configuración</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
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
            font-size: 24px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        p {
            font-size: 16px;
            margin: 10px 0;
        }

        strong {
            color: #28a745;
        }

        /* Botón de volver */
        .link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
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
        <h1>Detalle de Configuración</h1>
        <p><strong>ID:</strong> {{ $configuracion->id }}</p>
        <p><strong>Sensor:</strong> {{ $configuracion->sensor->nombre }}</p>
        <p><strong>Mínimo:</strong> {{ $configuracion->minimo }}</p>
        <p><strong>Máximo:</strong> {{ $configuracion->maximo }}</p>
        <a class="link" href="{{ route('configuracion.index') }}">Volver al Listado</a>
    </div>
</body>
</html>