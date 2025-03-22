<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Misión</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Estilos globales */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-align: center;
            background: url('/img/inv.jpg') no-repeat center center/cover;
            animation: backgroundBlur 5s infinite alternate;
        }

        /* Animación de desenfoque dinámico */
        @keyframes backgroundBlur {
            from {
                backdrop-filter: blur(3px);
            }
            to {
                backdrop-filter: blur(8px);
            }
        }

        /* Menú de navegación */
        .nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            background: rgba(0, 50, 0, 0.8);
            padding: 15px 0;
            animation: slideDown 1s ease-in-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin: 0 20px;
            padding: 10px 15px;
            transition: transform 0.3s, background 0.3s, box-shadow 0.3s;
            opacity: 0;
            animation: fadeInLinks 1s ease-in-out forwards;
        }

        @keyframes fadeInLinks {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            transform: scale(1.1);
            box-shadow: 0px 0px 10px rgba(255, 255, 255, 0.5);
        }

        /* Contenedor de la misión */
        .container {
            background: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            animation: zoomBounce 1.5s ease-in-out;
        }

        @keyframes zoomBounce {
            0% {
                transform: scale(0.8);
                opacity: 0;
            }
            60% {
                transform: scale(1.1);
                opacity: 1;
            }
            100% {
                transform: scale(1);
            }
        }

        h1 {
            color: #2c6e49;
            font-size: 36px;
            animation: fadeInText 1.5s ease-in-out forwards;
        }

        p {
            font-size: 18px;
            color: #34495e;
            line-height: 1.8;
            opacity: 0;
            animation: fadeInText 2s ease-in-out forwards;
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
        <a href="/vision">Visión</a>
        <a href="/objetivos">Objetivos</a>
    </div>

    <!-- Contenedor de la misión -->
    <div class="container">
        <h1>Misión</h1>
        <p>
            Desarrollar e implementar un sistema inteligente de riego basado en sensores IoT, 
            que permita optimizar el uso del agua y mejorar la eficiencia agrícola. 
            Nuestro objetivo es ofrecer una solución accesible y automatizada que ayude 
            a agricultores e invernaderos a incrementar la productividad, reducir costos 
            y gestionar cultivos en tiempo real, contribuyendo a la sostenibilidad del sector.
        </p>
    </div>

</body>
</html>
