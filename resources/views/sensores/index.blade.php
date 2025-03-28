<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Sensores</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --text-color: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.4);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('img/n.jpg') }}') no-repeat center center/cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px var(--shadow-color);
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            background: var(--primary-color);
            color: var(--text-color);
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            z-index: 3;
        }

        .back-button:hover {
            background: var(--secondary-color);
            transform: translateX(-5px);
        }

        h1 {
            color: var(--text-color);
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 2px 2px 8px var(--shadow-color);
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .search-form { flex: 1; min-width: 250px; }
        .search-form form { display: flex; gap: 10px; }
        .search-form input {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-form input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
        }

        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            color: var(--text-color);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary { background: var(--primary-color); }
        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 2rem;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        table { width: 100%; border-collapse: collapse; }
        thead { background: var(--primary-color); color: var(--text-color); }
        th, td { padding: 15px; text-align: center; }
        tbody tr { transition: all 0.3s ease; }
        tbody tr:hover { background: rgba(40, 167, 69, 0.1); transform: scale(1.01); }
        .actions-btns { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .btn-view { background: #3498db; }
        .btn-edit { background: #f39c12; }
        .btn-delete { background: #e74c3c; }
        .btn:hover { transform: translateY(-2px); opacity: 0.9; }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            color: var(--text-color);
        }

        .pagination .page-link {
            background: var(--primary-color);
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
        }

        .pagination .page-link:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
        }

        .graph-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(15px);
        }

        #sensorChart { width: 100% !important; height: 500px !important; }

        .error-message {
            color: #e74c3c;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success-message {
            color: #28a745;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .header-actions { flex-direction: column; align-items: stretch; }
            .search-form form { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
            .graph-container { max-width: 100%; padding: 1rem; }
            #sensorChart { height: 300px !important; }
        }
    </style>
</head>
<body>
    <a href="{{ route('admin.dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <div class="container">
        <h1>Lista de Sensores</h1>

        <!-- Mostrar mensaje de error si existe -->
        @if (isset($error))
            <div class="error-message">{{ $error }}</div>
        @endif

        <!-- Mostrar mensaje de éxito si existe -->
        @if (session('success'))
            <div class="success-message">{{ session('success') }}</div>
        @endif

        <div class="header-actions">
            <div class="search-form">
                <form method="GET" action="{{ route('sensores.index') }}" id="searchForm">
                    <input type="text" name="search" placeholder="Buscar sensores..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('sensores.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Sensor
                </a>
                <a href="{{ url('sensores/export') }}" class="btn btn-primary" id="exportExcel">
    <i class="fas fa-file-excel"></i> Exportar Excel
</a>
            </div>
        </div>

        <div class="table-container">
            <table id="sensorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sensores as $sensor)
                    <tr>
                        <td>{{ $sensor['id'] }}</td>
                        <td>{{ $sensor['nombre'] ?? 'N/A' }}</td>
                        <td>{{ $sensor['tipo'] ?? 'N/A' }}</td>
                        <td>{{ $sensor['ubicacion'] ?? 'N/A' }}</td>
                        <td class="actions-btns">
                            <a href="{{ route('sensores.show', $sensor['id']) }}" class="btn btn-view">
                                <i class="fas fa-eye"></i> Ver
                            </a>
                            <a href="{{ route('sensores.edit', $sensor['id']) }}" class="btn btn-edit">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <form action="{{ route('sensores.destroy', $sensor['id']) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('¿Estás seguro?')">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">No se encontraron sensores.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $sensores->links('pagination::bootstrap-4') }}
            <span>
                Mostrando {{ $sensores->firstItem() }} a {{ $sensores->lastItem() }} de {{ $sensores->total() }} resultados
            </span>
        </div>
    </div>

    <div class="graph-container">
        <canvas id="sensorChart"></canvas>
    </div>

    <script>
        let sensorChart;
        const ctx = document.getElementById('sensorChart').getContext('2d');

        // Función para actualizar la gráfica basada en la tabla visible
        function updateChart() {
            const tableRows = document.querySelectorAll('#sensorsTable tbody tr');
            const sensorTypes = {};

            tableRows.forEach(row => {
                const type = row.cells[2].textContent; // Columna "Tipo"
                if (type !== 'N/A') { // Ignorar valores no definidos
                    sensorTypes[type] = (sensorTypes[type] || 0) + 1;
                }
            });

            const labels = Object.keys(sensorTypes);
            const counts = Object.values(sensorTypes);

            if (sensorChart) {
                sensorChart.destroy();
            }

            sensorChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sensores por Tipo (Página Actual)',
                        data: counts,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { labels: { color: '#ffffff' } }
                    },
                    scales: {
                        x: { ticks: { color: '#ffffff' } },
                        y: { beginAtZero: true, ticks: { color: '#ffffff', stepSize: 1 } }
                    }
                }
            });
        }

        // Función para manejar la búsqueda y paginación con AJAX
        function handleTableUpdate(url, method = 'GET') {
            fetch(url, {
                method: method,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Actualizar tabla
                const newTable = doc.querySelector('#sensorsTable');
                if (newTable) {
                    document.getElementById('sensorsTable').replaceWith(newTable);
                }

                // Actualizar paginación
                const newPagination = doc.querySelector('.pagination');
                if (newPagination) {
                    document.querySelector('.pagination').replaceWith(newPagination);
                }

                // Actualizar gráfica con los nuevos datos visibles
                updateChart();

                // Reasignar eventos a los nuevos enlaces de paginación
                attachPaginationEvents();

                // Reasignar eventos a los nuevos formularios de eliminación
                attachDeleteEvents();
            })
            .catch(error => console.error('Error al actualizar la tabla:', error));
        }

        // Función para asignar eventos a los enlaces de paginación
        function attachPaginationEvents() {
            document.querySelectorAll('.pagination .page-link').forEach(link => {
                link.removeEventListener('click', handlePaginationClick); // Evitar duplicados
                link.addEventListener('click', handlePaginationClick);
            });
        }

        // Función para manejar los clics en los enlaces de paginación
        function handlePaginationClick(e) {
            e.preventDefault();
            const url = e.currentTarget.href;
            handleTableUpdate(url);
        }

        // Función para asignar eventos a los formularios de eliminación
        function attachDeleteEvents() {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.removeEventListener('submit', handleDelete); // Evitar duplicados
                form.addEventListener('submit', handleDelete);
            });
        }

        // Función para manejar la eliminación con AJAX
        function handleDelete(e) {
            e.preventDefault();
            if (!confirm('¿Estás seguro?')) return;

            const form = e.currentTarget;
            const url = form.action;

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    // Actualizar la tabla después de eliminar
                    const searchQuery = document.querySelector('input[name="search"]').value;
                    const url = `${document.getElementById('searchForm').action}?search=${encodeURIComponent(searchQuery)}`;
                    handleTableUpdate(url);
                } else {
                    alert('Error al eliminar el sensor: ' + (data.error || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error al eliminar:', error);
                alert('Error al eliminar el sensor');
            });
        }

        // Inicialización
        document.addEventListener('DOMContentLoaded', () => {
            // Gráfica inicial
            updateChart();

            // Búsqueda
            const searchForm = document.getElementById('searchForm');
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const searchQuery = searchForm.querySelector('input[name="search"]').value;
                const url = `${searchForm.action}?search=${encodeURIComponent(searchQuery)}`;
                handleTableUpdate(url);
            });

            // Eventos iniciales de paginación y eliminación
            attachPaginationEvents();
            attachDeleteEvents();
        });
    </script>
</body>
</html>