<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de SensorData</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --text-color: #ffffff;
            --shadow-color: rgba(0, 0, 0, 0.4);
            --error-color: #e74c3c;
            --success-color: #28a745;
            --temp-color: #ff3333;
            --humidity-color: #3366ff;
            --soil-color: #00cc66;
            --grid-color: rgba(255, 255, 255, 0.3);
            --label-color: #f0f0f0;
        }

        /* Resto de los estilos sin cambios */
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

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 1rem;
            color: var(--text-color);
            text-align: center;
        }

        .alert-danger {
            background: var(--error-color);
        }

        .alert-success {
            background: var(--success-color);
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
            background: rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 15px var(--shadow-color);
            transition: transform 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .graph-container.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            max-width: none;
            margin: 0;
            padding: 2rem;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.5);
            transform: scale(1);
        }

        .graph-container.fullscreen canvas {
            height: 80vh !important;
        }

        .graph-container:hover {
            transform: translateY(-5px);
        }

        .graph-title {
            color: var(--text-color);
            font-size: 1.2rem;
            text-align: center;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 4px var(--shadow-color);
        }

        canvas {
            width: 100% !important;
            height: 350px !important;
            border-radius: 10px;
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

            canvas {
                height: 250px !important;
            }
        }

    </style>
</head>

