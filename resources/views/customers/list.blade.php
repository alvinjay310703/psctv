@extends('layouts.admin')

@section('title', 'Customer List')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
            <!-- Clean Users Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
            </svg>
            Customer Management
        </h2>

        <a href="{{ route('customers.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm flex items-center gap-2 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Customer
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('customers.list') }}" class="flex flex-wrap items-center gap-3 mb-5">
        <input 
            type="text" 
            name="search"
            value="{{ request('search') }}"
            placeholder="🔍 Search by name or account #"
            class="border rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-blue-300 text-sm"
        >
        <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300">
            <option value="all" {{ request('status')=='all'?'selected':'' }}>Status: All</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 shadow-sm">
            Apply
        </button>
        <a href="{{ route('customers.list') }}" class="px-4 py-2 rounded-lg text-sm border hover:bg-gray-50 transition">
            Reset
        </a>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="p-3 text-left">Account #</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Contact</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($customers as $customer)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 font-medium text-gray-800">{{ $customer->account_number }}</td>
                    <td class="p-3">{{ $customer->user->name ?? '-' }}</td>
                    <td class="p-3 text-gray-600">{{ $customer->phone ?? '-' }}</td>
                    <td class="p-3">
                        @if ($customer->status == 'active')
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                        @elseif ($customer->status == 'inactive')
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs font-semibold">Inactive</span>
                        @elseif ($customer->status == 'suspended')
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-semibold">Suspended</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">{{ ucfirst($customer->status) }}</span>
                        @endif
                    </td>
                    <td class="p-3">
                        <div class="flex justify-center space-x-4">
                            <!-- View -->
                            <a href="{{ route('customers.show', $customer->id) }}" class="flex items-center text-blue-600 hover:text-blue-800 transition text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                            <!-- Edit -->
                            <a href="{{ route('customers.edit', $customer->id) }}" class="flex items-center text-yellow-600 hover:text-yellow-700 transition text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-9l5 5M13 6l7 7" />
                                </svg>
                                Edit
                            </a>
                            <!-- Delete -->
                            <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center text-red-600 hover:text-red-700 transition text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h-2m-8 0H5" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <p>
            Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} entries
        </p>
        <div class="flex space-x-1">
            {{ $customers->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection
