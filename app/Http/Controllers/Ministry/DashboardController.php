<?php
namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Graduate;
use App\Models\Alert;
use App\Models\University;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        // lists for filters
        $regions = Region::orderBy('name')->pluck('name');
        $degrees = Course::select('level')->whereNotNull('level')->groupBy('level')->orderBy('level')->pluck('level');
        if ($degrees->isEmpty()) {
            $degrees = collect(['Certificate', 'Diploma', 'Bachelor', 'Master', 'PhD']);
        }
        $universities = University::orderBy('name')->pluck('name');
        // course list comes from master Course model for clearer program selection
        $courses = Course::orderBy('name')->pluck('name');

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
            , 'regions', 'degrees', 'universities', 'courses'
        ));
    }

    /**
     * Export graduates list as CSV with optional filters
     */
    public function exportGraduates(Request $request)
    {
        $filters = $request->only(['region', 'degree', 'university', 'course']);

        $query = Graduate::query();
        if (!empty($filters['region'])) {
            $query->where('region', $filters['region']);
        }
        if (!empty($filters['degree'])) {
            $query->where('degree', $filters['degree']);
        }
        if (!empty($filters['course'])) {
            $query->where('course', $filters['course']);
        }
        if (!empty($filters['university'])) {
            $query->where('university', $filters['university']);
        }

        $fileName = 'graduates_export_' . date('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            // header row
            fputcsv($handle, ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'University', 'Course', 'Degree', 'Region', 'Employment Status', 'Graduation Year']);

            $query->chunk(200, function ($graduates) use ($handle) {
                foreach ($graduates as $g) {
                    fputcsv($handle, [
                        $g->id,
                        $g->first_name,
                        $g->last_name,
                        $g->user->email ?? '',
                        $g->phone,
                        $g->university,
                        $g->course,
                        $g->degree,
                        $g->region,
                        $g->employment_status,
                        $g->graduation_year,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$fileName}\"");

        return $response;
    }
}
