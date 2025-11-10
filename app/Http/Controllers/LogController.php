<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 100);

        $userFilter = $request->input('user');
        $moduleFilter = $request->input('module');
        $actionFilter = $request->input('action');
        $detailFilter = $request->input('detail');
        $timestampFilter = $request->input('timestamp');

        $query = Activity::with('causer')->latest();

        if ($userFilter) {
            $query->whereHas('causer', function ($q) use ($userFilter) {
                $q->where('name', 'like', "%{$userFilter}%");
            });
        }

        if ($moduleFilter) {
            $query->where('log_name', 'like', "%{$moduleFilter}%");
        }

        if ($actionFilter) {
            $query->where('description', 'like', "%{$actionFilter}%");
        }

        if ($detailFilter) {
            $query->whereJsonContains('properties', $detailFilter);
        }

        if ($timestampFilter) {
            $query->whereDate('created_at', $timestampFilter);
        }

        $logs = $query->paginate($perPage);

        $logs->getCollection()->transform(function ($log) {
            return [
                'id' => $log->id,
                'user' => $log->causer ? $log->causer->name : 'Sistema',
                'module' => $log->log_name,
                'action' => $log->description,
                'detail' => json_encode($log->properties['old'] ?? $log->properties),
                'timestamp' => $log->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json($logs);
    }

    public function show($id)
    {
        $log = Activity::with('causer')->find($id);

        if (!$log) {
            return response()->json(['message' => 'Log no encontrado'], 404);
        }

        return response()->json([
            'id' => $log->id,
            'user' => $log->causer ? $log->causer->name : 'Sistema',
            'module' => $log->log_name,
            'action' => $log->description,
            'detail' => json_encode($log->properties['old'] ?? $log->properties),
            'timestamp' => $log->created_at->format('Y-m-d H:i:s'),
        ]);
    }
}
