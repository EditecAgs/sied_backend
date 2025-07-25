<?php

namespace App\Http\Controllers;

use App\Models\Specialty;
use Symfony\Component\HttpFoundation\Response;

class SpecialtyController extends Controller
{
    public function getSpecialties()
    {
        $specialties = Specialty::with(['institution:id,name', 'career:id,name'])->get();

        return response()->json($specialties, Response::HTTP_OK);
    }

    public function getSpecialtyById($id)
    {
        $specialty = Specialty::with(['institution:id,name', 'career:id,name'])->findOrFail($id);

        return response()->json($specialty, Response::HTTP_OK);
    }
}
