<?php

namespace App\Http\Controllers;

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
}
