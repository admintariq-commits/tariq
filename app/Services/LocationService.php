<?php

namespace App\Services;

class LocationService
{
    /**
     * Map coordinates to region using the published GeoJSON data
     * The database and GeoJSON both contain the same regions
     */
    public static function estimateRegionFromCoordinates(?float $latitude, ?float $longitude): ?string
    {
        if ($latitude === null || $longitude === null) {
            return null;
        }

        // Simplified: Get from database regions table or hardcoded list from GeoJSON
        // Tanzanian regions (from tanzania.geojson)
        $regions = [
            'Arusha' => ['lat' => [-4.0, -3.0], 'lng' => [36.0, 37.0]],
            'Dar es Salaam' => ['lat' => [-7.0, -6.5], 'lng' => [39.0, 39.5]],
            'Dodoma' => ['lat' => [-6.5, -5.5], 'lng' => [35.5, 36.5]],
            'Geita' => ['lat' => [-3.5, -2.5], 'lng' => [32.0, 33.0]],
            'Iringa' => ['lat' => [-8.5, -7.5], 'lng' => [35.0, 36.0]],
            'Kagera' => ['lat' => [-2.5, -1.0], 'lng' => [31.0, 32.0]],
            'Katavi' => ['lat' => [-7.0, -6.0], 'lng' => [31.0, 32.0]],
            'Kigoma' => ['lat' => [-5.5, -4.5], 'lng' => [29.5, 30.5]],
            'Kilimanjaro' => ['lat' => [-4.0, -3.0], 'lng' => [37.0, 38.0]],
            'Lindi' => ['lat' => [-10.0, -9.0], 'lng' => [39.0, 40.0]],
            'Manyara' => ['lat' => [-5.0, -4.0], 'lng' => [36.0, 37.0]],
            'Mara' => ['lat' => [-2.5, -1.5], 'lng' => [34.0, 35.0]],
            'Mbeya' => ['lat' => [-9.5, -8.5], 'lng' => [33.0, 34.0]],
            'Morogoro' => ['lat' => [-7.5, -6.5], 'lng' => [37.0, 38.0]],
            'Mtwara' => ['lat' => [-11.0, -10.0], 'lng' => [39.5, 40.5]],
            'Mwanza' => ['lat' => [-3.5, -2.5], 'lng' => [32.5, 33.5]],
            'Njombe' => ['lat' => [-10.0, -9.0], 'lng' => [34.5, 35.5]],
            'Pemba Kaskazini' => ['lat' => [-5.0, -4.9], 'lng' => [39.7, 39.8]],
            'Pemba Kusini' => ['lat' => [-5.4, -5.3], 'lng' => [39.7, 39.8]],
            'Pwani' => ['lat' => [-8.0, -7.0], 'lng' => [38.5, 39.5]],
            'Rukwa' => ['lat' => [-9.0, -8.0], 'lng' => [31.5, 32.5]],
            'Ruvuma' => ['lat' => [-11.5, -10.5], 'lng' => [35.5, 36.5]],
            'Shinyanga' => ['lat' => [-4.5, -3.5], 'lng' => [33.0, 34.0]],
            'Simiyu' => ['lat' => [-3.5, -2.5], 'lng' => [34.0, 35.0]],
            'Singida' => ['lat' => [-6.0, -5.0], 'lng' => [34.5, 35.5]],
            'Songwe' => ['lat' => [-10.0, -9.0], 'lng' => [31.0, 32.0]],
            'Tabora' => ['lat' => [-6.0, -5.0], 'lng' => [32.5, 33.5]],
            'Tanga' => ['lat' => [-6.0, -5.0], 'lng' => [38.0, 39.0]],
            'Unguja Kaskazini' => ['lat' => [-6.0, -5.9], 'lng' => [39.2, 39.3]],
            'Unguja Kusini' => ['lat' => [-6.2, -6.1], 'lng' => [39.3, 39.4]],
            'Unguja Mjini' => ['lat' => [-6.2, -6.1], 'lng' => [39.1, 39.2]],
        ];

        foreach ($regions as $region => $bounds) {
            if ($latitude >= $bounds['lat'][0] && $latitude <= $bounds['lat'][1] &&
                $longitude >= $bounds['lng'][0] && $longitude <= $bounds['lng'][1]) {
                return $region;
            }
        }

        return null;
    }

    public static function normalizeRegionName(?string $region): ?string
    {
        if (!$region) {
            return null;
        }

        return trim(preg_replace('/\s+/', ' ', ucwords(strtolower($region))));
    }
}
