<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CareerResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type', // guide, course, video, article, tool
        'category', // interview_prep, skill_development, career_planning
        'url',
        'provider',
        'duration_minutes',
        'difficulty_level', // beginner, intermediate, advanced
        'is_free',
        'cost',
        'rating',
        'views_count',
        'helpful_count',
        'created_by',
        'is_featured',
        'tags',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'tags' => 'array',
        'rating' => 'float',
        'views_count' => 'integer',
        'helpful_count' => 'integer',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function graduateAccessLogs()
    {
        return $this->hasMany(CareerResourceAccess::class);
    }
}
