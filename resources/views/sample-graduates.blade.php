@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Sample Registered Graduates (Latest 100)</h1>
    </div>

    @if($grads->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                <thead class="bg-gray-100 dark:bg-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Phone</th>
                        <th class="px-4 py-2 text-left">Password</th>
                        <th class="px-4 py-2 text-left">ZIP</th>
                        <th class="px-4 py-2 text-left">University</th>
                        <th class="px-4 py-2 text-left">Course</th>
                        <th class="px-4 py-2 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grads as $grad)
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <td class="px-4 py-2">{{ $grad->id }}</td>
                        <td class="px-4 py-2">{{ $grad->first_name }} {{ $grad->last_name }}</td>
                        <td class="px-4 py-2">{{ $grad->user->email ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $grad->phone }}</td>
                        <td class="px-4 py-2">{{ $grad->sample_password ?? 'password123' }}</td>
                        <td class="px-4 py-2">{{ $grad->sample_zip ?? '0000' }}</td>
                        <td class="px-4 py-2">{{ $grad->university->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $grad->course->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $grad->employment_status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-500">No sample graduates found.</p>
        </div>
    @endif
</div>
@endsection