<body>
    <a href="{{ route('admin.dashboard') }}" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar
    </a>

    <div class="container">
        <h1>Monitor de Datos de Sensores</h1>

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="header-actions">
            <div class="search-form">
            </div>
            <div>
                <a href="#" class="btn btn-primary" id="addDataBtn">
                    <i class="fas fa-plus"></i> Nueva Lectura
                </a>
                <button class="btn btn-primary" id="exportPageExcelBtn">
                    <i class="fas fa-file-excel"></i> Exportar Página
                </button>
                <button class="btn btn-primary" id="exportAllExcelBtn">
                    <i class="fas fa-file-excel"></i> Exportar Todo
                </button>
            </div>
        </div>

        <!-- Formulario para agregar datos (oculto inicialmente) -->
        <form action="{{ route('sensor-data.store') }}" method="POST" class="mb-4" id="addDataForm" style="display: none;">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="temperature" class="form-label" style="color: var(--text-color);">Temperatura (°C)</label>
                    <input type="number" step="0.1" name="temperature" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="humidity" class="form-label" style="color: var(--text-color);">Humedad Aire (%)</label>
                    <input type="number" step="0.1" name="humidity" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="soilMoisture" class="form-label" style="color: var(--text-color);">Humedad Suelo (%)</label>
                    <input type="number" name="soilMoisture" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">
                <i class="fas fa-save"></i> Guardar Datos
            </button>
        </form>

        <div class="table-container" id="sensorDataTableContainer">
            <table id="sensorDataTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Temperatura (°C)</th>
                        <th>Humedad Aire (%)</th>
                        <th>Humedad Suelo (%)</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sensorData as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->temperature }}</td>
                            <td>{{ $data->humidity }}</td>
                            <td>{{ $data->soilMoisture }}</td>
                            <td>{{ $data->timestamp }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No hay datos disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination" id="sensorDataPagination">
            {{ $sensorData->links('pagination::bootstrap-4') }}
            <span>
                Mostrando {{ $sensorData->firstItem() }} a {{ $sensorData->lastItem() }} de {{ $sensorData->total() }} registros
            </span>
        </div>
    </div>

    <div class="graph-container">
        <div class="graph-title">Temperatura (°C) a lo largo del tiempo</div>
        <canvas id="tempChart"></canvas>
    </div>
    <div class="graph-container">
        <div class="graph-title">Humedad del Aire (%) a lo largo del tiempo</div>
        <canvas id="humidityChart"></canvas>
    </div>
    <div class="graph-container">
        <div class="graph-title">Humedad del Suelo (%) a lo largo del tiempo</div>
        <canvas id="soilChart"></canvas>
    </div>

    <script>
    // Configuración inicial de los gráficos
    const tempCtx = document.getElementById('tempChart').getContext('2d');
    const humidityCtx = document.getElementById('humidityChart').getContext('2d');
    const soilCtx = document.getElementById('soilChart').getContext('2d');

    // Configuración común para los gráficos
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { labels: { color: 'var(--label-color)', font: { size: 16 } } },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: { size: 16 },
                bodyFont: { size: 14 },
                padding: 10,
                cornerRadius: 5
            }
        },
        scales: {
            x: {
                ticks: { color: 'var(--label-color)', font: { size: 14 }, maxRotation: 45, minRotation: 45 },
                grid: { color: 'var(--grid-color)', lineWidth: 1.5 }
            },
            y: {
                beginAtZero: true,
                ticks: { color: 'var(--label-color)', font: { size: 14 }, stepSize: 5 },
                grid: { color: 'var(--grid-color)', lineWidth: 1.5 }
            }
        },
        animation: {
            duration: 1000,
            easing: 'easeInOutQuad'
        },
        interaction: {
            mode: 'nearest',
            intersect: false
        }
    };

    const tempChart = new Chart(tempCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Temperatura (°C)',
                data: [],
                borderColor: 'var(--temp-color)',
                backgroundColor: 'rgba(255, 51, 51, 0.3)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: { ...chartOptions, scales: { ...chartOptions.scales } }
    });

    const humidityChart = new Chart(humidityCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Humedad Aire (%)',
                data: [],
                borderColor: 'var(--humidity-color)',
                backgroundColor: 'rgba(51, 102, 255, 0.3)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                ...chartOptions.scales,
                y: { ...chartOptions.scales.y, max: 100, ticks: { stepSize: 10 } }
            }
        }
    });

    const soilChart = new Chart(soilCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Humedad Suelo (%)',
                data: [],
                borderColor: 'var(--soil-color)',
                backgroundColor: 'rgba(0, 204, 102, 0.3)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            ...chartOptions,
            scales: {
                ...chartOptions.scales,
                y: { ...chartOptions.scales.y, max: 100, ticks: { stepSize: 10 } }
            }
        }
    });

    // Variables de control
    let isPaused = false;
    let updateInterval;
    const maxDataPoints = 50;

    // Función para actualizar gráficos
    function updateCharts() {
        if (isPaused) return;

        fetch('{{ route("sensor-data.data") }}')
            .then(response => response.json())
            .then(data => {
                const timestamps = data.slice(-maxDataPoints).map(d => new Date(d.timestamp).toLocaleTimeString());
                const temperatures = data.slice(-maxDataPoints).map(d => d.temperature);
                const humidities = data.slice(-maxDataPoints).map(d => d.humidity);
                const soilMoistures = data.slice(-maxDataPoints).map(d => d.soilMoisture);

                tempChart.data.labels = timestamps;
                tempChart.data.datasets[0].data = temperatures;
                humidityChart.data.labels = timestamps;
                humidityChart.data.datasets[0].data = humidities;
                soilChart.data.labels = timestamps;
                soilChart.data.datasets[0].data = soilMoistures;

                tempChart.update('show');
                humidityChart.update('show');
                soilChart.update('show');
            })
            .catch(error => console.error('Error al obtener datos:', error));
    }

    // Función para exportar página actual
    function exportPageToExcel() {
        const table = document.getElementById('sensorDataTable');
        const rows = table.querySelectorAll('tr');
        const data = [['ID', 'Temperatura (°C)', 'Humedad Aire (%)', 'Humedad Suelo (%)', 'Fecha']];
        
        rows.forEach((row, index) => {
            if (index > 0) { // Saltar encabezado
                const rowData = [];
                row.querySelectorAll('td').forEach(cell => {
                    rowData.push(cell.textContent);
                });
                data.push(rowData);
            }
        });

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(data);
        
        ws['!cols'] = [
            { wch: 10 }, // ID
            { wch: 20 }, // Temperatura (°C)
            { wch: 20 }, // Humedad Aire (%)
            { wch: 20 }, // Humedad Suelo (%)
            { wch: 30 }  // Fecha
        ];

        XLSX.utils.book_append_sheet(wb, ws, "SensorData_Pagina");
        const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
        XLSX.writeFile(wb, `SensorData_Pagina_${timestamp}.xlsx`);
    }

    // Función para exportar todos los datos
    function exportAllToExcel() {
        fetch('{{ route("sensor-data.data") }}')
            .then(response => response.json())
            .then(data => {
                const formattedData = [['ID', 'Temperatura (°C)', 'Humedad Aire (%)', 'Humedad Suelo (%)', 'Fecha']];
                
                data.forEach(item => {
                    formattedData.push([
                        item.id,
                       `${item.temperature} °C`, // Temperatura con unidad
                        `${item.humidity} %`, // Humedad Aire con unidad
                        `${item.soilMoisture} %`, // Humedad Suelo con unidad
                        item.timestamp
                    ]);
                });

                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.aoa_to_sheet(formattedData);
                
                ws['!cols'] = [
                    { wch: 10 }, // ID
                    { wch: 20 }, // Temperatura (°C)
                    { wch: 20 }, // Humedad Aire (%)
                    { wch: 20 }, // Humedad Suelo (%)
                    { wch: 30 }  // Fecha
                ];

                XLSX.utils.book_append_sheet(wb, ws, "SensorData_Todo");
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                XLSX.writeFile(wb, `SensorData_Todo_${timestamp}.xlsx`);
            })
            .catch(error => console.error('Error al exportar todos los datos:', error));
    }

    // Configuración al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        updateCharts();
        updateInterval = setInterval(updateCharts, 5000);

        const addDataBtn = document.getElementById('addDataBtn');
        const addDataForm = document.getElementById('addDataForm');
        addDataBtn.addEventListener('click', (e) => {
            e.preventDefault();
            addDataForm.style.display = addDataForm.style.display === 'none' ? 'block' : 'none';
        });

        // Eventos de exportación
        const exportPageBtn = document.getElementById('exportPageExcelBtn');
        const exportAllBtn = document.getElementById('exportAllExcelBtn');
        
        exportPageBtn.addEventListener('click', (e) => {
            e.preventDefault();
            exportPageToExcel();
        });

        exportAllBtn.addEventListener('click', (e) => {
            e.preventDefault();
            exportAllToExcel();
        });

        // Controles adicionales
        const controlsHTML = `
            <div class="header-actions" style="margin-top: 1rem;">
                <button id="pauseBtn" class="btn btn-primary">Pausar</button>
                <button id="resumeBtn" class="btn btn-primary" style="display: none;">Reanudar</button>
                <button id="refreshBtn" class="btn btn-primary">Actualizar Ahora</button>
            </div>
        `;
        document.querySelector('.container').insertAdjacentHTML('beforeend', controlsHTML);

        const pauseBtn = document.getElementById('pauseBtn');
        const resumeBtn = document.getElementById('resumeBtn');
        const refreshBtn = document.getElementById('refreshBtn');

        pauseBtn.addEventListener('click', () => {
            isPaused = true;
            pauseBtn.style.display = 'none';
            resumeBtn.style.display = 'inline-flex';
            clearInterval(updateInterval);
        });

        resumeBtn.addEventListener('click', () => {
            isPaused = false;
            pauseBtn.style.display = 'inline-flex';
            resumeBtn.style.display = 'none';
            updateInterval = setInterval(updateCharts, 5000);
            updateCharts();
        });

        refreshBtn.addEventListener('click', () => updateCharts());

        const graphContainers = document.querySelectorAll('.graph-container');
        graphContainers.forEach(container => {
            container.addEventListener('mouseenter', () => container.classList.add('fullscreen'));
            container.addEventListener('mouseleave', () => container.classList.remove('fullscreen'));
        });
    });
    </script>
</body>
</html>