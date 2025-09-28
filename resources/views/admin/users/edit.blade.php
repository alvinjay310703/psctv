@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-lg max-w-lg mx-auto">
    <h2 class="text-xl font-bold mb-4">✏️ Edit User</h2>


   

    <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ $user->name }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
            </select>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('users.index') }}" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
        </div>
    </form>
</div>
@endsection
