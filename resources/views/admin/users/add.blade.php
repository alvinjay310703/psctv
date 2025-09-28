@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="flex justify-center items-center">
    <div class="bg-white shadow-lg rounded-xl w-full max-w-lg p-8">
        <!-- Header -->
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-6">
            <!-- Heroicon: User Plus -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" 
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v16m8-8H4" />
            </svg>
            Add New User
        </h2>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg border border-red-300 text-sm">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('users.store') }}" method="POST" autocomplete="off" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="name" 
                       class="mt-1 w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300" 
                       required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" autocomplete="new-email"
                       class="mt-1 w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300" 
                       required>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" autocomplete="new-password"
                       class="mt-1 w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300" 
                       required>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                       class="mt-1 w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300" 
                       required>
            </div>

            <!-- Role -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Role</label>
                <select name="role" class="mt-1 w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-300">
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('users.index') }}" 
                   class="text-gray-600 hover:text-gray-800 text-sm flex items-center gap-1">
                    ← Back
                </a>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                    💾 Save User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
