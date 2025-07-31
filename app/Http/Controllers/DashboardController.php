<?php

namespace App\Http\Controllers;

use App\Models\DualArea;
use App\Models\DualProject;
use App\Models\Institution;
use App\Models\Sector;
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
            ->groupBy('year', 'month_number', 'month_year', 'month_name') // Todos los campos no agregados
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
            ->orderBy('dual_areas.name')
            ->get();

        return response()->json(['data' => $results], Response::HTTP_OK);
    }

    public function countProjectsBySector()
    {
        $results = Sector::leftJoin('organizations', 'sectors.id', '=', 'organizations.id_sector')
            ->leftJoin('organizations_dual_projects', 'organizations.id', '=', 'organizations_dual_projects.id_organization')
            ->leftJoin('dual_projects', function ($join) {
                $join->on('dual_projects.id', '=', 'organizations_dual_projects.id_dual_project')
                    ->where('dual_projects.has_report', 1);
            })
            ->select(
                'sectors.id',
                'sectors.name as sector_name',
                DB::raw('COUNT(DISTINCT dual_projects.id) as project_count')
            )
            ->groupBy('sectors.id', 'sectors.name')
            ->orderBy('sectors.name')
            ->get();

        return response()->json(['data' => $results], Response::HTTP_OK);
    }

    public function getInstitutionProjectPercentage()
    {
        $results = Institution::leftJoin('dual_projects', function ($join) {
            $join->on('institutions.id', '=', 'dual_projects.id_institution')
                ->where('dual_projects.has_report', 1);
        })
            ->select(
                'institutions.id',
                'institutions.name as institution_name',
                DB::raw('COUNT(dual_projects.id) as project_count')
            )
            ->groupBy('institutions.id', 'institutions.name')
            ->orderBy('institutions.name')
            ->get();

        $totalProjects = $results->sum('project_count');
        $results = $results->map(function ($item) use ($totalProjects) {
            $percentage = $totalProjects > 0 ? round(($item->project_count / $totalProjects) * 100, 2) : 0;

            return [
                'id' => $item->id,
                'institution_name' => $item->institution_name,
                'project_count' => $item->project_count,
                'percentage' => $percentage,
            ];
        });

        return response()->json(['total_projects' => $totalProjects, 'data' => $results], Response::HTTP_OK);
    }
}
