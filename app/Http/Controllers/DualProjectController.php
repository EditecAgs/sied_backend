<?php

namespace App\Http\Controllers;

use App\Http\Requests\DualProjectRequest;
use App\Models\DualProject;
use App\Models\DualProjectReport;
use App\Models\OrganizationDualProject;
use App\Models\DualProjectStudent;
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
                'dualProjectReports:id,name,dual_project_id,is_concluded,is_hired,qualification,advisor,period_start,period_end,amount,id_dual_area,status_document,economic_support,dual_type_id',
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
                'dualProjectReports:id,name,dual_project_id,is_concluded,is_hired,qualification,advisor,period_start,period_end,amount,id_dual_area,status_document,economic_support,dual_type_id',
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

            $dualProject = DualProject::create([
                'has_report' => $data['has_report'],
                'id_institution' => $data['id_institution'],
                'number_student' => $numberOfStudents,
            ]);

            if ($data['has_report'] == 1) {
                $this->createDualProjectReport($data, $dualProject->id);
                $this->createOrganizationDualProject($data, $dualProject->id);
                $this->createStudents($data, $dualProject->id);
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

            $dualProject->update([
                'has_report' => $data['has_report'],
                'id_institution' => $data['id_institution'],
                'number_student' => $numberOfStudents,
            ]);

            if ($data['has_report'] == 1) {
                if ($previousHasReport == 0) {
                    $this->createDualProjectReport($data, $dualProject->id);
                    $this->createOrganizationDualProject($data, $dualProject->id);
                    $this->createStudents($data, $dualProject->id);
                } else {
                    $this->updateOrCreateDualProjectReport($data, $dualProject->id);
                    $this->updateOrCreateOrganizationDualProject($data, $dualProject->id);
                    $this->updateOrCreateStudents($data, $dualProject->id);
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
                $dualProject->dualProjectReports()->delete();
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
            'trace' => $e->getTrace()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function createDualProjectReport(array $data, int $dualProjectId)
    {
        $report = DualProjectReport::create([
            'name' => $data['name_report'],
            'dual_project_id' => $dualProjectId,
            'id_dual_area' => $data['id_dual_area'],
            'dual_type_id' => $data['dual_type_id'],
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'status_document' => $data['status_document'],
            'economic_support' => $data['economic_support'],
            'amount' => $data['amount'],
            'qualification' => $data['qualification'] ?? null,
            'advisor' => $data['advisor'] ?? null,
            'is_concluded' => $data['is_concluded'] ?? false,
            'is_hired' => $data['is_hired'] ?? false,
        ]);

        if (isset($data['micro_credentials']) && is_array($data['micro_credentials'])) {
            $report->microCredentials()->sync($data['micro_credentials']);
        }

        return $report;
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
        if (!isset($data['students']) || !is_array($data['students'])) return;

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
                    'id_dual_project' => $dualProjectId
                ]
            );
        }
    }

    protected function updateOrCreateDualProjectReport(array $data, int $dualProjectId)
    {
        $report = DualProjectReport::updateOrCreate(
            ['dual_project_id' => $dualProjectId],
            [
                'name' => $data['name_report'],
                'id_dual_area' => $data['id_dual_area'],
                'dual_type_id' => $data['dual_type_id'],
                'period_start' => $data['period_start'],
                'period_end' => $data['period_end'],
                'status_document' => $data['status_document'],
                'economic_support' => $data['economic_support'],
                'amount' => $data['amount'],
                'qualification' => $data['qualification'] ?? null,
                'advisor' => $data['advisor'] ?? null,
                'is_concluded' => $data['is_concluded'] ?? false,
                'is_hired' => $data['is_hired'] ?? false,
            ]
        );

        if (isset($data['micro_credentials']) && is_array($data['micro_credentials'])) {
            $report->microCredentials()->sync($data['micro_credentials']);
        }

        return $report;
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
        if (!isset($data['students']) || !is_array($data['students'])) return;

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
                    'id_dual_project' => $dualProjectId
                ]
            );
        }
    }
}
