<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectorRequest;
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

    public function createSector(SectorRequest $request)
    {
        $data = $request->validated();
        $sector = Sector::create($data);

        return response()->json($sector, Response::HTTP_CREATED);
    }

    public function updateSector(SectorRequest $request, $id)
    {
        $sector = Sector::findOrFail($id);
        $data = $request->validated();
        $sector->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteSector($id)
    {
        $sector = Sector::findOrFail($id);
        $sector->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
