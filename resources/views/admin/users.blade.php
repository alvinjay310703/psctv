@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <!-- Left: Title -->
        <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
            <!-- Heroicon: Users -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6h6m-6 0v-2a4 4 0 014-4h2m-6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
            Users Management
        </h2>

        <!-- Middle + Right -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full md:w-auto gap-3">
            <!-- Middle: Search + Filter -->
            <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" placeholder="🔍 Search by name or email"
                       value="{{ request('search') }}"
                       class="border rounded-lg px-3 py-2 w-56 focus:ring focus:ring-blue-200 text-sm">
                <select name="role" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                    <option value="staff" {{ request('role')=='staff' ? 'selected' : '' }}>Staff</option>
                    <option value="technician" {{ request('role')=='technician' ? 'selected' : '' }}>Technician</option>
                </select>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 shadow">
                    Apply
                </button>
                <a href="{{ route('users.index') }}" 
                   class="px-4 py-2 rounded-lg text-sm border hover:bg-gray-50">
                   Reset
                </a>
            </form>

            <!-- Right: Add User -->
            <a href="{{ route('users.create') }}" 
               class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm flex items-center gap-2 hover:bg-blue-700 shadow ml-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add User
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-300">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="p-3 text-left w-16">ID</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Role</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($users as $user)
                <tr class="hover:bg-gray-50 transition odd:bg-gray-50/50">
                    <td class="p-3 font-medium text-gray-800">{{ $user->id }}</td>
                    <td class="p-3">{{ $user->name }}</td>
                    <td class="p-3 text-gray-600">{{ $user->email }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs 
                            {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : 
                               ($user->role === 'technician' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="p-3 text-right">
                        <div class="flex justify-end space-x-2">
                            <!-- Edit -->
                            <a href="#" 
                               class="bg-yellow-500 text-white px-3 py-1 rounded text-xs hover:bg-yellow-600 flex items-center gap-1">
                                ✏️ Edit
                            </a>
                            <!-- Delete -->
                            <form action="#" method="POST" onsubmit="return confirm('Delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700 flex items-center gap-1">
                                    🗑 Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
