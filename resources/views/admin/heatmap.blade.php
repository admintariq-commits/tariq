@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <h1 class="text-2xl font-bold mb-4">🗺️ Regional Unemployment Heatmap</h1>
    <div id="map" style="height: 500px; width: 100%; border-radius: 12px;"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/admin-heatmap.js') }}" defer></script>
@endsection