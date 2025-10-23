<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;


class LogController extends Controller
{
    public function index()
    {
        $logs = Activity::with('causer')
            ->latest()
            ->get()
            ->map(function($log) {
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