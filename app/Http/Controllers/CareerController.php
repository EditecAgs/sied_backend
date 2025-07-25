<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Symfony\Component\HttpFoundation\Response;

class CareerController extends Controller
{
    public function getCareers()
    {
        $careers = Career::with(['institution:id,name'])->get();

        return response()->json($careers, Response::HTTP_OK);
    }

    public function getCareerById($id)
    {
        $career = Career::with(['institution:id,name'])->findOrFail($id);

        return response()->json($career, Response::HTTP_OK);
    }
}
