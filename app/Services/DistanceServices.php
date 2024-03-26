<?php

namespace App\Services;

class DistanceServices
{
    public function getDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km'): float|int
    {
        $earthRadius = [
            'km' => 6371, // kilometers
            'miles' => 3959, // miles
            'nm' => 3440, // nautical miles
        ];

        // Convert latitude and longitude from degrees to radians
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        // Calculate the distance between latitudes and longitudes
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        // Multiply by the Earth's radius to get the distance
        return $angle * $earthRadius[$unit];
    }
}
