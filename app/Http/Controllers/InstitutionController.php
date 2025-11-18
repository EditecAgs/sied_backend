<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionRequest;
use App\Models\Institution;
use Symfony\Component\HttpFoundation\Response;

class InstitutionController extends Controller
{
    public function getInstitutions()
    {
        $institutions = Institution::with(['subsystem:id,name', 'state:id,name', 'municipality:id,name', 'academicPeriod:id,name'])->orderBy('name', 'asc')->get();

        return response()->json($institutions, Response::HTTP_OK);
    }

    public function getInstitutionById($id)
    {
        $institution = Institution::with(['subsystem:id,name', 'state:id,name', 'municipality:id,name', 'academicPeriod:id,name'])->findOrFail($id);

        return response()->json($institution, Response::HTTP_OK);
    }

    public function createInstitution(InstitutionRequest $request)
    {
        $data = $request->validated();
        $institution = Institution::create($data);

        return response()->json($institution, Response::HTTP_CREATED);
    }

    public function updateInstitution(InstitutionRequest $request, $id)
    {
        $institution = Institution::findOrFail($id);
        $data = $request->validated();
        $institution->update($data);

        return response(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteInstitution($id)
    {
        $institution = Institution::findOrFail($id);
        $institution->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
