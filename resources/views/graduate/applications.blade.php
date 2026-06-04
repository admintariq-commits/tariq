@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-semibold text-slate-900">My Applications</h1>
            <p class="mt-2 text-sm text-slate-500">View the jobs you have applied for and track application status.</p>
        </div>
        <a href="{{ route('graduate.job-matches') }}" class="inline-flex items-center justify-center rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700 transition">
            Browse Job Matches
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <p class="text-xl font-semibold text-slate-900">No applications yet</p>
            <p class="mt-2 text-sm text-slate-500">You haven’t applied to any jobs. Start by browsing job matches.</p>
            <a href="{{ route('graduate.job-matches') }}" class="mt-6 inline-flex items-center justify-center rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition">Browse Jobs</a>
        </div>
    @else
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Job Title</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Employer</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Applied</th>
                        <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Cover Letter</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($applications as $application)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm text-slate-700">
                                {{ optional($application->job)->title ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-700">
                                {{ optional(optional($application->job)->employer)->name ?? 'Not specified' }}
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold {{ $application->status === 'pending' ? 'text-amber-600' : ($application->status === 'accepted' ? 'text-emerald-600' : 'text-slate-600') }}">
                                {{ ucfirst($application->status) }}
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-700">
                                {{ $application->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-4 text-sm text-slate-700 max-w-xs break-words">
                                {{ $application->cover_letter ?? 'No cover letter provided' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
