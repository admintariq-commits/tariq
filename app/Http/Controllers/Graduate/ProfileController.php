<?php
namespace App\Http\Controllers\Graduate;
use App\Http\Controllers\Controller;
use App\Http\Requests\GraduateProfileUpdateRequest;
use App\Models\University;
use App\Models\Course;
use App\Models\Alert;
use App\Models\AlertType;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ProfileCompleted;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $graduate = Auth::user()->graduate;
        $universities = University::all();
        $courses = Course::all();
        
        return view('graduate.profile.edit', compact('graduate', 'universities', 'courses'));
    }

    public function update(GraduateProfileUpdateRequest $request)
    {
        $graduate = Auth::user()->graduate;
        // determine whether profile was previously complete
        $wasComplete = !empty($graduate->phone) && !empty($graduate->university_id) && !empty($graduate->course_id) && !empty($graduate->employment_status);

        $validated = $request->validated();
        
        // Handle resume upload
        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store('resumes', 'local');
            $validated['resume'] = $path;
        }

        $graduate->update($validated);

        // check if profile became complete now
        $isComplete = !empty($graduate->phone) && !empty($graduate->university) && !empty($graduate->degree) && !empty($graduate->employment_status);

        if (!$wasComplete && $isComplete) {
            // create an internal alert record for completed profile
            $alertType = AlertType::firstOrCreate(
                ['name' => 'Profile Completed'],
                ['months_threshold' => 0]
            );

            Alert::create([
                'graduate_id' => $graduate->id,
                'alert_type_id' => $alertType->id,
                'sent_at' => now(),
                'status' => 'sent',
                'response_message' => 'Graduate profile completed successfully.',
            ]);

            // notify the user
            Auth::user()->notify(new ProfileCompleted($graduate));

            // notify admins
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                User::where('role_id', $adminRole->id)
                    ->get()
                    ->each(fn($admin) => $admin->notify(new ProfileCompleted($graduate)));
            }
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Show read-only profile view
     */
    public function show()
    {
        $graduate = Auth::user()->graduate;
        
        if (!$graduate) {
            return redirect()->route('graduate.profile.edit')->with('error', 'Graduate profile not found');
        }
        
        return view('graduate.profile.show', compact('graduate'));
    }
}