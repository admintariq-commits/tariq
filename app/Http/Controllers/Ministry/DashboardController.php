<?php
namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Graduate;
use App\Models\Alert;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $graduates = Graduate::all();
        $totalGraduates = $graduates->count();
        $registeredLast30Days = $graduates->filter(fn($graduate) => $graduate->created_at && $graduate->created_at->greaterThanOrEqualTo(now()->subDays(30)))->count();
        $unemployedCount = $graduates->where('employment_status', 'unemployed')->count();
        $atRiskGraduates = $graduates->where('employment_status', 'unemployed')->filter(fn($graduate) => $graduate->months_unemployed >= 8)->sortByDesc(fn($graduate) => $graduate->months_unemployed)->take(5);
        $atRiskCount = $atRiskGraduates->count();
        $averageEmployability = round($graduates->avg(fn($graduate) => $graduate->employability_score) ?? 0);
        $averageUnemployedEmployability = round($graduates->where('employment_status', 'unemployed')->avg(fn($graduate) => $graduate->employability_score) ?? 0);
        $averageProfileCompletion = round($graduates->avg(fn($graduate) => $graduate->completion_percentage) ?? 0);

        $topUnemployedRegions = Graduate::select('region', DB::raw('count(*) as count'))
            ->where('employment_status', 'unemployed')
            ->whereNotNull('region')
            ->groupBy('region')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $mostCommonDegrees = Graduate::select('degree', DB::raw('count(*) as count'))
            ->whereNotNull('degree')
            ->groupBy('degree')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $recentAlerts = Alert::latest()->limit(5)->get();

        return view('ministry.dashboard', compact(
            'totalGraduates',
            'registeredLast30Days',
            'unemployedCount',
            'atRiskCount',
            'averageEmployability',
            'averageUnemployedEmployability',
            'averageProfileCompletion',
            'topUnemployedRegions',
            'mostCommonDegrees',
            'atRiskGraduates',
            'recentAlerts'
        ));
    }
}
