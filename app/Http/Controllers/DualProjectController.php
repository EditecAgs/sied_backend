<?php

namespace App\Http\Controllers;

use App\Http\Requests\DualProjectRequest;
use App\Models\DualProject;
use App\Models\DualProjectReport;
use App\Models\DualProjectStudent;
use App\Models\OrganizationDualProject;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DualProjectController extends Controller
{
    public function getAllDualProjects(Request $request)
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            $page = (int) $request->input('page', 1);
            $filters = $request->input('filters', []);
            $user = Auth::user();


            $query = DualProject::query();

            if ($user->type != 0 && $user->id_institution) {
                $query->where('id_institution', $user->id_institution);
            }

            foreach ($filters as $field => $value) {
                if (empty($value)) continue;

                switch ($field) {
                    case 'status':
                        $query->where('has_report', strtolower($value) === 'completado' ? 1 : 0);
                        break;

                    case 'students':
                        $query->whereHas('dualProjectStudents.student', function ($q) use ($value) {
                            $q->where('name', 'like', "%{$value}%")
                                ->orWhere('lastname', 'like', "%{$value}%");
                        });
                        break;

                    case 'institution_name':
                        $query->whereHas('institution', fn ($q) =>
                        $q->where('name', 'like', "%{$value}%")
                        );
                        break;

                    case 'organization_name':
                        $query->whereHas('organizationDualProjects.organization', fn ($q) =>
                        $q->where('name', 'like', "%{$value}%")
                        );
                        break;
                }
            }

            $paginator = $query
                ->orderBy('id', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $paginator->load([
                'institution:id,name,city,id_state',
                'institution.state:id,name',
                'dualProjectReports.dualArea:id,name',
                'dualProjectReports.dualType:id,name',
                'dualProjectReports.statusDocument:id,name',
                'dualProjectReports.economicSupport:id,name',
                'dualProjectReports.microCredentials:id,name,organization,description,image,type,hours',
                'dualProjectReports.certifications:id,name,organization,description,image,type,hours',
                'dualProjectReports.diplomas:id,name,organization,description,image,type,hours',
                'dualProjectReports.benefitTypes' => fn ($q) =>
                $q->select('benefit_types.id', 'benefit_types.name')
                    ->withPivot('quantity'),
                'dualProjectStudents.student:id,name,lastname,id_career,id_specialty',
                'dualProjectStudents.student.career:id,name',
                'dualProjectStudents.student.specialty:id,name',
            ]);

            $organizationRelations = OrganizationDualProject::whereIn(
                'id_dual_project',
                $paginator->pluck('id')
            )->with([
                'organization:id,name,city,id_state,id_municipality,id_type,id_sector',
                'organization.type:id,name',
                'organization.sector:id,name',
                'organization.state:id,name',
                'organization.municipality:id,name',
            ])->get()->groupBy('id_dual_project');

            $data = $paginator->getCollection()->map(function ($project) use ($organizationRelations) {

                $organizationData = $organizationRelations[$project->id][0]->organization ?? null;
                $institutionData = $project->institution;
                $reportData = $project->dualProjectReports;

                $data = [
                    'id' => $project->id,
                    'has_report' => $project->has_report,
                    'institution_id' => $institutionData->id ?? null,
                    'institution_name' => $institutionData->name ?? 'Por definir',
                    'institution_state' => $institutionData->state->name ?? 'Por definir',
                    'institution_city' => $institutionData->city ?? 'Por definir',
                    'organization_name' => $organizationData->name ?? 'Por definir',
                    'organization_state' => $organizationData->state->name ?? 'Por definir',
                    'organization_city' => $organizationData->city ?? ($organizationData->municipality->name ?? 'Por definir'),
                    'organization_sector' => $organizationData->sector->name ?? 'Por definir',
                    'organization_type' => $organizationData->type->name ?? 'Por definir',
                ];

                if ($project->has_report && $reportData) {

                    $certifications = $reportData->certifications->map(fn ($c) => [
                        'id' => $c->id,
                        'name' => $c->name ?? 'Sin nombre',
                        'type' => 'Certificación',
                        'organization' => $c->organization ?? 'Sin organización',
                        'description' => $c->description ?? '',
                        'image' => $c->image ?? null,
                        'credential_type' => $c->type ?? 'Certificación',
                        'hours' => $c->hours ?? 0,
                    ])->toArray();

                    $microcredentials = $reportData->microCredentials->map(fn ($m) => [
                        'id' => $m->id,
                        'name' => $m->name ?? 'Sin nombre',
                        'type' => 'Microcredencial',
                        'organization' => $m->organization ?? 'Sin organización',
                        'description' => $m->description ?? '',
                        'image' => $m->image ?? null,
                        'credential_type' => $m->type ?? 'Microcredencial',
                        'hours' => $m->hours ?? 0,
                    ])->toArray();

                    $diplomas = $reportData->diplomas->map(fn ($d) => [
                        'id' => $d->id,
                        'name' => $d->name ?? 'Sin nombre',
                        'type' => 'Diploma',
                        'organization' => $d->organization ?? 'Sin organización',
                        'description' => $d->description ?? '',
                        'image' => $d->image ?? null,
                        'credential_type' => $d->type ?? 'Diploma',
                        'hours' => $d->hours ?? 0,
                    ])->toArray();

                    $benefitTypes = $reportData->benefitTypes->map(fn ($b) => [
                        'id' => $b->id,
                        'name' => $b->name ?? 'Sin nombre',
                        'quantity' => $b->pivot->quantity ?? 0
                    ])->toArray();

                    $studentNames = '';
                    $rawStudents = [];

                    if ($project->dualProjectStudents->isNotEmpty()) {
                        $studentNames = $project->dualProjectStudents
                            ->map(fn ($ds) =>
                                trim(($ds->student->name ?? '') . ' ' . ($ds->student->lastname ?? ''))
                                .' – '.($ds->student->career->name ?? 'Sin carrera')
                                .' – '.($ds->student->specialty->name ?? 'Sin especialidad')
                            )->join(', ');

                        $rawStudents = $project->dualProjectStudents->map(fn ($ds) => [
                            'name' => trim(($ds->student->name ?? '') . ' ' . ($ds->student->lastname ?? '')),
                            'career' => $ds->student->career->name ?? 'Sin carrera',
                            'specialty' => $ds->student->specialty->name ?? 'Sin especialidad',
                        ])->toArray();
                    }

                    $data = array_merge($data, [
                        'project_name' => $reportData->name ?? 'Por definir',
                        'area' => $reportData->dualArea->name ?? 'Por definir',
                        'education_type' => $reportData->dualType->name ?? 'Por definir',
                        'agreement' => $reportData->statusDocument->name ?? 'Por definir',
                        'project_status' => $reportData->is_concluded ? 'Concluido' : 'En progreso',
                        'grade' => $reportData->qualification ?? 'N/A',
                        'certifications' => $certifications,
                        'microcredentials' => $microcredentials,
                        'benefit_types' => $benefitTypes,
                        'diplomas' => $diplomas,
                        'student_name' => $studentNames,
                        'raw_students' => $rawStudents,
                    ]);
                }

                return $data;
            });


            $paginator->setCollection($data);

            /* ==================================================
             | RESPUESTA FINAL (META CORRECTA)
             ================================================== */
            return response()->json([
                'data' => $paginator->items(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'from' => $paginator->firstItem(),
                    'to' => $paginator->lastItem(),
                ]
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener proyectos duales',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

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
            'institution:id,name,city,id_state',
            'institution.state:id,name',
            'dualProjectReports:id,name,dual_project_id,is_concluded,is_hired,hired_observation,qualification,max_qualification,period_start,period_end,period_observation,amount,id_dual_area,status_document,economic_support,dual_type_id,internal_advisor_name,internal_advisor_qualification,external_advisor_name,external_advisor_qualification',
            'dualProjectReports.dualArea:id,name',
            'dualProjectReports.dualType:id,name',
            'dualProjectReports.statusDocument:id,name',
            'dualProjectReports.economicSupport:id,name',
            'dualProjectReports.microCredentials:id,name,organization,description,image',
            'dualProjectReports.certifications:id,name,organization,description,image,type,hours',
            'dualProjectReports.diplomas:id,name,organization,description,image,type,hours',
            // CORRECCIÓN: Agregar withPivot('quantity') en benefitTypes
            'dualProjectReports.benefitTypes' => function($query) {
                $query->withPivot('quantity')->select('benefit_types.id', 'benefit_types.name');
            },
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
            // CORRECCIÓN: Agregar withPivot('quantity') en benefitTypes
            'dualProjectReports.benefitTypes' => function($query) {
                $query->withPivot('quantity')->select('benefit_types.id', 'benefit_types.name');
            },
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

        // LOG DE DEPURACIÓN
        \Log::info('=== CREANDO DUAL PROJECT ===');
        \Log::info('Datos recibidos:', $data);
        \Log::info('Benefit types recibidos:', ['benefit_types' => $data['benefit_types'] ?? 'No recibido']);

        $numberOfStudents = isset($data['students']) && is_array($data['students'])
            ? count($data['students'])
            : 0;

        $dualProject = DualProject::create([
            'has_report' => $data['has_report'],
            'id_institution' => $data['id_institution'],
            'number_student' => $numberOfStudents,
        ]);

        \Log::info('DualProject creado ID: ' . $dualProject->id);

        if ($data['has_report'] == 1) {
            $report = $this->createDualProjectReport($data, $dualProject->id);
            $this->createOrganizationDualProject($data, $dualProject->id);
            $this->createStudents($data, $dualProject->id);

            \Log::info('Report creado ID: ' . $report->id);

            if (! empty($data['micro_credentials'])) {
                $report->microCredentials()->sync($data['micro_credentials']);
                \Log::info('Microcredenciales sincronizadas');
            }

            if (! empty($data['diplomas'])) {
                $report->diplomas()->sync($data['diplomas']);
                \Log::info('Diplomas sincronizados');
            }

            if (! empty($data['certifications'])) {
                $report->certifications()->sync($data['certifications']);
                \Log::info('Certificaciones sincronizadas');
            }

            if (! empty($data['benefit_types'])) {
            \Log::info('Sincronizando benefit_types:', $data['benefit_types']);

            // TRANSFORMAR EL ARRAY PARA QUE SEA COMPATIBLE CON sync()
            $benefitTypesSync = [];

            foreach ($data['benefit_types'] as $benefit) {
                // La KEY debe ser el ID del benefit type
                // El VALUE debe ser un array con los campos adicionales
                $benefitTypesSync[$benefit['id']] = ['quantity' => $benefit['quantity']];
            }

            \Log::info('Benefit types transformados para sync:', $benefitTypesSync);

            $report->benefitTypes()->sync($benefitTypesSync);
            \Log::info('Benefit types sincronizados');
            } else {
            \Log::info('No hay benefit_types para sincronizar');
            }
        }

        DB::commit();

        \Log::info('=== TRANSACCIÓN COMPLETADA EXITOSAMENTE ===');

        return response()->json($dualProject, Response::HTTP_CREATED);
    } catch (Exception $e) {
        DB::rollBack();

        // LOG DEL ERROR
        \Log::error('Error al crear el proyecto dual: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());

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
                If (! empty($data['benefit_types'])) {
                    // TRANSFORMAR EL ARRAY PARA QUE SEA COMPATIBLE CON sync()
                    $benefitTypesSync = [];

                    foreach ($data['benefit_types'] as $benefit) {
                        $benefitTypesSync[$benefit['id']] = ['quantity' => $benefit['quantity']];
                    }

                    $report->benefitTypes()->sync($benefitTypesSync);
                } else {
                    $report->benefitTypes()->detach();
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

            DualProjectStudent::where('id_dual_project', $id)->delete();
            OrganizationDualProject::where('id_dual_project', $id)->delete();

            $report = DualProjectReport::where('dual_project_id', $id)->first();

            if ($report) {
                $report->microCredentials()->detach();
                $report->certifications()->detach();
                $report->diplomas()->detach();
                $report->benefitTypes()->detach();

                $report->delete();
            }

            $dualProject->delete();

            DB::commit();

            return response()->json([
                'message' => 'Proyecto eliminado correctamente'
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ], 500);
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
