<?php

namespace App\Http\Controllers;

use App\Models\EconomicSupport;
use Symfony\Component\HttpFoundation\Response;

class EconomicSupportController extends Controller
{
    public function getEconomicSupports()
    {
        return response()->json(EconomicSupport::all(), Response::HTTP_OK);
    }

    public function getEconomicSupportById($id)
    {
        $support = EconomicSupport::all()->findOrFail($id);

        return response()->json($support, Response::HTTP_OK);
    }
}
