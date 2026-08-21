<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TARIQ')</title>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; background-color: #f8fafc; }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900">
    <div class="min-h-screen flex flex-col justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="mx-auto w-full max-w-md">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-4 h-16 w-16 rounded-3xl bg-gradient-to-r from-indigo-600 to-blue-500 flex items-center justify-center text-white shadow-lg">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold">TARIQ</h1>
                <p class="text-sm text-slate-500">Graduate Employment Intelligence System</p>
            </div>
            @yield('content')
        </div>
    </div>
</body>
</html>
