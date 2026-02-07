<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Dashboard Dual</title>
    <style>
        @page {
            margin: 15px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            font-size: 16px;
            color: #83181b;
            text-align: center;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 2px solid #83181b;
        }

        h2 {
            font-size: 11px;
            color: #6a1416;
            background-color: #f8f9fa;
            padding: 4px 8px;
            margin-top: 12px;
            margin-bottom: 6px;
            border-left: 3px solid #83181b;
            page-break-inside: avoid;
        }

        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin-bottom: 12px;
        }

        .stat-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: 1px solid #dee2e6;
            border-radius: 3px;
            padding: 6px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #83181b;
            margin: 2px 0;
        }

        .stat-label {
            font-size: 8px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .chart-container {
            margin: 10px 0;
            page-break-inside: avoid;
        }

        .chart-img {
            width: 100%;
            height: auto;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #eee;
            border-radius: 3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
            font-size: 8px;
            page-break-inside: avoid;
        }

        th {
            background-color: #83181b;
            color: white;
            font-weight: bold;
            padding: 4px 5px;
            text-align: left;
            font-size: 8px;
        }

        td {
            border: 1px solid #dee2e6;
            padding: 3px 5px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .page-break {
            page-break-before: always;
        }

        .section-title {
            font-size: 12px;
            color: #83181b;
            font-weight: bold;
            margin-top: 15px;
            padding-bottom: 4px;
            border-bottom: 1px solid #ddd;
            text-transform: uppercase;
        }

        .two-columns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 8px 0;
        }

        .column {
            page-break-inside: avoid;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7px;
            color: #6c757d;
            padding: 2px 0;
            border-top: 1px solid #dee2e6;
            background-color: white;
        }

        .no-data {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px dashed #adb5bd;
            border-radius: 3px;
            color: #6c757d;
            font-style: italic;
            margin: 8px 0;
            font-size: 9px;
        }

        .filters-box {
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 3px;
            padding: 6px 8px;
            margin-bottom: 10px;
            font-size: 9px;
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
                    @if($idState) • Estado ID: {{ $idState }}<br> @endif
                    @if($idInstitution) • Institución ID: {{ $idInstitution }} @endif
                </div>
            @else
                <div style="color: #28a745; font-weight: bold;">
                    • Vista global completa
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Estadísticas principales -->
<div class="section-title">RESUMEN EJECUTIVO</div>
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

@isset($charts['dual_types'])
    <h2>1.4 Proyectos por Tipo Dual</h2>
    <div class="chart-container">
        <img src="{{ $charts['dual_types'] }}" class="chart-img">
    </div>
@endisset

@isset($charts['projects_by_sector_plan_mexico'])
    <h2>1.5 Proyectos al Plan México</h2>
    <div class="chart-container">
        <img src="{{ $charts['projects_by_sector_plan_mexico'] }}" class="chart-img">
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

    <div class="two-columns">
        @isset($charts['projects_by_economic_support'])
            <div class="column">
                <h2>3.1 Distribución por Tipo</h2>
                <div class="chart-container">
                    <img src="{{ $charts['projects_by_economic_support'] }}" class="chart-img">
                </div>
            </div>
        @endisset

        @isset($charts['economic_support_avg'])
            <div class="column">
                <h2>3.2 Monto Promedio</h2>
                <div class="chart-container">
                    <img src="{{ $charts['economic_support_avg'] }}" class="chart-img">
                </div>
            </div>
        @endisset
    </div>

    @if(!empty($data['averageAmountByEconomicSupport']))
        <h2>Detalle de Apoyos Económicos</h2>
        <table>
            <thead>
            <tr>
                <th>Tipo de Apoyo</th>
                <th>Proyectos</th>
                <th>Participación</th>
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
@endif

<!-- SECCIÓN 4: INSTITUCIONES -->
@isset($charts['institution_projects'])
    <div class="page-break"></div>
    <div class="section-title">4. PARTICIPACIÓN INSTITUCIONAL</div>

    <h2>4.1 Instituciones con Mayor Participación</h2>
    <div class="chart-container">
        <img src="{{ $charts['institution_projects'] }}" class="chart-img">
    </div>

    @if(!empty($data['getInstitutionProjectPercentage']))
        <h2>Ranking de Instituciones (Top 10)</h2>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Institución</th>
                <th>Proyectos</th>
                <th>Participación</th>
            </tr>
            </thead>
            <tbody>
            @foreach(array_slice($data['getInstitutionProjectPercentage'] ?? [], 0, 10) as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item['institution_name'] ?? 'Sin nombre' }}</td>
                    <td style="text-align: center;">{{ $item['project_count'] ?? 0 }}</td>
                    <td style="text-align: center; color: #83181b; font-weight: bold;">{{ number_format($item['percentage'] ?? 0, 2) }}%</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
@endisset

<!-- SECCIÓN 5: BENEFICIOS Y CLUSTERS -->
@if(isset($charts['benefit_types']) || isset($charts['projects_by_cluster']))
    <div class="section-title">5. BENEFICIOS Y CLUSTERS</div>

    @isset($charts['benefit_types'])
        <h2>5.1 Promedio por Tipo de Beneficio</h2>
        <div class="chart-container">
            <img src="{{ $charts['benefit_types'] }}" class="chart-img">
        </div>

        @if(!empty($data['statsByBenefitType']))
            <h2>Detalle de Beneficios (Top 10)</h2>
            <table>
                <thead>
                <tr>
                    <th>Tipo de Beneficio</th>
                    <th>Proyectos</th>
                    <th>Promedio</th>
                </tr>
                </thead>
                <tbody>
                @foreach(array_slice($data['statsByBenefitType'] ?? [], 0, 10) as $item)

                    @php
                        if (is_object($item)) {
                            $item = (array) $item;
                        }
                    @endphp

                    <tr>
                        <td>{{ $item['benefit_type_name'] ?? 'Sin nombre' }}</td>
                        <td style="text-align: center;">{{ $item['project_count'] ?? 0 }}</td>
                        <td style="text-align: center;">{{ number_format($item['avg_quantity'] ?? 0, 2) }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif
    @endisset

    @isset($charts['projects_by_cluster'])
        <h2>5.2 Proyectos por Cluster</h2>
        <div class="chart-container">
            <img src="{{ $charts['projects_by_cluster'] }}" class="chart-img">
        </div>
    @endisset
@endif

<!-- Pie de página -->
<div class="footer">
    Sistema Integral de Educación Dual • Reporte generado automáticamente • {{ $current_date->format('d/m/Y H:i') }}
</div>

</body>
</html>
