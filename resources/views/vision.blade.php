<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visión</title>
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
            background: url('/img/vi.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Efecto de fondo parallax */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
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

        /* Efecto de subrayado animado en el menú */
        .nav a::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -5px;
            width: 0;
            height: 3px;
            background: white;
            transition: 0.3s;
        }

        .nav a:hover::after {
            width: 100%;
            left: 0;
        }

        /* Contenedor de la visión */
        .container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
            max-width: 650px;
            position: relative;
            overflow: hidden;
            animation: fadeInSlide 1.5s ease-in-out;
        }

        /* Borde iluminado animado */
        .container::before {
            content: "";
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 15px;
            background: linear-gradient(45deg, #2c6e49, #4caf50, #1e5631);
            z-index: -1;
            opacity: 0.6;
            animation: borderGlow 3s linear infinite;
        }

        @keyframes borderGlow {
            0% { filter: blur(5px); opacity: 0.6; }
            50% { filter: blur(10px); opacity: 0.9; }
            100% { filter: blur(5px); opacity: 0.6; }
        }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        h1 {
            color: white;
            background: linear-gradient(90deg, #2c6e49, #1e5631);
            padding: 12px;
            border-radius: 10px;
            font-size: 38px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        h1:hover {
            transform: scale(1.05);
            box-shadow: 0px 0px 20px rgba(44, 110, 73, 0.8);
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
        <a href="/mision">Misión</a>
        <a href="/objetivos">Objetivos</a>
    </div>

    <!-- Contenedor de la visión -->
    <div class="container">
        <h1>Visión</h1>
        <p>
            Ser un referente en sistemas de riego inteligente, promoviendo una agricultura más eficiente y sostenible. 
            Buscamos expandir nuestra tecnología para ofrecer mayor automatización y análisis avanzado de cultivos, 
            facilitando la toma de decisiones y mejorando el uso responsable de los recursos naturales.
        </p>
    </div>

</body>
</html>
