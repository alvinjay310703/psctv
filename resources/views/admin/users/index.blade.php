@extends('layouts.admin')

@section('title', 'User Accounts')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-lg transition">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
           <!-- Heroicon: Users --> 
            <svg xmlns="http://www.w3.org/2000/svg" 
                class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            User Management

        </h2>
        <a href="{{ route('users.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center gap-1 shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add User
        </a>
    </div>

    <!-- ✅ Search & Filter -->
    <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-3 mb-5">
        <!-- Search -->
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search by name or email"
                   class="border rounded-lg px-3 py-2 pl-9 w-64 text-sm focus:ring-2 focus:ring-blue-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-2 top-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
            </svg>
        </div>

      <!-- Role Filter -->
<select name="role"
        class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300">
    <option value="">All Roles</option>
    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
    <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
</select>


        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 shadow">
            Apply
        </button>
        <a href="{{ route('users.index') }}"
           class="px-4 py-2 rounded-lg text-sm border hover:bg-gray-50">
            Reset
        </a>
    </form>

   {{--  Success Notification --}}
@if(session('success'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition 
        x-init="setTimeout(() => show = false, 3000)" 
        class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50"
    >
        ✅ {{ session('success') }}
    </div>
@endif

{{-- Error Notification --}}
@if(session('error'))
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition 
        x-init="setTimeout(() => show = false, 4000)" 
        class="fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50"
    >
        ⚠️ {{ session('error') }}
    </div>
@endif

{{--  Validation Errors (multiple) --}}
@if($errors->any())
    <div 
        x-data="{ show: true }" 
        x-show="show" 
        x-transition 
        x-init="setTimeout(() => show = false, 5000)" 
        class="fixed top-4 right-4 bg-red-600 text-white px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm"
    >
        <ul class="list-disc ml-5 text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



    <!-- ✅ Users Table -->
    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="py-3 px-4 text-left">#</th>
                    <th class="py-3 px-4 text-left">Name</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Role</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3">
                         {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                   </td>
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $user->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                    <td class="py-3 px-4">
                        @if($user->role === 'admin')
                            <span class="px-2 py-1 bg-purple-100 text-purple-700 text-xs font-medium rounded-full">Admin</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">Staff</span>
                        @endif
                    </td>
                 <td class="py-3 px-4 text-center space-x-2">
                 
    <div class="flex justify-center space-x-4">
        <!-- Edit -->
        <a href="{{ route('users.edit', $user->id) }}" 
           class="text-blue-600 hover:text-blue-800 text-sm flex items-center gap-1">
            <!-- Heroicon Pencil -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3zM3 21h18" />
            </svg>
            Edit
        </a>

        <!-- Delete -->
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" 
              onsubmit="return confirm('Are you sure you want to delete this user?')" 
              class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                class="text-red-600 hover:text-red-800 text-sm flex items-center gap-1">
                <!-- Heroicon Trash -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a2 2 0 012 2v2H8V5a2 2 0 012-2z" />
                </svg>
                Delete
            </button>
        </form>
    </div>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

   <!-- Pagination -->
<div class="flex justify-between items-center mt-6 text-sm text-gray-600">
    <p>
        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
    </p>

    <div class="flex space-x-1">
        <!-- Previous -->
        @if ($users->onFirstPage())
            <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">Previous</span>
        @else
            <a href="{{ $users->previousPageUrl() }}" 
               class="px-3 py-1 border rounded hover:bg-gray-100">Previous</a>
        @endif

        <!-- Page Numbers -->
        @php
            $current = $users->currentPage();
            $last = $users->lastPage();
            $start = max(1, $current - 2);
            $end = min($last, $current + 2);
        @endphp

        {{-- First page --}}
        @if ($start > 1)
            <a href="{{ $users->url(1) }}" class="px-3 py-1 border rounded hover:bg-gray-100">1</a>
            @if ($start > 2)
                <span class="px-2">...</span>
            @endif
        @endif

        {{-- Middle pages --}}
        @for ($page = $start; $page <= $end; $page++)
            @if ($page == $current)
                <span class="px-3 py-1 border rounded bg-blue-600 text-white">{{ $page }}</span>
            @else
                <a href="{{ $users->url($page) }}" 
                   class="px-3 py-1 border rounded hover:bg-gray-100">{{ $page }}</a>
            @endif
        @endfor

        {{-- Last page --}}
        @if ($end < $last)
            @if ($end < $last - 1)
                <span class="px-2">...</span>
            @endif
            <a href="{{ $users->url($last) }}" class="px-3 py-1 border rounded hover:bg-gray-100">{{ $last }}</a>
        @endif

        <!-- Next -->
        @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" 
               class="px-3 py-1 border rounded hover:bg-gray-100">Next</a>
        @else
            <span class="px-3 py-1 border rounded text-gray-400 cursor-not-allowed">Next</span>
        @endif
    </div>
</div>


</div>
@endsection
