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
            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);
            $filters = $request->input('filters', []);
            $user = Auth::user();

            $query = DualProject::query();

            if ($user->type != 0 && $user->id_institution) {
                $query->where('id_institution', $user->id_institution);
            }

            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    if (!empty($value)) {
                        switch ($field) {
                            case 'status':
                                if (strtolower($value) === 'completado') {
                                    $query->where('has_report', 1);
                                } elseif (strtolower($value) === 'incompleto') {
                                    $query->where('has_report', 0);
                                }
                                break;

                            case 'students':
                                $query->whereHas('dualProjectStudents.student', function ($q) use ($value) {
                                    $q->where(function ($subQuery) use ($value) {
                                        $subQuery->where('name', 'like', "%{$value}%")
                                            ->orWhere('lastname', 'like', "%{$value}%");
                                    });
                                });
                                break;

                            case 'institution_name':
                                $query->whereHas('institution', function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%");
                                });
                                break;
                            case 'institution_state':
                                $query->whereHas('institution.state', function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%");
                                });
                                break;
                            case 'institution_city':
                                $query->whereHas('institution', function ($q) use ($value) {
                                    $q->where('city', 'like', "%{$value}%");
                                });
                                break;

                            case 'organization_name':
                                $query->whereHas('organizationDualProjects.organization', function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%");
                                });
                                break;
                            case 'organization_state':
                                $query->whereHas('organizationDualProjects.organization.state', function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%");
                                });
                                break;
                            case 'organization_city':
                                $query->whereHas('organizationDualProjects.organization', function ($q) use ($value) {
                                    $q->where('city', 'like', "%{$value}%");
                                });
                                break;
                            case 'organization_sector':
                                $query->whereHas('organizationDualProjects.organization.sector', function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%");
                                });
                                break;
                            case 'organization_type':
                                $query->whereHas('organizationDualProjects.organization.type', function ($q) use ($value) {
                                    $q->where('name', 'like', "%{$value}%");
                                });
                                break;

                            default:
                                break;
                        }
                    }
                }
            }

            $query->orderBy('id', 'desc');
            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            // Cargar relaciones con todas las columnas existentes
            $paginator->load([
                'institution:id,name,city,id_state',
                'institution.state:id,name',
                'dualProjectReports:id,name,dual_project_id,is_concluded,is_hired,hired_observation,qualification,max_qualification,period_start,period_end,period_observation,amount,id_dual_area,status_document,economic_support,dual_type_id,internal_advisor_name,internal_advisor_qualification,external_advisor_name,external_advisor_qualification',
                'dualProjectReports.dualArea:id,name',
                'dualProjectReports.dualType:id,name',
                'dualProjectReports.statusDocument:id,name',
                'dualProjectReports.economicSupport:id,name',
                // Cargar todas las columnas de cada tabla
                'dualProjectReports.microCredentials:id,name,organization,description,image,type,hours',
                'dualProjectReports.certifications:id,name,organization,description,image,type,hours',
                'dualProjectReports.diplomas:id,name,organization,description,image,type,hours',
                'dualProjectStudents.student:id,control_number,name,lastname,gender,semester,id_institution,id_career,id_specialty',
                'dualProjectStudents.student.institution:id,name',
                'dualProjectStudents.student.career:id,name',
                'dualProjectStudents.student.specialty:id,name',
            ]);

            $projectIds = $paginator->pluck('id')->toArray();
            $organizationRelations = OrganizationDualProject::whereIn('id_dual_project', $projectIds)
                ->with([
                    'organization:id,name,id_type,id_sector,size,id_cluster,street,external_number,internal_number,neighborhood,postal_code,id_state,id_municipality,country,city,google_maps',
                    'organization.type:id,name',
                    'organization.sector:id,name',
                    'organization.cluster:id,name',
                    'organization.state:id,name',
                    'organization.municipality:id,name',
                ])
                ->get()
                ->groupBy('id_dual_project');

            $transformedData = $paginator->map(function ($project) use ($organizationRelations) {
                $institutionData = $project->institution;

                $organizationData = null;
                if (isset($organizationRelations[$project->id]) && $organizationRelations[$project->id]->isNotEmpty()) {
                    $orgRelation = $organizationRelations[$project->id]->first();
                    $organizationData = $orgRelation->organization;
                }

                // Datos base para todos los proyectos
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

                // Si tiene reporte, agregar información adicional
                if ($project->has_report && $project->dualProjectReports) {
                    $reportData = $project->dualProjectReports;

                    // Obtener certificaciones
                    $certifications = $reportData->certifications ?? collect();
                    $formattedCertifications = $certifications->map(function ($cert) {
                        return [
                            'id' => $cert->id,
                            'name' => $cert->name ?? 'Sin nombre',
                            'type' => 'Certificación',
                            'organization' => $cert->organization ?? 'Sin organización',
                            'description' => $cert->description ?? '',
                            'image' => $cert->image ?? null,
                            'credential_type' => $cert->type ?? 'Certificación',
                            'hours' => $cert->hours ?? 0,
                        ];
                    })->toArray();

                    // Obtener microcredenciales
                    $microCredentials = $reportData->microCredentials ?? collect();
                    $formattedMicroCredentials = $microCredentials->map(function ($micro) {
                        return [
                            'id' => $micro->id,
                            'name' => $micro->name ?? 'Sin nombre',
                            'type' => 'Microcredencial',
                            'organization' => $micro->organization ?? 'Sin organización',
                            'description' => $micro->description ?? '',
                            'image' => $micro->image ?? null,
                            'credential_type' => $micro->type ?? 'Microcredencial',
                            'hours' => $micro->hours ?? 0,
                        ];
                    })->toArray();

                    // Obtener diplomas/certificados
                    $diplomas = $reportData->diplomas ?? collect();
                    $formattedCertificates = $diplomas->map(function ($diploma) {
                        return [
                            'id' => $diploma->id,
                            'name' => $diploma->name ?? 'Sin nombre',
                            'type' => 'Diploma',
                            'organization' => $diploma->organization ?? 'Sin organización',
                            'description' => $diploma->description ?? '',
                            'image' => $diploma->image ?? null,
                            'credential_type' => $diploma->type ?? 'Diploma',
                            'hours' => $diploma->hours ?? 0,
                        ];
                    })->toArray();

                    // Obtener información de estudiantes
                    $studentNames = '';
                    $rawStudents = [];

                    if ($project->dualProjectStudents && $project->dualProjectStudents->isNotEmpty()) {
                        $studentNames = $project->dualProjectStudents
                            ->map(function ($dualStudent) {
                                $student = $dualStudent->student;
                                $name = trim(($student->name ?? '') . ' ' . ($student->lastname ?? ''));
                                $career = $student->career->name ?? 'Sin carrera';
                                $specialty = $student->specialty->name ?? 'Sin especialidad';
                                return "{$name} – {$career} – {$specialty}";
                            })
                            ->join(', ');

                        $rawStudents = $project->dualProjectStudents->map(function ($dualStudent) {
                            return [
                                'name' => trim(($dualStudent->student->name ?? '') . ' ' . ($dualStudent->student->lastname ?? '')),
                                'career' => $dualStudent->student->career->name ?? 'Sin carrera',
                                'specialty' => $dualStudent->student->specialty->name ?? 'Sin especialidad',
                            ];
                        })->toArray();
                    }

                    // Agregar datos del reporte
                    $data = array_merge($data, [
                        'project_name' => $reportData->name ?? 'Por definir',
                        'area' => $reportData->dualArea->name ?? 'Por definir',
                        'education_type' => $reportData->dualType->name ?? 'Por definir',
                        'agreement' => $reportData->statusDocument->name ?? 'Por definir',
                        'project_status' => $reportData->is_concluded == 1 ? 'Concluido' : 'En progreso',
                        'grade' => $reportData->qualification ?? 'N/A',
                        'certifications' => $formattedCertifications,
                        'microcredentials' => $formattedMicroCredentials,
                        'certificates' => $formattedCertificates,
                        'status_document' => $reportData->statusDocument->name ?? 'Por definir',
                        'student_name' => $studentNames,
                        'raw_students' => $rawStudents,
                    ]);
                } else {
                    // Si no tiene reporte, valores por defecto
                    $data = array_merge($data, [
                        'project_name' => 'Por definir',
                        'area' => 'Por definir',
                        'education_type' => 'Por definir',
                        'agreement' => 'Por definir',
                        'project_status' => 'Por definir',
                        'grade' => 'N/A',
                        'certifications' => [],
                        'microcredentials' => [],
                        'certificates' => [],
                        'status_document' => 'Por definir',
                        'student_name' => '',
                        'raw_students' => [],
                    ]);
                }

                return $data;
            });

            // Aplicar filtros del frontend en memoria
            $filteredData = collect($transformedData);

            if (!empty($filters)) {
                foreach ($filters as $field => $value) {
                    if (!empty($value) && in_array($field, [
                            'project_name', 'agreement', 'project_status', 'grade',
                            'education_type', 'area', 'certifications', 'microcredentials', 'certificates'
                        ])) {
                        $filterValueStr = strtolower(trim($value));
                        $filteredData = $filteredData->filter(function ($project) use ($field, $filterValueStr) {
                            $projectValue = $project[$field] ?? '';

                            if (is_array($projectValue)) {
                                // Para arrays (certificaciones, microcredenciales, certificados)
                                // Buscar en los nombres de los elementos del array
                                foreach ($projectValue as $item) {
                                    $itemName = strtolower($item['name'] ?? '');
                                    if (str_contains($itemName, $filterValueStr)) {
                                        return true;
                                    }
                                }
                                return false;
                            } else {
                                // Para valores simples
                                $projectValueStr = strtolower((string)$projectValue);
                                return str_contains($projectValueStr, $filterValueStr);
                            }
                        });
                    }
                }
            }

            // Recalcular paginación
            $total = $filteredData->count();
            $offset = ($page - 1) * $perPage;
            $paginatedData = $filteredData->slice($offset, $perPage)->values();

            return response()->json([
                'data' => $paginatedData,
                'meta' => [
                    'current_page' => (int)$page,
                    'last_page' => $perPage > 0 ? ceil($total / $perPage) : 1,
                    'per_page' => (int)$perPage,
                    'total' => $total,
                    'from' => $total > 0 ? $offset + 1 : 0,
                    'to' => min($offset + $perPage, $total),
                ]
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error al obtener proyectos duales',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
        $dualProject = DualProject::with([
            'dualProjectReports.microCredentials',
            'dualProjectReports.certifications',
            'dualProjectReports.diplomas',
            'dualProjectStudents',
            'organizationDualProjects'
        ])->findOrFail($id);

        collect($dualProject->dualProjectStudents)->each(fn($student) => $student->delete());

        collect($dualProject->organizationDualProjects)->each(fn($org) => $org->delete());

        collect($dualProject->dualProjectReports)->each(function($report) {
            $report->microCredentials()->detach();
            $report->certifications()->detach();
            $report->diplomas()->detach();
            $report->delete();
        });

        $dualProject->delete();

        DB::commit();

        return response()->json(['message' => 'Proyecto eliminado correctamente'], 200);

    } catch (Exception $e) {
        DB::rollBack();


        return response()->json([
            'message' => 'Error al eliminar proyecto',
            'error' => $e->getMessage()
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
