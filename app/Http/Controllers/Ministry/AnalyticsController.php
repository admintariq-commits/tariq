<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected AnalyticsService $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function dashboard()
    {
        $reports = $this->analyticsService->getReportHistory(8);

        return view('ministry.analytics.dashboard', compact('reports'));
    }

    public function employmentTrends(Request $request)
    {
        $filters = $request->only(['region', 'university_id']);
        $report = $this->analyticsService->generateEmploymentTrendsReport($filters);

        return view('ministry.analytics.employment-trends', compact('report'));
    }

    public function salaryAnalysis(Request $request)
    {
        $filters = $request->only(['region', 'job_title']);
        $report = $this->analyticsService->generateSalaryAnalysisReport($filters);

        return view('ministry.analytics.salary-analysis', compact('report'));
    }

    public function skillsGap()
    {
        $report = $this->analyticsService->generateSkillsGapReport();

        return view('ministry.analytics.skills-gap', compact('report'));
    }

    public function profileCompleteness(Request $request)
    {
        $filters = $request->only(['region', 'degree']);
        $report = $this->analyticsService->generateProfileCompletenessReport($filters);

        return view('ministry.analytics.profile-completeness', compact('report'));
    }
}
