<?php

namespace App\Services\AcademicVerification;

interface AcademicVerificationInterface
{
    /**
     * Verify academic record using the provided index number.
     *
     * @param string $indexNumber
     * @param string|null $registrationNumber
     * @return array{status: string, data?: array, message?: string}
     */
    public function verify(string $indexNumber, ?string $registrationNumber = null): array;
}
