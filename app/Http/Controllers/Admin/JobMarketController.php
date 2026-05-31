<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobMarketTrend;
use App\Services\AuditService;
use Illuminate\Http\Request;

class JobMarketController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Job market trends dashboard
     */
    public function dashboard()
    {
        $topDemandedJobs = JobMarketTrend::where('demand_level', 'high')
            ->orderBy('vacancy_count', 'desc')
            ->limit(10)
            ->get();

        $trendingUp = JobMarketTrend::where('trending_up', true)
            ->orderBy('trend_percentage', 'desc')
            ->limit(5)
            ->get();

        $bySalary = JobMarketTrend::orderBy('average_salary', 'desc')
            ->limit(10)
            ->get();

        $industries = JobMarketTrend::selectRaw('industry, COUNT(*) as count, AVG(average_salary) as avg_salary')
            ->groupBy('industry')
            ->get();

        return view('admin.job-market.dashboard', compact(
            'topDemandedJobs',
            'trendingUp',
            'bySalary',
            'industries'
        ));
    }

    /**
     * List all trends
     */
    public function index(Request $request)
    {
        $query = JobMarketTrend::query();

        if ($request->has('industry')) {
            $query->where('industry', $request->industry);
        }

        if ($request->has('demand_level')) {
            $query->where('demand_level', $request->demand_level);
        }

        if ($request->has('trending')) {
            $query->where('trending_up', $request->trending === 'up' ? true : false);
        }

        $trends = $query->paginate(20);

        return view('admin.job-market.index', compact('trends'));
    }

    /**
     * Create trend (manual entry or from integration)
     */
    public function create()
    {
        return view('admin.job-market.create');
    }

    /**
     * Store trend
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'industry' => 'required|string|max:100',
            'job_title' => 'required|string|max:100',
            'region' => 'nullable|string|max:100',
            'demand_level' => 'required|in:high,medium,low',
            'average_salary' => 'nullable|numeric',
            'salary_range_min' => 'nullable|numeric',
            'salary_range_max' => 'nullable|numeric',
            'required_skills' => 'nullable|string',
            'vacancy_count' => 'nullable|integer',
            'trend_percentage' => 'nullable|numeric',
        ]);

        $validated['required_skills'] = explode(',', $validated['required_skills'] ?? '');
        $validated['last_updated'] = now();
        $validated['trending_up'] = ($validated['trend_percentage'] ?? 0) > 0;

        $trend = JobMarketTrend::create($validated);

        $this->auditService->log('created', 'JobMarketTrend', $trend->id, [], $validated, 'Created job market trend');

        return redirect()->route('admin.job-market.show', $trend)
            ->with('success', 'Job market trend created successfully.');
    }

    /**
     * Show trend detail
     */
    public function show(JobMarketTrend $trend)
    {
        // Get related graduates
        $matchedGraduates = \App\Models\Graduate::where('job_title', $trend->job_title)
            ->limit(10)
            ->get();

        return view('admin.job-market.show', compact('trend', 'matchedGraduates'));
    }

    /**
     * Bulk import trends
     */
    public function bulkImport(Request $request)
    {
        $request->validate(['csv_file' => 'required|file|mimes:csv,txt']);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $count = 0;

        while (($line = fgetcsv($handle)) !== false) {
            if ($count === 0) {
                $count++;
                continue; // Skip header
            }

            JobMarketTrend::create([
                'industry' => $line[0] ?? null,
                'job_title' => $line[1] ?? null,
                'region' => $line[2] ?? null,
                'demand_level' => $line[3] ?? 'medium',
                'average_salary' => $line[4] ?? null,
                'vacancy_count' => $line[5] ?? 0,
                'last_updated' => now(),
            ]);
            $count++;
        }

        fclose($handle);

        return back()->with('success', "Imported {$count} job market trends.");
    }
}
