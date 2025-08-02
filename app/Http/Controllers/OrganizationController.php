<?php

namespace App\Http\Controllers;

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
}
