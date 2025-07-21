<?php

use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\MunicipalityController;
use App\Http\Controllers\StateController;
use App\Http\Controllers\SubsystemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [UserController::class, 'logout']);
});

Route::get('institutions', [InstitutionController::class, 'getInstitutions']);
Route::get('institutions/{id}', [InstitutionController::class, 'getInstitutionById']);
Route::post('institutions', [InstitutionController::class, 'createInstitution']);
Route::put('institutions/{id}', [InstitutionController::class, 'updateInstitution']);
Route::delete('institutions/{id}', [InstitutionController::class, 'deleteInstitution']);

Route::get('users', [UserController::class, 'getUsers']);
Route::get('users/{id}', [UserController::class, 'getUsersById']);
Route::post('users', [UserController::class, 'createUser']);
Route::put('users/{id}', [UserController::class, 'updateUser']);
Route::delete('users/{id}', [UserController::class, 'deleteUser']);

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

Route::get('municipalities', [MunicipalityController::class, 'getMunicipalities']);
Route::get('municipalities/{id}', [MunicipalityController::class, 'getMunicipalityById']);
Route::get('municipalities/state/{stateId}', [MunicipalityController::class, 'getMunicipalityByStateId']);

Route::get('states', [StateController::class, 'getStates']);
Route::get('states/{id}', [StateController::class, 'getStateById']);
