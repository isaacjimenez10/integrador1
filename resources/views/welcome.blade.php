<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Net Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background: #1a3d1a;
            color: white;
            overflow-x: hidden;
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .nav {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(0, 50, 0, 0.7);
            padding: 15px;
            text-align: center;
            z-index: 3;
            animation: slideDown 1s ease-out, fadeInNav 2s ease-in-out;
        }
        @keyframes slideDown {
            0% { transform: translateY(-100%); }
            100% { transform: translateY(0); }
        }
        @keyframes fadeInNav {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .nav a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-size: 18px;
            font-weight: bold;
            transition: 0.3s;
            opacity: 0;
            animation: fadeInLink 1.5s ease-in-out forwards;
        }
        .nav a:nth-child(1) { animation-delay: 0.3s; }
        .nav a:nth-child(2) { animation-delay: 0.6s; }
        .nav a:nth-child(3) { animation-delay: 0.9s; }
        .nav a:nth-child(4) { animation-delay: 1.2s; }
        @keyframes fadeInLink {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .nav a:hover {
            color: #a0e08c;
            box-shadow: 0 4px 8px rgba(160, 224, 140, 0.7); 
        }
        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
            width: 100%;
            position: relative;
        }
        .left {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 150vh;
            background: url('/img/bkg_invernadero.jpg') no-repeat center center/cover;
            filter: brightness(50%);
            animation: zoomIn 3s ease-out infinite alternate, backgroundBlink 5s infinite;
            z-index: -1;
        }
        @keyframes zoomIn {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        @keyframes backgroundBlink {
            0% { filter: brightness(50%); }
            50% { filter: brightness(60%); }
            100% { filter: brightness(50%); }
        }
        .right {
            position: relative;
            z-index: 2;
            background: rgba(0, 50, 0, 0.8);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            width: 80%;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            animation: slideUp 1.5s ease-in-out, bounceUp 1s ease-out;
        }
        @keyframes slideUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes bounceUp {
            0% { transform: translateY(10px); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }
        .content {
            flex: 1;
            margin-right: 40px;
        }
        h1 {
            font-size: 40px;
            letter-spacing: 2px;
            color: #a0e08c;
            margin-bottom: 20px;
            animation: slideIn 1s ease-out;
        }
        @keyframes slideIn {
            0% { transform: translateX(-50%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        p {
            font-size: 20px;
            max-width: 80%;
            line-height: 1.6;
            color: #d4f8c4;
            margin-bottom: 30px;
            animation: slideIn 1s ease-out 0.3s;
        }
        .btn {
            display: inline-block;
            padding: 12px 25px;
            border: 2px solid #a0e08c;
            text-decoration: none;
            color: #a0e08c;
            margin-top: 20px;
            font-size: 20px;
            border-radius: 5px;
            text-transform: uppercase;
            transition: background 0.3s, color 0.3s;
            animation: buttonFadeIn 1s ease-out 0.6s;
        }
        @keyframes buttonFadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .btn:hover {
            background: #a0e08c;
            color: black;
            animation: bounce 0.3s ease-in-out;
        }
        @keyframes bounce {
            0% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
            100% { transform: translateY(0); }
        }
        .nav a.login-icon {
            font-size: 20px;
            position: absolute;
            top: 15px;
            right: 20px;
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s;
        }
        .nav a.admin-icon {
            font-size: 20px;
            position: absolute;
            top: 15px;
            right:115px;
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s;
        }
        .nav a.admin-icon i {
            margin-right: 8px;
        }
        .nav a.admin-icon:hover {
            transform: rotate(360deg); 
        }
        .nav a.login-icon i {
            margin-right: 8px;
        }
        .nav a.login-icon:hover {
            transform: rotate(360deg); 
        }
        .contact-form {
            flex: 1;
            max-width: 400px;
            padding: 20px;
            background: rgba(0, 50, 0, 0.7);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #a0e08c;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 16px;
        }
        .contact-form input::placeholder, .contact-form textarea::placeholder {
            color: #d4f8c4;
        }
        .contact-form button {
            width: 100%;
            padding: 12px;
            background: #a0e08c;
            color: black;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .contact-form button:hover {
            background: #8cc576;
        }
        .projects-container {
            width: 80%;
            background: rgba(0, 50, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin: 10px auto; 
            animation: slideUp 1.5s ease-in-out;
        }
        .projects-container h2 {
            font-size: 32px;
            color: #a0e08c;
            margin-bottom: 20px;
            text-align: center;
        }
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .project-card {
            background: rgba(0, 50, 0, 0.7);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(160, 224, 140, 0.5);
        }
        .project-card h3 {
            font-size: 24px;
            color: #a0e08c;
            margin-bottom: 10px;
        }
        .project-card p {
            font-size: 16px;
            color: #d4f8c4;
            line-height: 1.5;
        }
        .location-container {
            width: 80%;
            background: rgba(0, 50, 0, 0.8);
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            margin: 10px auto; 
            animation: slideUp 1.5s ease-in-out;
        }
        .location-container h2 {
            font-size: 32px;
            color: #a0e08c;
            margin-bottom: 20px;
            text-align: center;
        }
        .location-container p {
            font-size: 20px;
            color: #d4f8c4;
            text-align: center;
            margin-bottom: 20px;
        }
        .location-container img {
    width: 45%;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: block; 
    margin: 0 auto;
}
    </style>
</head>
<body>
    <div class="nav">
        <a href="{{ url('/vision') }}">Vision</a>
        <a href="{{ url('/mision') }}">Mision</a>
        <a href="{{ url('/objetivos') }}">Obletivos</a>
        <a href="{{ url('login') }}" class="login-icon"><i class="fas fa-user"></i>Login</a>
    </div>
    <div class="container">
        <div class="left"></div>
        <div class="right">
            <div class="content">
                <h1>Net Nexus</h1>
                <p>Net Nexus ofrece soluciones inteligentes para la gestión eficiente del agua en invernaderos.</p>
                <a href="{{ url('/nosotros') }}" class="btn">Sobre Nosotros</a>
            </div>
            <!-- Formulario de contacto -->
            <form class="contact-form">
                <input type="text" placeholder="Nombre" required>
                <input type="email" placeholder="Correo electrónico" required>
                <textarea placeholder="Mensaje" rows="5" required></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
    <!-- Nuevo contenedor de proyectos -->
    <div class="projects-container">
        <h2>Nuestros Proyectos</h2>
        <div class="projects-grid">
            <div class="project-card">
                <h3>Sistema de Riego Inteligente</h3>
                <p>Desarrollo de un sistema automatizado para el control de riego.</p>
            </div>
            
        </div>
    </div>
    <!-- Contenedor de ubicación -->
    <div class="location-container">
        <h2>Ubicación</h2>
        <p>Nuestra oficina principal está ubicada en la siguiente dirección:</p>
        <img src="img/ubi.png" alt="Mapa de Ubicación">
    </div>
</body>
</html>