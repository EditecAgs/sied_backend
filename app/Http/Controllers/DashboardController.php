<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    private function getCache()
    {
        return Cache::get('dashboard_cache');
    }

    private function fetchFromCache($metricKey)
    {
        $idState = request('id_state');
        $idInstitution = request('id_institution');

        $cache = $this->getCache();
        if (!$cache) {
            return response()->json(['error' => 'Cache not ready'], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        if (!empty($idInstitution)) {
            return response()->json($cache['institutions'][$idInstitution][$metricKey] ?? [], Response::HTTP_OK);
        }

        if (!empty($idState)) {
            return response()->json($cache['states'][$idState][$metricKey] ?? [], Response::HTTP_OK);
        }

        return response()->json($cache['global'][$metricKey] ?? [], Response::HTTP_OK);
    }

    public function countDualProjectCompleted()
    {
        return $this->fetchFromCache('countDualProjectCompleted');
    }

    public function countRegisteredStudents()
    {
        return $this->fetchFromCache('countRegisteredStudents');
    }

    public function countRegisteredOrganizations()
    {
        return $this->fetchFromCache('countRegisteredOrganizations');
    }

    public function countOrganizationsByScope()
    {
        return $this->fetchFromCache('countOrganizationsByScope');
    }

    public function countProjectsByMonth()
    {
        return $this->fetchFromCache('countProjectsByMonth');
    }

    public function countProjectsByArea()
    {
        return $this->fetchFromCache('countProjectsByArea');
    }

    public function countProjectsBySector()
    {
        return $this->fetchFromCache('countProjectsBySector');
    }

    public function countProjectsBySectorPlanMexico()
    {
        return $this->fetchFromCache('countProjectsBySectorPlanMexico');
    }

    public function countProjectsByEconomicSupport()
    {
        return $this->fetchFromCache('countProjectsByEconomicSupport');
    }

    public function averageAmountByEconomicSupport()
    {
        return $this->fetchFromCache('averageAmountByEconomicSupport');
    }

    public function getInstitutionProjectPercentage()
    {
        return $this->fetchFromCache('getInstitutionProjectPercentage');
    }

    public function countProjectsByDualType()
    {
        return $this->fetchFromCache('countProjectsByDualType');
    }

    public function countOrganizationsByCluster()
    {
        return $this->fetchFromCache('countOrganizationsByCluster');
    }

    public function countProjectsByCluster()
    {
        return $this->fetchFromCache('countProjectsByCluster');
    }

    
    public function showFullCache()
    {
        $cache = $this->getCache();
        
        if (!$cache) {
            return response()->json([
                'error' => 'Cache not ready',
                'timestamp' => now()->toDateTimeString()
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
        
        $institutionKeys = array_keys($cache['institutions'] ?? []);
        $stateKeys = array_keys($cache['states'] ?? []);
        
        $sampleInstitutionKeys = array_slice($institutionKeys, 0, 5);
        $sampleStateKeys = array_slice($stateKeys, 0, 5);

        $availableMetrics = isset($cache['global']) ? array_keys($cache['global']) : [];
        
        return response()->json([
            'timestamp' => now()->toDateTimeString(),
            'cache_ttl' => '2 minutes',
            'structure' => [
                'has_global' => !empty($cache['global']),
                'institutions_count' => count($institutionKeys),
                'states_count' => count($stateKeys),
                'sample_institution_keys' => $sampleInstitutionKeys,
                'sample_state_keys' => $sampleStateKeys,
                'available_metrics' => $availableMetrics
            ],
            'cache' => $cache
        ]);
    }
}
