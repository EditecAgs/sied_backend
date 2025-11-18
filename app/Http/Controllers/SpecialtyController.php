<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialtyRequest;
use App\Models\Specialty;
use Symfony\Component\HttpFoundation\Response;

class SpecialtyController extends Controller
{
    public function getSpecialties()
    {
        $specialties = Specialty::with(['institution:id,name', 'career:id,name'])->orderBy('name', 'asc')->get();

        return response()->json($specialties, Response::HTTP_OK);
    }

    public function getSpecialtyById($id)
    {
        $specialty = Specialty::with(['institution:id,name', 'career:id,name'])->findOrFail($id);

        return response()->json($specialty, Response::HTTP_OK);
    }

    public function createSpecialty(SpecialtyRequest $request)
    {
        $data = $request->validated();
        $specialty = Specialty::create($data);

        return response()->json($specialty, Response::HTTP_CREATED);
    }

    public function updateSpecialty(SpecialtyRequest $request, $id)
    {
        $specialty = Specialty::findOrFail($id);
        $data = $request->validated();
        $specialty->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteSpecialty($id)
    {
        $specialty = Specialty::findOrFail($id);
        $specialty->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
