@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900/95 rounded-[2rem] p-8 shadow-2xl border border-white/10 text-white mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Employment Trends</h1>
                        <p class="text-sm text-slate-300 mt-2">A ministry-level report on workforce status, unemployment duration and placement.</p>
                    </div>
                    <a href="{{ route('ministry.analytics.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 text-white px-5 py-2 text-sm font-semibold hover:bg-indigo-700 transition">Back to Analytics</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3 mb-8">
                <div class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Total Graduates</p>
                    <p class="text-4xl font-bold text-white mt-4">{{ $report->data['total_graduates'] ?? 0 }}</p>
                </div>
                <div class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Employed %</p>
                    <p class="text-4xl font-bold text-white mt-4">{{ $report->data['employed_percentage'] ?? 0 }}%</p>
                </div>
                <div class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <p class="text-sm uppercase tracking-[0.18em] text-slate-400">Average days to employment</p>
                    <p class="text-4xl font-bold text-white mt-4">{{ $report->data['average_days_to_employment'] ?? 0 }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <h2 class="text-xl font-semibold text-white mb-4">Employment Status Breakdown</h2>
                    <div class="space-y-4">
                        @foreach($report->data['status_breakdown'] ?? [] as $item)
                            <div class="rounded-2xl bg-slate-800/80 p-4 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-white">{{ ucfirst($item['employment_status'] ?? 'Unknown') }}</p>
                                    <span class="text-slate-400">{{ $item['count'] ?? 0 }} grads</span>
                                </div>
                                <p class="text-sm text-slate-400 mt-2">Avg experience: {{ round($item['avg_experience'] ?? 0, 1) }} years</p>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <h2 class="text-xl font-semibold text-white mb-4">Unemployment Duration</h2>
                    <div class="space-y-4">
                        @foreach($report->data['unemployment_duration'] ?? [] as $item)
                            <div class="rounded-2xl bg-slate-800/80 p-4 border border-white/10">
                                <div class="flex items-center justify-between">
                                    <p class="font-semibold text-white">{{ $item['duration_range'] ?? 'Unknown range' }}</p>
                                    <span class="text-slate-400">{{ $item['count'] ?? 0 }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
