<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardExportController extends Controller
{
    public function export(Request $request)
    {
        $cache = Cache::get('dashboard_cache');

        if (!$cache) {
            abort(503, 'Dashboard cache not ready');
        }

        $idState = $request->query('id_state');
        $idInstitution = $request->query('id_institution');

        $data = $cache['global'] ?? [];

        if ($idInstitution && isset($cache['institutions'][$idInstitution])) {
            $data = $cache['institutions'][$idInstitution];
        }

        if ($idState && isset($cache['states'][$idState])) {
            $data = $cache['states'][$idState];
        }

        $pdf = Pdf::loadView('pdf.dashboard', [
            'data' => $data,
            'filters' => [
                'id_state' => $idState,
                'id_institution' => $idInstitution
            ]
        ])->setPaper('a4', 'portrait');

        return $pdf->download('dashboard.pdf');
    }
}
