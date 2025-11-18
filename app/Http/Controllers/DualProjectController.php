<?php

namespace App\Http\Controllers;

use App\Http\Requests\DualProjectRequest;
use App\Models\DualProject;
use App\Models\DualProjectReport;
use App\Models\DualProjectStudent;
use App\Models\OrganizationDualProject;
use App\Models\Student;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DualProjectController extends Controller
{
    public function getUnreportedDualProjects()
    {
        try {
            $unReports = DualProject::with(['institution:id,name'])
                ->where('has_report', 0)
                ->get();

            return response()->json($unReports, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->handleException($e, 'Error al obtener proyectos no reportados');
        }
    }

    public function getReportedDualProject()
    {
        try {
            $reports = DualProject::with([
                'institution:id,name',
                'dualProjectReports:id,name,dual_project_id,is_concluded,is_hired,hired_observation,qualification,max_qualification,period_start,period_end,period_observation,amount,id_dual_area,status_document,economic_support,dual_type_id,internal_advisor_name,internal_advisor_qualification,external_advisor_name,external_advisor_qualification',
                'dualProjectReports.dualArea:id,name',
                'dualProjectReports.dualType:id,name',
                'dualProjectReports.statusDocument:id,name',
                'dualProjectReports.economicSupport:id,name',
                'dualProjectReports.microCredentials:id,name,organization,description,image',
                'organizationDualProjects:id,id_organization,id_dual_project',
                'organizationDualProjects.organization:id,name,id_type,id_sector,size,id_cluster,street,external_number,internal_number,neighborhood,postal_code,id_state,id_municipality,country,city,google_maps',
                'organizationDualProjects.organization.type:id,name',
                'organizationDualProjects.organization.sector:id,name',
                'organizationDualProjects.organization.cluster:id,name',
                'organizationDualProjects.organization.state:id,name',
                'organizationDualProjects.organization.municipality:id,name',
                'dualProjectStudents.student:id,control_number,name,lastname,gender,semester,id_institution,id_career,id_specialty',
                'dualProjectStudents.student.institution:id,name',
                'dualProjectStudents.student.career:id,name',
                'dualProjectStudents.student.specialty:id,name',
                'dualProjectReports.certifications',
                'dualProjectReports.diplomas',
            ])->where('has_report', 1)->get();

            return response()->json($reports, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->handleException($e, 'Error al obtener proyectos reportados');
        }
    }

    public function getDualProjectById($id)
    {
        try {
            $project = DualProject::with(['institution:id,name'])->findOrFail($id);

            if ($project->has_report == 0) {
                return response()->json($project, Response::HTTP_OK);
            }

            $project = DualProject::with([
                'institution:id,name',
                'dualProjectReports:id,name,dual_project_id,is_concluded,is_hired,hired_observation,qualification,max_qualification,description,period_start,period_end,period_observation,amount,id_dual_area,status_document,economic_support,dual_type_id,internal_advisor_name,internal_advisor_qualification,external_advisor_name,external_advisor_qualification',
                'dualProjectReports.dualArea:id,name',
                'dualProjectReports.dualType:id,name',
                'dualProjectReports.statusDocument:id,name',
                'dualProjectReports.economicSupport:id,name',
                'dualProjectReports.microCredentials:id,name,organization,description,image',
                'dualProjectReports.certifications',
                'dualProjectReports.diplomas',
                'organizationDualProjects:id,id_organization,id_dual_project',
                'organizationDualProjects.organization:id,name,id_type,id_sector,size,id_cluster,street,external_number,internal_number,neighborhood,postal_code,id_state,id_municipality,country,city,google_maps',
                'organizationDualProjects.organization.type:id,name',
                'organizationDualProjects.organization.sector:id,name',
                'organizationDualProjects.organization.cluster:id,name',
                'organizationDualProjects.organization.state:id,name',
                'organizationDualProjects.organization.municipality:id,name',
                'dualProjectStudents.student:id,control_number,name,lastname,gender,semester,id_institution,id_career,id_specialty',
                'dualProjectStudents.student.institution:id,name',
                'dualProjectStudents.student.career:id,name',
                'dualProjectStudents.student.specialty:id,name',
            ])->findOrFail($id);

            return response()->json($project, Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->handleException($e, 'Error al obtener proyecto por ID');
        }
    }

    public function createDualProject(DualProjectRequest $request)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $numberOfStudents = isset($data['students']) && is_array($data['students'])
                ? count($data['students'])
                : 0;

            $dualProject = DualProject::create([
                'has_report' => $data['has_report'],
                'id_institution' => $data['id_institution'],
                'number_student' => $numberOfStudents,
            ]);

            if ($data['has_report'] == 1) {
                $report = $this->createDualProjectReport($data, $dualProject->id);
                $this->createOrganizationDualProject($data, $dualProject->id);
                $this->createStudents($data, $dualProject->id);

                if (! empty($data['micro_credentials'])) {
                    $report->microCredentials()->sync($data['micro_credentials']);
                }
                If (! empty($data['diplomas'])) {
                    $report->diplomas()->sync($data['diplomas']);
                }
                If (! empty($data['certifications'])) {
                    $report->certifications()->sync($data['certifications']);
                }
            }

            DB::commit();

            return response()->json($dualProject, Response::HTTP_CREATED);
        } catch (Exception $e) {
            DB::rollBack();

            return $this->handleException($e, 'Error al crear el proyecto dual');
        }
    }

    public function updateDualProject(DualProjectRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();
            $dualProject = DualProject::findOrFail($id);
            $previousHasReport = $dualProject->has_report;
            $numberOfStudents = isset($data['students']) && is_array($data['students'])
                ? count($data['students'])
                : 0;

            $dualProject->update([
                'has_report' => $data['has_report'],
                'id_institution' => $data['id_institution'],
                'number_student' => $numberOfStudents,
            ]);

            if ($data['has_report'] == 1) {
                if ($previousHasReport == 0) {
                    $report = $this->createDualProjectReport($data, $dualProject->id);
                    $this->createOrganizationDualProject($data, $dualProject->id);
                    $this->createStudents($data, $dualProject->id);
                } else {
                    $report = $this->updateOrCreateDualProjectReport($data, $dualProject->id);
                    $this->updateOrCreateOrganizationDualProject($data, $dualProject->id);
                    $this->updateOrCreateStudents($data, $dualProject->id);
                }

                // 🔗 microcredenciales
                if (! empty($data['micro_credentials'])) {
                    $report->microCredentials()->sync($data['micro_credentials']);
                } else {
                    $report->microCredentials()->detach();
                }
                If (! empty($data['diplomas'])) {
                    $report->diplomas()->sync($data['diplomas']);
                } else {
                    $report->diplomas()->detach();
                }
                If (! empty($data['certifications'])) {
                    $report->certifications()->sync($data['certifications']);
                } else {
                    $report->certifications()->detach();
                }
            }

            DB::commit();

            return response()->json($dualProject, Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            return $this->handleException($e, 'Error al actualizar el proyecto dual');
        }
    }

    public function deleteDualProject($id)
    {
        DB::beginTransaction();

        try {
            $dualProject = DualProject::findOrFail($id);

            if ($dualProject->has_report == 1) {
                $dualProject->dualProjectStudents()->delete();
                $dualProject->organizationDualProjects()->delete();

                foreach ($dualProject->dualProjectReports as $report) {
                    $report->microCredentials()->detach();
                    $report->certifications()->detach();
                    $report->diplomas()->detach();
                    $report->delete();
                }
            }

            $dualProject->delete();
            DB::commit();

            return response()->json(['message' => 'Proyecto eliminado correctamente'], Response::HTTP_OK);
        } catch (Exception $e) {
            DB::rollBack();

            return $this->handleException($e, 'Error al eliminar proyecto');
        }
    }

    protected function handleException(Exception $e, $message = 'Error interno')
    {
        return response()->json([
            'message' => $message,
            'error' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function createDualProjectReport(array $data, int $dualProjectId)
    {
        return DualProjectReport::create([
            'name' => $data['name_report'],
            'dual_project_id' => $dualProjectId,
            'id_dual_area' => $data['id_dual_area'],
            'dual_type_id' => $data['dual_type_id'],
            'description' => $data['description'],
            'period_observation' => $data['period_observation'],
            'hired_observation' => $data ['hired_observation'],
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'status_document' => $data['status_document'],
            'economic_support' => $data['economic_support'],
            'amount' => $data['amount'],
            'qualification' => $data['qualification'] ?? null,
            'is_concluded' => $data['is_concluded'] ?? false,
            'is_hired' => $data['is_hired'] ?? false,
            'max_qualification' => $data['max_qualification'] ?? 10,
            'internal_advisor_name' => $data['internal_advisor_name'] ?? null,
            'internal_advisor_qualification' => $data['internal_advisor_qualification'] ?? null,
            'external_advisor_name' => $data['external_advisor_name'] ?? null,
            'external_advisor_qualification' => $data['external_advisor_qualification'] ?? null,
        ]);
    }

    protected function createOrganizationDualProject(array $data, int $dualProjectId)
    {
        return OrganizationDualProject::create([
            'id_organization' => $data['id_organization'],
            'id_dual_project' => $dualProjectId,
        ]);
    }

    protected function createStudents(array $data, int $dualProjectId)
    {
        if (! isset($data['students']) || ! is_array($data['students'])) {
            return;
        }

        foreach ($data['students'] as $studentData) {
            $student = Student::updateOrCreate(
                ['control_number' => $studentData['control_number']],
                [
                    'name' => $studentData['name_student'],
                    'lastname' => $studentData['lastname'],
                    'gender' => $studentData['gender'],
                    'semester' => $studentData['semester'],
                    'id_institution' => $studentData['id_institution'],
                    'id_career' => $studentData['id_career'],
                    'id_specialty' => $studentData['id_specialty'],
                ]
            );

            DualProjectStudent::updateOrCreate(
                [
                    'id_student' => $student->id,
                    'id_dual_project' => $dualProjectId,
                ]
            );
        }
    }

    protected function updateOrCreateDualProjectReport(array $data, int $dualProjectId)
    {
        return DualProjectReport::updateOrCreate(
            ['dual_project_id' => $dualProjectId],
            [
                'name' => $data['name_report'],
                'id_dual_area' => $data['id_dual_area'],
                'dual_type_id' => $data['dual_type_id'],
                'description' => $data['description'],
                'period_observation' => $data['period_observation'],
                'hired_observation' => $data ['hired_observation'],
                'period_start' => $data['period_start'],
                'period_end' => $data['period_end'],
                'status_document' => $data['status_document'],
                'economic_support' => $data['economic_support'],
                'amount' => $data['amount'],
                'qualification' => $data['qualification'] ?? null,
                'is_concluded' => $data['is_concluded'] ?? false,
                'is_hired' => $data['is_hired'] ?? false,
                'max_qualification' => $data['max_qualification'] ?? 10,
                'internal_advisor_name' => $data['internal_advisor_name'] ?? null,
                'internal_advisor_qualification' => $data['internal_advisor_qualification'] ?? null,
                'external_advisor_name' => $data['external_advisor_name'] ?? null,
                'external_advisor_qualification' => $data['external_advisor_qualification'] ?? null,
            ]
        );
    }

    protected function updateOrCreateOrganizationDualProject(array $data, int $dualProjectId)
    {
        OrganizationDualProject::updateOrCreate(
            ['id_dual_project' => $dualProjectId],
            ['id_organization' => $data['id_organization']]
        );
    }

    protected function updateOrCreateStudents(array $data, int $dualProjectId)
    {
        if (! isset($data['students']) || ! is_array($data['students'])) {
            DualProjectStudent::where('id_dual_project', $dualProjectId)->delete();

            return;
        }

        $incomingStudentIds = [];

        foreach ($data['students'] as $studentData) {
            $student = Student::updateOrCreate(
                ['control_number' => $studentData['control_number']],
                [
                    'name' => $studentData['name_student'],
                    'lastname' => $studentData['lastname'],
                    'gender' => $studentData['gender'],
                    'semester' => $studentData['semester'],
                    'id_institution' => $studentData['id_institution'],
                    'id_career' => $studentData['id_career'],
                    'id_specialty' => $studentData['id_specialty'],
                ]
            );

            DualProjectStudent::updateOrCreate(
                [
                    'id_student' => $student->id,
                    'id_dual_project' => $dualProjectId,
                ]
            );

            $incomingStudentIds[] = $student->id;
        }

        DualProjectStudent::where('id_dual_project', $dualProjectId)
            ->whereNotIn('id_student', $incomingStudentIds)
            ->delete();
    }
}
