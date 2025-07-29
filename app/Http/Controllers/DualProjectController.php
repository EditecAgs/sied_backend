<?php

namespace App\Http\Controllers;

use App\Http\Requests\DualProjectRequest;
use Illuminate\Http\Request;
use App\Models\DualProject;
use App\Models\DualProjectReport;
use App\Models\OrganizationDualProject;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;


class DualProjectController extends Controller
{
    public function getUnreportedDualProjects()
    {
        $unReports = DualProject::with(['institution:id,name'])->where('has_report', 0)->get();
        return response()->json($unReports, Response::HTTP_OK);
    }

    public function getReportedDualProject()
    {
        $reports = DualProject::with([
            'institution:id,name',
            'dualProjectReports:id,name,dual_project_id,number_men,number_women,period_start,period_end,amount,id_dual_area,status_document,economic_support',
            'dualProjectReports.dualArea:id,name',
            'dualProjectReports.statusDocument:id,name',
            'dualProjectReports.economicSupport:id,name',
            'organizationDualProjects:id,id_organization,id_dual_project',
            'organizationDualProjects.organization:id,name,id_type,id_sector,size,id_cluster,street,external_number,internal_number,neighborhood,postal_code,id_state,id_municipality,country,city,google_maps',
            'organizationDualProjects.organization.type:id,name',
            'organizationDualProjects.organization.sector:id,name',
            'organizationDualProjects.organization.cluster:id,name',
            'organizationDualProjects.organization.state:id,name',
            'organizationDualProjects.organization.municipality:id,name',
            'students:id,control_number,name,lastname,gender,semester,id_institution,id_career,id_specialty,id_dual_project',
            'students.institution:id,name',
            'students.career:id,name',
            'students.specialty:id,name'
        ])
            ->where('has_report', 1)
            ->get();
        return response()->json($reports, Response::HTTP_OK);
    }

    public function getDualProjectById($id)
    {
        $project = DualProject::with(['institution:id,name'])->findOrFail($id);
        if ($project->has_report == 0) {
            return response()->json($project, Response::HTTP_OK);
        } else {
            $project = DualProject::with([
                'institution:id,name',
                'dualProjectReports:id,name,dual_project_id,number_men,number_women,period_start,period_end,amount,id_dual_area,status_document,economic_support',
                'dualProjectReports.dualArea:id,name',
                'dualProjectReports.statusDocument:id,name',
                'dualProjectReports.economicSupport:id,name',
                'organizationDualProjects:id,id_organization,id_dual_project',
                'organizationDualProjects.organization:id,name,id_type,id_sector,size,id_cluster,street,external_number,internal_number,neighborhood,postal_code,id_state,id_municipality,country,city,google_maps',
                'organizationDualProjects.organization.type:id,name',
                'organizationDualProjects.organization.sector:id,name',
                'organizationDualProjects.organization.cluster:id,name',
                'organizationDualProjects.organization.state:id,name',
                'organizationDualProjects.organization.municipality:id,name',
                'students:id,control_number,name,lastname,gender,semester,id_institution,id_career,id_specialty,id_dual_project',
                'students.institution:id,name',
                'students.career:id,name',
                'students.specialty:id,name'
            ])->findOrFail($id);
            return response()->json($project, Response::HTTP_OK);
        }
    }

    public function createDualProject(DualProjectRequest $request)
    {
        $data = $request->validated();
        if ($data['has_report'] == 0) {
            $dualProject = new DualProject();
            $dualProject->has_report = $data['has_report'];
            $dualProject->id_institution = $data['id_institution'];
            $dualProject->save();
            return response()->json($dualProject, Response::HTTP_CREATED);
        } else {
            $dualProject = new DualProject();
            $dualProject->has_report = $data['has_report'];
            $dualProject->id_institution = $data['id_institution'];
            $dualProject->save();

            $dualProjectReport = new DualProjectReport();
            $dualProjectReport->name = $data['name_report'];
            $dualProjectReport->dual_project_id = $dualProject->id;
            $dualProjectReport->number_men = $data['number_men'];
            $dualProjectReport->number_women = $data['number_women'];
            $dualProjectReport->id_dual_area = $data['id_dual_area'];
            $dualProjectReport->period_start = $data['period_start'];
            $dualProjectReport->period_end = $data['period_end'];
            $dualProjectReport->status_document = $data['status_document'];
            $dualProjectReport->economic_support = $data['economic_support'];
            $dualProjectReport->amount = $data['amount'];
            $dualProjectReport->save();

            $organizationDualProject = new OrganizationDualProject();
            $organizationDualProject->id_organization = $data['id_organization'];
            $organizationDualProject->id_dual_project = $dualProject->id;
            $organizationDualProject->save();

            $student = new Student();
            $student->control_number = $data['control_number'];
            $student->name = $data['name_student'];
            $student->lastname = $data['lastname'];
            $student->gender = $data['gender'];
            $student->semester = $data['semester'];
            $student->id_career = $data['id_career'];
            $student->id_specialty = $data['id_specialty'];
            $student->id_dual_project = $dualProject->id;
            $student->save();

            return response()->json([$dualProject], Response::HTTP_CREATED);
        }
    }
}
