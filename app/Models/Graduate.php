<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Graduate extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'middle_name', 'last_name', 'graduation_date',
        'university_id', 'course_id', 'gpa', 'phone', 'skills', 'employment_status',
        'last_employment_date', 'resume_path', 'is_verified',
        'national_id', 'gender', 'university', 'course', 'degree', 'graduation_year',
        'region', 'latitude', 'longitude', 'detected_region', 'location_source', 'region_match', 'location_accuracy',
        'job_title', 'expected_salary', 'experience_years', 'linkedin',
        'languages', 'certifications', 'job_preferences', 'phone_verified',
        'document_hash', 'document_verification_status', 'document_verified_at', 'document_verified_by'
    ];

    protected $casts = [
        'graduation_date' => 'date',
        'last_employment_date' => 'date',
        'is_verified' => 'boolean',
        'region_match' => 'boolean',
        'document_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function employments()
    {
        return $this->hasMany(Employment::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function academicRecords()
    {
        return $this->hasMany(AcademicRecord::class);
    }

    /**
     * Calculate profile completion percentage
     */
    public function getCompletionPercentage()
    {
        $fields = [
            'phone' => 5,
            'national_id' => 5,
            'gender' => 5,
            'university' => 10,
            'course' => 10,
            'degree' => 10,
            'graduation_date' => 5,
            'gpa' => 5,
            'region' => 5,
            'employment_status' => 10,
            'job_title' => 5,
            'experience_years' => 5,
            'skills' => 5,
            'languages' => 3,
            'linkedin' => 2,
            'resume_path' => 5,
        ];

        $completed = 0;

        foreach ($fields as $field => $weight) {
            if (!empty($this->$field)) {
                $completed += $weight / 100;
            }
        }

        return min(100, (int)($completed * 100));
    }

    public function getCompletionPercentageAttribute()
    {
        return $this->getCompletionPercentage();
    }

    public function getEmployabilityScoreAttribute()
    {
        $score = 0;

        $score += $this->getCompletionPercentage() * 0.35;
        $score += $this->is_verified ? 15 : 0;
        $score += !empty($this->resume_path) ? 10 : 0;

        if ($this->employment_status === 'employed') {
            $score += 10;
        } elseif ($this->employment_status === 'self_employed') {
            $score += 8;
        }

        $score += min(10, max(0, (int) $this->experience_years));
        $score += !empty($this->skills) ? 10 : 0;
        $score += $this->region_match ? 5 : 0;

        if ($this->employment_status === 'unemployed') {
            $score -= min(20, $this->months_unemployed * 1.5);
        }

        return max(0, min(100, (int) round($score)));
    }

    public function getCareerReadinessAttribute()
    {
        $score = $this->employability_score;

        if ($score >= 80) {
            return 'Highly Ready';
        }

        if ($score >= 60) {
            return 'Ready for jobs';
        }

        if ($score >= 40) {
            return 'Needs upskilling';
        }

        return 'High priority support';
    }

    public function getFullNameAttribute()
    {
        $middle = $this->middle_name ? ' ' . $this->middle_name . ' ' : ' ';
        return $this->first_name . $middle . $this->last_name;
    }

    public function getMonthsUnemployedAttribute()
    {
        if ($this->employment_status !== 'unemployed') {
            return 0;
        }

        $start = $this->last_employment_date ?? $this->graduation_date;
        if (!$start) {
            return 0;
        }

        return max(0, now()->diffInMonths($start));
    }
}
