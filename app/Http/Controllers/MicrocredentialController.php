<?php

namespace App\Http\Controllers;

use App\Http\Requests\MicrocredentialRequest;
use App\Models\MicroCredential;
use Symfony\Component\HttpFoundation\Response;

class MicroCredentialController extends Controller
{
    public function getMicroCredentials()
    {
        $microCredentials = MicroCredential::with(['dualProjectReportMicroCredentials'])->orderBy('name', 'asc')->get();

        return response()->json($microCredentials, Response::HTTP_OK);
    }

    public function getMicroCredentialById($id)
    {
        $microCredential = MicroCredential::with(['dualProjectReportMicroCredentials'])->findOrFail($id);

        return response()->json($microCredential, Response::HTTP_OK);
    }

    public function createMicroCredential(MicroCredentialRequest $request)
    {
        $data = $request->validated();
        $microCredential = MicroCredential::create($data);

        return response()->json($microCredential, Response::HTTP_CREATED);
    }

    public function updateMicroCredential(MicroCredentialRequest $request, $id)
    {
        $microCredential = MicroCredential::findOrFail($id);
        $data = $request->validated();
        $microCredential->update($data);

        return response(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteMicroCredential($id)
    {
        $microCredential = MicroCredential::findOrFail($id);
        $microCredential->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
