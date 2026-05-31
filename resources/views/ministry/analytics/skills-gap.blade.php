@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-slate-900/95 rounded-[2rem] p-8 shadow-2xl border border-white/10 text-white mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold">Skills Gap Analysis</h1>
                        <p class="text-sm text-slate-300 mt-2">Assess where graduate skill supply diverges from employer demand.</p>
                    </div>
                    <a href="{{ route('ministry.analytics.dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-indigo-600 text-white px-5 py-2 text-sm font-semibold hover:bg-indigo-700 transition">Back to Analytics</a>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2 mb-8">
                <section class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <h2 class="text-xl font-semibold text-white mb-4">High Demand Skills</h2>
                    @if(empty($report->data['demanded_skills']))
                        <p class="text-slate-400">No demand data is available right now.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($report->data['demanded_skills'] as $item)
                                <li class="rounded-2xl bg-slate-800/80 p-4 border border-white/10 flex items-center justify-between">
                                    <span class="text-white">{{ $item['skill'] ?? 'Unknown' }}</span>
                                    <span class="text-slate-400">{{ $item['demand_count'] ?? 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                <section class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                    <h2 class="text-xl font-semibold text-white mb-4">Available Graduate Skills</h2>
                    @if(empty($report->data['available_skills']))
                        <p class="text-slate-400">No graduate skills data is available yet.</p>
                    @else
                        <ul class="space-y-4">
                            @foreach($report->data['available_skills'] as $item)
                                <li class="rounded-2xl bg-slate-800/80 p-4 border border-white/10 flex items-center justify-between">
                                    <span class="text-white">{{ $item['skill'] ?? 'Unknown' }}</span>
                                    <span class="text-slate-400">{{ $item['available_count'] ?? 0 }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            <div class="rounded-3xl bg-slate-900/95 p-6 border border-white/10">
                <h2 class="text-xl font-semibold text-white mb-4">Summary</h2>
                <p class="text-slate-400">{{ $report->data['gap_analysis'] ?? 'Skills gap analysis generated.' }}</p>
            </div>
        </div>
    </div>
@endsection
