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

        $organization = Organization::when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
            $query->whereHas('dualProjects', function($q) use ($user) {
                $q->where('dual_projects.id_institution', $user->id_institution);
            });
        })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->whereHas('dualProjects.institution', function($q) use ($userStateId) {
                    $q->where('id_state', $userStateId);
                });
            })
            ->count();

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

        $counts = Organization::select('scope', DB::raw('COUNT(*) as total'))
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->whereHas('dualProjects', function($q) use ($user) {
                    $q->where('dual_projects.id_institution', $user->id_institution);
                });
            })
            ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
                $query->whereHas('dualProjects.institution', function($q) use ($userStateId) {
                    $q->where('id_state', $userStateId);
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

        $nacionales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COUNT(organizations.id) as organization_count')
        )
            ->leftJoin('organizations', function($join) use ($filtersAdd, $user, $userStateId) {
                $join->on('clusters.id', '=', 'organizations.id_cluster')
                    ->where('clusters.type', 'Nacional');

                if ($filtersAdd == 1 && $user && $user->id_institution) {
                    $join->whereHas('dualProjects', function($q) use ($user) {
                        $q->where('dual_projects.id_institution', $user->id_institution);
                    });
                } elseif ($filtersAdd == 2 && $userStateId) {
                    $join->whereHas('dualProjects.institution', function($q) use ($userStateId) {
                        $q->where('id_state', $userStateId);
                    });
                }
            })
            ->where('clusters.type', 'Nacional')
            ->groupBy('clusters.id', 'clusters.name', 'clusters.type')
            ->orderBy('organization_count', 'desc')
            ->get();

        $locales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COUNT(organizations.id) as organization_count')
        )
            ->leftJoin('organizations', function($join) use ($filtersAdd, $user, $userStateId) {
                $join->on('clusters.id', '=', 'organizations.id_cluster_local')
                    ->where('clusters.type', 'Local');

                if ($filtersAdd == 1 && $user && $user->id_institution) {
                    $join->whereHas('dualProjects', function($q) use ($user) {
                        $q->where('dual_projects.id_institution', $user->id_institution);
                    });
                } elseif ($filtersAdd == 2 && $userStateId) {
                    $join->whereHas('dualProjects.institution', function($q) use ($userStateId) {
                        $q->where('id_state', $userStateId);
                    });
                }
            })
            ->where('clusters.type', 'Local')
            ->groupBy('clusters.id', 'clusters.name', 'clusters.type')
            ->orderBy('organization_count', 'desc')
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
