@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900/95 rounded-[2rem] p-8 shadow-2xl border border-white/10 text-white mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Salary Analysis</h1>
                        <p class="text-sm text-slate-300 mt-2">Compare salaries from the job market with graduate expectations and regional pay levels.</p>
                    </div>
                    <a href="{{ route('ministry.analytics.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 text-white px-5 py-2 text-sm font-semibold hover:bg-indigo-700 transition">Back to Analytics</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3 mb-8">
                <div class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Overall average</p>
                    <p class="text-4xl font-bold text-white mt-4">{{ $report->data['overall_average_salary'] ?? 0 }}</p>
                    <p class="text-slate-400 mt-2">Average salary from employment records.</p>
                </div>
            </div>

            <section class="rounded-3xl bg-slate-900/95 p-6 border border-white/10 mb-6">
                <h2 class="text-xl font-semibold text-white mb-4">Salary by Job Title</h2>
                @if(empty($report->data['salary_by_job_title']))
                    <p class="text-slate-400">No salary data was found.</p>
                @else
                    <div class="space-y-4">
                        @foreach($report->data['salary_by_job_title'] as $item)
                            <div class="rounded-2xl bg-slate-800/80 p-4 border border-white/10">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-white">{{ $item['job_title'] ?? 'Unknown' }}</p>
                                        <p class="text-sm text-slate-400 mt-1">Count: {{ $item['count'] ?? 0 }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-white">TZS {{ number_format($item['avg_salary'] ?? 0) }}</p>
                                        <p class="text-sm text-slate-400">Min {{ number_format($item['min_salary'] ?? 0) }} / Max {{ number_format($item['max_salary'] ?? 0) }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                <h2 class="text-xl font-semibold text-white mb-4">Regional Expected Salaries</h2>
                @if(empty($report->data['regional_salaries']))
                    <p class="text-slate-400">No regional salary expectations are available.</p>
                @else
                    <div class="space-y-4">
                        @foreach($report->data['regional_salaries'] as $item)
                            <div class="rounded-2xl bg-slate-800/80 p-4 border border-white/10 flex items-center justify-between">
                                <span class="font-semibold text-white">{{ $item['region'] ?? 'Unknown' }}</span>
                                <span class="text-slate-400">TZS {{ number_format($item['avg_expected_salary'] ?? 0) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
@endsection
