<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DashboardChartService
{
    /**
     * Genera una imagen PNG (base64) usando QuickChart
     */
    protected function makeChart(array $config, int $width = 900, int $height = 400): string
    {
        try {
            $response = Http::timeout(30)->post('https://quickchart.io/chart', [
                'format' => 'png',
                'width' => $width,
                'height' => $height,
                'backgroundColor' => 'white',
                'chart' => $config,
            ]);

            if ($response->successful()) {
                return 'data:image/png;base64,' . base64_encode($response->body());
            }

            return $this->emptyChart($width, $height);
        } catch (\Exception $e) {
            return $this->emptyChart($width, $height);
        }
    }

    /**
     * Gráfica vacía cuando no hay datos
     */
    public function emptyChart(int $width = 900, int $height = 400): string
    {
        $config = [
            'type' => 'text',
            'data' => [
                'datasets' => []
            ],
            'options' => [
                'plugins' => [
                    'text' => [
                        'text' => 'No hay datos disponibles',
                        'fontSize' => 20,
                        'color' => '#666666',
                        'x' => 'center',
                        'y' => 'center'
                    ]
                ]
            ]
        ];

        return $this->makeChart($config, $width, $height);
    }

    /**
     * Gráfico de barras genérico
     */
    protected function barChart(string $title, array $labels, array $data,
                                string $color = '#83181b', bool $horizontal = false): string
    {
        if (empty($labels) || empty($data)) {
            return $this->emptyChart();
        }

        return $this->makeChart([
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => $title,
                    'data' => $data,
                    'backgroundColor' => $color,
                    'borderColor' => $color,
                    'borderWidth' => 1
                ]]
            ],
            'options' => array_merge(
                $this->defaultOptions(),
                $horizontal ? ['indexAxis' => 'y'] : []
            )
        ]);
    }

    /* ===========================================================
     |  PROYECTOS POR MES
     |===========================================================*/
    public function projectsByMonth(array $rows): string
    {
        if (empty($rows)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $labels[] = substr($row['month_name'], 0, 3) . ' ' . $row['year'];
            $values[] = (int) $row['project_count'];
        }

        return $this->barChart(
            'Proyectos por mes',
            $labels,
            $values,
            '#83181b'
        );
    }

    /* ===========================================================
     |  ORGANIZACIONES POR ALCANCE
     |===========================================================*/
    public function organizationsByScope(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $item['scope'] ?? 'Sin alcance';
            $values[] = (int) ($item['total'] ?? 0);
        }

        return $this->barChart(
            'Organizaciones por alcance',
            $labels,
            $values,
            '#a34245'
        );
    }

    /* ===========================================================
     |  PROYECTOS POR ÁREA (HORIZONTAL)
     |===========================================================*/
    public function projectsByArea(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart(900, 500);
        }

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $item['area_name'] ?? 'Sin área';
            $values[] = (int) ($item['project_count'] ?? 0);
        }

        return $this->barChart(
            'Proyectos por área',
            $labels,
            $values,
            '#6a1416',
            true
        );
    }

    /* ===========================================================
     |  PROYECTOS POR SECTOR (PIE)
     |===========================================================*/
    public function projectsBySector(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        // Limitar a los primeros 10 sectores para mejor visualización
        $limitedData = array_slice($data, 0, 10);

        foreach ($limitedData as $item) {
            $labels[] = $this->truncateLabel($item['name'] ?? 'Sin sector', 20);
            $values[] = (int) ($item['project_count'] ?? 0);
        }

        // Colores para el gráfico de pastel
        $colors = [
            '#83181b', '#a34245', '#c36b6f', '#e39499', '#d1a17a',
            '#b8ae9d', '#8c8c8c', '#6a1416', '#9d5c5f', '#4a0f11'
        ];

        return $this->makeChart([
            'type' => 'pie',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'data' => $values,
                    'backgroundColor' => array_slice($colors, 0, count($values)),
                    'borderWidth' => 1,
                    'borderColor' => '#ffffff'
                ]]
            ],
            'options' => [
                'responsive' => false,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'right',
                        'labels' => [
                            'font' => ['size' => 9],
                            'padding' => 15
                        ]
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Proyectos por sector (%)',
                        'font' => ['size' => 12]
                    ]
                ]
            ]
        ], 900, 400);
    }

    /* ===========================================================
     |  PROYECTOS POR SECTOR PLAN MÉXICO
     |===========================================================*/
    public function projectsBySectorPlanMexico(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $this->truncateLabel($item['sector_name'] ?? 'Sin sector', 20);
            $values[] = (float) ($item['percentage'] ?? 0);
        }

        return $this->barChart(
            'Proyectos por sector Plan México (%)',
            $labels,
            $values,
            '#4a0f11'
        );
    }

    /* ===========================================================
     |  PROYECTOS POR APOYO ECONÓMICO
     |===========================================================*/
    public function projectsByEconomicSupport(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $this->truncateLabel($item['support_name'] ?? 'Sin apoyo', 15);
            $values[] = (float) ($item['percentage'] ?? 0);
        }

        return $this->barChart(
            'Proyectos por apoyo económico (%)',
            $labels,
            $values,
            '#9d5c5f'
        );
    }

    /* ===========================================================
     |  PROMEDIO DE APOYOS ECONÓMICOS
     |===========================================================*/
    public function averageEconomicSupport(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart(900, 450);
        }

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $this->truncateLabel($item['support_name'] ?? 'Sin apoyo', 15);
            $values[] = (float) ($item['average_amount'] ?? 0);
        }

        return $this->makeChart([
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'label' => 'Monto promedio ($)',
                    'data' => $values,
                    'backgroundColor' => '#6a1416',
                    'borderColor' => '#4a0f11',
                    'borderWidth' => 1
                ]]
            ],
            'options' => $this->defaultOptions('Monto promedio de apoyos económicos')
        ], 900, 450);
    }

    /* ===========================================================
     |  INSTITUCIONES CON MÁS PROYECTOS
     |===========================================================*/
    public function institutionProjects(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        // Limitar a las primeras 10 instituciones
        $limitedData = array_slice($data, 0, 10);

        foreach ($limitedData as $item) {
            $labels[] = $this->truncateLabel($item['institution_name'] ?? 'Sin nombre', 25);
            $values[] = (float) ($item['percentage'] ?? 0);
        }

        return $this->barChart(
            'Instituciones con más proyectos (%)',
            $labels,
            $values,
            '#b8ae9d',
            true
        );
    }

    /* ===========================================================
     |  TIPOS DUAL (DONUT)
     |===========================================================*/
    public function dualTypes(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart(600, 400);
        }

        $labels = [];
        $values = [];

        foreach ($data as $item) {
            $labels[] = $item['dual_type'] ?? 'Sin tipo';
            $values[] = (int) ($item['total'] ?? 0);
        }

        return $this->makeChart([
            'type' => 'doughnut',
            'data' => [
                'labels' => $labels,
                'datasets' => [[
                    'data' => $values,
                    'backgroundColor' => ['#83181b', '#a34245', '#c36b6f', '#e39499'],
                    'borderWidth' => 2,
                    'borderColor' => '#ffffff'
                ]]
            ],
            'options' => [
                'responsive' => false,
                'plugins' => [
                    'legend' => [
                        'display' => true,
                        'position' => 'right',
                        'labels' => [
                            'font' => ['size' => 10]
                        ]
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Proyectos por tipo dual',
                        'font' => ['size' => 12]
                    ]
                ]
            ]
        ], 600, 400);
    }

    /* ===========================================================
     |  ORGANIZACIONES POR CLUSTER
     |===========================================================*/
    public function organizationsByCluster(array $data): string
    {
        if (empty($data['nacionales']) && empty($data['locales'])) {
            return $this->emptyChart();
        }

        $labels = [];
        $nacionalValues = [];
        $localValues = [];

        // Procesar clusters nacionales
        foreach (array_slice($data['nacionales'] ?? [], 0, 5) as $item) {
            $labels[] = $this->truncateLabel($item['cluster_name'] ?? 'Sin nombre', 20) . ' (N)';
            $nacionalValues[] = (float) ($item['percentage'] ?? 0);
        }

        // Procesar clusters locales
        foreach (array_slice($data['locales'] ?? [], 0, 5) as $item) {
            $labels[] = $this->truncateLabel($item['cluster_name'] ?? 'Sin nombre', 20) . ' (L)';
            $localValues[] = (float) ($item['percentage'] ?? 0);
        }

        return $this->makeChart([
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Nacionales',
                        'data' => $nacionalValues,
                        'backgroundColor' => '#83181b',
                        'borderColor' => '#6a1416',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Locales',
                        'data' => $localValues,
                        'backgroundColor' => '#b8ae9d',
                        'borderColor' => '#a0998a',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'options' => $this->defaultOptions('Organizaciones por cluster (%)')
        ]);
    }

    /* ===========================================================
     |  PROYECTOS POR CLUSTER
     |===========================================================*/
    public function projectsByCluster(array $data): string
    {
        if (empty($data['nacionales']) && empty($data['locales'])) {
            return $this->emptyChart();
        }

        $labels = [];
        $nacionalValues = [];
        $localValues = [];

        // Procesar clusters nacionales
        foreach (array_slice($data['nacionales'] ?? [], 0, 5) as $item) {
            $labels[] = $this->truncateLabel($item['cluster_name'] ?? 'Sin nombre', 20) . ' (N)';
            $nacionalValues[] = (int) ($item['project_count'] ?? 0);
        }

        // Procesar clusters locales
        foreach (array_slice($data['locales'] ?? [], 0, 5) as $item) {
            $labels[] = $this->truncateLabel($item['cluster_name'] ?? 'Sin nombre', 20) . ' (L)';
            $localValues[] = (int) ($item['project_count'] ?? 0);
        }

        return $this->makeChart([
            'type' => 'bar',
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Nacionales',
                        'data' => $nacionalValues,
                        'backgroundColor' => '#c36b6f',
                        'borderColor' => '#a34245',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Locales',
                        'data' => $localValues,
                        'backgroundColor' => '#d1a17a',
                        'borderColor' => '#b8ae9d',
                        'borderWidth' => 1
                    ]
                ]
            ],
            'options' => $this->defaultOptions('Proyectos por cluster')
        ]);
    }

    /* ===========================================================
 |  TIPOS DE BENEFICIO (PROMEDIO)
 |===========================================================*/
    public function benefitTypes(array $data): string
    {
        if (empty($data)) {
            return $this->emptyChart();
        }

        $labels = [];
        $values = [];

        foreach (array_slice($data, 0, 10) as $item) {

            // 🛡️ Blindaje contra stdClass
            if (is_object($item)) {
                $item = (array) $item;
            }

            $labels[] = $this->truncateLabel(
                $item['benefit_type_name'] ?? 'Sin beneficio',
                20
            );

            $values[] = (float) ($item['avg_quantity'] ?? 0);
        }

        if (empty($labels) || empty($values)) {
            return $this->emptyChart();
        }

        return $this->barChart(
            'Promedio por tipo de beneficio',
            $labels,
            $values,
            '#e39499'
        );
    }


    /* ===========================================================
     |  OPCIONES BASE
     |===========================================================*/
    protected function defaultOptions(string $title = ''): array
    {
        $options = [
            'responsive' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'labels' => [
                        'font' => ['size' => 10],
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'font' => ['size' => 9],
                        'autoSkip' => false,
                        'maxRotation' => 45,
                        'minRotation' => 45,
                    ],
                ],
                'y' => [
                    'ticks' => [
                        'font' => ['size' => 9],
                        'precision' => 0,
                        'callback' => $title === 'Organizaciones por cluster (%)' ||
                        strpos($title, '%') !== false ?
                            'function(value) { return value + "%"; }' :
                            'function(value) { return value; }'
                    ],
                ],
            ],
        ];

        if (!empty($title)) {
            $options['plugins']['title'] = [
                'display' => true,
                'text' => $title,
                'font' => ['size' => 12, 'weight' => 'bold']
            ];
        }

        return $options;
    }

    /**
     * Truncar etiquetas largas
     */
    protected function truncateLabel(string $label, int $length): string
    {
        if (strlen($label) <= $length) {
            return $label;
        }
        return substr($label, 0, $length) . '...';
    }
}
