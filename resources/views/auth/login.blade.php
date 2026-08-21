@extends('layouts.guest')

@section('title', 'Login | TARIQ')

@section('content')
<div class="overflow-hidden rounded-2xl border border-slate-700 bg-slate-900 shadow-2xl shadow-black/30">
    <div class="border-b border-slate-700 bg-gradient-to-r from-slate-800 via-slate-900 to-indigo-950 px-6 py-7 text-center sm:px-8">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-fuchsia-600 to-indigo-600 text-white shadow-lg">
            <i class="fas fa-sign-in-alt text-lg"></i>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-white">Welcome back</h2>
        <p class="mt-2 text-sm text-slate-400">Access your TARIQ graduate intelligence account.</p>
    </div>

    <div class="px-6 py-7 sm:px-8">
        @if ($errors->any())
            <div class="mb-5 rounded-xl border border-red-800 bg-red-950/50 p-4 text-sm text-red-200" role="alert">
                <p class="mb-2 font-semibold">Please check the following:</p>
                <ul class="list-disc space-y-1 pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-5 rounded-xl border border-emerald-800 bg-emerald-950/50 p-4 text-sm text-emerald-200" role="status">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post', [], false) }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="mb-2 block text-sm font-semibold text-slate-200">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus placeholder="you@example.com" class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-500 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20">
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between gap-3">
                    <label for="password" class="block text-sm font-semibold text-slate-200">Password</label>
                    <a href="{{ url('/forgot-password') }}" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300">Forgot password?</a>
                </div>
                <input id="password" type="password" name="password" autocomplete="current-password" required placeholder="Enter your password" class="block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-500 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/20">
            </div>

            <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-950/40 transition hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-indigo-500/30">
                <i class="fas fa-sign-in-alt"></i>
                <span>Sign in to TARIQ</span>
            </button>
        </form>

        <div class="mt-6 border-t border-slate-700 pt-5 text-center">
            <p class="text-sm text-slate-400">New to the graduate registry?</p>
            <a href="{{ route('graduate.register') }}" class="mt-2 inline-flex items-center gap-2 text-sm font-bold text-indigo-400 hover:text-indigo-300">
                Create your graduate profile <i class="fas fa-external-link-alt text-xs"></i>
            </a>
        </div>
    </div>
</div>
@endsection
