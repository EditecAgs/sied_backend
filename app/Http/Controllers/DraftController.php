<?php

namespace App\Http\Controllers;

use App\Models\ProjectDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class DraftController extends Controller
{
    public function save(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'project_id' => 'nullable|exists:dual_projects,id',
                'form_data' => 'required|json',
                'reporta_modelo_dual' => 'boolean',
                'section1_expanded' => 'boolean',
                'section2_expanded' => 'boolean',
                'section3_expanded' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $userId = Auth::id();

            if ($request->project_id) {
                ProjectDraft::where('user_id', $userId)
                    ->whereNull('project_id')
                    ->delete();
            }

            $draft = ProjectDraft::updateOrCreate(
                [
                    'user_id' => $userId,
                    'project_id' => $request->project_id,
                ],
                [
                    'form_data' => $request->form_data,
                    'reporta_modelo_dual' => $request->boolean('reporta_modelo_dual', false),
                    'section1_expanded' => $request->boolean('section1_expanded', true),
                    'section2_expanded' => $request->boolean('section2_expanded', false),
                    'section3_expanded' => $request->boolean('section3_expanded', false),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Borrador guardado correctamente',
                'draft' => $draft
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el borrador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function load(Request $request)
    {
        try {
            $query = ProjectDraft::where('user_id', Auth::id());

            if ($request->has('project_id') && $request->project_id) {
                $query->where('project_id', $request->project_id);
            } else {
                $query->whereNull('project_id');
            }

            $draft = $query->first();

            if (!$draft) {
                return response()->json([
                    'exists' => false
                ]);
            }

            return response()->json([
                'exists' => true,
                'form_data' => json_decode($draft->form_data),
                'reporta_modelo_dual' => $draft->reporta_modelo_dual,
                'section1_expanded' => $draft->section1_expanded,
                'section2_expanded' => $draft->section2_expanded,
                'section3_expanded' => $draft->section3_expanded,
                'updated_at' => $draft->updated_at
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar el borrador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function clear(Request $request)
    {
        try {
            $userId = Auth::id();
            $query = ProjectDraft::where('user_id', $userId);

            if ($request->has('project_id') && $request->project_id !== null && $request->project_id !== 'null') {
                $query->where('project_id', $request->project_id);
                $message = 'Borrador de proyecto existente eliminado';
            } else {
                $query->whereNull('project_id');
                $message = 'Borrador de creación nueva eliminado';
            }

            $deleted = $query->delete();

            return response()->json([
                'success' => true,
                'message' => $message,
                'user_id' => $userId,
                'deleted' => $deleted
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el borrador',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function check(Request $request)
    {
        try {
            $query = ProjectDraft::where('user_id', Auth::id());

            if ($request->has('project_id') && $request->project_id) {
                $query->where('project_id', $request->project_id);
            } else {
                $query->whereNull('project_id');
            }

            $exists = $query->exists();

            return response()->json([
                'exists' => $exists
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al verificar borrador',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
