<?php

namespace App\Http\Controllers;

use App\Services\DashboardChartService;
use App\Services\DashboardService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class DashboardExportController extends Controller
{
    public function export(Request $request)
    {
        try {
            $idState = $request->query('id_state');
            $idInstitution = $request->query('id_institution');

            /* =====================================================
             |  CACHE SEGURO
             |=====================================================*/
            $cache = Cache::get('dashboard_cache');

            if (!$cache || !isset($cache['global'])) {
                Log::info('Generando métricas del dashboard (sin cache)');

                $dashboardService = app(DashboardService::class);
                $globalData = $dashboardService->getAllMetrics(
                    $idState,
                    $idInstitution
                );

                $cache = [
                    'global' => $this->normalize($globalData),
                ];

                Cache::put('dashboard_cache', $cache, 300);
                Cache::put('dashboard_cache_updated_at', now(), 300);
            }

            $data = $cache['global'] ?? [];

            /* =====================================================
             |  MAPA DE GRÁFICAS
             |=====================================================*/
            $chartMap = [
                'projects_by_month' => ['method' => 'projectsByMonth', 'data' => 'countProjectsByMonth'],
                'projects_by_area' => ['method' => 'projectsByArea', 'data' => 'countProjectsByArea'],
                'projects_by_sector' => ['method' => 'projectsBySector', 'data' => 'countProjectsBySector'],
                'dual_types' => ['method' => 'dualTypes', 'data' => 'countProjectsByDualType'],
                'organizations_by_scope' => ['method' => 'organizationsByScope', 'data' => 'countOrganizationsByScope'],
                'projects_by_economic_support' => ['method' => 'projectsByEconomicSupport', 'data' => 'countProjectsByEconomicSupport'],
                'economic_support_avg' => ['method' => 'averageEconomicSupport', 'data' => 'averageAmountByEconomicSupport'],
                'projects_by_sector_plan_mexico' => ['method' => 'projectsBySectorPlanMexico', 'data' => 'countProjectsBySectorPlanMexico'],
                'institution_projects' => ['method' => 'institutionProjects', 'data' => 'getInstitutionProjectPercentage'],
                'organizations_by_cluster' => ['method' => 'organizationsByCluster', 'data' => 'countOrganizationsByCluster'],
                'projects_by_cluster' => ['method' => 'projectsByCluster', 'data' => 'countProjectsByCluster'],
                'benefit_types' => ['method' => 'benefitTypes', 'data' => 'statsByBenefitType'],
            ];

            $chartService = app(DashboardChartService::class);

            /* =====================================================
             |  ASEGURAR CLAVES
             |=====================================================*/
            foreach ($chartMap as $config) {
                if (!array_key_exists($config['data'], $data)) {
                    $data[$config['data']] = [];
                }
            }

            /* =====================================================
             |  GENERAR GRÁFICAS
             |=====================================================*/
            $charts = [];

            foreach ($chartMap as $key => $config) {
                try {
                    $value = $data[$config['data']];

                    if ($value instanceof Collection) {
                        $value = $value->toArray();
                    }

                    if (!is_array($value) || empty($value)) {
                        Log::warning("Gráfica vacía", [
                            'chart' => $key,
                            'data_key' => $config['data']
                        ]);
                        $charts[$key] = $chartService->emptyChart();
                        continue;
                    }

                    $charts[$key] = $chartService->{$config['method']}($value);
                    Log::info("Gráfica generada correctamente", ['chart' => $key]);

                } catch (\Throwable $e) {
                    Log::error("Error generando gráfica {$key}", [
                        'message' => $e->getMessage()
                    ]);
                    $charts[$key] = $chartService->emptyChart();
                }
            }

            /* =====================================================
             |  PDF
             |=====================================================*/
            $pdf = Pdf::loadView('pdf.dashboard_extended', [
                'data' => $data,
                'charts' => $charts,
                'filters' => [
                    'id_state' => $idState,
                    'id_institution' => $idInstitution,
                ],
                'report_date' => Cache::get('dashboard_cache_updated_at', now()),
                'current_date' => now(),
            ])
                ->setPaper('a4', 'portrait')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isRemoteEnabled', true)
                ->setOption('defaultFont', 'sans-serif');

            return $pdf->download(
                'reporte_dashboard_' . now()->format('Y-m-d_H-i') . '.pdf'
            );

        } catch (\Throwable $e) {
            Log::critical('Error crítico generando PDF', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Error generando el reporte'
            ], 500);
        }
    }

    /* ==========================================================
     |  NORMALIZAR DATA (Collection → array)
     |==========================================================*/
    private function normalize($data): array
    {
        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        if (!is_array($data)) {
            return [];
        }

        foreach ($data as $key => $value) {

            // Collection → array
            if ($value instanceof Collection) {
                $data[$key] = $value->toArray();
                continue;
            }

            // Array de stdClass → array puro
            if (is_array($value) && isset($value[0]) && is_object($value[0])) {
                $data[$key] = array_map(function ($item) {
                    return (array) $item;
                }, $value);
            }
        }

        return $data;
    }
    /**
     * Método para pruebas: genera PDF simple
     */
    public function exportSimple(Request $request)
    {
        try {
            // Datos de prueba
            $data = [
                'countDualProjectCompleted' => 150,
                'countRegisteredStudents' => 1200,
                'countRegisteredOrganizations' => 85,
                'countProjectsByMonth' => [
                    ['month_name' => 'Enero', 'year' => 2024, 'project_count' => 12],
                    ['month_name' => 'Febrero', 'year' => 2024, 'project_count' => 18],
                    ['month_name' => 'Marzo', 'year' => 2024, 'project_count' => 15],
                ],
                'countProjectsByArea' => [
                    ['area_name' => 'Tecnología', 'project_count' => 45],
                    ['area_name' => 'Administración', 'project_count' => 32],
                    ['area_name' => 'Ingeniería', 'project_count' => 28],
                ],
                'countProjectsBySector' => [
                    ['name' => 'Industrial', 'project_count' => 60],
                    ['name' => 'Servicios', 'project_count' => 45],
                    ['name' => 'Comercio', 'project_count' => 30],
                ]
            ];

            $chartService = new DashboardChartService();

            $pdf = Pdf::loadView('pdf.dashboard_simple', [
                'data' => $data,
                'charts' => [
                    'projects_by_month' => $chartService->projectsByMonth($data['countProjectsByMonth']),
                    'projects_by_area' => $chartService->projectsByArea($data['countProjectsByArea']),
                    'projects_by_sector' => $chartService->projectsBySector($data['countProjectsBySector']),
                ],
                'filters' => [ // Asegúrate de pasar el array filters
                    'id_state' => $request->query('id_state'),
                    'id_institution' => $request->query('id_institution')
                ],
                'report_date' => now(),
                'current_date' => now()
            ]);

            return $pdf->download('dashboard_prueba.pdf');

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
