<?php

use App\Http\Controllers\InstitutionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('institutions', [InstitutionController::class, 'getInstitutions']);
Route::post('institutions', [InstitutionController::class, 'createInstitution']);
Route::put('institutions/{id}', [InstitutionController::class, 'updateInstitution']);
Route::delete('institutions/{id}', [InstitutionController::class, 'deleteInstitution']);

Route::get('users', [UserController::class, 'getUsers']);
Route::get('users/{id}', [UserController::class, 'getUsersById']);
Route::post('users', [UserController::class, 'createUser']);
Route::put('users/{id}', [UserController::class, 'updateUser']);
Route::delete('users/{id}', [UserController::class, 'deleteUser']);
