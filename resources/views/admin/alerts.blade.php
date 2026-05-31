@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">🚨 Government Alerts</h1>
        <span class="text-sm text-gray-500">Total Alerts: {{ $alerts->count() }}</span>
    </div>

    @if($alerts->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-600">
                    <tr>
                        <th>ID</th><th>Graduate</th><th>Region</th><th>Alert Type</th><th>Status</th><th>Created At</th><th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alerts as $alert)
                    <tr>
                        <td>{{ $alert->id }}</td>
                        <td>{{ $alert->graduate->full_name ?? 'N/A' }}</td>
                        <td>{{ $alert->graduate->region ?? 'N/A' }}</td>
                        <td>{{ $alert->type->name ?? 'N/A' }}</td>
                        <td><span class="px-2 py-1 rounded-full text-xs bg-gray-500 text-white">{{ $alert->status }}</span></td>
                        <td>{{ $alert->created_at->format('Y-m-d H:i') }}</td>
                        <td>@if($alert->status == 'pending')<form method="POST" action="{{ route('admin.alerts.send', $alert) }}">@csrf<button type="submit">Send</button></form>@endif</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p>No alerts yet.</p>
    @endif
</div>
@endsection