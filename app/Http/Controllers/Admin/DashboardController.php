<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Mail\TestEmail;
use App\Models\Graduate;
use App\Models\Alert;
use App\Models\Region;
use App\Models\AcademicRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGraduates = Graduate::count();
        $graduates = Graduate::all();
        $unemployed = $graduates->where('employment_status', 'unemployed')->count();
        $employed = $graduates->where('employment_status', 'employed')->count();
        $critical = $graduates->where('employment_status', 'unemployed')
            ->filter(fn($grad) => $grad->graduation_date && now()->diffInMonths($grad->graduation_date) >= 8)
            ->count();
        $alertsSent = Alert::where('status', 'sent')->count();
        $regionData = Region::withCount('graduates')->get();

        $averageEmployability = $graduates->count() ? round($graduates->avg->employability_score, 1) : 0;
        $atRiskGraduates = $graduates->filter(fn($grad) => $grad->employability_score < 45)->count();
        $highPotentialGraduates = $graduates->filter(fn($grad) => $grad->employability_score >= 75)->count();

        // Academic Verification Stats
        $verifiedRecords = AcademicRecord::where('status', 'verified')->count();
        $pendingRecords = AcademicRecord::where('status', 'pending')->count();
        $rejectedRecords = AcademicRecord::where('status', 'rejected')->count();
        $manualReviewRecords = AcademicRecord::where('status', 'manual_review')->count();
        $totalAcademicRecords = AcademicRecord::count();
        
        // Graduates with verified academic records
        $graduatesWithVerification = Graduate::whereHas('academicRecords', function($query) {
            $query->where('status', 'verified');
        })->count();
        
        return view('admin.dashboard', compact(
            'totalGraduates', 'unemployed', 'employed', 'critical', 'alertsSent', 'regionData',
            'verifiedRecords', 'pendingRecords', 'rejectedRecords', 'manualReviewRecords', 
            'totalAcademicRecords', 'graduatesWithVerification',
            'averageEmployability', 'atRiskGraduates', 'highPotentialGraduates'
        ));
    }

    public function heatmap()
    {
        return view('admin.heatmap');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function settings()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        // Validate and save settings
        $validated = $request->validate([
            'system_name' => 'nullable|string|max:255',
            'system_email' => 'nullable|email',
            'alert_threshold' => 'nullable|integer|min:1|max:24',
            'items_per_page' => 'nullable|integer|in:10,25,50,100',
            'mail_from_address' => 'nullable|email',
            'mail_from_name' => 'nullable|string|max:255',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer',
            'smtp_encryption' => 'nullable|in:tls,ssl,none',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_timeout' => 'nullable|integer|min:1|max:120',
            'session_timeout_minutes' => 'nullable|integer|min:5|max:1440',
            'password_min_length' => 'nullable|integer|min:6|max:64',
            'require_strong_passwords' => 'nullable|boolean',
        ]);

        $validated['require_strong_passwords'] = $request->boolean('require_strong_passwords');

        // Persist settings to DB
        foreach ($validated as $key => $value) {
            Setting::set($key, $value ?? '');
        }

        return back()->with('success', 'Settings saved successfully!');
    }

    public function testMail(Request $request)
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            Mail::to($validated['test_email'])->send(new TestEmail());
        } catch (\Throwable $e) {
            return back()->with('error', 'Test email failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Test email sent successfully to ' . $validated['test_email']);
    }

    public function clearCache()
    {
        Artisan::call('optimize:clear');
        return back()->with('success', 'All cache cleared successfully!');
    }

    public function backup()
    {
        // Simple backup - create JSON export
        $data = [
            'graduates' => Graduate::all(),
            'alerts' => Alert::all(),
            'exported_at' => now()->toISOString()
        ];
        
        $filename = 'backup_' . now()->format('Y_m_d_H_i_s') . '.json';
        $path = storage_path('app/backups/' . $filename);
        
        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0777, true);
        }
        
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
        
        return back()->with('success', 'Backup created: ' . $filename);
    }
}