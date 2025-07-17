<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use Symfony\Component\HttpFoundation\Response;

class MunicipalityController extends Controller
{
    public function getMunicipalities()
    {
        $municipalities = Municipality::with(['state:id,name'])->get();

        return response()->json($municipalities, Response::HTTP_OK);
    }

    public function getMunicipalityById($id)
    {
        $municipality = Municipality::with(['state:id,name'])->findOrFail($id);

        return response()->json($municipality, Response::HTTP_OK);
    }

    public function getMunicipalityByStateId($stateId)
    {
        $municipalities = Municipality::with(['state:id,name'])->where('id_state', $stateId)->get();

        return response()->json($municipalities, Response::HTTP_OK);
    }
}
