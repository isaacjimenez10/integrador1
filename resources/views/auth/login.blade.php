<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso al Invernadero</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background: url('/img/bkg_invernadero.jpg') no-repeat center center/cover;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-container h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #1a3c34;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 1rem;
            color: #333;
        }

        .form-group input:focus {
            outline: none;
            border-color: #10b981;
        }

        .login-button {
            width: 100%;
            padding: 0.75rem;
            background-color: #10b981;
            color: white;
            font-size: 1rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .login-button:hover {
            background-color: #059669;
        }

        .login-button:disabled {
            background-color: #d1d5db;
            cursor: not-allowed;
        }

        .register-link {
            text-align: center;
            margin-top: 1rem;
        }

        .register-link a {
            color: #10b981;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-message, .blocked-message {
            text-align: center;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #ef4444;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ACCESO AL INVERNADERO</h2>

        <!-- Mensaje de error general -->
        @if ($errors->any())
            <div class="error-message" id="error-message">
                {{ $errors->first('email') ?: $errors->first('password') ?: 'Credenciales incorrectas. Por favor, intenta de nuevo.' }}
            </div>
        @endif

        <!-- Mensaje de bloqueo -->
        <div class="blocked-message" id="blocked-message" style="display: none;">
            Tu cuenta está bloqueada. Contacta al administrador.
        </div>

        <form method="POST" action="{{ route('login') }}" id="login-form">
            @csrf

            <!-- Correo electrónico -->
            <div class="form-group">
                <label for="email">{{ __('Correo electrónico') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contraseña -->
            <div class="form-group">
                <label for="password">{{ __('Contraseña') }}</label>
                <input id="password" type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Botón de inicio de sesión -->
            <button type="submit" class="login-button" id="login-button">{{ __('Iniciar sesión') }}</button>

            <!-- Enlace de registro -->
            <div class="register-link">
                <p>{{ __('¿No tienes una cuenta?') }} <a href="{{ route('register') }}">{{ __('Regístrate aquí') }}</a></p>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const loginButton = document.getElementById('login-button');
            const blockedMessage = document.getElementById('blocked-message');

            // Función para verificar el estado del usuario
            function checkUserStatus(email) {
                if (!email) return;

                fetch(`{{ route('check.user.status') }}?email=${encodeURIComponent(email)}`, {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.blocked) {
                        blockedMessage.style.display = 'block';
                        loginButton.disabled = true;
                    } else {
                        blockedMessage.style.display = 'none';
                        loginButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error al verificar el estado del usuario:', error);
                });
            }

            // Verificar el estado cuando se escribe el email
            emailInput.addEventListener('input', function() {
                const email = emailInput.value;
                checkUserStatus(email);
            });

            // Verificar el estado inicial si hay un email prellenado
            const initialEmail = emailInput.value;
            if (initialEmail) {
                checkUserStatus(initialEmail);
            }
        });
    </script>
</body>
</html>