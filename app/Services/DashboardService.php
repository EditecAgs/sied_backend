<?php

namespace App\Services;

use App\Models\DualArea;
use App\Models\DualType;
use App\Models\DualProject;
use App\Models\EconomicSupport;
use App\Models\Institution;
use App\Models\Organization;
use App\Models\Sector;
use App\Models\Student;
use App\Models\Cluster;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getAllMetrics($idState = null, $idInstitution = null)
    {
        return [
            'countDualProjectCompleted' => $this->countDualProjectCompleted($idState, $idInstitution),
            'countRegisteredStudents' => $this->countRegisteredStudents($idState, $idInstitution),
            'countRegisteredOrganizations' => $this->countRegisteredOrganizations($idState, $idInstitution),
            'countOrganizationsByScope' => $this->countOrganizationsByScope($idState, $idInstitution),
            'countProjectsByMonth' => $this->countProjectsByMonth($idState, $idInstitution),
            'countProjectsByArea' => $this->countProjectsByArea($idState, $idInstitution),
            'countProjectsBySector' => $this->countProjectsBySector($idState, $idInstitution),
            'countProjectsBySectorPlanMexico' => $this->countProjectsBySectorPlanMexico($idState, $idInstitution),
            'countProjectsByEconomicSupport' => $this->countProjectsByEconomicSupport($idState, $idInstitution),
            'averageAmountByEconomicSupport' => $this->averageAmountByEconomicSupport($idState, $idInstitution),
            'getInstitutionProjectPercentage' => $this->getInstitutionProjectPercentage($idState, $idInstitution),
            'countProjectsByDualType' => $this->countProjectsByDualType($idState, $idInstitution),
            'countOrganizationsByCluster' => $this->countOrganizationsByCluster($idState, $idInstitution),
            'countProjectsByCluster' => $this->countProjectsByCluster($idState, $idInstitution),
        ];
    }

    private function applySimpleFilters($query, $idState, $idInstitution)
    {
        if (!empty($idInstitution)) {
            $query->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $query->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        return $query;
    }

    public function countDualProjectCompleted($idState = null, $idInstitution = null)
    {
        $q = DualProject::query()->where('has_report', 1);
        $this->applySimpleFilters($q, $idState, $idInstitution);
        return $q->count();
    }

    public function countRegisteredStudents($idState = null, $idInstitution = null)
    {
        $q = Student::query();
        if (!empty($idInstitution)) {
            $q->where('id_institution', $idInstitution);
        }
        if (!empty($idState)) {
            $q->whereHas('institution', function ($qq) use ($idState) {
                $qq->where('id_state', $idState);
            });
        }
        return $q->count();
    }

    public function countRegisteredOrganizations($idState = null, $idInstitution = null)
    {
        $q = Organization::query();

        if (!empty($idInstitution)) {
            $q->whereHas('organizationDualProjects.dualProject', function ($sub) use ($idInstitution) {
                $sub->where('id_institution', $idInstitution)->where('has_report', 1);
            });
        }

        if (!empty($idState)) {
            $q->whereHas('organizationDualProjects.dualProject', function ($sub) use ($idState) {
                $sub->whereHas('institution', function ($ii) use ($idState) {
                    $ii->where('id_state', $idState);
                })->where('has_report', 1);
            });
        }

        return $q->count();
    }

    public function countOrganizationsByScope($idState = null, $idInstitution = null)
    {
        $q = Organization::query();

        if (!empty($idInstitution)) {
            $q->whereHas('organizationDualProjects.dualProject', function ($sub) use ($idInstitution) {
                $sub->where('id_institution', $idInstitution)->where('has_report', 1);
            });
        }

        if (!empty($idState)) {
            $q->whereHas('organizationDualProjects.dualProject.institution', function ($sub) use ($idState) {
                $sub->where('id_state', $idState);
            });
        }

        $counts = $q->select('scope', DB::raw('COUNT(*) as total'))->groupBy('scope')->get();
        return $counts->toArray();
    }

    public function countProjectsByMonth($idState = null, $idInstitution = null)
    {
        $q = DualProject::query()->where('has_report', 1);
        $this->applySimpleFilters($q, $idState, $idInstitution);

        $results = $q->join('dual_project_reports', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id')
            ->select(
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
                DB::raw("DATE_FORMAT(dual_project_reports.period_start, '%Y-%m') as month_year"),
                DB::raw('MONTH(dual_project_reports.period_start) as month_number'),
                DB::raw('MONTHNAME(dual_project_reports.period_start) as month_name'),
                DB::raw('YEAR(dual_project_reports.period_start) as year')
            )
            ->groupBy('year', 'month_number', 'month_year', 'month_name')
            ->orderBy('year')
            ->orderBy('month_number')
            ->get();

        return $results->toArray();
    }

    public function countProjectsByArea($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todas las áreas primero
        $areas = DualArea::all();

        // Construir consulta para contar proyectos con filtros
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Para cada área, contar proyectos filtrados
        $results = $areas->map(function ($area) use ($filteredProjectIds) {
            $projectCount = DB::table('dual_project_reports')
                ->where('id_dual_area', $area->id)
                ->whereIn('dual_project_id', $filteredProjectIds)
                ->count(DB::raw('DISTINCT dual_project_id'));

            return [
                'id' => $area->id,
                'area_name' => $area->name,
                'project_count' => $projectCount
            ];
        })->sortByDesc('project_count')->values();

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 10;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function countProjectsBySector($idState = null, $idInstitution = null, $perPage = 30, $page = 1)
    {
        // Obtener todos los sectores
        $sectors = Sector::all();

        // Construir consulta para contar proyectos con filtros
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Total de proyectos filtrados para calcular porcentajes
        $totalFilteredProjects = count($filteredProjectIds);

        // Para cada sector, contar proyectos filtrados
        $results = $sectors->map(function ($sector) use ($filteredProjectIds, $totalFilteredProjects) {
            $projectCount = DB::table('organizations')
                ->join('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
                ->where('organizations.id_sector', $sector->id)
                ->whereIn('organizations_dual_projects.id_dual_project', $filteredProjectIds)
                ->count(DB::raw('DISTINCT organizations_dual_projects.id_dual_project'));

            $percentage = $totalFilteredProjects > 0 ? round(($projectCount * 100) / $totalFilteredProjects, 2) : 0;

            return [
                'id' => $sector->id,
                'name' => $sector->name,
                'sector_name' => $sector->name,
                'project_count' => $projectCount,
                'percentage' => $percentage
            ];
        })->sortByDesc('project_count')->values();

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 30;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function countProjectsBySectorPlanMexico($idState = null, $idInstitution = null, $perPage = 11, $page = 1)
    {
        // Obtener todos los sectores con plan_mexico = 1
        $sectors = Sector::where('plan_mexico', 1)->get();

        // Construir consulta para contar proyectos con filtros
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Total de proyectos filtrados para calcular porcentajes
        $totalFilteredProjects = count($filteredProjectIds);

        // Para cada sector, contar proyectos filtrados
        $results = $sectors->map(function ($sector) use ($filteredProjectIds, $totalFilteredProjects) {
            $projectCount = DB::table('organizations')
                ->join('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
                ->where('organizations.id_sector', $sector->id)
                ->whereIn('organizations_dual_projects.id_dual_project', $filteredProjectIds)
                ->count(DB::raw('DISTINCT organizations_dual_projects.id_dual_project'));

            $percentage = $totalFilteredProjects > 0 ? round(($projectCount * 100) / $totalFilteredProjects, 2) : 0;

            return [
                'id' => $sector->id,
                'sector_name' => $sector->name,
                'plan_mexico' => $sector->plan_mexico,
                'project_count' => $projectCount,
                'percentage' => $percentage
            ];
        })->sortByDesc('project_count')->values();

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 11;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function countProjectsByEconomicSupport($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todos los apoyos económicos
        $economicSupports = EconomicSupport::all();

        // Construir consulta para contar proyectos con filtros
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Total de proyectos filtrados para calcular porcentajes
        $totalFilteredProjects = count($filteredProjectIds);

        // Para cada apoyo económico, contar proyectos filtrados
        $results = $economicSupports->map(function ($support) use ($filteredProjectIds, $totalFilteredProjects) {
            $projectCount = DB::table('dual_project_reports')
                ->where('economic_support', $support->id)
                ->whereIn('dual_project_id', $filteredProjectIds)
                ->count(DB::raw('DISTINCT dual_project_id'));

            $percentage = $totalFilteredProjects > 0 ? round(($projectCount * 100) / $totalFilteredProjects, 2) : 0;

            return [
                'id' => $support->id,
                'support_name' => $support->name,
                'project_count' => $projectCount,
                'percentage' => $percentage
            ];
        })->sortByDesc('project_count')->values();

        // Asegurarse de que se muestren todos, incluso con 0 proyectos
        // (ya lo hace por cómo está estructurado el código)

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 10;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function averageAmountByEconomicSupport($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todos los apoyos económicos
        $economicSupports = EconomicSupport::all();

        // Construir consulta para proyectos filtrados
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Total de proyectos filtrados para calcular porcentajes
        $totalFilteredProjects = count($filteredProjectIds);

        // Para cada apoyo económico, calcular promedio y contar proyectos
        $results = $economicSupports->map(function ($support) use ($filteredProjectIds, $totalFilteredProjects) {
            // Contar proyectos con este apoyo económico
            $projectCount = DB::table('dual_project_reports')
                ->where('economic_support', $support->id)
                ->whereIn('dual_project_id', $filteredProjectIds)
                ->count(DB::raw('DISTINCT dual_project_id'));

            // Calcular promedio de amount (solo si hay proyectos)
            $averageAmount = 0;
            if ($projectCount > 0) {
                $averageAmount = DB::table('dual_project_reports')
                    ->where('economic_support', $support->id)
                    ->whereIn('dual_project_id', $filteredProjectIds)
                    ->avg('amount');
            }

            $percentage = $totalFilteredProjects > 0 ? round(($projectCount * 100) / $totalFilteredProjects, 2) : 0;

            return [
                'id' => $support->id,
                'support_name' => $support->name,
                'project_count' => $projectCount,
                'average_amount' => $averageAmount ? round($averageAmount, 2) : 0,
                'percentage' => $percentage
            ];
        })->sortByDesc('average_amount')->values();

        // Asegurarse de que se muestren todos, incluso con 0 proyectos y promedio 0

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 10;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function getInstitutionProjectPercentage($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todas las instituciones
        $institutionsQuery = Institution::query();

        if (!empty($idState)) {
            $institutionsQuery->where('id_state', $idState);
        }

        $institutions = $institutionsQuery->get();

        // Construir consulta para proyectos filtrados
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Total de proyectos filtrados para calcular porcentajes
        $totalFilteredProjects = count($filteredProjectIds);

        // Para cada institución, contar proyectos filtrados
        $results = $institutions->map(function ($institution) use ($filteredProjectIds, $totalFilteredProjects, $idInstitution) {
            // Si hay filtro por institución, solo contar si es la institución filtrada
            if (!empty($idInstitution) && $institution->id != $idInstitution) {
                $projectCount = 0;
            } else {
                $projectCount = count(array_filter($filteredProjectIds, function ($projectId) use ($institution) {
                    $project = DualProject::find($projectId);
                    return $project && $project->id_institution == $institution->id;
                }));
            }

            $percentage = $totalFilteredProjects > 0 ? round(($projectCount * 100) / $totalFilteredProjects, 2) : 0;

            return [
                'id' => $institution->id,
                'institution_name' => $institution->name,
                'image' => $institution->image,
                'project_count' => $projectCount,
                'percentage' => $percentage
            ];
        })->sortByDesc('percentage')->values();

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 10;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function countProjectsByDualType($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todos los tipos duales
        $dualTypes = DualType::all();

        // Construir consulta para proyectos filtrados
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Total de proyectos filtrados para calcular porcentajes
        $totalFilteredProjects = count($filteredProjectIds);

        // Para cada tipo dual, contar proyectos filtrados
        $results = $dualTypes->map(function ($dualType) use ($filteredProjectIds, $totalFilteredProjects) {
            $projectCount = DB::table('dual_project_reports')
                ->where('dual_type_id', $dualType->id)
                ->whereIn('dual_project_id', $filteredProjectIds)
                ->count(DB::raw('DISTINCT dual_project_id'));

            $percentage = $totalFilteredProjects > 0 ? round(($projectCount * 100) / $totalFilteredProjects, 2) : 0;

            return [
                'id' => $dualType->id,
                'dual_type' => $dualType->name,
                'total' => $projectCount,
                'percentage' => $percentage  // Agregar porcentaje para consistencia
            ];
        })->sortByDesc('total')->values();

        // Paginación manual
        $total = $results->count();
        $perPage = $perPage ?: 10;
        $currentPage = $page ?: 1;
        $offset = ($currentPage - 1) * $perPage;
        $paginatedResults = $results->slice($offset, $perPage)->values();

        return [
            'data' => $paginatedResults->toArray(),
            'pagination' => [
                'total' => $total,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => ceil($total / $perPage)
            ]
        ];
    }

    public function countOrganizationsByCluster($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todos los clusters nacionales
        $nacionalClusters = Cluster::where('type', 'Nacional')->get();
        $localClusters = Cluster::where('type', 'Local')->get();

        // Construir consulta para organizaciones filtradas
        $filteredOrgsQuery = Organization::query();

        if (!empty($idInstitution) || !empty($idState)) {
            $filteredOrgsQuery->whereHas('organizationDualProjects.dualProject.institution', function ($q) use ($idInstitution, $idState) {
                if (!empty($idInstitution)) {
                    $q->where('id', $idInstitution);
                }
                if (!empty($idState)) {
                    $q->where('id_state', $idState);
                }
            });
        }

        // Obtener IDs de organizaciones filtradas
        $filteredOrgIds = $filteredOrgsQuery->pluck('id')->toArray();

        // Para clusters nacionales
        $nacionales = $nacionalClusters->map(function ($cluster) use ($filteredOrgIds) {
            $orgCount = count(array_filter($filteredOrgIds, function ($orgId) use ($cluster) {
                $org = Organization::find($orgId);
                return $org && $org->id_cluster == $cluster->id;
            }));

            return [
                'id' => $cluster->id,
                'cluster_name' => $cluster->name,
                'type' => $cluster->type,
                'organization_count' => $orgCount
            ];
        })->sortByDesc('organization_count')->values();

        // Para clusters locales
        $locales = $localClusters->map(function ($cluster) use ($filteredOrgIds) {
            $orgCount = count(array_filter($filteredOrgIds, function ($orgId) use ($cluster) {
                $org = Organization::find($orgId);
                return $org && $org->id_cluster_local == $cluster->id;
            }));

            return [
                'id' => $cluster->id,
                'cluster_name' => $cluster->name,
                'type' => $cluster->type,
                'organization_count' => $orgCount
            ];
        })->sortByDesc('organization_count')->values();

        // Paginación manual para nacionales
        $totalNacionales = $nacionales->count();
        $perPageNacionales = $perPage ?: 10;
        $currentPageNacionales = $page ?: 1;
        $offsetNacionales = ($currentPageNacionales - 1) * $perPageNacionales;
        $paginatedNacionales = $nacionales->slice($offsetNacionales, $perPageNacionales)->values();

        // Paginación manual para locales
        $totalLocales = $locales->count();
        $perPageLocales = $perPage ?: 10;
        $currentPageLocales = $page ?: 1;
        $offsetLocales = ($currentPageLocales - 1) * $perPageLocales;
        $paginatedLocales = $locales->slice($offsetLocales, $perPageLocales)->values();

        return [
            'data' => [
                'nacionales' => $paginatedNacionales->toArray(),
                'locales' => $paginatedLocales->toArray()
            ],
            'pagination' => [
                'nacionales' => [
                    'total' => $totalNacionales,
                    'per_page' => $perPageNacionales,
                    'current_page' => $currentPageNacionales,
                    'last_page' => ceil($totalNacionales / $perPageNacionales)
                ],
                'locales' => [
                    'total' => $totalLocales,
                    'per_page' => $perPageLocales,
                    'current_page' => $currentPageLocales,
                    'last_page' => ceil($totalLocales / $perPageLocales)
                ]
            ]
        ];
    }

    public function countProjectsByCluster($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        // Obtener todos los clusters
        $nacionalClusters = Cluster::where('type', 'Nacional')->get();
        $localClusters = Cluster::where('type', 'Local')->get();

        // Construir consulta para proyectos filtrados
        $filteredProjectsQuery = DualProject::where('has_report', 1);

        if (!empty($idInstitution)) {
            $filteredProjectsQuery->where('id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $filteredProjectsQuery->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        // Obtener IDs de proyectos filtrados
        $filteredProjectIds = $filteredProjectsQuery->pluck('id')->toArray();

        // Función para contar proyectos por cluster
        $countProjectsForClusters = function ($clusters, $clusterColumn) use ($filteredProjectIds) {
            return $clusters->map(function ($cluster) use ($clusterColumn, $filteredProjectIds) {
                $projectCount = DB::table('organizations')
                    ->join('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
                    ->where('organizations.' . $clusterColumn, $cluster->id)
                    ->whereIn('organizations_dual_projects.id_dual_project', $filteredProjectIds)
                    ->count(DB::raw('DISTINCT organizations_dual_projects.id_dual_project'));

                return [
                    'id' => $cluster->id,
                    'cluster_name' => $cluster->name,
                    'type' => $cluster->type,
                    'project_count' => $projectCount
                ];
            })->sortByDesc('project_count')->values();
        };

        // Contar proyectos para clusters nacionales y locales
        $nacionales = $countProjectsForClusters($nacionalClusters, 'id_cluster');
        $locales = $countProjectsForClusters($localClusters, 'id_cluster_local');

        // Paginación manual para nacionales
        $totalNacionales = $nacionales->count();
        $perPageNacionales = $perPage ?: 10;
        $currentPageNacionales = $page ?: 1;
        $offsetNacionales = ($currentPageNacionales - 1) * $perPageNacionales;
        $paginatedNacionales = $nacionales->slice($offsetNacionales, $perPageNacionales)->values();

        // Paginación manual para locales
        $totalLocales = $locales->count();
        $perPageLocales = $perPage ?: 10;
        $currentPageLocales = $page ?: 1;
        $offsetLocales = ($currentPageLocales - 1) * $perPageLocales;
        $paginatedLocales = $locales->slice($offsetLocales, $perPageLocales)->values();

        return [
            'data' => [
                'nacionales' => $paginatedNacionales->toArray(),
                'locales' => $paginatedLocales->toArray()
            ],
            'pagination' => [
                'nacionales' => [
                    'total' => $totalNacionales,
                    'per_page' => $perPageNacionales,
                    'current_page' => $currentPageNacionales,
                    'last_page' => ceil($totalNacionales / $perPageNacionales)
                ],
                'locales' => [
                    'total' => $totalLocales,
                    'per_page' => $perPageLocales,
                    'current_page' => $currentPageLocales,
                    'last_page' => ceil($totalLocales / $perPageLocales)
                ]
            ]
        ];
    }
}
