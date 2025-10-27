<?php

use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentStatusController;
use App\Http\Controllers\DualAreaController;
use App\Http\Controllers\DualProjectController;
use App\Http\Controllers\DualTypeController;
use App\Http\Controllers\EconomicSupportController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MicrocredentialController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubsystemController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BitacoraAccesoController;

Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('users', [UserController::class, 'getUsers']);
    Route::get('users/{id}', [UserController::class, 'getUsersById']);
    Route::post('users', [UserController::class, 'createUser']);
    Route::put('users/{id}', [UserController::class, 'updateUser']);
    Route::delete('users/{id}', [UserController::class, 'deleteUser']);
});

Route::middleware(['auth:sanctum', 'token.expiration'])->group(function () {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('institutions', [InstitutionController::class, 'getInstitutions'])->name('getInstitutions');
    Route::get('institutions/{id}', [InstitutionController::class, 'getInstitutionById']);
    Route::post('institutions', [InstitutionController::class, 'createInstitution']);
    Route::put('institutions/{id}', [InstitutionController::class, 'updateInstitution']);
    Route::delete('institutions/{id}', [InstitutionController::class, 'deleteInstitution']);

    Route::get('profile', [UserController::class, 'getProfile']);

    Route::get('subsystems', [SubsystemController::class, 'getSubsystems']);
    Route::get('subsystems/{id}', [SubsystemController::class, 'getSubsystemById']);
    Route::post('subsystems', [SubsystemController::class, 'createSubsystem']);
    Route::put('subsystems/{id}', [SubsystemController::class, 'updateSubsystem']);
    Route::delete('subsystems/{id}', [SubsystemController::class, 'deleteSubsystem']);

    Route::get('municipalities', [MunicipalityController::class, 'getMunicipalities']);
    Route::get('municipalities/{id}', [MunicipalityController::class, 'getMunicipalityById']);
    Route::get('municipalities/state/{stateId}', [MunicipalityController::class, 'getMunicipalityByStateId']);

    Route::get('states', [StateController::class, 'getStates']);
    Route::get('states/{id}', [StateController::class, 'getStateById']);

    Route::get('careers', [CareerController::class, 'getCareers']);
    Route::get('careers/{id}', [CareerController::class, 'getCareerById']);
    Route::get('careers/institution/{id}', [CareerController::class, 'getCareerByInstitution']);
    Route::post('careers', [CareerController::class, 'createCareer']);
    Route::put('careers/{id}', [CareerController::class, 'updateCareer']);
    Route::delete('careers/{id}', [CareerController::class, 'deleteCareer']);

    Route::get('subsystems', [SubsystemController::class, 'getSubsystems']);
    Route::get('subsystems/{id}', [SubsystemController::class, 'getSubsystemById']);
    Route::post('subsystems', [SubsystemController::class, 'createSubsystem']);
    Route::put('subsystems/{id}', [SubsystemController::class, 'updateSubsystem']);
    Route::delete('subsystems/{id}', [SubsystemController::class, 'deleteSubsystem']);

    Route::get('academic-periods', [AcademicPeriodController::class, 'getAcademicPeriods']);
    Route::get('academic-periods/{id}', [AcademicPeriodController::class, 'getAcademicPeriodById']);
    Route::post('academic-periods', [AcademicPeriodController::class, 'createAcademicPeriod']);
    Route::put('academic-periods/{id}', [AcademicPeriodController::class, 'updateAcademicPeriod']);
    Route::delete('academic-periods/{id}', [AcademicPeriodController::class, 'deleteAcademicPeriod']);

    Route::get('clusters', [ClusterController::class, 'getClusters']);
    Route::get('clusters/{id}', [ClusterController::class, 'getClusterById']);
    Route::post('clusters', [ClusterController::class, 'createCluster']);
    Route::put('clusters/{id}', [ClusterController::class, 'updateCluster']);
    Route::delete('clusters/{id}', [ClusterController::class, 'deleteCluster']);

    Route::get('documents-statuses', [DocumentStatusController::class, 'getDocumentStatuses']);
    Route::get('documents-statuses/{id}', [DocumentStatusController::class, 'getDocumentStatusById']);
    Route::post('documents-statuses', [DocumentStatusController::class, 'createDocumentStatus']);
    Route::put('documents-statuses/{id}', [DocumentStatusController::class, 'updateDocumentStatus']);
    Route::delete('documents-statuses/{id}', [DocumentStatusController::class, 'deleteDocumentStatus']);

    Route::get('dual-areas', [DualAreaController::class, 'getDualAreas']);
    Route::get('dual-areas/{id}', [DualAreaController::class, 'getDualAreaById']);
    Route::post('dual-areas', [DualAreaController::class, 'createDualArea']);
    Route::put('dual-areas/{id}', [DualAreaController::class, 'updateDualArea']);
    Route::delete('dual-areas/{id}', [DualAreaController::class, 'deleteDualArea']);

    Route::get('dual-types', [DualTypeController::class, 'getDualTypes']);
    Route::get('dual-types/{id}', [DualTypeController::class, 'getDualTypeById']);
    Route::post('dual-types', [DualTypeController::class, 'createDualType']);
    Route::put('dual-types/{id}', [DualTypeController::class, 'updateDualType']);
    Route::delete('dual-types/{id}', [DualTypeController::class, 'deleteDualType']);

    Route::get('economic-supports', [EconomicSupportController::class, 'getEconomicSupports']);
    Route::get('economic-supports/{id}', [EconomicSupportController::class, 'getEconomicSupportById']);
    Route::post('economic-supports', [EconomicSupportController::class, 'createEconomicSupport']);
    Route::put('economic-supports/{id}', [EconomicSupportController::class, 'updateEconomicSupport']);
    Route::delete('economic-supports/{id}', [EconomicSupportController::class, 'deleteEconomicSupport']);

    Route::get('organizations', [OrganizationController::class, 'getOrganizations']);
    Route::get('organizations/{id}', [OrganizationController::class, 'getOrganizationById']);
    Route::post('organizations', [OrganizationController::class, 'createOrganization']);
    Route::put('organizations/{id}', [OrganizationController::class, 'updateOrganization']);
    Route::delete('organizations/{id}', [OrganizationController::class, 'deleteOrganization']);

    Route::get('sectors', [SectorController::class, 'getSectors']);
    Route::get('sectors/{id}', [SectorController::class, 'getSectorById']);
    Route::post('sectors', [SectorController::class, 'createSector']);
    Route::put('sectors/{id}', [SectorController::class, 'updateSector']);
    Route::delete('sectors/{id}', [SectorController::class, 'deleteSector']);

    Route::get('specialties', [SpecialtyController::class, 'getSpecialties']);
    Route::get('specialties/{id}', [SpecialtyController::class, 'getSpecialtyById']);
    Route::post('specialties', [SpecialtyController::class, 'createSpecialty']);
    Route::put('specialties/{id}', [SpecialtyController::class, 'updateSpecialty']);
    Route::delete('specialties/{id}', [SpecialtyController::class, 'deleteSpecialty']);

    Route::get('types', [TypeController::class, 'getTypes']);
    Route::get('types/{id}', [TypeController::class, 'getTypeById']);
    Route::post('types', [TypeController::class, 'createType']);
    Route::put('types/{id}', [TypeController::class, 'updateType']);
    Route::delete('types/{id}', [TypeController::class, 'deleteType']);

    Route::get('micro-credentials', [MicrocredentialController::class, 'getMicroCredentials']);
    Route::get('micro-credentials/{id}', [MicrocredentialController::class, 'getMicroCredentialById']);
    Route::post('micro-credentials', [MicrocredentialController::class, 'createMicroCredential']);
    Route::put('micro-credentials/{id}', [MicrocredentialController::class, 'updateMicroCredential']);
    Route::delete('micro-credentials/{id}', [MicrocredentialController::class, 'deleteMicroCredential']);

    Route::get('students', [StudentController::class, 'getStudents']);
    Route::get('students/{id}', [StudentController::class, 'getStudentById']);
    Route::post('students', [StudentController::class, 'createStudent']);
    Route::put('students/{id}', [StudentController::class, 'updateStudent']);
    Route::delete('students/{id}', [StudentController::class, 'deleteStudent']);
    Route::patch('students/{id}/restore', [StudentController::class, 'restoreStudent']);

    Route::get('dual-projects/unreported', [DualProjectController::class, 'getUnreportedDualProjects'])
        ->name('dual-projects-unreported');
    Route::get('dual-projects/reported', [DualProjectController::class, 'getReportedDualProject'])
        ->name('dual-projects-reported');
    Route::get('dual-projects/{id}', [DualProjectController::class, 'getDualProjectById']);
    Route::post('dual-projects', [DualProjectController::class, 'createDualProject'])
        ->name('dual-projects-create');
    Route::delete('dual-projects/{id}', [DualProjectController::class, 'deleteDualProject'])->name('dual-projects-update');
    Route::put('dual-projects/{id}', [DualProjectController::class, 'updateDualProject'])->name('dual-projects-delete');

    Route::get('dual-projects/completed/count', [DashboardController::class, 'countDualProjectCompleted']);
    Route::get('students/registered/count', [DashboardController::class, 'countRegisteredStudents']);
    Route::get('projects/by-month', [DashboardController::class, 'countProjectsByMonth']);
    Route::get('projects/dual-area', [DashboardController::class, 'countProjectsByArea']);
    Route::get('projects/sectors', [DashboardController::class, 'countProjectsBySector']);
    Route::get('dual-projects/percetange/institutions', [DashboardController::class, 'getInstitutionProjectPercentage']);
    Route::get('organizations/registered/count', [DashboardController::class, 'countRegisteredOrganizations']);
    Route::get('organizations/scope/count', [DashboardController::class, 'countOrganizationsByScope']);
    Route::get('projects/sectors/mexico', [DashboardController::class, 'countProjectsBySectorPlanMexico']);
    Route::get('projects/economic-support', [DashboardController::class, 'countProjectsByEconomicSupport']);
    Route::get('projects/economic-support/average', [DashboardController::class, 'averageAmountByEconomicSupport']);
    Route::get('logs', [LogController::class, 'index']);
    Route::get('logs/{id}', [LogController::class, 'show']);
    Route::get('bitacora-accesos', [BitacoraAccesoController::class, 'index']);
    Route::get('bitacora-accesos/{id}', [BitacoraAccesoController::class, 'show']);
    Route::delete('bitacora-accesos/{id}', [BitacoraAccesoController::class, 'destroy']);
});
