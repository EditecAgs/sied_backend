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
use App\Models\DocumentStatus;
use App\Models\DualProjectReport;
use App\Models\DualProjectReportMicroCredential;
use App\Models\DualProjectReportCertification;
use App\Models\DualProjectReportDiploma;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    private function baseProjectQuery($idState = null, $idInstitution = null)
    {
        $q = DualProject::query()
            ->where('has_report', 1);

        $this->applySimpleFilters($q, $idState, $idInstitution);

        return $q;
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
            'statsByBenefitType' => $this->statsByBenefitType($idState, $idInstitution),
            'countProjectsByDocumentStatus' => $this->countProjectsByDocumentStatus($idState, $idInstitution),
            'countProjectsByStatus' => $this->countProjectsByStatus($idState, $idInstitution),
            'getMicroCredentialsStats' => $this->getMicroCredentialsStats($idState, $idInstitution),
            'getCertificationsStats' => $this->getCertificationsStats($idState, $idInstitution),
            'getDiplomasStats' => $this->getDiplomasStats($idState, $idInstitution),
            'getDualAreasStats' => $this->getDualAreasStats($idState, $idInstitution),
        ];
    }

    public function countDualProjectCompleted($idState = null, $idInstitution = null)
    {
        return $this->baseProjectQuery($idState, $idInstitution)->count();
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
        $q = $this->baseProjectQuery($idState, $idInstitution);

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
        $projectIds = $this->baseProjectQuery($idState, $idInstitution)->pluck('id');

        $results = DB::table('dual_areas')
            ->leftJoin('dual_project_reports', 'dual_areas.id', '=', 'dual_project_reports.id_dual_area')
            ->whereIn('dual_project_reports.dual_project_id', $projectIds)
            ->select(
                'dual_areas.id',
                'dual_areas.name as area_name',
                DB::raw('COUNT(DISTINCT dual_project_reports.dual_project_id) as project_count')
            )
            ->groupBy('dual_areas.id', 'dual_areas.name')
            ->having('project_count', '>', 0)
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
                      AND dp.has_report = 1 AND dp.deleted_at IS NULL
                  WHERE o.id_sector = sectors.id
                  ' . (!empty($idInstitution) ? ' AND dp.id_institution = ?' : '') . '
                  ' . (!empty($idState) ? ' AND EXISTS (SELECT 1 FROM institutions i WHERE i.id = dp.id_institution AND i.id_state = ?)' : '') . '
                 ) as project_count')
            ]);

        if (!empty($idInstitution)) {
            $query->addBinding($idInstitution, 'select');
        }
        if (!empty($idState)) {
            $query->addBinding($idState, 'select');
        }

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
                            ->where('dual_projects.has_report', 1) ->whereNull('dual_projects.deleted_at');
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
        $projectIds = $this->baseProjectQuery($idState, $idInstitution)->pluck('id');

        $results = DB::table('economic_supports as es')
            ->leftJoin('dual_project_reports as dpr', 'es.id', '=', 'dpr.economic_support')
            ->whereIn('dpr.dual_project_id', $projectIds)
            ->select(
                'es.id',
                'es.name as support_name',
                DB::raw('COUNT(DISTINCT dpr.dual_project_id) as project_count')
            )
            ->groupBy('es.id', 'es.name')
            ->having('project_count', '>', 0)
            ->orderByDesc('project_count')
            ->get();

        $totalProjects = $results->sum('project_count');

        return $results->map(function ($item) use ($totalProjects) {
            return (object) [
                'id' => $item->id,
                'support_name' => $item->support_name,
                'project_count' => $item->project_count,
                'percentage' => $totalProjects > 0 ? round(($item->project_count * 100) / $totalProjects, 2) : 0,
            ];
        })->toArray();
    }

    public function averageAmountByEconomicSupport($idState = null, $idInstitution = null)
    {
        $projectIds = $this->baseProjectQuery($idState, $idInstitution)->pluck('id');

        $results = DB::table('economic_supports as es')
            ->leftJoin('dual_project_reports as dpr', 'es.id', '=', 'dpr.economic_support')
            ->whereIn('dpr.dual_project_id', $projectIds)
            ->select(
                'es.id',
                'es.name as support_name',
                DB::raw('COUNT(DISTINCT dpr.dual_project_id) as project_count'),
                DB::raw('COALESCE(ROUND(AVG(NULLIF(dpr.amount, 0)), 2), 0) as average_amount')
            )
            ->groupBy('es.id', 'es.name')
            ->having('project_count', '>', 0)
            ->orderByDesc('project_count')
            ->get();

        $totalProjects = $results->sum('project_count');

        return $results->map(function ($item) use ($totalProjects) {
            return (object) [
                'id' => $item->id,
                'support_name' => $item->support_name,
                'project_count' => $item->project_count,
                'average_amount' => $item->average_amount,
                'percentage' => $totalProjects > 0 ? round(($item->project_count * 100) / $totalProjects, 2) : 0,
            ];
        })->toArray();
    }

    public function getInstitutionProjectPercentage($idState = null, $idInstitution = null)
    {
        $totalProjects = $this->baseProjectQuery($idState, $idInstitution)->count();

        $query = DB::table('institutions')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('institutions.id', '=', 'dual_projects.id_institution')
                    ->where('dual_projects.has_report', 1)
                    ->whereNull('dual_projects.deleted_at');
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
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->groupBy('institutions.id', 'institutions.name', 'institutions.image')
            ->having('project_count', '>', 0)
            ->orderByDesc('project_count')
            ->get();

        return $results->map(function ($item) use ($totalProjects) {
            return (object) [
                'id' => $item->id,
                'institution_name' => $item->institution_name,
                'image' => $item->image,
                'project_count' => $item->project_count,
                'percentage' => $totalProjects > 0 ? round(($item->project_count * 100) / $totalProjects, 2) : 0,
            ];
        })->toArray();
    }

    public function countProjectsByDualType($idState = null, $idInstitution = null)
    {
        $projectIds = $this->baseProjectQuery($idState, $idInstitution)->pluck('id');

        $results = DB::table('dual_types')
            ->leftJoin('dual_project_reports', 'dual_types.id', '=', 'dual_project_reports.dual_type_id')
            ->whereIn('dual_project_reports.dual_project_id', $projectIds)
            ->select(
                'dual_types.id',
                'dual_types.name as dual_type',
                DB::raw('COUNT(DISTINCT dual_project_reports.dual_project_id) as total')
            )
            ->groupBy('dual_types.id', 'dual_types.name')
            ->having('total', '>', 0)
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
            INNER JOIN dual_projects dp ON odp.id_dual_project = dp.id AND dp.has_report = 1 AND dp.deleted_at IS NULL
            INNER JOIN institutions i ON dp.id_institution = i.id
            WHERE o.id_cluster = clusters.id
            {$filterString}
        )";

            $subqueryLocal = "(
            SELECT COUNT(DISTINCT o.id)
            FROM organizations o
            INNER JOIN organizations_dual_projects odp ON o.id = odp.id_organization
            INNER JOIN dual_projects dp ON odp.id_dual_project = dp.id AND dp.has_report = 1 AND dp.deleted_at IS NULL
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
                        ->where('dual_projects.has_report', 1)
                        ->whereNull('dual_projects.deleted_at');
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

    public function statsByBenefitType($idState = null, $idInstitution = null)
    {
        $query = DB::table('benefit_types as bt')
            ->leftJoin(
                'benefit_type_dual_project_report as btdpr',
                function ($join) {
                    $join->on('bt.id', '=', 'btdpr.id_benefit_type')
                        ->whereNull('btdpr.deleted_at');
                }
            )
            ->leftJoin(
                'dual_project_reports as dpr',
                'dpr.id',
                '=',
                'btdpr.id_dual_project_report'
            )
            ->leftJoin('dual_projects as dp', function ($join) {
                $join->on('dp.id', '=', 'dpr.dual_project_id')
                    ->where('dp.has_report', 1) ->whereNull('dp.deleted_at');
            })
            ->leftJoin('institutions as i', 'i.id', '=', 'dp.id_institution')
            ->whereNull('bt.deleted_at');

        if (!empty($idInstitution)) {
            $query->where('i.id', $idInstitution);
        }

        if (!empty($idState)) {
            $query->where('i.id_state', $idState);
        }

        $results = $query
            ->groupBy('bt.id', 'bt.name')
            ->select(
                'bt.id',
                'bt.name as benefit_type_name',
                DB::raw('COUNT(DISTINCT dp.id) as project_count'),
                DB::raw('COALESCE(AVG(btdpr.quantity), 0) as avg_quantity')
            )
            ->orderByDesc('project_count')
            ->get();

        return $results->toArray();
    }

    public function countProjectsByDocumentStatus($idState = null, $idInstitution = null)
    {
        $projectIds = $this->baseProjectQuery($idState, $idInstitution)->pluck('id');
        $totalProjects = count($projectIds);

        $results = DB::table('document_statuses')
            ->leftJoin('dual_project_reports', 'document_statuses.id', '=', 'dual_project_reports.status_document')
            ->whereIn('dual_project_reports.dual_project_id', $projectIds)
            ->select(
                'document_statuses.id',
                'document_statuses.name as status_name',
                DB::raw('COUNT(DISTINCT dual_project_reports.dual_project_id) as project_count')
            )
            ->groupBy('document_statuses.id', 'document_statuses.name')
            ->having('project_count', '>', 0)
            ->orderByDesc('project_count')
            ->get();

        return $results->map(function ($item) use ($totalProjects) {
            return (object) [
                'id' => $item->id,
                'status_name' => $item->status_name,
                'project_count' => $item->project_count,
                'percentage' => $totalProjects > 0 ? round(($item->project_count * 100) / $totalProjects, 2) : 0,
            ];
        })->toArray();
    }

    public function countProjectsByStatus($idState = null, $idInstitution = null)
    {
        $query = DualProject::select(
            'dual_project_reports.is_concluded',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->where('dual_projects.has_report', 1)
            ->join('dual_project_reports', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id');

        if (!empty($idInstitution)) {
            $query->where('dual_projects.id_institution', $idInstitution);
        }

        if (!empty($idState)) {
            $query->whereHas('institution', function ($q) use ($idState) {
                $q->where('id_state', $idState);
            });
        }

        $results = $query->groupBy('dual_project_reports.is_concluded')
            ->get()
            ->keyBy('is_concluded');

        $concluded = $results[1]->project_count ?? 0;
        $inProgress = $results[0]->project_count ?? 0;
        $total = $concluded + $inProgress;

        return [
            [
                'status' => 'Concluidos',
                'status_key' => 'concluded',
                'is_concluded' => 1,
                'count' => $concluded,
                'percentage' => $total > 0 ? round(($concluded * 100) / $total, 2) : 0,
            ],
            [
                'status' => 'En proceso',
                'status_key' => 'in_progress',
                'is_concluded' => 0,
                'count' => $inProgress,
                'percentage' => $total > 0 ? round(($inProgress * 100) / $total, 2) : 0,
            ]
        ];
    }

    public function getMicroCredentialsStats($idState = null, $idInstitution = null)
    {
        $projectsQuery = DualProject::where('has_report', 1);
        $this->applySimpleFilters($projectsQuery, $idState, $idInstitution);

        $projectIds = $projectsQuery->pluck('id')->toArray();

        if (empty($projectIds)) {
            return [
                'total_micro_credentials' => 0,
                'total_projects' => 0,
                'projects_with_micro_credentials' => 0,
                'projects_without_micro_credentials' => 0,
                'average_per_project' => 0,
                'average_per_project_with_micro' => 0,
                'micro_credentials_by_project' => []
            ];
        }

        $totalMicroCredentials = DualProjectReportMicroCredential::whereIn(
            'id_dual_project_report',
            function ($query) use ($projectIds) {
                $query->select('id')
                    ->from('dual_project_reports')
                    ->whereIn('dual_project_id', $projectIds);
            }
        )->count();

        $totalProjects = count($projectIds);

        $projectsWithMicro = DualProjectReport::whereIn('dual_project_id', $projectIds)
            ->whereHas('microCredentials')
            ->count(DB::raw('DISTINCT dual_project_id'));

        $averagePerProject = $totalProjects > 0
            ? round($totalMicroCredentials / $totalProjects, 2)
            : 0;

        $averagePerProjectWithMicro = $projectsWithMicro > 0
            ? round($totalMicroCredentials / $projectsWithMicro, 2)
            : 0;

        return [
            'total_micro_credentials' => $totalMicroCredentials,
            'total_projects' => $totalProjects,
            'projects_with_micro_credentials' => $projectsWithMicro,
            'projects_without_micro_credentials' => $totalProjects - $projectsWithMicro,
            'average_per_project' => $averagePerProject,
            'average_per_project_with_micro' => $averagePerProjectWithMicro,
        ];
    }

    public function getCertificationsStats($idState = null, $idInstitution = null)
    {
        $projectsQuery = DualProject::where('has_report', 1);
        $this->applySimpleFilters($projectsQuery, $idState, $idInstitution);

        $projectIds = $projectsQuery->pluck('id')->toArray();

        if (empty($projectIds)) {
            return [
                'total_certifications' => 0,
                'total_projects' => 0,
                'projects_with_certifications' => 0,
                'projects_without_certifications' => 0,
                'average_per_project' => 0,
                'average_per_project_with_certifications' => 0,
            ];
        }

        $totalCertifications = DualProjectReportCertification::whereIn(
            'id_dual_project_report',
            function ($query) use ($projectIds) {
                $query->select('id')
                    ->from('dual_project_reports')
                    ->whereIn('dual_project_id', $projectIds);
            }
        )->count();

        $totalProjects = count($projectIds);

        $projectsWithCertifications = DualProjectReport::whereIn('dual_project_id', $projectIds)
            ->whereHas('certifications')
            ->count(DB::raw('DISTINCT dual_project_id'));

        $averagePerProject = $totalProjects > 0
            ? round($totalCertifications / $totalProjects, 2)
            : 0;

        $averagePerProjectWithCertifications = $projectsWithCertifications > 0
            ? round($totalCertifications / $projectsWithCertifications, 2)
            : 0;

        return [
            'total_certifications' => $totalCertifications,
            'total_projects' => $totalProjects,
            'projects_with_certifications' => $projectsWithCertifications,
            'projects_without_certifications' => $totalProjects - $projectsWithCertifications,
            'average_per_project' => $averagePerProject,
            'average_per_project_with_certifications' => $averagePerProjectWithCertifications,
        ];
    }

    public function getDiplomasStats($idState = null, $idInstitution = null)
    {
        $projectsQuery = DualProject::where('has_report', 1);
        $this->applySimpleFilters($projectsQuery, $idState, $idInstitution);

        $projectIds = $projectsQuery->pluck('id')->toArray();

        if (empty($projectIds)) {
            return [
                'total_diplomas' => 0,
                'total_projects' => 0,
                'projects_with_diplomas' => 0,
                'projects_without_diplomas' => 0,
                'average_per_project' => 0,
                'average_per_project_with_diplomas' => 0,
            ];
        }

        $totalDiplomas = DualProjectReportDiploma::whereIn(
            'id_dual_project_report',
            function ($query) use ($projectIds) {
                $query->select('id')
                    ->from('dual_project_reports')
                    ->whereIn('dual_project_id', $projectIds);
            }
        )->count();

        $totalProjects = count($projectIds);

        $projectsWithDiplomas = DualProjectReport::whereIn('dual_project_id', $projectIds)
            ->whereHas('diplomas')
            ->count(DB::raw('DISTINCT dual_project_id'));

        $averagePerProject = $totalProjects > 0
            ? round($totalDiplomas / $totalProjects, 2)
            : 0;

        $averagePerProjectWithDiplomas = $projectsWithDiplomas > 0
            ? round($totalDiplomas / $projectsWithDiplomas, 2)
            : 0;

        return [
            'total_diplomas' => $totalDiplomas,
            'total_projects' => $totalProjects,
            'projects_with_diplomas' => $projectsWithDiplomas,
            'projects_without_diplomas' => $totalProjects - $projectsWithDiplomas,
            'average_per_project' => $averagePerProject,
            'average_per_project_with_diplomas' => $averagePerProjectWithDiplomas,
        ];
    }

    public function getDualAreasStats($idState = null, $idInstitution = null)
    {
        $areas = DualArea::all();

        $projectsQuery = $this->baseProjectQuery($idState, $idInstitution)
            ->join('dual_project_reports', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id');

        $totalProjects = (clone $projectsQuery)
            ->distinct('dual_projects.id')
            ->count('dual_projects.id');

        if ($totalProjects === 0) {
            return $areas->map(function ($area) {
                return [
                    'id' => $area->id,
                    'name' => $area->name,
                    'project_count' => 0,
                    'percentage' => 0,
                ];
            })->toArray();
        }

        $areaCounts = $projectsQuery
            ->select(
                'dual_project_reports.id_dual_area',
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
            )
            ->groupBy('dual_project_reports.id_dual_area')
            ->pluck('project_count', 'dual_project_reports.id_dual_area')
            ->toArray();

        $results = [];

        foreach ($areas as $area) {
            $count = $areaCounts[$area->id] ?? 0;

            $results[] = [
                'id' => $area->id,
                'name' => $area->name,
                'project_count' => $count,
                'percentage' => round(($count * 100) / $totalProjects, 2),
            ];
        }

        usort($results, fn($a, $b) => $b['project_count'] <=> $a['project_count']);

        return $results;
    }
}
