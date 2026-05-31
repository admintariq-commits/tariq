<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobMarketTrend extends Model
{
    use HasFactory;

    protected $fillable = [
        'industry',
        'job_title',
        'region',
        'demand_level', // high, medium, low
        'average_salary',
        'salary_range_min',
        'salary_range_max',
        'required_skills',
        'experience_level',
        'vacancy_count',
        'applications_count',
        'trending_up', // boolean
        'trend_percentage', // % change
        'data_source', // integration source
        'last_updated',
    ];

    protected $casts = [
        'required_skills' => 'array',
        'trending_up' => 'boolean',
        'trend_percentage' => 'float',
        'last_updated' => 'datetime',
        'vacancy_count' => 'integer',
        'applications_count' => 'integer',
    ];

    public function getTrendIndicatorAttribute()
    {
        if ($this->trending_up) {
            return ['direction' => 'up', 'icon' => '📈', 'color' => 'green'];
        }
        return ['direction' => 'down', 'icon' => '📉', 'color' => 'red'];
    }
}
