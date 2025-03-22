<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Objetivos</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos globales */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            background: url('/img/obj.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            padding: 20px;
        }

        /* Efecto de fondo oscuro */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        /* Menú de navegación */
        .nav {
            position: absolute;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            background: rgba(0, 80, 0, 0.9);
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin: 0 20px;
            padding: 10px 15px;
            transition: 0.3s;
            position: relative;
        }

        .nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        /* Estilo del título */
        h1 {
            font-size: 42px;
            font-weight: bold;
            background: linear-gradient(90deg, #2c6e49, #1e5631);
            padding: 12px 20px;
            border-radius: 10px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
            margin-bottom: 30px;
            animation: fadeInSlide 1.5s ease-in-out;
        }

        /* Tarjetas de objetivos */
        .objetivos-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1000px;
        }

        .objetivo {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
            width: 300px;
            text-align: center;
            color: #2c3e50;
            transition: transform 0.3s ease-in-out;
            animation: fadeInText 1.5s ease-in-out;
        }

        .objetivo:hover {
            transform: translateY(-10px);
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.4);
        }

        .objetivo i {
            font-size: 40px;
            color: #2c6e49;
            margin-bottom: 10px;
        }

        .objetivo h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        /* Animaciones */
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInText {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

    <!-- Menú de navegación -->
    <div class="nav">
        <a href="/"> Inicio</a>
        <a href="/mision">Misión</a>
        <a href="/vision">Vision</a>
    </div>

    <!-- Título -->
    <h1>Objetivos</h1>

    <!-- Contenedor de tarjetas -->
    <div class="objetivos-container">
        <div class="objetivo">
            <i class="fas fa-tint"></i>
            <h3>Optimizar el uso del agua</h3>
            <p>
                Implementar un sistema de riego inteligente con sensores IoT para mejorar el consumo de agua en la agricultura.
            </p>
        </div>

        <div class="objetivo">
            <i class="fas fa-chart-line"></i>
            <h3>Reducir costos y mejorar productividad</h3>
            <p>
                Disminuir gastos operativos y aumentar la eficiencia en los cultivos con tecnología automatizada.
            </p>
        </div>

        <div class="objetivo">
            <i class="fas fa-mobile-alt"></i>
            <h3>Control remoto de cultivos</h3>
            <p>
                Facilitar la gestión de los cultivos a través de una plataforma web y móvil con monitoreo en tiempo real.
            </p>
        </div>
    </div>

</body>
</html>
