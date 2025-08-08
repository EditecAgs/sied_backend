<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentStatusRequest;
use App\Models\DocumentStatus;
use Symfony\Component\HttpFoundation\Response;

class DocumentStatusController extends Controller
{
    public function getDocumentStatuses()
    {
        return response()->json(DocumentStatus::all(), Response::HTTP_OK);
    }

    public function getDocumentStatusById($id)
    {
        $status = DocumentStatus::all()->findOrFail($id);

        return response()->json($status, Response::HTTP_OK);
    }

    public function createDocumentStatus(DocumentStatusRequest $request)
    {
        $data = $request->validated();
        $status = DocumentStatus::create($data);

        return response()->json($status, Response::HTTP_CREATED);
    }

    public function updateDocumentStatus(DocumentStatusRequest $request, $id)
    {
        $status = DocumentStatus::findOrFail($id);
        $data = $request->validated();
        $status->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteDocumentStatus($id)
    {
        $status = DocumentStatus::findOrFail($id);
        $status->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
