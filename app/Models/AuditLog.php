<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action', // created, updated, deleted, verified, exported
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'description',
        'timestamp',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'timestamp' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable change description
     */
    public function getChangeDescriptionAttribute()
    {
        if (empty($this->old_values) || empty($this->new_values)) {
            return null;
        }

        $changes = [];
        foreach ($this->old_values as $key => $oldValue) {
            $newValue = $this->new_values[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changes[] = "{$key}: '{$oldValue}' → '{$newValue}'";
            }
        }

        return implode(', ', $changes);
    }
}
