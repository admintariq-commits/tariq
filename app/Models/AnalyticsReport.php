<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnalyticsReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type', // employment_trends, salary_analysis, skills_gap, regional_analysis
        'description',
        'data',
        'generated_by',
        'generated_at',
        'filters', // stores filter criteria used
        'is_public',
        'scheduled_email_recipients',
    ];

    protected $casts = [
        'data' => 'array',
        'filters' => 'array',
        'scheduled_email_recipients' => 'array',
        'generated_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function getReportSummaryAttribute()
    {
        return [
            'type' => $this->type,
            'generated_at' => $this->generated_at->format('Y-m-d H:i:s'),
            'data_points' => count($this->data ?? []),
            'is_recent' => $this->generated_at->diffInDays(now()) < 7,
        ];
    }
}
