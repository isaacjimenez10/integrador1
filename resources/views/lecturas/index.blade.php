<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lista de Lecturas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --text-color: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.4);
            --error-color: #e74c3c;
            --success-color: #28a745;
            --background-light: rgba(255, 255, 255, 0.95);
            --background-glass: rgba(255, 255, 255, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('img/lec.jpg') }}') no-repeat center center/cover;
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
            background: rgba(0, 0, 0, 0.6);
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
            background: var(--background-glass);
            backdrop-filter: blur(15px);
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px var(--shadow-color);
            width: 100%;
            max-width: 1200px;
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
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        h1 {
            color: var(--text-color);
            font-size: 2.5rem;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 2px 2px 8px var(--shadow-color);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            color: var(--text-color);
            text-align: center;
            font-weight: 500;
            animation: slideIn 0.5s ease-out;
        }

        .alert-danger {
            background: var(--error-color);
        }

        .alert-success {
            background: var(--success-color);
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 2rem;
        }

        .search-form {
            flex: 1;
            min-width: 300px;
        }

        .search-form form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-form input {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-form input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
            background: rgba(255, 255, 255, 1);
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

        .btn-primary {
            background: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 2rem;
            border-radius: 15px;
            background: var(--background-light);
            box-shadow: 0 4px 15px var(--shadow-color);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: var(--primary-color);
            color: var(--text-color);
        }

        th, td {
            padding: 15px;
            text-align: center;
            font-size: 0.95rem;
        }

        th {
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr:hover {
            background: rgba(40, 167, 69, 0.1);
            transform: scale(1.01);
        }

        .actions-btns {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-view { background: #3498db; }
        .btn-edit { background: #f39c12; }
        .btn-delete { background: #e74c3c; }

        .btn:hover {
            transform: translateY(-2px);
            opacity: 0.9;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            color: var(--text-color);
            margin-top: 1.5rem;
        }

        .pagination .page-item .page-link {
            background: var(--primary-color);
            padding: 8px 15px;
            border-radius: 20px;
            text-decoration: none;
            color: var(--text-color);
            transition: all 0.3s ease;
        }

        .pagination .page-item.active .page-link {
            background: var(--secondary-color);
            font-weight: 600;
        }

        .pagination .page-item .page-link:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .graph-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: var(--background-glass);
            border-radius: 15px;
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 32px var(--shadow-color);
        }

        #lecturasChart {
            width: 100% !important;
            height: 500px !important;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            h1 {
                font-size: 1.8rem;
            }

            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .search-form form {
                flex-direction: column;
            }

            .search-form input,
            .btn {
                width: 100%;
                justify-content: center;
            }

            th, td {
                padding: 10px;
                font-size: 0.85rem;
            }

            .graph-container {
                max-width: 100%;
                padding: 1rem;
            }

            #lecturasChart {
                height: 300px !important;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('admin.dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <div class="container">
        <h1>Lista de Lecturas</h1>

        <!-- Mostrar mensajes de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header con búsqueda y botones -->
        <div class="header-actions">
            <div class="search-form">
                <form id="searchForm" method="GET" action="{{ route('lecturas.index') }}">
                    <input type="text" name="search" placeholder="Buscar por sensor, valor, unidad o fecha" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('lecturas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Lectura
                </a>
                <a href="{{ route('lecturas.export') }}" class="btn btn-primary">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </a>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-container" id="lecturasTableContainer">
            <table id="lecturasTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sensor</th>
                        <th>Valor</th>
                        <th>Unidad</th>
                        <th>Fecha y Hora</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lecturas as $lectura)
                        <tr>
                            <td>{{ $lectura['id'] ?? 'N/A' }}</td>
                            <td>{{ $lectura['sensor']['nombre'] ?? 'Sensor Desconocido' }}</td>
                            <td>{{ $lectura['valor'] ?? 'N/A' }}</td>
                            <td>{{ $lectura['unidad'] ?? 'N/A' }}</td>
                            <td>{{ isset($lectura['fecha_hora']) ? \Carbon\Carbon::parse($lectura['fecha_hora'])->format('Y-m-d H:i:s') : 'N/A' }}</td>
                            <td class="actions-btns">
                                <a href="{{ route('lecturas.show', $lectura['id'] ?? '') }}" class="btn btn-view">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('lecturas.edit', $lectura['id'] ?? '') }}" class="btn btn-edit">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('lecturas.destroy', $lectura['id'] ?? '') }}" method="POST" class="delete-form" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No hay lecturas disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="pagination" id="lecturasPagination">
            {{ $lecturas->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <div class="graph-container">
        <canvas id="lecturasChart"></canvas>
    </div>

    <script>
        let lecturasChart;
        const ctx = document.getElementById('lecturasChart')?.getContext('2d');

        function updateChart() {
            if (!ctx) {
                console.error('No se encontró el canvas #lecturasChart');
                return;
            }

            const tableRows = document.querySelectorAll('#lecturasTable tbody tr');
            const datasets = {};
            const dates = new Set();

            console.log('Filas de la tabla:', tableRows.length);

            tableRows.forEach((row, index) => {
                const sensor = row.cells[1]?.textContent || 'Desconocido';
                const valueText = row.cells[2]?.textContent || '0';
                const value = parseFloat(valueText);
                const dateText = row.cells[4]?.textContent || '';
                const date = dateText.split(' ')[0] || '';

                console.log(`Fila ${index}: Sensor=${sensor}, Valor=${valueText} (parseado: ${value}), Fecha=${date}`);

                if (!isNaN(value) && date && sensor !== 'Sensor Desconocido') {
                    if (!datasets[sensor]) {
                        datasets[sensor] = {};
                    }
                    if (!datasets[sensor][date]) {
                        datasets[sensor][date] = [];
                    }
                    datasets[sensor][date].push(value);
                    dates.add(date);
                } else {
                    console.warn(`Datos inválidos en fila ${index}: Sensor=${sensor}, Valor=${valueText}, Fecha=${dateText}`);
                }
            });

            const labels = Array.from(dates).sort();
            console.log('Etiquetas (fechas):', labels);
            console.log('Datasets:', datasets);

            const chartDatasets = Object.keys(datasets).map((sensor, index) => {
                const colors = [
                    'rgba(40, 167, 69, 0.8)',  // Verde
                    'rgba(52, 152, 219, 0.8)', // Azul
                    'rgba(243, 156, 18, 0.8)', // Naranja
                    'rgba(231, 76, 60, 0.8)'   // Rojo
                ];
                const color = colors[index % colors.length];

                const data = labels.map(date => {
                    const values = datasets[sensor][date] || [];
                    return values.length ? values.reduce((a, b) => a + b, 0) / values.length : null;
                });

                return {
                    label: sensor,
                    data: data,
                    backgroundColor: color,
                    borderColor: color.replace('0.8', '1'),
                    borderWidth: 1,
                    fill: false,
                    tension: 0.3
                };
            });

            if (lecturasChart) {
                lecturasChart.destroy();
            }

            if (labels.length === 0 || chartDatasets.length === 0) {
                console.warn('No hay datos suficientes para generar el gráfico');
                return;
            }

            lecturasChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: chartDatasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#ffffff',
                                font: { size: 14 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#ffffff',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#ffffff', font: { size: 12 } },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#ffffff', font: { size: 12 } },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateChart();

            const searchForm = document.getElementById('searchForm');
            searchForm?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(searchForm);

                try {
                    const response = await fetch(searchForm.action + '?' + new URLSearchParams(formData), {
                        method: 'GET',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });

                    if (!response.ok) throw new Error('Error en la respuesta del servidor');

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newTableContainer = doc.querySelector('#lecturasTableContainer');
                    const newPagination = doc.querySelector('#lecturasPagination');

                    if (newTableContainer && newPagination) {
                        document.getElementById('lecturasTableContainer').replaceWith(newTableContainer);
                        document.getElementById('lecturasPagination').replaceWith(newPagination);
                        updateChart();
                    } else {
                        console.error('Elementos no encontrados en la respuesta');
                    }
                } catch (error) {
                    console.error('Error al buscar:', error);
                    alert('Ocurrió un error al realizar la búsqueda. Por favor, intenta de nuevo.');
                }
            });

            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (!confirm('¿Estás seguro de eliminar esta lectura?')) return;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: new FormData(form)
                        });

                        if (!response.ok) throw new Error('Error al eliminar');

                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newTableContainer = doc.querySelector('#lecturasTableContainer');
                        const newPagination = doc.querySelector('#lecturasPagination');

                        if (newTableContainer && newPagination) {
                            document.getElementById('lecturasTableContainer').replaceWith(newTableContainer);
                            document.getElementById('lecturasPagination').replaceWith(newPagination);
                            updateChart();
                        }
                    } catch (error) {
                        console.error('Error al eliminar:', error);
                        alert('Ocurrió un error al eliminar la lectura.');
                    }
                });
            });
        });
    </script>
</body>
</html>