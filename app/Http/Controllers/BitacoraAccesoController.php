<?php

namespace App\Http\Controllers;

use App\Models\BitacoraAcceso;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BitacoraAccesoController extends Controller
{
    public function index(Request $request)
    {

        $query = BitacoraAcceso::with('user:id,name,email')
            ->orderBy('fecha_hora', 'desc');

        if ($request->filled('accion')) {
            $query->where('accion', $request->accion);
        }

    
        if ($request->filled('user')) {
            $query->whereRelation('user', 'name', 'like', '%' . $request->user . '%');
        }


        if ($request->filled('fecha_hora')) {
            $query->whereDate('fecha_hora', $request->fecha_hora);
        }


        $perPage = $request->get('per_page', 100);
        $bitacoras = $query->paginate($perPage);

        return response()->json($bitacoras, Response::HTTP_OK);
    }

    public function show($id)
    {
        $bitacora = BitacoraAcceso::with('user:id,name,email')->findOrFail($id);
        return response()->json($bitacora, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $bitacora = BitacoraAcceso::find($id);

        if (!$bitacora) {
            return response()->json(['message' => 'Registro no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $bitacora->delete();

        return response()->json(['message' => 'Registro eliminado'], Response::HTTP_OK);
    }
}
