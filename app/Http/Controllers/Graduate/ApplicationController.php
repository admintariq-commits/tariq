<?php
namespace App\Http\Controllers\Graduate;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobMarket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::where('graduate_id', Auth::user()->graduate->id)->with('job')->get();
        return view('graduate.applications', compact('applications'));
    }

    public function store(Request $request, JobMarket $job)
    {
        $graduate = Auth::user()->graduate;
        $exists = JobApplication::where('graduate_id', $graduate->id)->where('job_market_id', $job->id)->exists();
        
        if ($exists) {
            return back()->with('error', 'You have already applied for this job.');
        }
        
        JobApplication::create([
            'graduate_id' => $graduate->id,
            'job_market_id' => $job->id,
            'status' => 'pending',
            'cover_letter' => $request->cover_letter,
        ]);
        
        return back()->with('success', 'Application submitted successfully.');
    }
}