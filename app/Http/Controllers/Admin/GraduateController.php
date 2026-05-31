<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Graduate;
use App\Models\User;
use App\Models\Role;
use App\Models\University;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GraduateController extends Controller
{
    public function index()
    {
        $graduates = Graduate::with(['user', 'university', 'course'])->orderBy('created_at', 'desc')->get();
        return view('admin.graduates', compact('graduates'));
    }

    public function create()
    {
        $universities = University::orderBy('name')->get();
        $courses = Course::orderBy('name')->get();
        return view('admin.graduates-create', compact('universities', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'graduation_date' => 'required|date',
            'university_id' => 'required|exists:universities,id',
            'course_id' => 'required|exists:courses,id',
            'employment_status' => 'required|in:employed,self_employed,unemployed',
        ]);

        $user = User::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make('password123'),
            'role_id' => Role::where('name', 'graduate')->first()->id,
        ]);

        Graduate::create([
            'user_id' => $user->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'university_id' => $validated['university_id'],
            'course_id' => $validated['course_id'],
            'employment_status' => $validated['employment_status'],
        ]);

        return redirect()->route('admin.graduates.index')->with('success', 'Graduate added successfully.');
    }

    public function destroy(Graduate $graduate)
    {
        $user = $graduate->user;
        $graduate->delete();
        $user->delete();
        return redirect()->back()->with('success', 'Graduate deleted successfully.');
    }
}