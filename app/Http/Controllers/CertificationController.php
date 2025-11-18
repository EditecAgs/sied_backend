<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificationRequest;
use App\Models\Certification;
use Symfony\Component\HttpFoundation\Response;

class CertificationController extends Controller
{
    public function getCertifications()
    {
        $certifications = Certification::with(['dualProjectReportCertifications'])->orderBy('name', 'asc')->get();

        return response()->json($certifications, Response::HTTP_OK);
    }

    public function getCertificationById($id)
    {
        $certification = Certification::with(['dualProjectReportCertifications'])->findOrFail($id);

        return response()->json($certification, Response::HTTP_OK);
    }

    public function createCertification(CertificationRequest $request)
    {
        $data = $request->validated();
        $certification = Certification::create($data);

        return response()->json($certification, Response::HTTP_CREATED);
    }

    public function updateCertification(CertificationRequest $request, $id)
    {
        $certification = Certification::findOrFail($id);
        $data = $request->validated();
        $certification->update($data);

        return response(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteCertification($id)
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
