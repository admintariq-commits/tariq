<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use App\Http\Requests\Graduate\AcademicVerificationRequest;
use App\Models\AcademicRecord;
use App\Services\AcademicVerification\LocalAcademicVerification;
use Illuminate\Http\Request;

class AcademicVerificationController extends Controller
{
    protected LocalAcademicVerification $verificationService;

    public function __construct()
    {
        $this->verificationService = new LocalAcademicVerification();
    }

    public function show()
    {
        return view('graduate.verify-academic');
    }

    public function verify(AcademicVerificationRequest $request)
    {
        $indexNumber = $request->validated()['index_number'];
        $registrationNumber = $request->validated()['registration_number'] ?? null;

        // Check if already verified
        $existing = AcademicRecord::where('index_number', $indexNumber)->first();
        if ($existing && $existing->isVerified()) {
            return response()->json([
                'status' => 'already_verified',
                'message' => 'This index number is already verified.',
                'data' => [
                    'university' => $existing->university,
                    'course' => $existing->course,
                    'degree' => $existing->degree,
                ],
            ]);
        }

        // Verify using the service
        $result = $this->verificationService->verify($indexNumber, $registrationNumber);

        // Create or update academic record
        $record = AcademicRecord::updateOrCreate(
            ['index_number' => $indexNumber],
            [
                'registration_number' => $registrationNumber,
                'status' => $result['status'],
                'source' => $result['data']['source'] ?? 'manual',
                'verification_data' => $result['data'] ?? null,
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
            'record_id' => $record->id,
            'verification_status' => $record->status,
        ]);
    }

    public function checkStatus($indexNumber)
    {
        $record = AcademicRecord::where('index_number', $indexNumber)->first();

        if (!$record) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'No record found for this index number.',
            ], 404);
        }

        return response()->json([
            'status' => $record->status,
            'message' => match($record->status) {
                'verified' => 'Academic record verified ✓',
                'pending' => 'Verification pending - please check later',
                'manual_review' => 'Under manual review',
                'rejected' => 'Verification rejected',
                default => 'Unknown status',
            },
            'data' => [
                'university' => $record->university,
                'course' => $record->course,
                'degree' => $record->degree,
                'gpa' => $record->gpa,
            ],
        ]);
    }
}
