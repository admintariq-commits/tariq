<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TARIQ')</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="relative min-h-screen overflow-hidden bg-gradient-to-br from-slate-50 via-white to-indigo-50/70 py-10 px-4 sm:px-6 lg:px-8 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950/60">
        <div class="pointer-events-none absolute -left-24 top-10 h-64 w-64 rounded-full bg-indigo-400/10 blur-3xl dark:bg-indigo-500/10"></div>
        <div class="pointer-events-none absolute -right-24 bottom-10 h-72 w-72 rounded-full bg-purple-400/10 blur-3xl dark:bg-purple-500/10"></div>
        <div class="relative flex min-h-[calc(100vh-5rem)] flex-col justify-center">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 h-16 w-16 rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-500 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold">TARIQ</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400">Graduate Employment Intelligence System</p>
            </div>
            @yield('content')
        </div>
        </div>
    </div>
</body>
</html>
