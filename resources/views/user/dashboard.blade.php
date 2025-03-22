<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Usuario - Invernadero</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .dashboard-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px;
            text-align: center;
            animation: slideUp 1s ease-in-out;
        }

        @keyframes slideUp {
            0% { transform: translateY(50px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        h2 {
            margin-bottom: 20px;
            color: #2c3e50;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        p {
            color: #666;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .logout-btn {
            display: inline-block;
            padding: 12px 25px;
            background-color: #27ae60;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #219653;
            transform: translateY(-3px);
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        .user-info {
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(39, 174, 96, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(39, 174, 96, 0.2);
        }

        .user-info p {
            margin: 0;
            color: #2c3e50;
            font-size: 16px;
        }

        .user-info p strong {
            color: #27ae60;
        }

        .quick-links {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .quick-links a {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .quick-links a:hover {
            background-color: #2980b9;
            transform: translateY(-3px);
        }

        .quick-links a:active {
            transform: translateY(0);
        }

        .project-info {
            margin-top: 30px;
            padding: 20px;
            background: rgba(39, 174, 96, 0.1);
            border-radius: 10px;
            border: 1px solid rgba(39, 174, 96, 0.2);
            text-align: left;
        }

        .project-info h3 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .project-info p {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .project-info ul {
            list-style-type: disc;
            padding-left: 20px;
            color: #666;
            font-size: 16px;
            line-height: 1.6;
        }

        .project-info ul li {
            margin-bottom: 10px;
        }

        .project-info a {
            color: #27ae60;
            text-decoration: none;
            font-weight: bold;
        }

        .project-info a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        .success {
            background-color: rgba(39, 174, 96, 0.1);
            border: 1px solid #27ae60;
            color: #27ae60;
        }

        .error {
            background-color: rgba(231, 76, 60, 0.1);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        @if (session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        <div class="user-info">
            <h2>Bienvenido, <strong>{{ Auth::user()->name }}</strong></h2>
            <p>Este es tu panel de usuario. Aquí puedes gestionar tu cuenta y acceder a las funcionalidades disponibles.</p>
            <p>Email: <strong>{{ Auth::user()->email }}</strong></p>
            <p>Rol: <strong>{{ Auth::user()->role }}</strong></p>
        </div>

        <div class="quick-links">
            <a href="">Mi Perfil</a>
            <a href="">Configuración</a>
        </div>

        <div class="project-info">
            <h3>Proyectos de Net Nexus</h3>
            <p>Net Nexus ofrece soluciones inteligentes para la gestión eficiente del agua en invernaderos. Nuestros proyectos están diseñados para optimizar el uso de recursos y mejorar la productividad agrícola. A continuación, te explicamos cómo funcionan:</p>
            <ul>
                <li><strong>Invernadero Inteligente:</strong> Sistema automatizado para el control de riego y clima en invernaderos. Utiliza sensores para monitorear las condiciones ambientales y ajusta el riego y la ventilación de manera automática.</li>
            </ul>
            <p>Para más información sobre cómo utilizar estas herramientas, visita nuestra <a href="#">documentación</a> o contacta a nuestro equipo de soporte.</p>
        </div>
        <br>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">Cerrar Sesión</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.message');
            messages.forEach(message => {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                }, 5000);
            });
        });
    </script>
</body>
</html>