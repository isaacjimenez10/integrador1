<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .dashboard-container {
            animation: fadeIn 0.5s ease-in-out;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 0.375rem;
            z-index: 10;
        }
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Barra de navegación -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-xl font-bold text-gray-800 hover:text-blue-600 transition duration-300">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i> 
                    Panel Admin
                </a>
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="{{ url('/configuracion') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-cog mr-2 text-gray-500"></i> 
                        Configuración
                    </a>
                    <a href="{{ url('/sensores') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-microchip mr-2 text-gray-500"></i> 
                        Sensores
                    </a>
                    <a href="{{ url('/lecturas') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-chart-line mr-2 text-gray-500"></i> 
                        Lecturas
                    </a>
                    <a href="{{ url('/sensor-data') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-microchip mr-2 text-gray-500"></i> 
                        SensoresData
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative dropdown">
                        <button class="flex items-center text-gray-800 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full p-1 transition duration-300" aria-expanded="false" aria-haspopup="true">
                            <i class="fas fa-user-circle mr-2 text-xl text-gray-600"></i>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down ml-2 text-sm"></i>
                        </button>
                        <div class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                            <form action="{{ route('admin.logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </div>
                    </div>
                    <button id="menu-toggle" class="md:hidden text-gray-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md p-2 transition duration-300" aria-controls="mobile-menu" aria-expanded="false">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
            <div id="mobile-menu" class="hidden md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <a href="{{ url('/configuracion') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-cog mr-2 text-gray-500"></i> Configuración
                    </a>
                    <a href="{{ url('/sensores') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-microchip mr-2 text-gray-500"></i> Sensores
                    </a>
                    <a href="{{ url('/lecturas') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-chart-line mr-2 text-gray-500"></i> Lecturas
                    </a>
                    <a href="{{ url('/sensor-data') }}" class="flex items-center text-gray-700 hover:text-blue-600 hover:bg-blue-50 px-3 py-2 rounded-md transition duration-300">
                        <i class="fas fa-microchip mr-2 text-gray-500"></i> SensoresData
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="dashboard-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Mensajes de éxito y error -->
        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard de Administrador</h1>

        <!-- Cantidad de Usuarios Registrados -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Cantidad de Usuarios Registrados</h2>
            <p class="text-2xl font-bold text-gray-800">{{ $users->total() }}</p>
        </div>

        <!-- Lista de Usuarios -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-8">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Lista de Usuarios</h2>
            <div class="flex space-x-4 mb-4">
    <a href="{{ route('users.export') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-300">Exportar a Excel</a>
    <form id="importForm" action="{{ route('admin.import') }}" method="POST" enctype="multipart/form-data" class="inline-block">
        @csrf
        <input type="file" name="file" id="importExcel" accept=".xlsx,.csv,.ods" class="hidden" />
        <label for="importExcel" class="bg-green-500 text-white px-4 py-2 rounded cursor-pointer hover:bg-green-600 transition duration-300">Importar desde Excel</label>
        <span class="text-gray-600 text-sm ml-2">Requiere: name, email, password</span>
    </form>
</div>
            <div class="overflow-x-auto mt-4">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">ID</th>
                            <th class="px-6 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Nombre</th>
                            <th class="px-6 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Correo</th>
                            <th class="px-6 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Rol</th>
                            <th class="px-6 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Estado</th>
                            <th class="px-6 py-3 border-b text-left text-xs font-semibold text-gray-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 border-b border-gray-200">{{ $user->id }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ $user->name }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">{{ $user->email }}</td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <span class="{{ $user->role === 'admin' ? 'text-blue-600' : 'text-green-600' }}">
                                    {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <span class="{{ $user->status === 'blocked' ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $user->status === 'blocked' ? 'Bloqueado' : 'Activo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 border-b border-gray-200">
                                <div class="action-buttons">
                                    <a href="{{ route('user.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 transition duration-300">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('users.toggleBlock', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-800 transition duration-300">
                                            <i class="fas fa-lock{{ $user->status === 'blocked' ? '-open' : '' }}"></i> 
                                            {{ $user->status === 'blocked' ? 'Desbloquear' : 'Bloquear' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition duration-300" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {!! $users->links() !!}
            </div>
        </div>

        <!-- Gráfica de Registros Mensuales -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Registros Mensuales</h2>
            <canvas id="registrosMensualesChart" class="w-full h-48"></canvas>
        </div>
    </div>

    <script>
        // Menú móvil
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
            this.setAttribute('aria-expanded', menu.classList.contains('hidden') ? 'false' : 'true');
        });

        // Gráfica de registros mensuales
        const registrosCtx = document.getElementById('registrosMensualesChart').getContext('2d');
        const registrosMensualesChart = new Chart(registrosCtx, {
            type: 'bar',
            data: {
                labels: @json($meses),
                datasets: [{
                    label: 'Registros Mensuales',
                    data: @json($registrosPorMes),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Importar desde Excel con validación básica
        document.getElementById('importExcel').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                console.log('Archivo seleccionado:', file.name, file.type);
                document.getElementById('importForm').submit();
            } else {
                alert('Por favor, selecciona un archivo Excel válido.');
            }
        });
    </script>
</body>
</html>