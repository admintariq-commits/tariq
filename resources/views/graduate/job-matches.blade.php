@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">Job Matches</h1>
            <p class="mt-2 text-sm text-slate-500">Matched jobs based on your profile and skills.</p>
        </div>
        <a href="{{ route('graduate.applications') }}" class="inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition">
            View My Applications
        </a>
    </div>

    @if($matches->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <p class="text-xl font-semibold text-slate-900">No job matches available yet</p>
            <p class="mt-2 text-sm text-slate-500">Complete more profile details or check back later for new job matches.</p>
            <a href="{{ route('graduate.profile.edit') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">Edit Profile</a>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($matches as $match)
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">{{ $match->title }}</h2>
                            <p class="mt-1 text-sm text-slate-500">{{ $match->salary_range ?? 'Salary not specified' }}</p>
                        </div>
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-indigo-700">{{ $match->match_score }}%</span>
                    </div>
                    <p class="mt-4 text-sm leading-6 text-slate-600">{{ Str::limit($match->description, 140) }}</p>
                    <div class="mt-6 flex items-center justify-between gap-3">
                        <a href="{{ route('graduate.job-matches') }}#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">See details</a>
                        <form method="POST" action="{{ route('graduate.apply', ['job' => $match->id]) }}">
                            @csrf
                            <input type="hidden" name="cover_letter" value="I am very interested in this role and would like to apply.">
                            <button type="submit" class="inline-flex items-center justify-center rounded-full bg-green-600 px-4 py-2 text-sm font-semibold text-white hover:bg-green-700 transition">Apply</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
