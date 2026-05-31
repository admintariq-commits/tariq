<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - TARIQ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-orange-50 to-orange-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center max-w-lg">
            <div class="mb-8">
                <i class="fas fa-lock text-6xl text-orange-500 mb-4"></i>
            </div>
            <h1 class="text-5xl font-bold text-slate-900 mb-2">403</h1>
            <h2 class="text-2xl font-semibold text-slate-700 mb-4">Access Denied</h2>
            <p class="text-slate-600 mb-8">You don't have permission to access this resource. If you believe this is an error, please contact support.</p>
            <div class="flex gap-3 justify-center">
                <a href="{{ route('home') }}" class="bg-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-orange-700 transition">
                    <i class="fas fa-home mr-2"></i> Back to Home
                </a>
                <button type="button" data-action="go-back" class="border-2 border-orange-600 text-orange-600 px-6 py-2 rounded-lg font-semibold hover:bg-orange-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/tariq.js') }}" defer></script>
</body>
</html>
