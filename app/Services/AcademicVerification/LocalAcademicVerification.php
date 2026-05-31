<?php

namespace App\Services\AcademicVerification;

class LocalAcademicVerification implements AcademicVerificationInterface
{
    /**
     * Local/fallback verification - returns pending status.
     * In production, this would call real ministry/university APIs.
     */
    public function verify(string $indexNumber, ?string $registrationNumber = null): array
    {
        // For now, this is a placeholder
        // In future, this can be switched to:
        // - RemoteMinistryVerification (HTTP API call)
        // - UniversityDatabaseVerification (direct DB query)
        // - CachedVerification (check local cache)

        return [
            'status' => 'pending',
            'message' => 'Academic record submitted. Verification pending.',
            'data' => [
                'index_number' => $indexNumber,
                'registration_number' => $registrationNumber,
                'verified_at' => null,
                'source' => 'manual',
            ],
        ];
    }
}
