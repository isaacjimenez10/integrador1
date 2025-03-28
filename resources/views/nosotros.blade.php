<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nosotros</title>
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
            min-height: 100vh;
            text-align: center;
            background: url('/img/n.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
            position: relative;
            animation: fadeInBackground 2s ease-in-out;
        }

        /* Fondo con superposición */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
            animation: fadeInOverlay 2s ease-in-out;
        }

        @keyframes fadeInOverlay {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Menú de navegación */
        .nav {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            background: rgba(0, 80, 0, 0.9);
            padding: 15px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 1000;
            animation: slideDown 1.5s ease-in-out;
        }

        .nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin: 0 20px;
            padding: 10px 15px;
            position: relative;
            transition: all 0.3s ease-in-out;
        }

        .nav a:hover {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.7);
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Contenedor principal */
        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.4);
            max-width: 700px;
            position: relative;
            overflow: hidden;
            animation: fadeInSlide 1.5s ease-in-out;
            transition: transform 0.5s ease-in-out;
        }

        .container:hover {
            transform: scale(1.05);
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

        /* Título con efecto de escritura */
        h1 {
            color: white;
            background: linear-gradient(90deg, #2c6e49, #1e5631);
            padding: 15px;
            border-radius: 10px;
            font-size: 36px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.4);
            display: inline-block;
            overflow: hidden;
            white-space: nowrap;
            border-right: 4px solid white;
            width: 0;
            animation: typing 2s steps(30, end) forwards, blink 0.8s infinite;
        }

        @keyframes typing {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        @keyframes blink {
            50% {
                border-color: transparent;
            }
        }

        /* Animación en los párrafos */
        p {
            font-size: 18px;
            color: #34495e;
            line-height: 1.8;
            text-align: justify;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInText 2s ease-in-out forwards;
        }

        p:nth-child(1) { animation-delay: 0.5s; }
        p:nth-child(2) { animation-delay: 1s; }
        p:nth-child(3) { animation-delay: 1.5s; }

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
        <a href="/">Inicio</a>
        <a href="/mision">Misión</a>
        <a href="/objetivos">Objetivos</a>
    </div>

    <!-- Contenedor de la visión -->
    <div class="container">
        <h1>Sobre Nosotros</h1>
        <p>
            Somos una empresa dedicada a la innovación y al desarrollo de soluciones tecnológicas 
            en el campo del Internet de las Cosas (IoT). Nos especializamos en la creación de productos 
            inteligentes que mejoran la eficiencia y la conectividad en diversos sectores.
        </p>
        <p>
            Nuestro equipo está conformado por profesionales apasionados por la tecnología, 
            quienes trabajan constantemente en la integración de hardware y software para ofrecer 
            soluciones de vanguardia. Desarrollamos aplicaciones móviles y páginas web optimizadas 
            para interactuar con dispositivos IoT, proporcionando a nuestros clientes herramientas 
            innovadoras y fáciles de usar.
        </p>
        <p>
            Nos enfocamos en la calidad, la seguridad y la usabilidad, asegurando que cada uno de 
            nuestros productos cumpla con los más altos estándares tecnológicos. <br>
            Creemos en el futuro digital y estamos comprometidos en hacer que la tecnología sea accesible y eficiente 
            para todos.
        </p>
    </div>

</body>
</html>
