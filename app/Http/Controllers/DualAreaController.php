<?php

namespace App\Http\Controllers;

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
}
