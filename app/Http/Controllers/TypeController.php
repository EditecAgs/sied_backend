<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    public function getTypes()
    {
        $types = Type::all();

        return response()->json($types, Response::HTTP_OK);
    }

    public function getTypeById($id)
    {
        $type = Type::findOrFail($id);

        return response()->json($type, Response::HTTP_OK);
    }
}
