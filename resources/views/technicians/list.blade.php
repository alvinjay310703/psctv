    @extends('layouts.admin')

    @section('title', 'Technician List')

    @section('content')
    <div class="bg-white p-6 rounded-2xl shadow-md">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
                <!-- Wrench Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M7 8a4 4 0 015.656-3.657l1.415 1.414a4 4 0 010 5.657l-5.657 5.657a4 4 0 01-5.657-5.657L7 8z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M14 14l7 7" />
                </svg>
                Technician Management
            </h2>

            <a href="add" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm flex items-center gap-2 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Technician
            </a>
        </div>

        <!-- Search & Filter -->
        <form method="GET" action="" class="flex flex-wrap items-center gap-3 mb-5">
            <div class="relative">
                <input 
                    type="text" 
                    name="search"
                    value="{{ request('search') }}"
                    placeholder=""
                    class="border rounded-lg pl-9 pr-3 py-2 w-64 focus:ring-2 focus:ring-blue-300 text-sm"
                    oninput="this.form.submit()"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-2 top-2.5 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </div>
            
            <select name="status" onchange="this.form.submit()"
                class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-300">
                <option value="">Status: All</option>
                <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Name</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Phone</th>
                        <th class="p-3 text-left">Specialization</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 font-medium text-gray-800">TECH-001</td>
                        <td class="p-3">Juan Dela Cruz</td>
                        <td class="p-3 text-gray-600">juan@example.com</td>
                        <td class="p-3 text-gray-600">0912-345-6789</td>
                        <td class="p-3 text-gray-600">Cable Installation</td>
                        <td class="p-3">
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs font-semibold">Active</span>
                        </td>
                    <td class="p-3">
        <div class="flex justify-center space-x-4">
            <!-- View -->
            <a href="{{ route('technicians.show', 1) }}" 
            class="flex items-center text-blue-600 hover:text-blue-800 transition text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                View
            </a>

            <!-- Edit -->
            <a href="#" class="flex items-center text-yellow-600 hover:text-yellow-700 transition text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-5-9l5 5M13 6l7 7" />
                </svg>
                Edit
            </a>

            <!-- Delete -->
            <a href="#" class="flex items-center text-red-600 hover:text-red-700 transition text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h-2m-8 0H5" />
                </svg>
                Delete
            </a>
        </div>
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
                <button class="px-3 py-1 border bg-blue-600 text-white rounded">1</button>
                <button class="px-3 py-1 border hover:bg-gray-100">2</button>
                <button class="px-3 py-1 border hover:bg-gray-100">Next »</button>
            </div>
        </div>
    </div>
    @endsection
