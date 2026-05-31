<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicRecord extends Model
{
    protected $fillable = [
        'graduate_id',
        'user_id',
        'index_number',
        'registration_number',
        'university',
        'course',
        'degree',
        'graduation_year',
        'gpa',
        'status',
        'source',
        'verification_data',
        'notes',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'verification_data' => 'array',
        'verified_at' => 'datetime',
        'graduation_year' => 'integer',
        'gpa' => 'float',
    ];

    public function graduate(): BelongsTo
    {
        return $this->belongsTo(Graduate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isPendingManualReview(): bool
    {
        return $this->status === 'manual_review';
    }

    public function markAsVerified(User $admin, ?array $data = null): void
    {
        $this->update([
            'status' => 'verified',
            'verified_at' => now(),
            'verified_by' => $admin->id,
            'verification_data' => $data,
        ]);
    }

    public function markAsManualReview(string $reason = ''): void
    {
        $this->update([
            'status' => 'manual_review',
            'notes' => $reason,
        ]);
    }

    public function markAsRejected(string $reason = ''): void
    {
        $this->update([
            'status' => 'rejected',
            'notes' => $reason,
        ]);
    }
}
