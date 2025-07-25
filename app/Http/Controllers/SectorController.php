<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Symfony\Component\HttpFoundation\Response;

class SectorController extends Controller
{
    public function getSectors()
    {
        return response()->json(Sector::all(), Response::HTTP_OK);
    }

    public function getSectorById($id)
    {
        $sector = Sector::findOrFail($id);

        return response()->json($sector, Response::HTTP_OK);
    }
}
