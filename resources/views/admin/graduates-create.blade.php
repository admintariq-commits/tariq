@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
    <h1 class="text-2xl font-bold mb-4">➕ Add New Graduate</h1>

    <form method="POST" action="{{ route('admin.graduates.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-gray-700 mb-2">First Name *</label>
                <input type="text" name="first_name" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Last Name *</label>
                <input type="text" name="last_name" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Phone *</label>
                <input type="text" name="phone" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Graduation Date *</label>
                <input type="date" name="graduation_date" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-gray-700 mb-2">University *</label>
                <select name="university_id" required class="w-full border rounded-lg p-2">
                    @foreach($universities as $uni)
                        <option value="{{ $uni->id }}">{{ $uni->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Course *</label>
                <select name="course_id" required class="w-full border rounded-lg p-2">
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-700 mb-2">Employment Status *</label>
                <select name="employment_status" required class="w-full border rounded-lg p-2">
                    <option value="employed">Employed</option>
                    <option value="self_employed">Self Employed</option>
                    <option value="unemployed">Unemployed</option>
                </select>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Save Graduate</button>
            <a href="{{ route('admin.graduates.index') }}" class="ml-2 text-gray-500">Cancel</a>
        </div>
    </form>
</div>
@endsection