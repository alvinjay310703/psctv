@extends('layouts.staff')

@section('title', 'Customer List')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md">
    <!-- Header (removed Add Customer button) -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex mb-2" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('staff.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 mr-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2A1 1 0 0 0 1 10h2v8a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-8h2a1 1 0 0 0 .707-1.707Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Customers</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a4 4 0 00-4-4h-1m-6 6H6a4 4 0 01-4-4v-1m12 5a4 4 0 00-4-4H6m6-6a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
                Customer Management
            </h2>
        </div>
    </div>

    <!-- Search & Filter -->
    <form method="GET" action="{{ route('staff.customers.index') }}" class="flex flex-wrap items-center gap-3 mb-5">
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
        <a href="{{ route('staff.customers.index') }}" class="px-4 py-2 rounded-lg text-sm border hover:bg-gray-50 transition">
            Reset
        </a>
    </form>

    <!-- Table (removed Delete column) -->
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
                            <a href="{{ route('staff.customers.show', $customer->id) }}" class="flex items-center text-blue-600 hover:text-blue-800 transition text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                View
                            </a>
                            <!-- Edit (limited) -->
                            <a href="{{ route('staff.customers.edit', $customer->id) }}" class="flex items-center text-yellow-600 hover:text-yellow-700 transition text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-9l5 5M13 6l7 7" />
                                </svg>
                                Edit
                            </a>
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
