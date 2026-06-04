<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobMarket extends Model
{
    protected $table = 'job_market';

    protected $fillable = [
        'title',
        'description',
        'required_skills',
        'min_gpa',
        'salary_range',
        'region_id',
        'employer_id',
    ];

    protected $casts = [
        'required_skills' => 'array',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function employer()
    {
        return $this->belongsTo(User::class, 'employer_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
