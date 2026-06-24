<?php

namespace App\Domains\Restaurant\Services;

use App\Models\RestaurantBranch;
use Illuminate\Database\Eloquent\Collection;

final class GeospatialLocationService
{
    // Earth radius in km used for Haversine formula
    private const EARTH_RADIUS_KM = 6371;

    public function findNearbyBranches(float $lat, float $lng, int $radiusKm = 10): Collection
    {
        $haversine = sprintf(
            '(%d * ACOS(GREATEST(-1, LEAST(1, COS(RADIANS(?)) * COS(RADIANS(lat)) * COS(RADIANS(lng) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(lat))))))',
            self::EARTH_RADIUS_KM
        );

        return RestaurantBranch::query()
            ->selectRaw("*, {$haversine} AS distance_km", [$lat, $lng, $lat])
            ->whereRaw("{$haversine} <= ?", [$lat, $lng, $lat, $radiusKm])
            ->where('is_active', true)
            ->where('accept_orders', true)
            ->orderBy('distance_km')
            ->get();
    }
}
