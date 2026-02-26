<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BenefitTypeRequest;
use App\Models\BenefitType;
use Symfony\Component\HttpFoundation\Response;

class BenefitTypeController extends Controller
{
    public function getBenefitTypes()
    {
        $benefits = BenefitType::orderBy('name', 'asc')->get();

        return response()->json($benefits, Response::HTTP_OK);
    }

    public function getBenefitTypeById($id)
    {
        $benefit = BenefitType::findOrFail($id);

        return response()->json($benefit, Response::HTTP_OK);
    }

    public function createBenefitType(BenefitTypeRequest $request)
    {
        $data = $request->validated();
        $benefit = BenefitType::create($data);

        return response()->json($benefit, Response::HTTP_CREATED);
    }

    public function updateBenefitType(BenefitTypeRequest $request, $id)
    {
        $benefit = BenefitType::findOrFail($id);
        $data = $request->validated();

        $benefit->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteBenefitType($id)
    {
        $benefit = BenefitType::findOrFail($id);
        $benefit->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
