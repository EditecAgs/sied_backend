<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequest;
use App\Models\Type;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    public function getTypes()
    {
        $types = Type::orderBy('name', 'asc')->get();

        return response()->json($types, Response::HTTP_OK);
    }

    public function getTypeById($id)
    {
        $type = Type::findOrFail($id);

        return response()->json($type, Response::HTTP_OK);
    }

    public function createType(TypeRequest $request)
    {
        $data = $request->validated();
        $type = Type::create($data);

        return response()->json($type, Response::HTTP_CREATED);
    }

    public function updateType(TypeRequest $request, $id)
    {
        $type = Type::findOrFail($id);
        $data = $request->validated();
        $type->update($data);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }

    public function deleteType($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
