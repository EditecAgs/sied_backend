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

    public function countProjectsByArea($idState = null, $idInstitution = null)
    {
        $whereClauses = [];
        $bindings = [];

        if (!empty($idInstitution)) {
            $whereClauses[] = 'dp.id_institution = ?';
            $bindings[] = $idInstitution;
        }

        if (!empty($idState)) {
            $whereClauses[] = 'i.id_state = ?';
            $bindings[] = $idState;
        }

        $whereSQL = !empty($whereClauses) ? 'AND ' . implode(' AND ', $whereClauses) : '';
        $joinInstitution = !empty($idState) ? 'LEFT JOIN institutions i ON dp.id_institution = i.id' : '';

        $results = DualArea::select(
            'dual_areas.id',
            'dual_areas.name as area_name',
            DB::raw("(
                SELECT COUNT(DISTINCT dp.id)
                FROM dual_project_reports dpr
                INNER JOIN dual_projects dp ON dpr.dual_project_id = dp.id
                    AND dp.has_report = 1
                {$joinInstitution}
                WHERE dpr.id_dual_area = dual_areas.id
                {$whereSQL}
            ) as project_count")
        )
            ->when(!empty($bindings), function ($query) use ($bindings) {
                foreach ($bindings as $binding) {
                    $query->addBinding($binding);
                }
            })
            ->orderByDesc('project_count')
            ->get();

        return $results->toArray();
    }

    public function countProjectsBySector($idState = null, $idInstitution = null)
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
            ->select([
                'sectors.*',
                DB::raw('(SELECT COUNT(DISTINCT dp.id)
                      FROM organizations o
                      LEFT JOIN organizations_dual_projects odp ON o.id = odp.id_organization
                      LEFT JOIN dual_projects dp ON dp.id = odp.id_dual_project
                          AND dp.has_report = 1
                      WHERE o.id_sector = sectors.id
                      ' . (!empty($idInstitution) ? ' AND dp.id_institution = ' . $idInstitution : '') . '
                      ' . (!empty($idState) ? ' AND EXISTS (SELECT 1 FROM institutions i WHERE i.id = dp.id_institution AND i.id_state = ' . $idState . ')' : '') . '
                     ) as project_count')
            ]);

        $results = $query->orderByDesc('project_count')->get();

        $collection = $results->map(function ($sector) use ($totalProjects) {
            $sector->percentage = $totalProjects > 0 ? round(($sector->project_count * 100) / $totalProjects, 2) : 0;
            return $sector;
        })->values();

        return $collection->toArray();
    }

    public function countProjectsBySectorPlanMexico($idState = null, $idInstitution = null)
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $results = Sector::where('plan_mexico', 1)
            ->withCount(['organizations as project_count' => function ($query) use ($idState, $idInstitution) {
                $query->select(DB::raw('COUNT(DISTINCT dual_projects.id)'))
                    ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
                    ->leftJoin('dual_projects', function ($join) {
                        $join->on('organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
                            ->where('dual_projects.has_report', 1);
                    })
                    ->when(!empty($idState), function ($q) use ($idState) {
                        $q->where('organizations.id_state', $idState);
                    })
                    ->when(!empty($idInstitution), function ($q) use ($idInstitution) {
                        $q->where('dual_projects.id_institution', $idInstitution);
                    });
            }])
            ->get()
            ->map(function ($sector) use ($totalProjects) {
                $sector->percentage = $totalProjects > 0
                    ? round(($sector->project_count * 100.0) / $totalProjects, 2)
                    : 0;

                return [
                    'id' => $sector->id,
                    'sector_name' => $sector->name,
                    'plan_mexico' => $sector->plan_mexico,
                    'project_count' => $sector->project_count,
                    'percentage' => $sector->percentage
                ];
            })
            ->toArray();

        return $results;
    }

    public function countProjectsByEconomicSupport($idState = null, $idInstitution = null)
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $whereConditions = [];
        if (!empty($idInstitution)) {
            $whereConditions[] = "i.id = " . (int)$idInstitution;
        }
        if (!empty($idState)) {
            $whereConditions[] = "i.id_state = " . (int)$idState;
        }

        $whereSQL = !empty($whereConditions) ? "AND " . implode(" AND ", $whereConditions) : "";
        $joinInstitution = (!empty($idInstitution) || !empty($idState)) ? "INNER JOIN institutions i ON dp.id_institution = i.id" : "";

        $results = EconomicSupport::select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw("(
                SELECT COUNT(DISTINCT dp.id)
                FROM dual_project_reports dpr
                INNER JOIN dual_projects dp ON dpr.dual_project_id = dp.id
                    AND dp.has_report = 1
                {$joinInstitution}
                WHERE dpr.economic_support = economic_supports.id
                {$whereSQL}
            ) as project_count"),
            DB::raw("CASE WHEN {$totalProjects} > 0
                THEN ROUND((
                    SELECT COUNT(DISTINCT dp.id)
                    FROM dual_project_reports dpr
                    INNER JOIN dual_projects dp ON dpr.dual_project_id = dp.id
                        AND dp.has_report = 1
                    {$joinInstitution}
                    WHERE dpr.economic_support = economic_supports.id
                    {$whereSQL}
                ) * 100.0 / {$totalProjects}, 2)
                ELSE 0
                END as percentage")
        )
            ->orderByDesc('project_count')
            ->get();

        return $results->toArray();
    }

    public function averageAmountByEconomicSupport($idState = null, $idInstitution = null)
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $whereConditions = [];
        if (!empty($idInstitution)) {
            $whereConditions[] = "i.id = " . (int)$idInstitution;
        }
        if (!empty($idState)) {
            $whereConditions[] = "i.id_state = " . (int)$idState;
        }

        $whereSQL = !empty($whereConditions) ? "AND " . implode(" AND ", $whereConditions) : "";
        $joinInstitution = (!empty($idInstitution) || !empty($idState)) ? "INNER JOIN institutions i ON dp.id_institution = i.id" : "";

        $results = EconomicSupport::select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw("(
            SELECT COUNT(DISTINCT dp.id)
            FROM dual_project_reports dpr
            INNER JOIN dual_projects dp ON dpr.dual_project_id = dp.id
                AND dp.has_report = 1
            {$joinInstitution}
            WHERE dpr.economic_support = economic_supports.id
            {$whereSQL}
        ) as project_count"),
            DB::raw("CASE
            WHEN economic_supports.id = 1 THEN 0  -- Sin Apoyo Económico siempre es 0
            ELSE (
                SELECT ROUND(AVG(dpr2.amount), 2)
                FROM dual_project_reports dpr2
                INNER JOIN dual_projects dp2 ON dpr2.dual_project_id = dp2.id
                    AND dp2.has_report = 1
                " . (!empty($idInstitution) || !empty($idState) ? "INNER JOIN institutions i2 ON dp2.id_institution = i2.id" : "") . "
                WHERE dpr2.economic_support = economic_supports.id
                " . (!empty($whereConditions) ? "AND " . implode(" AND ", array_map(function($cond) {
                        return str_replace('i.', 'i2.', $cond);
                    }, $whereConditions)) : "") . "
                AND dpr2.amount IS NOT NULL
                AND dpr2.amount > 0
            )
            END as average_amount"),
            DB::raw("CASE WHEN {$totalProjects} > 0
            THEN ROUND((
                SELECT COUNT(DISTINCT dp.id)
                FROM dual_project_reports dpr
                INNER JOIN dual_projects dp ON dpr.dual_project_id = dp.id
                    AND dp.has_report = 1
                {$joinInstitution}
                WHERE dpr.economic_support = economic_supports.id
                {$whereSQL}
            ) * 100.0 / {$totalProjects}, 2)
            ELSE 0
            END as percentage")
        )
            ->orderByDesc('project_count')
            ->get();

        return $results->toArray();
    }

    public function getInstitutionProjectPercentage($idState = null, $idInstitution = null)
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
            ->get();

        return $results->toArray();
    }

    public function countProjectsByDualType($idState = null, $idInstitution = null)
    {
        $whereConditions = [];
        if (!empty($idInstitution)) {
            $whereConditions[] = "i.id = " . (int)$idInstitution;
        }
        if (!empty($idState)) {
            $whereConditions[] = "i.id_state = " . (int)$idState;
        }

        $whereSQL = !empty($whereConditions) ? "AND " . implode(" AND ", $whereConditions) : "";
        $joinInstitution = (!empty($idInstitution) || !empty($idState)) ? "INNER JOIN institutions i ON dp.id_institution = i.id" : "";

        $results = DualType::select(
            'dual_types.id',
            'dual_types.name as dual_type',
            DB::raw("(
                SELECT COUNT(DISTINCT dp.id)
                FROM dual_project_reports dpr
                INNER JOIN dual_projects dp ON dpr.dual_project_id = dp.id
                    AND dp.has_report = 1
                {$joinInstitution}
                WHERE dpr.dual_type_id = dual_types.id
                {$whereSQL}
            ) as total")
        )
            ->orderByDesc('total')
            ->get();

        return $results->toArray();
    }

    public function countOrganizationsByCluster($idState = null, $idInstitution = null)
    {
        $hasFilters = !empty($idInstitution) || !empty($idState);

        if ($hasFilters) {
            $totalQuery = DB::table('organizations as o')
                ->join('organizations_dual_projects as odp', 'o.id', '=', 'odp.id_organization')
                ->join('dual_projects as dp', 'odp.id_dual_project', '=', 'dp.id')
                ->join('institutions as i', 'dp.id_institution', '=', 'i.id');

            if (!empty($idInstitution)) {
                $totalQuery->where('i.id', (int)$idInstitution);
            }

            if (!empty($idState)) {
                $totalQuery->where('i.id_state', (int)$idState);
            }

            $totalOrganizations = $totalQuery->count(DB::raw('DISTINCT o.id'));
        } else {
            $totalOrganizations = DB::table('organizations')->count();
        }
        if ($hasFilters) {
            $filterConditions = [];
            if (!empty($idInstitution)) {
                $filterConditions[] = "i.id = " . (int)$idInstitution;
            }
            if (!empty($idState)) {
                $filterConditions[] = "i.id_state = " . (int)$idState;
            }
            $filterString = !empty($filterConditions) ? "AND " . implode(' AND ', $filterConditions) : "";

            $subqueryNacional = "(
            SELECT COUNT(DISTINCT o.id)
            FROM organizations o
            INNER JOIN organizations_dual_projects odp ON o.id = odp.id_organization
            INNER JOIN dual_projects dp ON odp.id_dual_project = dp.id
            INNER JOIN institutions i ON dp.id_institution = i.id
            WHERE o.id_cluster = clusters.id
            {$filterString}
        )";

            $subqueryLocal = "(
            SELECT COUNT(DISTINCT o.id)
            FROM organizations o
            INNER JOIN organizations_dual_projects odp ON o.id = odp.id_organization
            INNER JOIN dual_projects dp ON odp.id_dual_project = dp.id
            INNER JOIN institutions i ON dp.id_institution = i.id
            WHERE o.id_cluster_local = clusters.id
            {$filterString}
        )";
        } else {
            $subqueryNacional = "(
            SELECT COUNT(DISTINCT o.id)
            FROM organizations o
            WHERE o.id_cluster = clusters.id
        )";

            $subqueryLocal = "(
            SELECT COUNT(DISTINCT o.id)
            FROM organizations o
            WHERE o.id_cluster_local = clusters.id
        )";
        }

        $nacionales = Cluster::where('type', 'Nacional')
            ->selectRaw("
            clusters.id,
            clusters.name as cluster_name,
            clusters.type,
            COALESCE({$subqueryNacional}, 0) as organization_count,
            CASE WHEN ? > 0
                THEN ROUND(COALESCE({$subqueryNacional}, 0) * 100.0 / ?, 2)
                ELSE 0 END as percentage
        ", [$totalOrganizations, $totalOrganizations])
            ->orderByRaw("organization_count DESC")
            ->get();

        $locales = Cluster::where('type', 'Local')
            ->selectRaw("
            clusters.id,
            clusters.name as cluster_name,
            clusters.type,
            COALESCE({$subqueryLocal}, 0) as organization_count,
            CASE WHEN ? > 0
                THEN ROUND(COALESCE({$subqueryLocal}, 0) * 100.0 / ?, 2)
                ELSE 0 END as percentage
        ", [$totalOrganizations, $totalOrganizations])
            ->orderByRaw("organization_count DESC")
            ->get();

        return [
            'nacionales' => $nacionales->toArray(),
            'locales' => $locales->toArray()
        ];
    }

    public function countProjectsByCluster($idState = null, $idInstitution = null)
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
            ->get();

        $locales = $buildQuery('Local', 'id_cluster_local')
            ->orderByDesc('project_count')
            ->get();

        return [
            'nacionales' => $nacionales->toArray(),
            'locales' => $locales->toArray()
        ];
    }
}
