<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Services\AuditService;
use App\Models\AnalyticsReport;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;
    protected $auditService;

    public function __construct(AnalyticsService $analyticsService, AuditService $auditService)
    {
        $this->analyticsService = $analyticsService;
        $this->auditService = $auditService;
    }

    /**
     * Analytics dashboard
     */
    public function dashboard()
    {
        $reports = $this->analyticsService->getReportHistory(5);
        
        return view('admin.analytics.dashboard', compact('reports'));
    }

    /**
     * Employment trends report
     */
    public function employmentTrends(Request $request)
    {
        $filters = $request->only(['region', 'university_id']);
        $report = $this->analyticsService->generateEmploymentTrendsReport($filters);
        
        $this->auditService->log('generated_report', 'AnalyticsReport', $report->id, [], [], 'Generated employment trends report');
        
        return view('admin.analytics.employment-trends', compact('report'));
    }

    /**
     * Salary analysis report
     */
    public function salaryAnalysis(Request $request)
    {
        $filters = $request->only(['region', 'job_title']);
        $report = $this->analyticsService->generateSalaryAnalysisReport($filters);
        
        return view('admin.analytics.salary-analysis', compact('report'));
    }

    /**
     * Skills gap report
     */
    public function skillsGap()
    {
        $report = $this->analyticsService->generateSkillsGapReport();
        
        return view('admin.analytics.skills-gap', compact('report'));
    }

    /**
     * Export report
     */
    public function exportReport(AnalyticsReport $report)
    {
        $csv = "Analytics Report - {$report->name}\n\n";
        $csv .= "Type: {$report->type}\n";
        $csv .= "Generated: {$report->generated_at}\n\n";
        $csv .= "Data:\n";
        $csv .= json_encode($report->data, JSON_PRETTY_PRINT);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="report-' . $report->id . '-' . date('Y-m-d') . '.csv"');
    }
}
