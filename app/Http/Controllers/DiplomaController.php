<?php

namespace App\Http\Controllers;

use App\Http\Requests\DiplomaRequest;
use App\Models\Diploma;
use Symfony\Component\HttpFoundation\Response;

class DiplomaController extends Controller
{
    public function getDiplomas()
    {
        $diplomas = Diploma::with(['dualProjectReportDiplomas'])->orderBy('name', 'asc')->get();

        return response()->json($diplomas, Response::HTTP_OK);
    }

    public function getDiplomaById($id)
    {
        $diploma = Diploma::with(['dualProjectReportDiplomas'])->findOrFail($id);

        return response()->json($diploma, Response::HTTP_OK);
    }

    public function createDiploma(DiplomaRequest $request)
    {
        $data = $request->validated();
        $diploma = Diploma::create($data);

        return response()->json($diploma, Response::HTTP_CREATED);
    }

    public function updateDiploma(DiplomaRequest $request, $id)
    {
        $diploma = Diploma::findOrFail($id);
        $data = $request->validated();
        $diploma->update($data);

        return response(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteDiploma($id)
    {
        $diploma = Diploma::findOrFail($id);
        $diploma->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
