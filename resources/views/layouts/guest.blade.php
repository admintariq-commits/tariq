<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TARIQ')</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <header class="border-b border-slate-700/80 bg-slate-900/95 shadow-lg shadow-black/10">
        <div class="mx-auto flex h-20 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="TARIQ home">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-fuchsia-600 to-indigo-600 text-white shadow-lg">
                    <i class="fas fa-graduation-cap text-lg"></i>
                </span>
                <span>
                    <span class="block text-xl font-extrabold tracking-wide text-white">TARIQ <span class="ml-1 rounded-md bg-fuchsia-600 px-1.5 py-0.5 text-[10px] font-bold text-white">v4.0</span></span>
                    <span class="block text-[10px] uppercase tracking-[0.24em] text-slate-400">Tanzania Graduate Intelligence</span>
                </span>
            </a>
            <div class="flex items-center gap-3 text-slate-400">
                <a href="{{ url('/') }}" class="hidden items-center gap-2 rounded-lg border border-slate-700 px-3 py-2 text-xs font-semibold text-slate-200 transition hover:border-indigo-400 hover:text-white sm:flex"><i class="fas fa-house"></i> Home</a>
                <span class="hidden items-center gap-2 text-xs font-medium sm:flex"><img src="{{ asset('images/tanzania-flag.svg') }}" alt="Tanzania flag" class="h-3 w-5 rounded-sm object-cover"> Tanzania</span>
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-800 text-slate-300"><i class="fas fa-user"></i></span>
            </div>
        </div>
    </header>

    <main class="relative flex min-h-[calc(100vh-5rem)] items-center justify-center overflow-hidden px-4 py-10 sm:px-6 lg:px-8">
        <div class="pointer-events-none absolute -left-24 top-16 h-72 w-72 rounded-full bg-indigo-600/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 bottom-8 h-80 w-80 rounded-full bg-fuchsia-600/10 blur-3xl"></div>
        <div class="relative w-full max-w-md">
            <div class="mb-7 text-center">
                <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-600 to-blue-500 text-white shadow-xl shadow-indigo-950/40">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-white">TARIQ</h1>
                <p class="mt-1 text-sm text-slate-400">Graduate Employment Intelligence System</p>
            </div>
            <div class="mb-5 text-center sm:hidden"><a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-300 hover:text-white"><i class="fas fa-arrow-left"></i> Back to Home</a></div>
            @yield('content')
        </div>
    </main>
</body>
</html>
