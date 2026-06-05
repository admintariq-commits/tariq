@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-slate-950 py-10">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-br from-slate-950 via-cyan-950 to-violet-900 rounded-[2rem] shadow-2xl p-8 mb-8 text-white overflow-hidden ring-1 ring-white/10">
                <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4">
                <p class="text-sm uppercase tracking-[0.24em] text-slate-300">Ministry Intelligence</p>
                <h1 class="text-4xl md:text-5xl font-bold">National Graduate Insight</h1>
                <p class="max-w-2xl text-slate-200 text-sm leading-7">Track unemployment signals, alert interventions, and regional skills dynamics for Tanzania's labor market.</p>
                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="rounded-3xl bg-slate-900/70 p-6 border border-white/10 shadow-lg shadow-slate-900/20">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Average Employability</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ $averageEmployability }}%</p>
                        <p class="text-slate-300 text-sm mt-2">Across all registered graduates.</p>
                    </div>
                    <div class="rounded-3xl bg-slate-900/70 p-6 border border-white/10 shadow-lg shadow-slate-900/20">
                        <p class="text-xs uppercase tracking-[0.24em] text-slate-400">Unemployed Average</p>
                        <p class="mt-3 text-3xl font-semibold text-white">{{ $averageUnemployedEmployability }}%</p>
                        <p class="text-slate-300 text-sm mt-2">Employability among unemployed graduates.</p>
                    </div>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.75rem] bg-slate-900/70 p-6 border border-white/10 shadow-lg shadow-slate-900/20 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">Total Graduates</p>
                    <p class="mt-3 text-4xl font-bold text-white">{{ number_format($totalGraduates) }}</p>
                    <p class="text-slate-300 text-sm mt-2">Registered in the platform.</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-900/70 p-6 border border-white/10 shadow-lg shadow-slate-900/20 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">New in 30 Days</p>
                    <p class="mt-3 text-4xl font-bold text-white">{{ number_format($registeredLast30Days) }}</p>
                    <p class="text-slate-300 text-sm mt-2">New graduate profiles added recently.</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-900/70 p-6 border border-white/10 shadow-lg shadow-slate-900/20 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">Unemployed</p>
                    <p class="mt-3 text-4xl font-bold text-white">{{ number_format($unemployedCount) }}</p>
                    <p class="text-slate-300 text-sm mt-2">Needs job support and placement.</p>
                </div>
                <div class="rounded-[1.75rem] bg-slate-900/70 p-6 border border-white/10 shadow-lg shadow-slate-900/20 backdrop-blur">
                    <p class="text-xs uppercase tracking-[0.24em] text-slate-300">At-Risk</p>
                    <p class="mt-3 text-4xl font-bold text-white">{{ number_format($atRiskCount) }}</p>
                    <p class="text-slate-300 text-sm mt-2">Long-term unemployed graduates.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 mb-6">
        <form method="GET" action="{{ route('ministry.dashboard') }}" class="flex gap-3 items-end flex-wrap">
            <div>
                <label class="block text-sm text-slate-400">Region</label>
                <select name="region" class="mt-1 rounded-lg bg-slate-50 px-3 py-2">
                    <option value="">All regions</option>
                    @foreach($regions ?? [] as $r)
                        <option value="{{ $r }}" {{ request('region') === $r ? 'selected' : '' }}>{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Degree level</label>
                <select name="degree" class="mt-1 rounded-lg bg-slate-50 px-3 py-2">
                    <option value="">Any level</option>
                    @foreach($degrees ?? [] as $d)
                        <option value="{{ $d }}" {{ request('degree') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">Course / Program</label>
                <select name="course" class="mt-1 rounded-lg bg-slate-50 px-3 py-2">
                    <option value="">Any course</option>
                    @foreach($courses ?? [] as $c)
                        <option value="{{ $c }}" {{ request('course') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-slate-400">University</label>
                <select name="university" class="mt-1 rounded-lg bg-slate-50 px-3 py-2">
                    <option value="">All universities</option>
                    @foreach($universities ?? [] as $u)
                        <option value="{{ $u }}" {{ request('university') === $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div>
            <div class="ml-auto flex gap-2">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg">Apply filters</button>
                <button type="submit" formaction="{{ route('ministry.export.graduates') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg">Export CSV</button>
            </div>
        </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-4 mb-8">
        <a href="{{ route('ministry.analytics.dashboard') }}" class="rounded-[2rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-indigo-400 transition hover:-translate-y-1">
            <p class="text-sm uppercase tracking-[0.18em] text-indigo-300">Analytics</p>
            <h3 class="mt-3 text-2xl font-semibold text-white">Run Employment Intelligence</h3>
            <p class="text-slate-400 mt-4">Launch the ministry analytics center for trends, salary reports, and skills gap analysis.</p>
        </a>
        <a href="{{ route('ministry.alerts.index') }}" class="rounded-[2rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-amber-400 transition hover:-translate-y-1">
            <p class="text-sm uppercase tracking-[0.18em] text-amber-300">Alerts</p>
            <h3 class="mt-3 text-2xl font-semibold text-white">Review Intervention Signals</h3>
            <p class="text-slate-400 mt-4">See the latest graduate risk alerts and decide on national labor market actions.</p>
        </a>
        <div class="rounded-[2rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20">
            <p class="text-sm uppercase tracking-[0.18em] text-sky-300">Policy</p>
            <h3 class="mt-3 text-2xl font-semibold text-white">Employment Strategy</h3>
            <p class="text-slate-400 mt-4">Use this portal to connect employment data with national planning, skills programs and interventions.</p>
        </div>
        <a href="{{ route('ministry.analytics.profile-completeness') }}" class="rounded-[2rem] border border-slate-800/70 bg-slate-900/95 p-6 shadow-2xl shadow-slate-900/20 hover:border-emerald-400 transition hover:-translate-y-1">
            <p class="text-sm uppercase tracking-[0.18em] text-emerald-300">Data Quality</p>
            <h3 class="mt-3 text-2xl font-semibold text-white">Profile Completion</h3>
            <p class="text-slate-400 mt-4">Compare filled graduate profile fields with employment outcomes and data completeness.</p>
        </a>
    </div>
        <div class="bg-slate-900/95 rounded-[2rem] p-6 shadow-2xl shadow-slate-900/20 border border-white/10">
            <h2 class="text-xl font-semibold text-white mb-4">Regional Unemployment Hotspots</h2>
            @if($topUnemployedRegions->isEmpty())
                <p class="text-slate-400">No region data available yet.</p>
            @else
                <ul class="space-y-4">
                    @foreach($topUnemployedRegions as $region)
                        <li class="rounded-3xl bg-slate-800/80 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-semibold text-white">{{ $region->region ?: 'Unknown region' }}</span>
                                <span class="text-slate-400 text-sm">{{ $region->count }} grads</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-slate-900/95 rounded-[2rem] p-6 shadow-2xl shadow-slate-900/20 border border-white/10">
            <h2 class="text-xl font-semibold text-white mb-4">Top Degree Areas</h2>
            @if($mostCommonDegrees->isEmpty())
                <p class="text-slate-400">Degree trend data is not available yet.</p>
            @else
                <ul class="space-y-4">
                    @foreach($mostCommonDegrees as $degree)
                        <li class="rounded-3xl bg-slate-800/80 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-semibold text-white">{{ ucfirst($degree->degree) }}</span>
                                <span class="text-slate-400 text-sm">{{ $degree->count }} grads</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-slate-900/95 rounded-[2rem] p-6 shadow-2xl shadow-slate-900/20 border border-white/10">
            <h2 class="text-xl font-semibold text-white mb-4">Policy Signals</h2>
            <ul class="space-y-4 text-sm text-slate-300">
                <li class="rounded-3xl bg-slate-800/80 p-4">
                    <p class="font-semibold text-white">Long-term unemployment</p>
                    <p class="mt-2">Graduates with 8+ months out of work are prioritized for intervention.</p>
                </li>
                <li class="rounded-3xl bg-slate-800/80 p-4">
                    <p class="font-semibold text-white">Employability signal</p>
                    <p class="mt-2">Use the average employability score to compare regional readiness.</p>
                </li>
                <li class="rounded-3xl bg-slate-800/80 p-4">
                    <p class="font-semibold text-white">Registration momentum</p>
                    <p class="mt-2">Track growth in registered graduates over the last 30 days.</p>
                </li>
            </ul>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-2 mb-8">
        <div class="bg-slate-900/95 rounded-[2rem] p-6 shadow-2xl shadow-slate-900/20 border border-white/10">
            <h2 class="text-xl font-semibold text-white mb-4">Top At-Risk Graduates</h2>
            @if($atRiskGraduates->isEmpty())
                <p class="text-slate-400">No at-risk graduates have been identified yet.</p>
            @else
                <div class="space-y-3">
                    @foreach($atRiskGraduates as $graduate)
                        <div class="rounded-3xl bg-slate-800/80 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-white">{{ $graduate->full_name }}</p>
                                    <p class="text-sm text-slate-400">{{ $graduate->region ?? 'Region n/a' }} • {{ ucfirst($graduate->degree ?? 'Degree n/a') }}</p>
                                </div>
                                <span class="rounded-full bg-rose-100 text-rose-700 px-3 py-1 text-xs font-semibold">{{ $graduate->months_unemployed }} mo</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="bg-slate-900/95 rounded-[2rem] p-6 shadow-2xl shadow-slate-900/20 border border-white/10">
            <div class="flex items-center justify-between gap-4 mb-4">
                <h2 class="text-xl font-semibold text-white">Recent Ministry Alerts</h2>
                <a href="{{ route('ministry.alerts.index') }}" class="inline-flex items-center gap-2 rounded-full bg-amber-500 px-4 py-2 text-sm font-semibold text-slate-950 hover:bg-amber-400 transition">
                    <i class="fas fa-bell"></i>
                    View all alerts
                </a>
            </div>
            @if($recentAlerts->isEmpty())
                <p class="text-slate-400">No alerts have been generated yet.</p>
            @else
                <ul class="space-y-4">
                    @foreach($recentAlerts as $alert)
                        <li class="rounded-3xl bg-slate-800/80 p-4">
                            <p class="font-semibold text-white">{{ $alert->title }}</p>
                            <p class="text-sm text-slate-400 mt-1">{{ \Illuminate\Support\Str::limit($alert->message, 140) }}</p>
                            <p class="text-xs uppercase tracking-[0.18em] text-slate-500 mt-2">{{ $alert->created_at->diffForHumans() }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
        </div>
    </div>
@endsection
