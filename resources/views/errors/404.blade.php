<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-50 to-slate-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-lg">
            <div class="mb-8">
                <i class="fas fa-search text-6xl text-slate-300 mb-4"></i>
            </div>
            <h1 class="text-5xl font-bold text-slate-900 mb-2">404</h1>
            <h2 class="text-2xl font-semibold text-slate-700 mb-4">Page Not Found</h2>
            <p class="text-slate-600 mb-8">The page you're looking for doesn't exist or has been moved. Let's get you back on track.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('home') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-purple-700 transition">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <button type="button" data-action="go-back" class="border-2 border-purple-600 text-purple-600 px-6 py-2 rounded-lg font-semibold hover:bg-purple-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/tariq.js') }}" defer></script>
</body>
</html>
