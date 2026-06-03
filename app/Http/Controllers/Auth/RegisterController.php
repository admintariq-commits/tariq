<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterGraduateRequest;
use App\Models\User;
use App\Models\Graduate;
use App\Models\Role;
use App\Models\University;
use App\Models\Course;
use App\Services\LocationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Mail\AdminUserRegistered;
class RegisterController extends Controller
{
    public function showGraduateRegisterForm()
    {
        $universities = collect();
        $courses = collect();

        try {
            if (Schema::hasTable('universities')) {
                $universities = University::orderBy('name')->get();
            }
            if (Schema::hasTable('courses')) {
                $courses = Course::orderBy('name')->get();
            }
        } catch (\Throwable $e) {
            // Database may not be available yet during initial deploy or maintenance.
            $universities = collect();
            $courses = collect();
        }

        return view('auth.graduate-register', compact('universities', 'courses'));
    }
    public function registerGraduate(RegisterGraduateRequest $request)
    {
        $validated = $request->validated();

        // Ensure phone OTP was verified in session
        $phoneKey = preg_replace('/\s+/', '', $request->phone);
        if (!session('otp_verified_'.$phoneKey)) {
            return back()->withErrors(['phone' => 'Phone number not verified. Please verify with OTP before completing registration.'])->withInput();
        }

        $role = Role::where('name', 'graduate')->first();
        if (!$role) {
            return back()->withErrors(['error' => 'Role not found. Run seeders first.']);
        }

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $role->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('user-agent'),
            'bot_score' => $request->input('bot_score', 0),
            'vpn_detected' => $request->input('vpn_detected', false),
            'is_suspicious' => $request->input('is_suspicious', false),
            'security_flags' => $request->input('security_flags', []),
        ]);

        // store resume file with a sanitized name
        $resumePath = null;
        $documentHash = null;
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $filename = Str::slug($user->name . '-' . pathinfo($resume->getClientOriginalName(), PATHINFO_FILENAME)) . '-' . time() . '.' . $resume->getClientOriginalExtension();
            $resumePath = $resume->storeAs('resumes', $filename);
            // Create hash of file content for document verification
            $documentHash = hash_file('sha256', $resume->getRealPath());
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $detectedRegion = LocationService::normalizeRegionName($request->input('detected_region'));
        $selectedRegion = LocationService::normalizeRegionName($request->input('region'));

        if (!$detectedRegion && $latitude !== null && $longitude !== null) {
            $detectedRegion = LocationService::estimateRegionFromCoordinates((float) $latitude, (float) $longitude);
        }

        $regionMatch = true;
        if ($detectedRegion && $selectedRegion) {
            $regionMatch = strcasecmp($selectedRegion, $detectedRegion) === 0;
        }

        Graduate::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'graduation_date' => $request->graduation_date,
            'national_id' => $request->national_id,
            'gender' => $request->gender,
            'university' => $request->university,
            'course' => $request->course,
            'degree' => $request->degree,
            'graduation_year' => $request->graduation_year,
            'gpa' => $request->gpa,
            'region' => $request->region,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'detected_region' => $detectedRegion,
            'location_source' => $request->input('location_source', $detectedRegion ? 'browser' : null),
            'location_accuracy' => $request->input('location_accuracy'),
            'region_match' => $regionMatch,
            'employment_status' => $request->employment_status ?? 'unemployed',
            'job_title' => $request->job_title,
            'expected_salary' => $request->expected_salary,
            'experience_years' => $request->experience_years,
            'linkedin' => $request->linkedin,
            'skills' => $request->skills,
            'languages' => $request->languages,
            'certifications' => $request->certifications,
            'job_preferences' => $request->job_preferences,
            'resume_path' => $resumePath,
            'phone_verified' => true,
            'document_hash' => $documentHash,
            'document_verification_status' => 'pending',
        ]);

        // notify admin about new graduate registration
        try {
            $graduate = \App\Models\Graduate::where('user_id', $user->id)->first();
            if ($graduate) {
                Mail::to(env('ADMIN_EMAIL'))->send(new AdminUserRegistered($graduate));
            }
        } catch (\Exception $e) {
            // swallow errors to not interrupt registration; logged by framework
        }

        event(new Registered($user));

        // clear OTP session flag
        session()->forget('otp_verified_'.$phoneKey);

        return redirect()->route('login')->with('success', 'Registration successful. Please login to continue.');
    }
}