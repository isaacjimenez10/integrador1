<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Configuraciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --text-color: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.4);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('/img/n.jpg') no-repeat center center/cover;
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

        .search-form {
            flex: 1;
            min-width: 250px;
        }

        .search-form form {
            display: flex;
            gap: 10px;
        }

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
            background: rgba(255, 255, 255, 0.95);
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
        }

        tbody tr {
            transition: all 0.3s ease;
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
            gap: 15px;
            flex-wrap: wrap;
            color: var(--text-color);
        }

        .pagination .page-link {
            background: var(--primary-color);
            padding: 8px 15px;
            border-radius: 20px;
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

        #configChart {
            width: 100% !important;
            height: 500px !important;
        }

        @media (max-width: 768px) {
            .header-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .search-form form {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .graph-container {
                max-width: 100%;
                padding: 1rem;
            }

            #configChart {
                height: 300px !important;
            }
        }
    </style>
</head>
<body>
    <a href="{{ url('admin/dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <div class="container">
        <h1>Lista de Configuraciones</h1>

        <div class="header-actions">
            <div class="search-form">
                <form method="GET" action="{{ route('configuracion.index') }}" id="searchForm">
                    <input type="text" name="search" placeholder="Buscar por sensor" value="{{ request()->input('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ url('configuracion/create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Configuración
                </a>
                <button id="exportExcel" class="btn btn-primary">
                    <i class="fas fa-file-excel"></i> Exportar Excel
                </button>
            </div>
        </div>

        <div class="table-container">
            <table id="configTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sensor</th>
                        <th>Mínimo</th>
                        <th>Máximo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($configuraciones as $configuracion)
    <tr>
        <td>{{ $configuracion['id'] }}</td>
        <td>{{ $configuracion['sensor']['nombre'] ?? 'Sin sensor' }}</td>
        <td>{{ $configuracion['minimo'] ?? 'N/A' }}</td>
        <td>{{ $configuracion['maximo'] ?? 'N/A' }}</td>
        <td class="actions-btns">
            <a href="{{ route('configuracion.show', $configuracion['id']) }}" class="btn btn-view">
                <i class="fas fa-eye"></i> Ver
            </a>
            <a href="{{ route('configuracion.edit', $configuracion['id']) }}" class="btn btn-edit">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="{{ route('configuracion.destroy', $configuracion['id']) }}" method="POST" class="delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-delete" onclick="return confirm('¿Estás seguro?')">
                    <i class="fas fa-trash"></i> Eliminar
                </button>
            </form>
        </td>
    </tr>
@endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $configuraciones->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <div class="graph-container">
        <canvas id="configChart"></canvas>
    </div>

    <script>
        let configChart;
        const ctx = document.getElementById('configChart').getContext('2d');

        function updateChart() {
            const tableRows = document.querySelectorAll('#configTable tbody tr');
            const labels = [];
            const minValues = [];
            const maxValues = [];

            tableRows.forEach(row => {
                const sensor = row.cells[1].textContent;
                const min = parseFloat(row.cells[2].textContent);
                const max = parseFloat(row.cells[3].textContent);
                
                labels.push(sensor);
                minValues.push(min);
                maxValues.push(max);
            });

            if (configChart) {
                configChart.destroy();
            }

            configChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Valor Mínimo',
                            data: minValues,
                            backgroundColor: 'rgba(40, 167, 69, 0.8)'
                        },
                        {
                            label: 'Valor Máximo',
                            data: maxValues,
                            backgroundColor: 'rgba(52, 152, 219, 0.8)'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateChart();
            document.getElementById("exportExcel").addEventListener("click", () => {
                const table = document.getElementById("configTable");
                if (table) {
                    const ws = XLSX.utils.table_to_sheet(table);
                    const wb = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(wb, ws, "Configuraciones");
                    XLSX.writeFile(wb, "configuraciones.xlsx");
                }
            });

            document.getElementById('searchForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(this.action + '?' + new URLSearchParams(formData), {
                    method: 'GET',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    document.getElementById('configTable').innerHTML = doc.querySelector('#configTable').innerHTML;
                    document.querySelector('.pagination').innerHTML = doc.querySelector('.pagination').innerHTML;
                    updateChart();
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>
</body>
</html>
