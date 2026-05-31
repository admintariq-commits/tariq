<?php

namespace App\Services;

use App\Models\Graduate;
use App\Models\AnalyticsReport;
use App\Models\Employment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    /**
     * Generate employment trends report
     */
    public function generateEmploymentTrendsReport($filters = [])
    {
        $query = Graduate::query();

        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (isset($filters['university_id'])) {
            $query->where('university_id', $filters['university_id']);
        }

        // Employment status breakdown
        $statusBreakdown = $query->groupBy('employment_status')
            ->selectRaw('employment_status, COUNT(*) as count, AVG(experience_years) as avg_experience')
            ->get()
            ->toArray();

        // Unemployment duration
        $unemploymentDuration = Graduate::where('employment_status', 'unemployed')
            ->selectRaw('
                CASE 
                    WHEN julianday("now") - julianday(graduation_date) < 30 THEN "0-1 month"
                    WHEN julianday("now") - julianday(graduation_date) < 90 THEN "1-3 months"
                    WHEN julianday("now") - julianday(graduation_date) < 180 THEN "3-6 months"
                    WHEN julianday("now") - julianday(graduation_date) < 365 THEN "6-12 months"
                    ELSE "12+ months"
                END as duration_range,
                COUNT(*) as count
            ')
            ->groupBy('duration_range')
            ->get()
            ->toArray();

        // Time to employment
        $employed = Graduate::where('employment_status', 'employed')
            ->selectRaw('AVG(julianday(last_employment_date) - julianday(graduation_date)) as avg_days_to_employment')
            ->value('avg_days_to_employment');

        $data = [
            'status_breakdown' => $statusBreakdown,
            'unemployment_duration' => $unemploymentDuration,
            'average_days_to_employment' => round($employed ?? 0),
            'total_graduates' => Graduate::count(),
            'employed_percentage' => round((Graduate::where('employment_status', 'employed')->count() / Graduate::count() * 100), 2),
        ];

        return AnalyticsReport::create([
            'name' => 'Employment Trends Report - ' . date('Y-m-d'),
            'type' => 'employment_trends',
            'description' => 'Analysis of employment status and trends',
            'data' => $data,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
            'filters' => $filters,
            'is_public' => false,
        ]);
    }

    /**
     * Generate salary analysis report
     */
    public function generateSalaryAnalysisReport($filters = [])
    {
        $query = Employment::query();

        if (isset($filters['region'])) {
            $query->whereHas('graduate', function ($q) {
                $q->where('region', $filters['region']);
            });
        }

        if (isset($filters['job_title'])) {
            $query->where('job_title', $filters['job_title']);
        }

        $salaryData = $query->selectRaw('
            job_title,
            COUNT(*) as count,
            AVG(salary) as avg_salary,
            MIN(salary) as min_salary,
            MAX(salary) as max_salary,
            STDDEV(salary) as salary_stddev
        ')
            ->groupBy('job_title')
            ->get()
            ->toArray();

        $regionalSalaries = Graduate::selectRaw('
            region,
            COUNT(*) as count,
            AVG(expected_salary) as avg_expected_salary
        ')
            ->groupBy('region')
            ->get()
            ->toArray();

        $data = [
            'salary_by_job_title' => $salaryData,
            'regional_salaries' => $regionalSalaries,
            'overall_average_salary' => round(Employment::avg('salary') ?? 0, 2),
        ];

        return AnalyticsReport::create([
            'name' => 'Salary Analysis Report - ' . date('Y-m-d'),
            'type' => 'salary_analysis',
            'description' => 'Salary analysis by job title and region',
            'data' => $data,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
            'filters' => $filters,
            'is_public' => false,
        ]);
    }

    /**
     * Generate skills gap analysis
     */
    public function generateSkillsGapReport()
    {
        // Get top in-demand skills from job market
        $demandedSkills = \App\Models\JobMarketTrend::selectRaw('
            json_each.value as skill,
            COUNT(*) as demand_count
        ')
            ->where('demand_level', 'high')
            ->orderBy('demand_count', 'desc')
            ->get()
            ->toArray();

        // Get available skills from graduates
        $availableSkills = Graduate::selectRaw('
            json_each.value as skill,
            COUNT(*) as available_count
        ')
            ->orderBy('available_count', 'desc')
            ->take(20)
            ->get()
            ->toArray();

        $data = [
            'demanded_skills' => $demandedSkills,
            'available_skills' => $availableSkills,
            'gap_analysis' => 'Skills analysis completed',
        ];

        return AnalyticsReport::create([
            'name' => 'Skills Gap Analysis - ' . date('Y-m-d'),
            'type' => 'skills_gap',
            'description' => 'Analysis of skills gaps between demand and supply',
            'data' => $data,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
            'is_public' => false,
        ]);
    }

    /**
     * Generate profile completeness report
     */
    public function generateProfileCompletenessReport($filters = [])
    {
        $query = Graduate::query();

        if (isset($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        if (isset($filters['degree'])) {
            $query->where('degree', $filters['degree']);
        }

        $graduates = $query->get();
        $totalGraduates = $graduates->count();

        $averageCompletion = round($graduates->avg(fn($graduate) => $graduate->completion_percentage) ?? 0, 2);
        $completeProfiles = $graduates->filter(fn($graduate) => $graduate->completion_percentage >= 90)->count();
        $minimumCompletion = $graduates->min(fn($graduate) => $graduate->completion_percentage) ?? 0;

        $missingFields = collect([
            'phone', 'national_id', 'gender', 'university', 'course', 'degree', 'graduation_date',
            'gpa', 'region', 'employment_status', 'job_title', 'experience_years', 'skills',
            'languages', 'linkedin', 'resume_path',
        ])->mapWithKeys(fn($field) => [$field => 0])->toArray();

        foreach ($graduates as $graduate) {
            foreach (array_keys($missingFields) as $field) {
                if (empty($graduate->$field)) {
                    $missingFields[$field]++;
                }
            }
        }

        arsort($missingFields);
        $missingFields = array_slice($missingFields, 0, 6, true);

        $completionByStatus = $graduates->groupBy('employment_status')->map(function ($group, $status) {
            return [
                'employment_status' => $status ?: 'Unknown',
                'average_completion' => round($group->avg(fn($graduate) => $graduate->completion_percentage) ?? 0, 2),
                'count' => $group->count(),
            ];
        })->values()->toArray();

        $completionByRegion = $graduates->groupBy('region')->map(function ($group, $region) {
            return [
                'region' => $region ?: 'Unknown',
                'average_completion' => round($group->avg(fn($graduate) => $graduate->completion_percentage) ?? 0, 2),
                'count' => $group->count(),
            ];
        })->sortByDesc('average_completion')->take(5)->values()->toArray();

        $data = [
            'total_graduates' => $totalGraduates,
            'average_profile_completion' => $averageCompletion,
            'complete_profiles' => $completeProfiles,
            'minimum_profile_completion' => $minimumCompletion,
            'missing_fields' => $missingFields,
            'completion_by_status' => $completionByStatus,
            'completion_by_region' => $completionByRegion,
            'report_summary' => 'Profile completion and data quality comparison for graduate profiles.',
        ];

        return AnalyticsReport::create([
            'name' => 'Profile Completeness Report - ' . date('Y-m-d'),
            'type' => 'profile_completeness',
            'description' => 'Analyze graduate profile completeness and compare filled fields against employment outcomes',
            'data' => $data,
            'generated_by' => auth()->id(),
            'generated_at' => now(),
            'filters' => $filters,
            'is_public' => false,
        ]);
    }

    /**
     * Get report history
     */
    public function getReportHistory($limit = 10)
    {
        return AnalyticsReport::orderBy('generated_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
