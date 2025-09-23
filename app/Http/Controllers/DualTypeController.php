<?php

namespace App\Http\Controllers;

use App\Http\Requests\DualTypeRequest;
use App\Models\DualType;
use Symfony\Component\HttpFoundation\Response;

class DualTypeController extends Controller
{
    public function getDualTypes()
    {
        return response()->json(DualType::all(), Response::HTTP_OK);
    }

    public function getDualTypeById($id)
    {
        $dualType = DualType::findOrFail($id);

        return response()->json($dualType, Response::HTTP_OK);
    }

    public function createDualType(DualTypeRequest $request)
    {
        $data = $request->validated();
        DualType::create($data);

        return response(status: Response::HTTP_CREATED);
    }

    public function updateDualType(DualTypeRequest $request, $id)
    {
        $data = $request->validated();
        $dualType = DualType::findOrFail($id);
        $dualType->update($data);

        return response(status: Response::HTTP_OK);
    }

    public function deleteDualType($id)
    {
        $dualType = DualType::findOrFail($id);
        $dualType->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
