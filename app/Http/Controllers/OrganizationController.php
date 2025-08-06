<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrganizationRequest;
use App\Models\Organization;
use Symfony\Component\HttpFoundation\Response;

class OrganizationController extends Controller
{
    public function getOrganizations()
    {
        $organizations = Organization::with(['type:id,name', 'sector:id,name', 'cluster:id,name', 'state:id,name', 'municipality:id,name'])->get();

        return response()->json($organizations, Response::HTTP_OK);
    }

    public function getOrganizationById($id)
    {
        $organization = Organization::with(['type:id,name', 'sector:id,name', 'cluster:id:name', 'state:id:name', 'municipality:id:name'])->findOrFail($id);

        return response()->json($organization, Response::HTTP_OK);
    }

    public function createOrganization(OrganizationRequest $request)
    {
        $data = $request->validated();
        $organization = Organization::create($data);

        return response()->json($organization, Response::HTTP_CREATED);
    }

    public function updateOrganization(OrganizationRequest $request, $id)
    {
        $organization = Organization::findOrFail($id);
        $data = $request->validated();
        $organization->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteOrganization($id)
    {
        $organization = Organization::findOrFail($id);
        $organization->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
