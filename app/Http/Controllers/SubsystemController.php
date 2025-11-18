<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubsytemRequest;
use App\Models\Subsystem;
use Symfony\Component\HttpFoundation\Response;

class SubsystemController extends Controller
{
    public function getSubsystems()
    {
        return Subsystem::orderBy('name', 'asc')->get();
    }

    public function getSubsystemById($id)
    {
        return Subsystem::findOrFail($id);
    }

    public function createSubsystem(SubsytemRequest $request)
    {
        $data = $request->validated();
        Subsystem::create($data);

        return response(status: Response::HTTP_CREATED);
    }

    public function updateSubsystem(SubsytemRequest $request, $id)
    {
        $data = $request->validated();
        $subsystem = Subsystem::findOrFail($id);
        $subsystem->update($data);

        return response(status: Response::HTTP_OK);
    }

    public function deleteSubsystem($id)
    {
        $subsystem = Subsystem::findOrFail($id);
        $subsystem->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
