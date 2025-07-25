<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Symfony\Component\HttpFoundation\Response;

class ClusterController extends Controller
{
    public function getClusters()
    {
        return response()->json(Cluster::all(), Response::HTTP_OK);
    }

    public function getClusterById($id)
    {
        $cluster = Cluster::findOrFail($id);

        return response()->json($cluster, Response::HTTP_OK);
    }
}
