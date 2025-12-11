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
        $query = DualArea::query();

        $query->whereHas('dualProjectReports.dualProject', function ($q) use ($idInstitution, $idState) {
            $q->where('has_report', 1);
            if (!empty($idInstitution)) {
                $q->where('id_institution', $idInstitution);
            }
            if (!empty($idState)) {
                $q->whereHas('institution', function ($qq) use ($idState) {
                    $qq->where('id_state', $idState);
                });
            }
        });

        $results = $query->leftJoin('dual_project_reports', 'dual_areas.id', '=', 'dual_project_reports.id_dual_area')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'dual_project_reports.dual_project_id')
                    ->where('dual_projects.has_report', 1);
            })
            ->select(
                'dual_areas.id',
                'dual_areas.name as area_name',
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
            )
            ->groupBy('dual_areas.id', 'dual_areas.name')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }

    public function countProjectsBySector($idState = null, $idInstitution = null, $perPage = 11, $page = 1)
    {
        $totalProjectsQ = DualProject::where('has_report', 1);
        if (!empty($idInstitution)) {
            $totalProjectsQ->where('id_institution', $idInstitution);
        }
        if (!empty($idState)) {
            $totalProjectsQ->whereHas('institution', function ($qq) use ($idState) {
                $qq->where('id_state', $idState);
            });
        }
        $totalProjects = $totalProjectsQ->count();

        $query = Sector::query()
            ->where('plan_mexico', 1)
            ->withCount(['organizations as project_count' => function ($q) use ($idState, $idInstitution) {
                $q->whereHas('organizationDualProjects.dualProject', function ($qp) use ($idState, $idInstitution) {
                    $qp->where('has_report', 1);

                    if (!empty($idInstitution)) {
                        $qp->where('id_institution', $idInstitution);
                    }

                    if (!empty($idState)) {
                        $qp->whereHas('institution', function ($qi) use ($idState) {
                            $qi->where('id_state', $idState);
                        });
                    }
                });
            }]);

        $results = $query->orderByDesc('project_count')->paginate($perPage, ['*'], 'page', $page);


        $collection = $results->getCollection()->map(function ($sector) use ($totalProjects) {
            $sector->percentage = $totalProjects > 0 ? round(($sector->project_count * 100) / $totalProjects, 2) : 0;
            return $sector;
        })->values();

        return [
            'data' => $collection->toArray(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }


    public function countProjectsBySectorPlanMexico($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $query = Sector::query()
            ->select(
                'sectors.id',
                'sectors.name as sector_name',
                'sectors.plan_mexico',
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
                DB::raw('CASE WHEN ' . $totalProjects . ' > 0 THEN ROUND(COUNT(DISTINCT dual_projects.id) * 100.0 / ' . $totalProjects . ', 2) ELSE 0 END as percentage')
            )
            ->leftJoin('organizations', 'sectors.id', '=', 'organizations.id_sector')
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                    ->where('dual_projects.has_report', 1);
            });

        if (!empty($idState)) {
            $query->where('organizations.id_state', $idState);
        }

        if (!empty($idInstitution)) {
            $query->where('dual_projects.id_institution', $idInstitution);
        }

        $results = $query->where('sectors.plan_mexico', 1)
            ->groupBy('sectors.id', 'sectors.name', 'sectors.plan_mexico')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }


    public function countProjectsByEconomicSupport($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $query = EconomicSupport::query()
            ->leftJoin('dual_project_reports', 'economic_supports.id', '=', 'dual_project_reports.economic_support')
            ->leftJoin('dual_projects', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id')
            ->leftJoin('institutions', 'institutions.id', '=', 'dual_projects.id_institution');

        if (!empty($idInstitution)) {
            $query->where('institutions.id', $idInstitution);
        }

        if (!empty($idState)) {
            $query->where('institutions.id_state', $idState);
        }

        $totalProjects = DualProject::where('has_report', 1)->count();

        $results = $query->select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
            DB::raw('CASE WHEN ' . $totalProjects . ' > 0 THEN ROUND(COUNT(DISTINCT dual_projects.id) * 100.0 / ' . $totalProjects . ', 2) ELSE 0 END as percentage')
        )
            ->where('dual_projects.has_report', 1)
            ->groupBy('economic_supports.id', 'economic_supports.name')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }


    public function averageAmountByEconomicSupport($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $query = EconomicSupport::query()
            ->leftJoin('dual_project_reports', 'economic_supports.id', '=', 'dual_project_reports.economic_support')
            ->leftJoin('dual_projects', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id')
            ->leftJoin('institutions', 'institutions.id', '=', 'dual_projects.id_institution');

        if (!empty($idInstitution)) {
            $query->where('institutions.id', $idInstitution);
        }

        if (!empty($idState)) {
            $query->where('institutions.id_state', $idState);
        }

        $totalProjects = DualProject::where('has_report', 1)->count();

        $results = $query->select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
            DB::raw('ROUND(AVG(dual_project_reports.amount), 2) as average_amount'),
            DB::raw('CASE WHEN ' . $totalProjects . ' > 0 THEN ROUND(COUNT(DISTINCT dual_projects.id) * 100.0 / ' . $totalProjects . ', 2) ELSE 0 END as percentage')
        )
            ->where('dual_projects.has_report', 1)
            ->groupBy('economic_supports.id', 'economic_supports.name')
            ->orderByDesc('average_amount')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }


    public function getInstitutionProjectPercentage($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $query = Institution::query()
            ->leftJoin('dual_projects', function ($join) {
                $join->on('institutions.id', '=', 'dual_projects.id_institution')
                    ->where('dual_projects.has_report', 1);
            });

        if (!empty($idInstitution)) {
            $query->where('institutions.id', $idInstitution);
        }

        if (!empty($idState)) {
            $query->where('institutions.id_state', $idState);
        }

        $results = $query->select(
            'institutions.id',
            'institutions.name as institution_name',
            'institutions.image',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
            DB::raw('CASE WHEN ' . $totalProjects . ' > 0 THEN ROUND(COUNT(DISTINCT dual_projects.id) * 100.0 / ' . $totalProjects . ', 2) ELSE 0 END as percentage')
        )
            ->groupBy('institutions.id', 'institutions.name', 'institutions.image')
            ->orderByDesc('percentage')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }


    public function countProjectsByDualType($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $query = DualType::query()
            ->leftJoin('dual_project_reports', 'dual_types.id', '=', 'dual_project_reports.dual_type_id')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'dual_project_reports.dual_project_id')
                    ->where('dual_projects.has_report', 1);
            })
            ->leftJoin('institutions', 'institutions.id', '=', 'dual_projects.id_institution');

        if (!empty($idInstitution)) {
            $query->where('institutions.id', $idInstitution);
        }

        if (!empty($idState)) {
            $query->where('institutions.id_state', $idState);
        }

        $results = $query->select(
            'dual_types.id',
            'dual_types.name as dual_type',
            DB::raw('COUNT(DISTINCT dual_projects.id) as total')
        )
            ->groupBy('dual_types.id', 'dual_types.name')
            ->orderByDesc('total')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => $results->items(),
            'pagination' => [
                'total' => $results->total(),
                'per_page' => $results->perPage(),
                'current_page' => $results->currentPage(),
                'last_page' => $results->lastPage()
            ]
        ];
    }


    public function countOrganizationsByCluster($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $baseQuery = Cluster::query();

        if (!empty($idInstitution) || !empty($idState)) {
            $baseQuery->whereHas('organizations.organizationDualProjects.dualProject.institution', function ($q) use ($idInstitution, $idState) {
                if (!empty($idInstitution)) {
                    $q->where('id', $idInstitution);
                }
                if (!empty($idState)) {
                    $q->where('id_state', $idState);
                }
            });
        }

        $nacionales = (clone $baseQuery)
            ->select(
                'clusters.id',
                'clusters.name as cluster_name',
                'clusters.type',
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
                DB::raw('CASE WHEN ' . $totalProjects . ' > 0 THEN ROUND(COUNT(DISTINCT dual_projects.id) * 100.0 / ' . $totalProjects . ', 2) ELSE 0 END as percentage')
            )
            ->leftJoin('organizations', function ($join) {
                $join->on('clusters.id', '=', 'organizations.id_cluster')
                    ->where('clusters.type', 'Nacional');
            })
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                    ->where('dual_projects.has_report', 1);
            })
            ->where('clusters.type', 'Nacional')
            ->groupBy('clusters.id', 'clusters.name', 'clusters.type')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        $locales = (clone $baseQuery)
            ->select(
                'clusters.id',
                'clusters.name as cluster_name',
                'clusters.type',
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count'),
                DB::raw('CASE WHEN ' . $totalProjects . ' > 0 THEN ROUND(COUNT(DISTINCT dual_projects.id) * 100.0 / ' . $totalProjects . ', 2) ELSE 0 END as percentage')
            )
            ->leftJoin('organizations', function ($join) {
                $join->on('clusters.id', '=', 'organizations.id_cluster_local')
                    ->where('clusters.type', 'Local');
            })
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                    ->where('dual_projects.has_report', 1);
            })
            ->where('clusters.type', 'Local')
            ->groupBy('clusters.id', 'clusters.name', 'clusters.type')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => [
                'nacionales' => $nacionales->items(),
                'locales' => $locales->items()
            ],
            'pagination' => [
                'nacionales' => [
                    'total' => $nacionales->total(),
                    'per_page' => $nacionales->perPage(),
                    'current_page' => $nacionales->currentPage(),
                    'last_page' => $nacionales->lastPage()
                ],
                'locales' => [
                    'total' => $locales->total(),
                    'per_page' => $locales->perPage(),
                    'current_page' => $locales->currentPage(),
                    'last_page' => $locales->lastPage()
                ]
            ]
        ];
    }


    public function countProjectsByCluster($idState = null, $idInstitution = null, $perPage = 10, $page = 1)
    {
        $buildQuery = function ($type, $clusterColumn) use ($idState, $idInstitution) {
            $q = Cluster::query()
                ->where('clusters.type', $type)
                ->leftJoin('organizations', "organizations.$clusterColumn", '=', 'clusters.id')
                ->leftJoin('organizations_dual_projects', 'organizations_dual_projects.id_organization', '=', 'organizations.id')
                ->leftJoin('dual_projects', function ($join) {
                    $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                        ->where('dual_projects.has_report', 1);
                })
                ->leftJoin('institutions', 'institutions.id', '=', 'dual_projects.id_institution');

            if (!empty($idInstitution)) {
                $q->where('institutions.id', $idInstitution);
            }

            if (!empty($idState)) {
                $q->where('institutions.id_state', $idState);
            }

            return $q->groupBy('clusters.id', 'clusters.name', 'clusters.type')
                ->select(
                    'clusters.id',
                    'clusters.name as cluster_name',
                    'clusters.type',
                    DB::raw('COUNT(DISTINCT dual_projects.id) AS project_count')
                );
        };

        $nacionales = $buildQuery('Nacional', 'id_cluster')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        $locales = $buildQuery('Local', 'id_cluster_local')
            ->orderByDesc('project_count')
            ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data' => [
                'nacionales' => $nacionales->items(),
                'locales' => $locales->items()
            ],
            'pagination' => [
                'nacionales' => [
                    'total' => $nacionales->total(),
                    'per_page' => $nacionales->perPage(),
                    'current_page' => $nacionales->currentPage(),
                    'last_page' => $nacionales->lastPage()
                ],
                'locales' => [
                    'total' => $locales->total(),
                    'per_page' => $locales->perPage(),
                    'current_page' => $locales->currentPage(),
                    'last_page' => $locales->lastPage()
                ]
            ]
        ];
    }
}
