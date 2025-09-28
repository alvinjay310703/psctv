@extends('layouts.admin')

@section('title', 'Technician Profile')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-8 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center space-x-4">
            <!-- Profile Picture -->
            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-blue-200 shadow-sm">
                <img src="{{ $technician->profile_picture ?? '/images/default-avatar.png' }}" 
                     alt="Profile" class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $technician->full_name }}</h2>
                <p class="text-sm text-gray-500">ID: {{ $technician->id }}</p>
                <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full
                    {{ $technician->status == 'active' ? 'bg-green-100 text-green-700' : 
                        ($technician->status == 'inactive' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($technician->status) }}
                </span>
            </div>
        </div>
        <a href="{{ route('technicians.list') }}" 
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm shadow">
            ← Back
        </a>
    </div>

    <!-- Performance Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-5 bg-blue-50 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-500 uppercase">Jobs Completed</p>
            <p class="text-2xl font-bold text-blue-700">
                {{ $technician->jobs_completed ?? 0 }}
            </p>
        </div>
        <div class="p-5 bg-yellow-50 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-500 uppercase">Jobs Pending</p>
            <p class="text-2xl font-bold text-yellow-700">
                {{ $technician->jobs_pending ?? 0 }}
            </p>
        </div>
        <div class="p-5 bg-green-50 rounded-xl shadow-sm text-center">
            <p class="text-xs text-gray-500 uppercase">Avg. Rating</p>
            <p class="text-2xl font-bold text-green-700">
                {{ $technician->average_rating ?? '—' }} ⭐
            </p>
        </div>
    </div>

    <!-- Quick Info -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-4 rounded-xl border bg-gray-50 shadow-sm text-center">
            <p class="text-xs text-gray-500 uppercase">Specialization</p>
            <p class="text-lg font-semibold text-blue-700">{{ ucfirst($technician->specialization) }}</p>
        </div>
        <div class="p-4 rounded-xl border bg-gray-50 shadow-sm text-center">
            <p class="text-xs text-gray-500 uppercase">Service Area</p>
            <p class="text-lg font-semibold text-green-700">{{ ucfirst($technician->service_area) }}</p>
        </div>
        <div class="p-4 rounded-xl border bg-gray-50 shadow-sm text-center">
            <p class="text-xs text-gray-500 uppercase">Date of Hire</p>
            <p class="text-lg font-semibold text-gray-800">{{ $technician->date_hire }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'contact' }">
        <!-- Tab Buttons -->
        <div class="border-b mb-6 flex space-x-8 text-sm font-medium">
            <button @click="tab = 'contact'" 
                :class="tab === 'contact' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'" 
                class="pb-2">📞 Contact</button>
            <button @click="tab = 'history'" 
                :class="tab === 'history' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'" 
                class="pb-2">📋 Work History</button>
        </div>

        <!-- Contact Tab -->
        <div x-show="tab === 'contact'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Email</h3>
                <p class="text-gray-800">{{ $technician->email }}</p>
            </div>
            <div>
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Phone</h3>
                <p class="text-gray-800">{{ $technician->phone }}</p>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Address</h3>
                <p class="text-gray-800">{{ $technician->address }}</p>
            </div>
            <div class="md:col-span-2">
                <h3 class="text-xs font-semibold text-gray-500 uppercase mb-1">Emergency Contact</h3>
                <p class="text-gray-800">{{ $technician->emergency_name }} ({{ $technician->emergency_phone }})</p>
            </div>
        </div>

        <!-- Work History Tab -->
        <div x-show="tab === 'history'" class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Request ID</th>
                        <th class="p-3 text-left">Type</th>
                        <th class="p-3 text-left">Date</th>
                        <th class="p-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">REQ-201</td>
                        <td class="p-3">Installation</td>
                        <td class="p-3">2025-08-20</td>
                        <td class="p-3"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Completed</span></td>
                    </tr>
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">REQ-205</td>
                        <td class="p-3">Repair</td>
                        <td class="p-3">2025-08-25</td>
                        <td class="p-3"><span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Pending</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
