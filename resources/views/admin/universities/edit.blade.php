@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.universities.index') }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white ml-4">Edit University</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-md mx-auto">
        <form action="{{ route('admin.universities.update', $university) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                    University Name *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror" 
                       type="text" name="name" id="name" value="{{ old('name', $university->name) }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="region_id">
                    Region *
                </label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('region_id') border-red-500 @enderror" 
                        name="region_id" id="region_id" required>
                    <option value="">Select a region</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}" {{ old('region_id', $university->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                    @endforeach
                </select>
                @error('region_id')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="ranking">
                    Ranking
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white" 
                       type="number" name="ranking" id="ranking" value="{{ old('ranking', $university->ranking) }}" placeholder="e.g. 1">
            </div>

            <div class="flex gap-4">
                <button class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded flex-1" type="submit">
                    <i class="fas fa-check mr-2"></i>Update University
                </button>
                <a href="{{ route('admin.universities.index') }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
