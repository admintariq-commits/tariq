<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Course;
use App\Models\Graduate;
use App\Models\Region;
use App\Models\University;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::orderBy('name')->pluck('name');
        $degrees = Course::select('level')->whereNotNull('level')->groupBy('level')->orderBy('level')->pluck('level');
        if ($degrees->isEmpty()) {
            $degrees = collect(['Certificate', 'Diploma', 'Bachelor', 'Master', 'PhD']);
        }
        $universities = University::orderBy('name')->pluck('name');
        $courses = Course::orderBy('name')->pluck('name');

        $filters = $request->only(['region', 'degree', 'university', 'course']);
        $query = Graduate::query();
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                $query->where($field, $value);
            }
        }

        // Keep one filtered snapshot so every card and policy signal uses the same population.
        $graduates = $query->get();
        $totalGraduates = $graduates->count();
        $registeredLast30Days = $graduates->filter(fn ($graduate) => $graduate->created_at && $graduate->created_at->greaterThanOrEqualTo(now()->subDays(30)))->count();
        $unemployed = $graduates->where('employment_status', 'unemployed');
        $unemployedCount = $unemployed->count();
        $atRiskCount = $unemployed->filter(fn ($graduate) => $graduate->months_unemployed >= 8)->count();
        $averageEmployability = (int) round($graduates->avg(fn ($graduate) => $graduate->employability_score) ?? 0);
        $averageUnemployedEmployability = (int) round($unemployed->avg(fn ($graduate) => $graduate->employability_score) ?? 0);
        $averageProfileCompletion = (int) round($graduates->avg(fn ($graduate) => $graduate->completion_percentage) ?? 0);

        $topUnemployedRegions = $unemployed
            ->filter(fn ($graduate) => filled($graduate->region))
            ->groupBy('region')
            ->map(fn ($items, $region) => (object) ['region' => $region, 'count' => $items->count()])
            ->sortByDesc('count')
            ->take(5)
            ->values();

        $mostCommonDegrees = $graduates
            ->filter(fn ($graduate) => filled($graduate->degree))
            ->groupBy('degree')
            ->map(fn ($items, $degree) => (object) ['degree' => $degree, 'count' => $items->count()])
            ->sortByDesc('count')
            ->take(5)
            ->values();

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
            'recentAlerts',
            'regions',
            'degrees',
            'universities',
            'courses',
            'filters'
        ));
    }

    /**
     * Export a filtered, privacy-conscious graduate registry CSV.
     */
    public function exportGraduates(Request $request)
    {
        $filters = $request->only(['region', 'degree', 'university', 'course']);
        $query = Graduate::query();

        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                $query->where($field, $value);
            }
        }

        $fileName = 'graduates_registry_export_' . date('Ymd_His') . '.csv';
        $response = new StreamedResponse(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Graduate ID', 'University', 'Course', 'Degree', 'Region',
                'Employment Status', 'Graduation Year', 'Skills', 'Verification Status'
            ]);

            $query->chunk(200, function ($graduates) use ($handle) {
                foreach ($graduates as $graduate) {
                    fputcsv($handle, [
                        $graduate->id,
                        $graduate->university,
                        $graduate->course,
                        $graduate->degree,
                        $graduate->region,
                        $graduate->employment_status,
                        $graduate->graduation_year,
                        $graduate->skills,
                        $graduate->document_verification_status,
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
