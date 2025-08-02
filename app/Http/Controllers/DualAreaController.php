<?php

namespace App\Http\Controllers;

use App\Http\Requests\DualAreaRequest;
use App\Models\DualArea;
use Symfony\Component\HttpFoundation\Response;

class DualAreaController extends Controller
{
    public function getDualAreas()
    {
        return response()->json(DualArea::all(), Response::HTTP_OK);
    }

    public function getDualAreaById($id)
    {
        $dualArea = DualArea::all()->findOrFail($id);

        return response()->json($dualArea, Response::HTTP_OK);
    }

    public function createDualArea(DualAreaRequest $request)
    {
        $data = $request->validated();
        DualArea::create($data);

        return response(status: Response::HTTP_CREATED);
    }

    public function updateDualArea(DualAreaRequest $request, $id)
    {
        $data = $request->validated();
        $academicPeriod = DualArea::findOrFail($id);
        $academicPeriod->update($data);

        return response(status: Response::HTTP_OK);
    }

    public function deleteDualArea($id)
    {
        $academicPeriod = DualArea::findOrFail($id);
        $academicPeriod->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
