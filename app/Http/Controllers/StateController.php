<?php

namespace App\Http\Controllers;

use App\Models\State;
use Symfony\Component\HttpFoundation\Response;

class StateController extends Controller
{
    public function getStates()
    {
        return response()->json(State::all(), Response::HTTP_OK);
    }

    public function getStateById($id)
    {
        $state = State::findOrFail($id);

        return response()->json($state, Response::HTTP_OK);
    }
}
