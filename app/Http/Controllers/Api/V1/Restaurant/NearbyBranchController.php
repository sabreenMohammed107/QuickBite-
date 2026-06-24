<?php

namespace App\Http\Controllers\Api\V1\Restaurant;

use App\Domains\Restaurant\Services\GeospatialLocationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NearbyBranchController extends Controller
{
    public function __invoke(Request $request, GeospatialLocationService $service): JsonResponse
    {
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius_km' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ]);

        $branches = $service->findNearbyBranches(
            lat: (float) $data['lat'],
            lng: (float) $data['lng'],
            radiusKm: (int) ($data['radius_km'] ?? 10),
        );

        return response()->json(['data' => $branches]);
    }
}
