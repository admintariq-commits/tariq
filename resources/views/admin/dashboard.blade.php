@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-purple-100 mt-1">Here's what's happening with youth employment today.</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-bold">{{ date('F j, Y') }}</div>
                <div class="text-purple-100">{{ date('l') }}</div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row 1 -->
    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Graduates</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalGraduates ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-green-600 text-sm">+12% from last month</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Unemployed</p>
                    <p class="text-3xl font-bold text-red-600">{{ $unemployed ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-frown text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-red-600 text-sm">⚠️ Needs attention</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Employed</p>
                    <p class="text-3xl font-bold text-green-600">{{ $employed ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-smile text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-green-600 text-sm">✅ On track</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Critical (>8 months)</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $critical ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-orange-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-orange-600 text-sm">🚨 Immediate action required</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Alerts Sent</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $alertsSent ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-purple-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-purple-600 text-sm">📧 To government</div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Average Employability</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ $averageEmployability ?? 0 }}%</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-indigo-600 text-sm">📊 National readiness index</div>
        </div>
    </div>

    <!-- Academic Verification Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">✅ Verified Records</p>
                    <p class="text-3xl font-bold text-green-600">{{ $verifiedRecords ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                @php 
                    $verifiedPercent = $totalAcademicRecords > 0 ? round(($verifiedRecords / $totalAcademicRecords) * 100) : 0;
                @endphp
                {{ $verifiedPercent }}% of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">⏳ Pending Verification</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $pendingRecords ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                @php 
                    $pendingPercent = $totalAcademicRecords > 0 ? round(($pendingRecords / $totalAcademicRecords) * 100) : 0;
                @endphp
                {{ $pendingPercent }}% of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">❌ Rejected Records</p>
                    <p class="text-3xl font-bold text-red-600">{{ $rejectedRecords ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                @php 
                    $rejectedPercent = $totalAcademicRecords > 0 ? round(($rejectedRecords / $totalAcademicRecords) * 100) : 0;
                @endphp
                {{ $rejectedPercent }}% of total
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">👤 Graduates Verified</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $graduatesWithVerification ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-check text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">
                @php 
                    $verifiedGradPercent = $totalGraduates > 0 ? round(($graduatesWithVerification / $totalGraduates) * 100) : 0;
                @endphp
                {{ $verifiedGradPercent }}% of graduates
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">🚨 At-risk Graduates</p>
                    <p class="text-3xl font-bold text-rose-600">{{ $atRiskGraduates ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-heart-broken text-rose-600 text-xl"></i>
                </div>
            </div>
            <div class="mt-2 text-gray-600 text-xs">Low employability score, needs support</div>
        </div>
    </div>

    <!-- Action Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 flex flex-col gap-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Verification Queue</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Review pending records</p>
                    <p class="text-sm text-gray-500 mt-2">Open the admin verification queue for academic and document checks.</p>
                </div>
                <a href="{{ route('admin.security.documents') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-600 text-white hover:bg-blue-700 transition">
                    <i class="fas fa-check-circle"></i>
                    Verify now
                </a>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-gray-900 p-4">
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 dark:text-gray-300">
                    <div><strong>{{ $pendingRecords ?? 0 }}</strong> pending academic reviews</div>
                    <div><strong>{{ $verifiedRecords ?? 0 }}</strong> already verified</div>
                    <div><strong>{{ $rejectedRecords ?? 0 }}</strong> rejected records</div>
                    <div><strong>{{ $manualReviewRecords ?? 0 }}</strong> manual review cases</div>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5 flex flex-col gap-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-gray-500 text-sm">Alerts Management</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">Open alert dashboard</p>
                    <p class="text-sm text-gray-500 mt-2">See the full government alert queue and send intervention messages.</p>
                </div>
                <a href="{{ route('admin.alerts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-600 text-white hover:bg-purple-700 transition">
                    <i class="fas fa-bell"></i>
                    Go to alerts
                </a>
            </div>
            <div class="rounded-2xl bg-slate-50 dark:bg-gray-900 p-4 text-sm text-gray-600 dark:text-gray-300">
                <p><strong>{{ $alertsSent ?? 0 }}</strong> alerts previously sent</p>
                <p class="mt-2">Use the alerts page to send or review pending notifications.</p>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Unemployment Trend Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">📈 Unemployment Trend (Last 6 Months)</h3>
                <select class="text-sm border rounded-lg px-2 py-1">
                    <option>Last 6 months</option>
                    <option>Last year</option>
                </select>
            </div>
            <canvas id="unemploymentChart" height="250" data-chart='{"labels":["Jan","Feb","Mar","Apr","May","Jun"],"data":[12,19,15,17,14,{{ $unemployed ?? 0 }}]}'></canvas>
        </div>

        <!-- Regional Distribution Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">🗺️ Regional Distribution</h3>
            </div>
            <canvas id="regionalChart" height="250" data-chart='{"labels":{!! json_encode($regionData->pluck('name')->toArray()) !!},"data":{!! json_encode($regionData->pluck('graduates_count')->toArray()) !!}}'></canvas>
        </div>
    </div>

    <!-- Recent Graduates & Alerts Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Recent Graduates -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">👥 Recent Graduates</h3>
                <a href="{{ route('admin.graduates.index') }}" class="text-blue-600 text-sm">View all →</a>
            </div>
            <div class="space-y-3">
                @php
                    $recentGrads = App\Models\Graduate::with('user')->latest()->take(5)->get();
                @endphp
                @foreach($recentGrads as $grad)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white">
                            {{ substr($grad->first_name, 0, 1) }}{{ substr($grad->last_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $grad->first_name }} {{ $grad->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $grad->course->name ?? 'No course' }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($grad->employment_status == 'employed') bg-green-100 text-green-600
                            @elseif($grad->employment_status == 'self_employed') bg-blue-100 text-blue-600
                            @else bg-red-100 text-red-600 @endif">
                            {{ str_replace('_', ' ', $grad->employment_status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Recent Alerts -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">🔔 Recent Alerts</h3>
                <a href="{{ route('admin.alerts.index') }}" class="text-blue-600 text-sm">View all →</a>
            </div>
            <div class="space-y-3">
                @php
                    $recentAlerts = App\Models\Alert::with('graduate')->latest()->take(5)->get();
                @endphp
                @forelse($recentAlerts as $alert)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell text-yellow-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold">{{ $alert->graduate->full_name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-500">{{ $alert->type->name ?? 'Alert' }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="px-2 py-1 rounded-full text-xs 
                            @if($alert->status == 'sent') bg-green-100 text-green-600
                            @elseif($alert->status == 'pending') bg-yellow-100 text-yellow-600
                            @else bg-gray-100 text-gray-600 @endif">
                            {{ $alert->status }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No alerts yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script src="{{ asset('js/admin-dashboard.js') }}" defer></script>
@endsection