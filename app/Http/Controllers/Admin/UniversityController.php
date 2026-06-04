<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use App\Models\Region;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    public function index()
    {
        $universities = University::with('region')->orderBy('name')->paginate(20);
        return view('admin.universities.index', compact('universities'));
    }

    public function create()
    {
        $regions = Region::orderBy('name')->get();
        return view('admin.universities.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:universities',
            'region_id' => 'required|exists:regions,id',
            'ranking' => 'nullable|integer',
        ]);

        University::create($validated);

        return redirect()->route('admin.universities.index')->with('success', 'University added successfully.');
    }

    public function show(University $university)
    {
        return view('admin.universities.show', compact('university'));
    }

    public function edit(University $university)
    {
        $regions = Region::orderBy('name')->get();
        return view('admin.universities.edit', compact('university', 'regions'));
    }

    public function update(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:universities,name,' . $university->id,
            'region_id' => 'required|exists:regions,id',
            'ranking' => 'nullable|integer',
        ]);

        $university->update($validated);

        return redirect()->route('admin.universities.index')->with('success', 'University updated successfully.');
    }

    public function destroy(University $university)
    {
        $university->delete();
        return redirect()->back()->with('success', 'University deleted successfully.');
    }
}
