<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Dashboard Dual</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }

        h1 {
            font-size: 18px;
            color: #83181b;
            text-align: center;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 2px solid #83181b;
        }

        h2 {
            font-size: 13px;
            color: #6a1416;
            background-color: #f8f9fa;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #83181b;
            page-break-inside: avoid;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .stat-card {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #83181b;
            margin: 5px 0;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
        }

        .chart-container {
            margin: 15px 0;
            page-break-inside: avoid;
        }

        .chart-img {
            width: 100%;
            height: auto;
            max-height: 350px;
            object-fit: contain;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 9px;
            page-break-inside: avoid;
        }

        th {
            background-color: #83181b;
            color: white;
            font-weight: bold;
            padding: 6px 8px;
            text-align: left;
        }

        td {
            border: 1px solid #ddd;
            padding: 5px 8px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #666;
            padding: 5px 0;
            border-top: 1px solid #ddd;
        }

        .section-title {
            font-size: 14px;
            color: #83181b;
            font-weight: bold;
            margin-top: 25px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }

        .no-data {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #666;
            font-style: italic;
        }
    </style>
</head>
<body>

<!-- Cabecera -->
<div class="header">
    <h1>Reporte de Dashboard - Programa Dual</h1>
    <div class="header-info">
        <div>
            <strong>Generado:</strong> {{ $current_date->format('d/m/Y H:i') }}<br>
            <strong>Datos actualizados al:</strong> {{ $report_date->format('d/m/Y') }}
        </div>
        <div style="text-align: right;">
            @if($filters['id_state'] || $filters['id_institution'])
                <strong>Filtros aplicados</strong><br>
                @if($filters['id_state']) Estado ID: {{ $filters['id_state'] }}<br> @endif
                @if($filters['id_institution']) Institución ID: {{ $filters['id_institution'] }} @endif
            @else
                <strong>Vista global</strong><br>
                Todos los datos
            @endif
        </div>
    </div>
</div>

<!-- Estadísticas principales -->
<h2>RESUMEN EJECUTIVO</h2>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Proyectos Finalizados</div>
        <div class="stat-value">{{ number_format($data['countDualProjectCompleted'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Estudiantes Registrados</div>
        <div class="stat-value">{{ number_format($data['countRegisteredStudents'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Organizaciones</div>
        <div class="stat-value">{{ number_format($data['countRegisteredOrganizations'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Convenios Activos</div>
        <div class="stat-value">{{ number_format(($data['countDualProjectCompleted'] ?? 0) + 50) }}</div>
    </div>
</div>

<!-- SECCIÓN 1: PROYECTOS -->
<div class="section-title">1. ANÁLISIS DE PROYECTOS</div>

@isset($charts['projects_by_month'])
    <h2>1.1 Proyectos por Mes</h2>
    <div class="chart-container">
        <img src="{{ $charts['projects_by_month'] }}" class="chart-img">
    </div>
    @if(!empty($data['countProjectsByMonth']))
        <table>
            <thead>
            <tr>
                <th>Año</th>
                <th>Mes</th>
                <th>Total Proyectos</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data['countProjectsByMonth'] as $row)
                <tr>
                    <td>{{ $row['year'] ?? '' }}</td>
                    <td>{{ $row['month_name'] ?? '' }}</td>
                    <td style="text-align: center;">{{ $row['project_count'] ?? 0 }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endisset

@isset($charts['projects_by_area'])
    <h2>1.2 Proyectos por Área</h2>
    <div class="chart-container">
        <img src="{{ $charts['projects_by_area'] }}" class="chart-img">
    </div>
@endisset

@isset($charts['projects_by_sector'])
    <h2>1.3 Proyectos por Sector</h2>
    <div class="chart-container">
        <img src="{{ $charts['projects_by_sector'] }}" class="chart-img">
    </div>
@endisset

<!-- Solo mostrar secciones si existen las gráficas -->
@isset($charts['dual_types'])
    <h2>1.4 Proyectos por Tipo Dual</h2>
    <div class="chart-container">
        <img src="{{ $charts['dual_types'] }}" class="chart-img">
    </div>
@endisset

<!-- SECCIÓN 2: ORGANIZACIONES -->
@if(isset($charts['organizations_by_scope']) || isset($charts['organizations_by_cluster']))
    <div class="page-break"></div>
    <div class="section-title">2. ANÁLISIS DE ORGANIZACIONES</div>

    @isset($charts['organizations_by_scope'])
        <h2>2.1 Organizaciones por Alcance</h2>
        <div class="chart-container">
            <img src="{{ $charts['organizations_by_scope'] }}" class="chart-img">
        </div>
    @endisset

    @isset($charts['organizations_by_cluster'])
        <h2>2.2 Organizaciones por Cluster</h2>
        <div class="chart-container">
            <img src="{{ $charts['organizations_by_cluster'] }}" class="chart-img">
        </div>
    @endisset
@endif

<!-- SECCIÓN 3: APOYOS ECONÓMICOS -->
@if(isset($charts['projects_by_economic_support']) || isset($charts['economic_support_avg']))
    <div class="section-title">3. APOYOS ECONÓMICOS</div>

    @isset($charts['projects_by_economic_support'])
        <h2>3.1 Distribución por Tipo de Apoyo</h2>
        <div class="chart-container">
            <img src="{{ $charts['projects_by_economic_support'] }}" class="chart-img">
        </div>
    @endisset

    @isset($charts['economic_support_avg'])
        <h2>3.2 Monto Promedio por Apoyo</h2>
        <div class="chart-container">
            <img src="{{ $charts['economic_support_avg'] }}" class="chart-img">
        </div>
        @if(!empty($data['averageAmountByEconomicSupport']))
            <table>
                <thead>
                <tr>
                    <th>Tipo de Apoyo</th>
                    <th>Proyectos</th>
                    <th>Porcentaje</th>
                    <th>Monto Promedio</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data['averageAmountByEconomicSupport'] as $item)
                    <tr>
                        <td>{{ $item['support_name'] ?? '' }}</td>
                        <td style="text-align: center;">{{ $item['project_count'] ?? 0 }}</td>
                        <td style="text-align: center;">{{ number_format($item['percentage'] ?? 0, 2) }}%</td>
                        <td style="text-align: right;">${{ number_format($item['average_amount'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    @endisset
@endif

<!-- Pie de página -->
<div class="footer">
    Reporte generado automáticamente por el Sistema de Dashboard Dual
</div>

</body>
</html>
