<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Dashboard Dual - Prueba</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }

        h1 {
            font-size: 18px;
            color: #83181b;
            text-align: center;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 14px;
            color: #6a1416;
            margin-top: 20px;
            margin-bottom: 10px;
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
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }

        .chart-container {
            margin: 15px 0;
        }

        .chart-img {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: contain;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 10px;
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

        .filters-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 8px;
            border-radius: 3px;
            font-size: 10px;
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
            @php
                // Asegúrate de que $filters exista y tenga valores por defecto
                $filters = $filters ?? [];
                $idState = $filters['id_state'] ?? null;
                $idInstitution = $filters['id_institution'] ?? null;
            @endphp

            @if($idState || $idInstitution)
                <div class="filters-box">
                    <strong>Filtros aplicados</strong><br>
                    @if($idState) Estado ID: {{ $idState }}<br> @endif
                    @if($idInstitution) Institución ID: {{ $idInstitution }} @endif
                </div>
            @else
                <div style="color: #28a745; font-weight: bold;">
                    Vista global completa
                </div>
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

<!-- Gráficas básicas -->
<h2>Proyectos por Mes</h2>
<div class="chart-container">
    @if(isset($charts['projects_by_month']))
        <img src="{{ $charts['projects_by_month'] }}" class="chart-img">
    @else
        <div style="text-align: center; padding: 20px; background-color: #f8f9fa; border: 1px dashed #ccc;">
            No hay datos para esta gráfica
        </div>
    @endif
</div>

@if(!empty($data['countProjectsByMonth']))
    <h2>Detalle por Mes</h2>
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

<h2>Proyectos por Área</h2>
<div class="chart-container">
    @if(isset($charts['projects_by_area']))
        <img src="{{ $charts['projects_by_area'] }}" class="chart-img">
    @else
        <div style="text-align: center; padding: 20px; background-color: #f8f9fa; border: 1px dashed #ccc;">
            No hay datos para esta gráfica
        </div>
    @endif
</div>

<h2>Proyectos por Sector</h2>
<div class="chart-container">
    @if(isset($charts['projects_by_sector']))
        <img src="{{ $charts['projects_by_sector'] }}" class="chart-img">
    @else
        <div style="text-align: center; padding: 20px; background-color: #f8f9fa; border: 1px dashed #ccc;">
            No hay datos para esta gráfica
        </div>
    @endif
</div>

<!-- Información del sistema -->
<div style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9px; color: #666;">
    Reporte generado automáticamente por el Sistema de Dashboard Dual
</div>

</body>
</html>
