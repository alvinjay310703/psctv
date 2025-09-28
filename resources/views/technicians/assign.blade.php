@extends('layouts.admin')

@section('title', 'Assign Jobs')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
            <!-- Clipboard Check Icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Assign Jobs
        </h2>
    </div>

    <!-- Search -->
    <div class="flex flex-wrap items-center gap-3 mb-5">
        <input 
            type="text" 
            placeholder="🔍 Search by request ID or customer name"
            class="border rounded-lg px-3 py-2 w-72 focus:ring-2 focus:ring-purple-300 text-sm"
        >
        <select class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-300">
            <option>Status: All</option>
            <option>Pending</option>
            <option>Assigned</option>
            <option>Completed</option>
        </select>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="p-3 text-left">Request ID</th>
                    <th class="p-3 text-left">Customer</th>
                    <th class="p-3 text-left">Service Type</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Technician</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 font-medium text-gray-800">REQ-1001</td>
                    <td class="p-3">Maria Santos</td>
                    <td class="p-3">Installation</td>
                    <td class="p-3">
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs font-semibold">Pending</span>
                    </td>
                    <td class="p-3">
                        <select class="border rounded-lg px-2 py-1 text-sm focus:ring-2 focus:ring-purple-300">
                            <option>-- Select Technician --</option>
                            <option>John Doe</option>
                            <option>Jane Smith</option>
                        </select>
                    </td>
                    <td class="p-3 text-center">
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-xs shadow">
                            Assign
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <p>Showing 1 to 10 of 25 entries</p>
        <div class="flex space-x-1">
            <button class="px-3 py-1 border rounded hover:bg-gray-100">« Prev</button>
            <button class="px-3 py-1 border bg-purple-600 text-white rounded">1</button>
            <button class="px-3 py-1 border hover:bg-gray-100">2</button>
            <button class="px-3 py-1 border hover:bg-gray-100">Next »</button>
        </div>
    </div>
</div>
@endsection
