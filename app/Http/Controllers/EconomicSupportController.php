<?php

namespace App\Http\Controllers;

use App\Http\Requests\EconomicSupportRequest;
use App\Models\EconomicSupport;
use Symfony\Component\HttpFoundation\Response;

class EconomicSupportController extends Controller
{
    public function getEconomicSupports()
    {
        return response()->json(EconomicSupport::orderBy('name', 'asc')->get(), Response::HTTP_OK);
    }

    public function getEconomicSupportById($id)
    {
        $support = EconomicSupport::all()->findOrFail($id);

        return response()->json($support, Response::HTTP_OK);
    }

    public function createEconomicSupport(EconomicSupportRequest $request)
    {
        $data = $request->validated();
        $support = EconomicSupport::create($data);

        return response()->json($support, Response::HTTP_CREATED);
    }

    public function updateEconomicSupport(EconomicSupportRequest $request, $id)
    {
        $support = EconomicSupport::findOrFail($id);
        $data = $request->validated();
        $support->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteEconomicSupport($id)
    {
        $support = EconomicSupport::findOrFail($id);
        $support->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
