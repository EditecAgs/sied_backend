<?php

namespace App\Http\Controllers;

use App\Models\DualArea;
use App\Models\DualType;
use App\Models\DualProject;
use App\Models\EconomicSupport;
use App\Models\Institution;
use App\Models\Organization;
use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    public function countDualProjectCompleted(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $count = DualProject::with(['dualProjectReports', 'organizationDualProjects', 'students'])
            ->where('has_report', 1)
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('id_institution', $user->id_institution);
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->whereHas('institution', function($q) use ($userStateId) {
                    $q->where('id_state', $userStateId);
                });
            })
            ->count();

        return response()->json(['count' => $count], Response::HTTP_OK);
    }

    public function countRegisteredStudents(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $students = Student::when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
            $query->where('id_institution', $user->id_institution);
        })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->whereHas('institution', function($q) use ($userStateId) {
                    $q->where('id_state', $userStateId);
                });
            })
            ->count();

        return response()->json(['count' => $students], Response::HTTP_OK);
    }

    public function countRegisteredOrganizations(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $query = Organization::query();

        if ($filtersAdd == 1 && $user && $user->id_institution) {
            $query->whereExists(function ($subQuery) use ($user) {
                $subQuery->select(DB::raw(1))
                    ->from('organizations_dual_projects')
                    ->join('dual_projects', 'organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
                    ->whereColumn('organizations_dual_projects.id_organization', 'organizations.id')
                    ->where('dual_projects.has_report', 1)
                    ->where('dual_projects.id_institution', $user->id_institution);
            });
        } elseif ($filtersAdd == 2 && $userStateId) {
            $query->whereExists(function ($subQuery) use ($userStateId) {
                $subQuery->select(DB::raw(1))
                    ->from('organizations_dual_projects')
                    ->join('dual_projects', 'organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
                    ->join('institutions', 'dual_projects.id_institution', '=', 'institutions.id')
                    ->whereColumn('organizations_dual_projects.id_organization', 'organizations.id')
                    ->where('dual_projects.has_report', 1)
                    ->where('institutions.id_state', $userStateId);
            });
        } elseif ($filtersAdd == 0) {
        }

        $organization = $query->count();

        return response()->json(['count' => $organization], Response::HTTP_OK);
    }

    public function countOrganizationsByScope(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $counts = Organization::select('scope', DB::raw('COUNT(DISTINCT organizations.id) as total'))
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->whereExists(function ($subQuery) use ($user) {
                    $subQuery->select(DB::raw(1))
                        ->from('organizations_dual_projects')
                        ->join('dual_projects', 'organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
                        ->whereColumn('organizations_dual_projects.id_organization', 'organizations.id')
                        ->where('dual_projects.has_report', 1)
                        ->where('dual_projects.id_institution', $user->id_institution);
                });
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->whereExists(function ($subQuery) use ($userStateId) {
                    $subQuery->select(DB::raw(1))
                        ->from('organizations_dual_projects')
                        ->join('dual_projects', 'organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
                        ->join('institutions', 'dual_projects.id_institution', '=', 'institutions.id')
                        ->whereColumn('organizations_dual_projects.id_organization', 'organizations.id')
                        ->where('dual_projects.has_report', 1)
                        ->where('institutions.id_state', $userStateId);
                });
            })
            ->groupBy('scope')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $counts,
        ], Response::HTTP_OK);
    }


    public function countProjectsByMonth(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $results = DualProject::join('dual_project_reports', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id')
            ->where('dual_projects.has_report', 1)
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('dual_projects.id_institution', $user->id_institution);
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->join('institutions', 'dual_projects.id_institution', '=', 'institutions.id')
                    ->where('institutions.id_state', $userStateId);
            })
            ->select(
                DB::raw('COUNT(*) as project_count'),
                DB::raw("DATE_FORMAT(dual_project_reports.period_start, '%Y-%m') as month_year"),
                DB::raw('MONTH(dual_project_reports.period_start) as month_number'),
                DB::raw('MONTHNAME(dual_project_reports.period_start) as month_name'),
                DB::raw('YEAR(dual_project_reports.period_start) as year')
            )
            ->groupBy('year', 'month_number', 'month_year', 'month_name')
            ->orderBy('year')
            ->orderBy('month_number')
            ->get();

        return response()->json(['data' => $results], Response::HTTP_OK);
    }

    public function countProjectsByArea(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }


        $results = DualArea::select(
            'dual_areas.id',
            'dual_areas.name as area_name',
            DB::raw('COALESCE(project_counts.project_count, 0) as project_count')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    dual_project_reports.id_dual_area,
                    COUNT(DISTINCT dual_projects.id) as project_count
                FROM dual_project_reports
                INNER JOIN dual_projects ON dual_project_reports.dual_project_id = dual_projects.id
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions ON dual_projects.id_institution = institutions.id
                      AND institutions.id_state = ' . $userStateId : '') . '
                GROUP BY dual_project_reports.id_dual_area
            ) as project_counts'), 'dual_areas.id', '=', 'project_counts.id_dual_area')
            ->orderByDesc('project_count')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $results->items(), 'pagination' => ['total' => $results->total(), 'per_page' => $results->perPage(), 'current_page' => $results->currentPage(), 'last_page' => $results->lastPage()]], Response::HTTP_OK);
    }

    public function countProjectsBySector(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $results = Sector::select(
            'sectors.id',
            'sectors.name as sector_name',
            DB::raw('COALESCE(project_counts.project_count, 0) as project_count')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    organizations.id_sector,
                    COUNT(DISTINCT dual_projects.id) as project_count
                FROM organizations
                INNER JOIN organizations_dual_projects ON organizations.id = organizations_dual_projects.id_organization
                INNER JOIN dual_projects ON organizations_dual_projects.id_dual_project = dual_projects.id
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions ON dual_projects.id_institution = institutions.id
                      AND institutions.id_state = ' . $userStateId : '') . '
                GROUP BY organizations.id_sector
            ) as project_counts'), 'sectors.id', '=', 'project_counts.id_sector')
            ->orderByDesc('project_count')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $results->items(), 'pagination' => ['total' => $results->total(), 'per_page' => $results->perPage(), 'current_page' => $results->currentPage(), 'last_page' => $results->lastPage()]], Response::HTTP_OK);
    }

    public function countProjectsBySectorPlanMexico(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $results = Sector::select(
            'sectors.id',
            'sectors.name as sector_name',
            'sectors.plan_mexico',
            DB::raw('COALESCE(project_counts.project_count, 0) as project_count')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    organizations.id_sector,
                    COUNT(DISTINCT dual_projects.id) as project_count
                FROM organizations
                INNER JOIN organizations_dual_projects ON organizations.id = organizations_dual_projects.id_organization
                INNER JOIN dual_projects ON organizations_dual_projects.id_dual_project = dual_projects.id
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions ON dual_projects.id_institution = institutions.id
                      AND institutions.id_state = ' . $userStateId : '') . '
                GROUP BY organizations.id_sector
            ) as project_counts'), 'sectors.id', '=', 'project_counts.id_sector')
            ->where('sectors.plan_mexico', 1)
            ->orderByDesc('project_count')
            ->paginate(11);

        return response()->json(['success' => true, 'data' => $results->items(), 'pagination' => ['total' => $results->total(), 'per_page' => $results->perPage(), 'current_page' => $results->currentPage(), 'last_page' => $results->lastPage()]], Response::HTTP_OK);
    }

    public function countProjectsByEconomicSupport(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $results = EconomicSupport::select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw('COALESCE(project_counts.project_count, 0) as project_count')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    dual_project_reports.economic_support,
                    COUNT(DISTINCT dual_projects.id) as project_count
                FROM dual_project_reports
                INNER JOIN dual_projects ON dual_project_reports.dual_project_id = dual_projects.id
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions ON dual_projects.id_institution = institutions.id
                      AND institutions.id_state = ' . $userStateId : '') . '
                GROUP BY dual_project_reports.economic_support
            ) as project_counts'), 'economic_supports.id', '=', 'project_counts.economic_support')
            ->orderByDesc('project_count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
        ], Response::HTTP_OK);
    }

    public function averageAmountByEconomicSupport(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $results = EconomicSupport::select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw('COALESCE(project_stats.average_amount, 0) as average_amount')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    dual_project_reports.economic_support,
                    ROUND(AVG(dual_project_reports.amount), 2) as average_amount
                FROM dual_project_reports
                INNER JOIN dual_projects ON dual_project_reports.dual_project_id = dual_projects.id
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions ON dual_projects.id_institution = institutions.id
                      AND institutions.id_state = ' . $userStateId : '') . '
                GROUP BY dual_project_reports.economic_support
            ) as project_stats'), 'economic_supports.id', '=', 'project_stats.economic_support')
            ->orderByDesc('average_amount')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
        ], Response::HTTP_OK);
    }

    public function getInstitutionProjectPercentage(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }


        $totalProjectsQuery = DualProject::where('has_report', 1)
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('id_institution', $user->id_institution);
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->whereHas('institution', function($q) use ($userStateId) {
                    $q->where('id_state', $userStateId);
                });
            });

        $totalProjects = $totalProjectsQuery->count();

        $results = Institution::select(
            'institutions.id',
            'institutions.name as institution_name',
            'institutions.image',
            DB::raw('COALESCE(project_counts.project_count, 0) as project_count'),
            DB::raw('CASE WHEN ' . $totalProjects . ' > 0
                 THEN ROUND(COALESCE(project_counts.project_count, 0) * 100.0 / ' . $totalProjects . ', 2)
                 ELSE 0 END as percentage')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    dual_projects.id_institution,
                    COUNT(dual_projects.id) as project_count
                FROM dual_projects
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions AS inst_filter ON dual_projects.id_institution = inst_filter.id
                      AND inst_filter.id_state = ' . $userStateId : '') . '
                GROUP BY dual_projects.id_institution
            ) as project_counts'), 'institutions.id', '=', 'project_counts.id_institution')
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('institutions.id', $user->id_institution);
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->where('institutions.id_state', $userStateId);
            })
            ->orderByDesc('project_count')
            ->get();

        return response()->json([
            'success' => true,
            'total_projects' => $totalProjects,
            'data' => $results,
        ], Response::HTTP_OK);
    }

    public function countProjectsByDualType(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $results = \App\Models\DualType::select(
            'dual_types.id',
            'dual_types.name as dual_type',
            DB::raw('COALESCE(project_counts.total, 0) as total')
        )
            ->leftJoin(DB::raw('(
                SELECT
                    dual_project_reports.dual_type_id,
                    COUNT(DISTINCT dual_projects.id) as total
                FROM dual_project_reports
                INNER JOIN dual_projects ON dual_project_reports.dual_project_id = dual_projects.id
                WHERE dual_projects.has_report = 1
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dual_projects.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions ON dual_projects.id_institution = institutions.id
                      AND institutions.id_state = ' . $userStateId : '') . '
                GROUP BY dual_project_reports.dual_type_id
            ) as project_counts'), 'dual_types.id', '=', 'project_counts.dual_type_id')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ], Response::HTTP_OK);
    }

    public function countOrganizationsByCluster(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        // Para nacionales: Usar subquery para el conteo de organizaciones con proyectos
        $nacionales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COALESCE(org_counts.organization_count, 0) as organization_count')
        )
            ->leftJoin(DB::raw('(
            SELECT
                organizations.id_cluster,
                COUNT(DISTINCT organizations.id) as organization_count
            FROM organizations
            WHERE EXISTS (
                SELECT 1
                FROM organizations_dual_projects odp
                INNER JOIN dual_projects dp ON odp.id_dual_project = dp.id
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dp.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions inst ON dp.id_institution = inst.id
                      AND inst.id_state = ' . $userStateId : '') . '
                WHERE odp.id_organization = organizations.id
                AND dp.has_report = 1
            )
            GROUP BY organizations.id_cluster
        ) as org_counts'), 'clusters.id', '=', 'org_counts.id_cluster')
            ->where('clusters.type', 'Nacional')
            ->orderByDesc('organization_count')
            ->get();

        // Para locales: Usar subquery para el conteo de organizaciones con proyectos
        $locales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COALESCE(org_counts.organization_count, 0) as organization_count')
        )
            ->leftJoin(DB::raw('(
            SELECT
                organizations.id_cluster_local,
                COUNT(DISTINCT organizations.id) as organization_count
            FROM organizations
            WHERE EXISTS (
                SELECT 1
                FROM organizations_dual_projects odp
                INNER JOIN dual_projects dp ON odp.id_dual_project = dp.id
                ' . ($filtersAdd == 1 && $user && $user->id_institution ?
                    ' AND dp.id_institution = ' . $user->id_institution : '') . '
                ' . ($filtersAdd == 2 && $userStateId ?
                    ' INNER JOIN institutions inst ON dp.id_institution = inst.id
                      AND inst.id_state = ' . $userStateId : '') . '
                WHERE odp.id_organization = organizations.id
                AND dp.has_report = 1
            )
            GROUP BY organizations.id_cluster_local
        ) as org_counts'), 'clusters.id', '=', 'org_counts.id_cluster_local')
            ->where('clusters.type', 'Local')
            ->orderByDesc('organization_count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'nacionales' => $nacionales,
                'locales' => $locales
            ]
        ], Response::HTTP_OK);
    }

    public function countProjectsByCluster(Request $request)
    {
        $user = Auth::user();
        $filtersAdd = $request->query('filtersAdd');
        $userStateId = null;

        if ($filtersAdd == 2 && $user && $user->id_institution) {
            $userInstitution = $user->institution;
            if ($userInstitution && $userInstitution->id_state) {
                $userStateId = $userInstitution->id_state;
            }
        }

        $nacionales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->leftJoin('organizations', 'clusters.id', '=', 'organizations.id_cluster')
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', 'organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
            ->where('clusters.type', 'Nacional')
            ->where('dual_projects.has_report', 1)
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('dual_projects.id_institution', $user->id_institution);
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->join('institutions', 'dual_projects.id_institution', '=', 'institutions.id')
                    ->where('institutions.id_state', $userStateId);
            })
            ->groupBy('clusters.id', 'clusters.name', 'clusters.type')
            ->orderBy('project_count', 'desc')
            ->get();

        $locales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->leftJoin('organizations', 'clusters.id', '=', 'organizations.id_cluster_local')
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', 'organizations_dual_projects.id_dual_project', '=', 'dual_projects.id')
            ->where('clusters.type', 'Local')
            ->where('dual_projects.has_report', 1)
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('dual_projects.id_institution', $user->id_institution);
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->join('institutions', 'dual_projects.id_institution', '=', 'institutions.id')
                    ->where('institutions.id_state', $userStateId);
            })
            ->groupBy('clusters.id', 'clusters.name', 'clusters.type')
            ->orderBy('project_count', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'nacionales' => $nacionales,
                'locales' => $locales
            ]
        ], Response::HTTP_OK);
    }
}
