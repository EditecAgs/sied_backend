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
    public function countDualProjectCompleted()
    {
        $count = DualProject::with(['dualProjectReports', 'organizationDualProjects', 'students'])
            ->where('has_report', 1)
            ->count();

        return response()->json(['count' => $count], Response::HTTP_OK);
    }

    public function countRegisteredStudents()
    {
        $students = Student::count();

        return response()->json(['count' => $students], Response::HTTP_OK);
    }

    public function countRegisteredOrganizations()
    {
        $organization = Organization::count();

        return response()->json(['count' => $organization], Response::HTTP_OK);
    }

    public function countOrganizationsByScope()
    {
        $counts = Organization::select('scope', DB::raw('COUNT(*) as total'))
            ->groupBy('scope')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $counts,
        ], Response::HTTP_OK);
    }

    public function countProjectsByMonth()
    {
        $results = DualProject::join('dual_project_reports', 'dual_projects.id', '=', 'dual_project_reports.dual_project_id')
            ->where('dual_projects.has_report', 1)
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

    public function countProjectsByArea()
    {
        $results = DualArea::leftJoin('dual_project_reports', 'dual_areas.id', '=', 'dual_project_reports.id_dual_area')
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
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $results->items(), 'pagination' => ['total' => $results->total(), 'per_page' => $results->perPage(), 'current_page' => $results->currentPage(), 'last_page' => $results->lastPage()]], Response::HTTP_OK);
    }

    public function countProjectsBySector()
    {
        $results = Sector::select(
            'sectors.id',
            'sectors.name as sector_name',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->leftJoin('organizations', 'sectors.id', '=', 'organizations.id_sector')
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                    ->where('dual_projects.has_report', 1);
            })
            ->groupBy('sectors.id', 'sectors.name')
            ->orderByDesc('project_count')
            ->paginate(10);

        return response()->json(['success' => true, 'data' => $results->items(), 'pagination' => ['total' => $results->total(), 'per_page' => $results->perPage(), 'current_page' => $results->currentPage(), 'last_page' => $results->lastPage()]], Response::HTTP_OK);
    }

    public function countProjectsBySectorPlanMexico()
    {
        $results = Sector::select(
            'sectors.id',
            'sectors.name as sector_name',
            'sectors.plan_mexico',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->leftJoin('organizations', 'sectors.id', '=', 'organizations.id_sector')
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                    ->where('dual_projects.has_report', 1);
            })
            ->where('sectors.plan_mexico', 1)
            ->groupBy('sectors.id', 'sectors.name', 'sectors.plan_mexico')
            ->orderByDesc('project_count')
            ->paginate(11);

        return response()->json(['success' => true, 'data' => $results->items(), 'pagination' => ['total' => $results->total(), 'per_page' => $results->perPage(), 'current_page' => $results->currentPage(), 'last_page' => $results->lastPage()]], Response::HTTP_OK);
    }

    public function countProjectsByEconomicSupport()
    {
        $results = EconomicSupport::select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
        )
            ->leftJoin('dual_project_reports', 'economic_supports.id', '=', 'dual_project_reports.economic_support')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'dual_project_reports.dual_project_id')
                    ->where('dual_projects.has_report', 1);
            })
            ->groupBy('economic_supports.id', 'economic_supports.name')
            ->orderByDesc('project_count')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
        ], Response::HTTP_OK);
    }

    public function averageAmountByEconomicSupport()
    {
        $results = EconomicSupport::select(
            'economic_supports.id',
            'economic_supports.name as support_name',
            DB::raw('ROUND(AVG(dual_project_reports.amount), 2) as average_amount')
        )
            ->leftJoin('dual_project_reports', 'economic_supports.id', '=', 'dual_project_reports.economic_support')
            ->groupBy('economic_supports.id', 'economic_supports.name')
            ->orderByDesc('average_amount')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results,
        ], Response::HTTP_OK);
    }

    public function getInstitutionProjectPercentage()
    {
        $totalProjects = DualProject::where('has_report', 1)->count();

        $results = Institution::leftJoin('dual_projects', function ($join) {
            $join->on('institutions.id', '=', 'dual_projects.id_institution')
                ->where('dual_projects.has_report', 1);
        })
            ->select(
                'institutions.id',
                'institutions.name as institution_name',
                'institutions.image', // ✅ añadimos la imagen
                DB::raw('COUNT(dual_projects.id) as project_count'),
                DB::raw('CASE WHEN ' . $totalProjects . ' > 0
                 THEN ROUND(COUNT(dual_projects.id) * 100.0 / ' . $totalProjects . ', 2)
                 ELSE 0 END as percentage')
            )
            ->groupBy('institutions.id', 'institutions.name', 'institutions.image') // ✅ incluimos en el groupBy
            ->orderByDesc('percentage')
            ->get();

        return response()->json([
            'success' => true,
            'total_projects' => $totalProjects,
            'data' => $results,
        ], Response::HTTP_OK);
    }

    public function countProjectsByDualType()
    {
        $results = \App\Models\DualType::leftJoin('dual_project_reports', 'dual_types.id', '=', 'dual_project_reports.dual_type_id')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'dual_project_reports.dual_project_id')
                    ->where('dual_projects.has_report', 1);
            })
            ->select(
                'dual_types.id',
                'dual_types.name as dual_type',
                DB::raw('COUNT(DISTINCT dual_projects.id) as total')
            )
            ->groupBy('dual_types.id', 'dual_types.name')
            ->orderByDesc('total')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $results
        ], Response::HTTP_OK);
    }

    public function countOrganizationsByCluster()
    {
        $nacionales = \App\Models\Cluster::select(
            'clusters.id',
            'clusters.name as cluster_name',
            'clusters.type',
            DB::raw('COUNT(organizations.id) as organization_count')
        )
            ->leftJoin('organizations', function($join) {
                $join->on('clusters.id', '=', 'organizations.id_cluster')
                    ->where('clusters.type', 'Nacional');
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
            ->leftJoin('organizations', function($join) {
                $join->on('clusters.id', '=', 'organizations.id_cluster_local')
                    ->where('clusters.type', 'Local');
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
            ->when($filtersAdd == 1 && $user && $user->id_institution, function($query) use ($user) {
                $query->where('dual_projects.id_institution', $user->id_institution);
        })
                ->when($filtersAdd == 2 && $userStateId, function($query) use ($userStateId) {
            $query->where('institutions.id_state', $userStateId);
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
