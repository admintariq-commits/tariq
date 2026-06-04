@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('admin.courses.index') }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white ml-4">Add New Course</h1>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-md mx-auto">
        <form action="{{ route('admin.courses.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="name">
                    Course Name *
                </label>
                <input class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror" 
                       type="text" name="name" id="name" value="{{ old('name') }}" required>
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2" for="level">
                    Education Level *
                </label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white @error('level') border-red-500 @enderror" 
                        name="level" id="level" required>
                    <option value="">Select Level</option>
                    <option value="certificate" {{ old('level') === 'certificate' ? 'selected' : '' }}>Certificate</option>
                    <option value="diploma" {{ old('level') === 'diploma' ? 'selected' : '' }}>Diploma</option>
                    <option value="degree" {{ old('level') === 'degree' ? 'selected' : '' }}>Degree</option>
                    <option value="masters" {{ old('level') === 'masters' ? 'selected' : '' }}>Master's</option>
                </select>
                @error('level')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded flex-1" type="submit">
                    <i class="fas fa-check mr-2"></i>Add Course
                </button>
                <a href="{{ route('admin.courses.index') }}" class="bg-gray-400 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex-1 text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
