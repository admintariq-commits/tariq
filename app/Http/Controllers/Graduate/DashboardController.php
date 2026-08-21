<?php

namespace App\Http\Controllers\Graduate;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $graduate = Auth::user()->graduate;

        if (!$graduate) {
            return redirect()->route('graduate.profile.edit')
                ->with('error', 'Please complete your graduate profile first.');
        }

        $profileSections = [
            'Personal information' => filled($graduate->phone) && filled($graduate->gender),
            'Education background' => filled($graduate->university) && filled($graduate->course) && filled($graduate->degree),
            'Skills and experience' => filled($graduate->skills) || filled($graduate->experience_years),
            'Employment information' => filled($graduate->employment_status),
            'Verification documents' => $graduate->is_verified || $graduate->document_verification_status === 'verified',
        ];

        return view('graduate.dashboard', compact('graduate', 'profileSections'));
    }
}
