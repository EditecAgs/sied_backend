<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClusterRequest;
use App\Models\Cluster;
use Symfony\Component\HttpFoundation\Response;

class ClusterController extends Controller
{
    public function getClusters()
    {
        return response()->json(Cluster::orderBy('name', 'asc')->get(), Response::HTTP_OK);
    }

    public function getClusterById($id)
    {
        $cluster = Cluster::findOrFail($id);

        return response()->json($cluster, Response::HTTP_OK);
    }

    public function createCluster(ClusterRequest $request)
    {
        $data = $request->validated();
        $cluster = Cluster::create($data);

        return response()->json($cluster, Response::HTTP_CREATED);
    }

    public function updateCluster(ClusterRequest $request, $id)
    {
        $cluster = Cluster::findOrFail($id);
        $data = $request->validated();
        $cluster->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteCluster($id)
    {
        $cluster = Cluster::findOrFail($id);
        $cluster->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
