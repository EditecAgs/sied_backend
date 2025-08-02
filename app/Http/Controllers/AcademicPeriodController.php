<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicPeriodRequest;
use App\Models\AcademicPeriod;
use Symfony\Component\HttpFoundation\Response;

class AcademicPeriodController extends Controller
{
    public function getAcademicPeriods()
    {
        return AcademicPeriod::all();
    }

    public function getAcademicPeriodById($id)
    {
        return AcademicPeriod::findOrFail($id);
    }

    public function createAcademicPeriod(AcademicPeriodRequest $request)
    {
        $data = $request->validated();
        AcademicPeriod::create($data);

        return response(status: Response::HTTP_CREATED);
    }

    public function updateAcademicPeriod(AcademicPeriodRequest $request, $id)
    {
        $data = $request->validated();
        $academicPeriod = AcademicPeriod::findOrFail($id);
        $academicPeriod->update($data);

        return response(status: Response::HTTP_OK);
    }

    public function deleteAcademicPeriod($id)
    {
        $academicPeriod = AcademicPeriod::findOrFail($id);
        $academicPeriod->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
