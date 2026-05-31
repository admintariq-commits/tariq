@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900/95 rounded-[2rem] p-8 shadow-2xl border border-white/10 text-white mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Profile Completeness</h1>
                        <p class="text-sm text-slate-300 mt-2">Compare entered graduate profile fields against employment readiness and data quality requirements.</p>
                    </div>
                    <a href="{{ route('ministry.analytics.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 text-white px-5 py-2 text-sm font-semibold hover:bg-indigo-700 transition">Back to Analytics</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-4 mb-8">
                <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Average Completion</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ $report->data['average_profile_completion'] ?? 0 }}%</p>
                    <p class="text-slate-400 mt-3 text-sm">Average completeness score across all graduate profiles.</p>
                </div>
                <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Complete Profiles</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ $report->data['complete_profiles'] ?? 0 }}</p>
                    <p class="text-slate-400 mt-3 text-sm">Profiles scoring 90% or higher on completion.</p>
                </div>
                <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Incomplete Profiles</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ max(0, ($report->data['total_graduates'] ?? 0) - ($report->data['complete_profiles'] ?? 0)) }}</p>
                    <p class="text-slate-400 mt-3 text-sm">Profiles that need additional data or validation.</p>
                </div>
                <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Lowest Completion</p>
                    <p class="mt-4 text-4xl font-semibold text-white">{{ $report->data['minimum_profile_completion'] ?? 0 }}%</p>
                    <p class="text-slate-400 mt-3 text-sm">The lowest profile completion percentage found in the dataset.</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2 mb-8">
                <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                    <h2 class="text-xl font-semibold text-white mb-4">Top Missing Fields</h2>
                    <div class="space-y-3">
                        @foreach($report->data['missing_fields'] ?? [] as $field => $count)
                            <div class="rounded-3xl bg-slate-800/80 p-4 border border-white/10 flex items-center justify-between">
                                <span class="text-slate-300 capitalize">{{ str_replace('_', ' ', $field) }}</span>
                                <span class="text-slate-400 text-sm">{{ $count }} missing</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                    <h2 class="text-xl font-semibold text-white mb-4">Completion by Employment Status</h2>
                    <ul class="space-y-3">
                        @foreach($report->data['completion_by_status'] ?? [] as $status)
                            <li class="rounded-3xl bg-slate-800/80 p-4 border border-white/10">
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-white font-semibold">{{ ucfirst($status['employment_status']) }}</span>
                                    <span class="text-slate-400 text-sm">{{ $status['count'] ?? 0 }} grads</span>
                                </div>
                                <p class="text-slate-400 mt-2 text-sm">Avg completion: {{ $status['average_completion'] ?? 0 }}%</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="rounded-[2rem] bg-slate-900/95 p-6 border border-white/10 shadow-2xl shadow-slate-900/20">
                <h2 class="text-xl font-semibold text-white mb-4">Top Regions by Data Completion</h2>
                <div class="space-y-3">
                    @foreach($report->data['completion_by_region'] ?? [] as $region)
                        <div class="rounded-3xl bg-slate-800/80 p-4 border border-white/10 flex items-center justify-between">
                            <span class="text-white font-semibold">{{ $region['region'] }}</span>
                            <span class="text-slate-400 text-sm">{{ $region['average_completion'] ?? 0 }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection