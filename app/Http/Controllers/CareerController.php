<?php

namespace App\Http\Controllers;

use App\Http\Requests\CareerRequest;
use App\Models\Career;
use Symfony\Component\HttpFoundation\Response;

class CareerController extends Controller
{
    public function getCareers()
    {
        $careers = Career::with(['institution:id,name'])->get();

        return response()->json($careers, Response::HTTP_OK);
    }

    public function getCareerById($id)
    {
        $career = Career::with(['institution:id,name'])->findOrFail($id);

        return response()->json($career, Response::HTTP_OK);
    }

    public function getCareerByInstitution($id)
    {
        $career = Career::where('id_institution', $id)->get();

        return response()->json($career, Response::HTTP_OK);
    }

    public function createCareer(CareerRequest $request)
    {
        $data = $request->validated();
        $career = Career::create($data);

        return response()->json($career, Response::HTTP_CREATED);
    }

    public function updateCareer(CareerRequest $request, $id)
    {
        $career = Career::findOrFail($id);
        $data = $request->validated();
        $career->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteCareer($id)
    {
        $career = Career::findOrFail($id);
        $career->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
