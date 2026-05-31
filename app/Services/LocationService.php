<?php

namespace App\Services;

class LocationService
{
    public static function estimateRegionFromCoordinates(?float $latitude, ?float $longitude): ?string
    {
        if ($latitude === null || $longitude === null) {
            return null;
        }

        if ($latitude >= 6.4 && $latitude <= 7.2 && $longitude >= 39.0 && $longitude <= 39.5) {
            return 'Dar es Salaam';
        }

        if ($latitude >= -3.5 && $latitude <= -2.0 && $longitude >= 36.4 && $longitude <= 37.2) {
            return 'Arusha';
        }

        if ($latitude >= -6.4 && $latitude <= -5.6 && $longitude >= 35.4 && $longitude <= 36.6) {
            return 'Dodoma';
        }

        if ($latitude >= -9.0 && $latitude <= -7.9 && $longitude >= 32.5 && $longitude <= 34.0) {
            return 'Mbeya';
        }

        if ($latitude >= -4.5 && $latitude <= -2.5 && $longitude >= 37.0 && $longitude <= 38.5) {
            return 'Kilimanjaro';
        }

        if ($latitude >= -7.3 && $latitude <= -5.5 && $longitude >= 37.0 && $longitude <= 38.7) {
            return 'Morogoro';
        }

        if ($latitude >= -3.5 && $latitude <= -1.0 && $longitude >= 31.0 && $longitude <= 33.0) {
            return 'Mwanza';
        }

        if ($latitude >= -6.0 && $latitude <= -4.0 && $longitude >= 37.5 && $longitude <= 39.5) {
            return 'Tanga';
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
