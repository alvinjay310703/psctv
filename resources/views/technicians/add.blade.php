@extends('layouts.admin')

@section('title', 'Add Technician')

@section('content')
<div class="bg-white p-8 rounded-2xl shadow-md max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M5 13l4 4L19 7" />
            </svg>
            Add New Technician
        </h2>
        <a href="/technicians" 
           class="text-sm text-gray-600 hover:text-blue-600 transition">← Back to List</a>
    </div>

    <!-- Form -->
    <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Profile Picture Upload -->
        <div class="flex flex-col items-center">
            <label class="relative w-32 h-32 rounded-full overflow-hidden border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center cursor-pointer hover:border-blue-400 transition">
                <input type="file" class="hidden" name="profile_picture" accept="image/*">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 4v16m8-8H4" />
                </svg>
                <span class="absolute bottom-2 bg-white text-gray-600 text-xs px-2 py-1 rounded shadow">Upload</span>
            </label>
            <p class="mt-2 text-sm text-gray-500">Profile Picture</p>
        </div>

        <!-- Technician ID -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Technician ID</label>
            <input type="text" name="technician_id" placeholder="e.g. TECH-001"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="full_name" placeholder="Enter full name"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" placeholder="example@email.com"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" placeholder="09XX-XXX-XXXX"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Address -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Address</label>
            <input type="text" name="address" placeholder="Street, City, Province"
                   class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Service Area + Date of Hire -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Service Area</label>
                <select name="service_area" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="">Select Area</option>
                    <option value="north">North Zone</option>
                    <option value="south">South Zone</option>
                    <option value="city">City Proper</option>
                    <option value="province">Province</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Date of Hire</label>
                <input type="date" name="date_hire"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Specialization -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Specialization</label>
            <select name="specialization" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="">Select Specialization</option>
                <option value="cable">Cable Installation</option>
                <option value="internet">Internet Setup</option>
                <option value="satellite">Satellite Services</option>
                <option value="hybrid">Hybrid (All Services)</option>
            </select>
        </div>

        <!-- Emergency Contact -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Emergency Contact Name</label>
                <input type="text" name="emergency_name" placeholder="Contact Person"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Emergency Contact Phone</label>
                <input type="text" name="emergency_phone" placeholder="09XX-XXX-XXXX"
                       class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="mt-1 w-full rounded-lg border px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3">
            <a href="/technicians" 
               class="px-5 py-2 rounded-lg border text-gray-600 hover:bg-gray-50">Cancel</a>
            <button type="submit"
                    class="px-6 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                Save Technician
            </button>
        </div>
    </form>
</div>
@endsection
