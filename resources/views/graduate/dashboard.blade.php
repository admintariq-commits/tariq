@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 px-4 py-8 dark:bg-slate-950 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="rounded-3xl bg-gradient-to-br from-indigo-950 via-slate-900 to-violet-950 p-6 text-white shadow-xl sm:p-8">
            <div class="max-w-3xl">
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-indigo-200">TARIQ Graduate Registry</p>
                <h1 class="mt-3 text-2xl font-bold tracking-tight sm:text-3xl">Your graduate profile helps shape youth employment action.</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-300">Keep your education, skills, location, and employment information accurate. Aggregated information helps decision-makers understand where graduates are and what support they need.</p>
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('graduate.profile.edit') }}" class="inline-flex items-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-indigo-950 transition hover:bg-indigo-50"><i class="fas fa-pen mr-2"></i>Update my information</a>
                <a href="{{ route('graduate.profile') }}" class="inline-flex items-center rounded-xl border border-white/20 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10"><i class="fas fa-eye mr-2"></i>View profile</a>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-indigo-100 bg-white p-5 shadow-sm dark:border-indigo-900/50 dark:bg-slate-900">
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500 dark:text-slate-400">Profile completeness</span><i class="fas fa-user-check text-indigo-500"></i></div>
                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ $graduate->completion_percentage }}%</p>
                <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"><div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-500" style="width: {{ $graduate->completion_percentage }}%"></div></div>
            </div>
            <div class="rounded-2xl border border-emerald-100 bg-white p-5 shadow-sm dark:border-emerald-900/50 dark:bg-slate-900">
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500 dark:text-slate-400">Employment status</span><i class="fas fa-briefcase text-emerald-500"></i></div>
                <p class="mt-3 text-xl font-bold capitalize text-slate-900 dark:text-white">{{ str_replace('_', ' ', $graduate->employment_status ?? 'Not set') }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Keep this updated so national data stays accurate.</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-white p-5 shadow-sm dark:border-amber-900/50 dark:bg-slate-900">
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500 dark:text-slate-400">Employability signal</span><i class="fas fa-chart-line text-amber-500"></i></div>
                <p class="mt-3 text-3xl font-bold text-slate-900 dark:text-white">{{ $graduate->employability_score }}<span class="text-sm font-medium text-slate-500 dark:text-slate-400"> / 100</span></p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $graduate->career_readiness }}</p>
            </div>
            <div class="rounded-2xl border border-purple-100 bg-white p-5 shadow-sm dark:border-purple-900/50 dark:bg-slate-900">
                <div class="flex items-center justify-between"><span class="text-sm font-medium text-slate-500 dark:text-slate-400">Data verification</span><i class="fas fa-shield-halved text-purple-500"></i></div>
                <p class="mt-3 text-xl font-bold capitalize text-slate-900 dark:text-white">{{ $graduate->is_verified || $graduate->document_verification_status === 'verified' ? 'Verified' : 'In progress' }}</p>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Verified information strengthens national insights.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900 lg:col-span-2">
                <div class="flex items-center justify-between gap-4"><div><h2 class="text-lg font-bold text-slate-900 dark:text-white">Registry information</h2><p class="mt-1 text-sm text-slate-500 dark:text-slate-400">The information currently connected to your graduate record.</p></div><a href="{{ route('graduate.profile.edit') }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-300">Edit</a></div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/70"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Education</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $graduate->degree ?: 'Degree not set' }}</p><p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $graduate->course ?: 'Course not set' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/70"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Institution</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $graduate->university ?: 'University not set' }}</p><p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Graduated {{ $graduate->graduation_year ?: optional($graduate->graduation_date)->format('Y') ?: 'Year not set' }}</p></div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/70"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Location</p><p class="mt-2 font-semibold text-slate-900 dark:text-white">{{ $graduate->region ?: 'Region not set' }}</p><p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Location helps identify regional needs.</p></div>
                    <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-800/70"><p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-slate-400">Skills</p><p class="mt-2 line-clamp-2 font-semibold text-slate-900 dark:text-white">{{ $graduate->skills ?: 'Skills not set' }}</p><p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Skills data supports training priorities.</p></div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Profile checklist</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Complete more fields to improve data quality.</p>
                <div class="mt-5 space-y-3">
                    @foreach($profileSections as $section => $complete)
                        <div class="flex items-center justify-between gap-3 text-sm"><span class="text-slate-600 dark:text-slate-300">{{ $section }}</span><i class="fas {{ $complete ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-slate-300 dark:text-slate-600' }}"></i></div>
                    @endforeach
                </div>
                <a href="{{ route('graduate.profile.edit') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">Complete profile</a>
            </section>
        </div>

        <section class="rounded-2xl border border-indigo-100 bg-indigo-50 p-6 dark:border-indigo-900/60 dark:bg-indigo-950/40">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start"><div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white"><i class="fas fa-landmark"></i></div><div><h2 class="text-lg font-bold text-indigo-950 dark:text-white">Why your information matters</h2><p class="mt-2 max-w-4xl text-sm leading-6 text-indigo-900/80 dark:text-indigo-100/80">TARIQ turns verified, aggregated graduate information into evidence for youth employment planning. Your record contributes to understanding graduate numbers, skills, education backgrounds, locations, and employment conditions. Personal information should remain protected; government intelligence is built from responsible aggregation.</p></div></div>
        </section>
    </div>
</div>
@endsection
