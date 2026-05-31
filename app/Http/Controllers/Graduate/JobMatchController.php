<?php
namespace App\Http\Controllers\Graduate;
use App\Http\Controllers\Controller;
use App\Models\JobMarket;
use Illuminate\Support\Facades\Auth;

class JobMatchController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $graduate = $user->graduate;
        
        if (!$graduate) {
            return redirect()->route('graduate.profile')->with('error', 'Please complete your profile first');
        }
        
        $skills = array_map('trim', explode(',', $graduate->skills ?? ''));
        $jobs = JobMarket::all();
        
        $matches = $jobs->map(function($job) use ($skills, $graduate) {
            $required = is_array($job->required_skills) ? $job->required_skills : json_decode($job->required_skills, true) ?? [];
            $matched = count(array_intersect($skills, $required));
            $score = count($required) > 0 ? ($matched / count($required)) * 70 : 0;
            if ($graduate->gpa >= $job->min_gpa) $score += 30;
            return (object)[
                'id' => $job->id,
                'title' => $job->title,
                'match_score' => round(min(100, $score)),
                'salary_range' => $job->salary_range,
                'description' => $job->description
            ];
        })->sortByDesc('match_score')->take(10);
        
        return view('graduate.job-matches', compact('matches', 'graduate'));
    }
}