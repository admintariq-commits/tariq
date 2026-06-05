@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold">Document Verification Queue</h1>
                <p class="text-sm text-gray-500">Review pending graduate verification records and update their status.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-slate-900 text-white px-4 py-2 hover:bg-slate-800 transition">
                <i class="fas fa-arrow-left"></i>
                Back to dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-100 text-emerald-900 px-4 py-3 mb-4">{{ session('success') }}</div>
        @endif

        @if($pending->isEmpty())
            <div class="rounded-2xl bg-slate-50 dark:bg-gray-900 p-6 text-gray-500">
                There are no graduates waiting for verification at the moment.
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border border-slate-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-slate-200 dark:divide-gray-700">
                    <thead class="bg-slate-100 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Graduate</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Email</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Region</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Degree</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700 dark:text-slate-300">Status</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-slate-700 dark:text-slate-300">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @foreach($pending as $graduate)
                        <tr>
                            <td class="px-4 py-4 text-sm text-slate-900 dark:text-slate-100">{{ $graduate->full_name }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $graduate->user->email ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-300">{{ $graduate->region ?? 'N/A' }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600 dark:text-slate-300">{{ ucfirst($graduate->degree ?? 'Unknown') }}</td>
                            <td class="px-4 py-4 text-sm">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold 
                                    @if($graduate->document_verification_status == 'pending') bg-yellow-100 text-yellow-800 
                                    @elseif($graduate->document_verification_status == 'verified') bg-emerald-100 text-emerald-800 
                                    @elseif($graduate->document_verification_status == 'rejected') bg-red-100 text-red-800 
                                    @else bg-slate-100 text-slate-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $graduate->document_verification_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <form method="POST" action="{{ route('admin.security.documents.verify', $graduate) }}" class="flex flex-wrap justify-end gap-2">
                                    @csrf
                                    <button type="submit" name="status" value="verified" class="rounded-full bg-emerald-600 px-3 py-1 text-xs font-semibold text-white hover:bg-emerald-700 transition">Verify</button>
                                    <button type="submit" name="status" value="rejected" class="rounded-full bg-red-600 px-3 py-1 text-xs font-semibold text-white hover:bg-red-700 transition">Reject</button>
                                    <button type="submit" name="status" value="manual_review" class="rounded-full bg-slate-600 px-3 py-1 text-xs font-semibold text-white hover:bg-slate-700 transition">Review</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
