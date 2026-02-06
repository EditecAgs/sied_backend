<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dashboard</title>
        <style>
            body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
            h2 { margin-top: 25px; }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }
            td, th {
                border: 1px solid #ccc;
                padding: 6px;
            }
        </style>
    </head>
    <body>

    <h1>Reporte del Dashboard</h1>
    <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    @if($filters['id_state'] || $filters['id_institution'])
        <p><strong>Filtros aplicados:</strong></p>
        <ul>
            @if($filters['id_state'])
                <li>Estado ID: {{ $filters['id_state'] }}</li>
            @endif
            @if($filters['id_institution'])
                <li>Institución ID: {{ $filters['id_institution'] }}</li>
            @endif
        </ul>
    @endif

    <h2>Proyectos finalizados</h2>
    <p>{{ $data['countDualProjectCompleted'] ?? 0 }}</p>

    <h2>Estudiantes registrados</h2>
    <p>{{ $data['countRegisteredStudents'] ?? 0 }}</p>

    <h2>Organizaciones registradas</h2>
    <p>{{ $data['countRegisteredOrganizations'] ?? 0 }}</p>

    <h2>Proyectos por mes</h2>
    <table>
        <tr>
            <th>Mes</th>
            <th>Total</th>
        </tr>
        @if(!empty($data['countProjectsByMonth']))
            @foreach($data['countProjectsByMonth'] as $row)
                <tr>
                    <td>{{ $row['month'] ?? '-' }}</td>
                    <td>{{ $row['total'] ?? 0 }}</td>
                </tr>
            @endforeach
        @endif

    </table>

    </body>
</html>
