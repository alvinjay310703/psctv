@extends('layouts.admin')

@section('title', 'Pre-Registered Customers')

@section('content')
<div class="bg-white rounded-2xl shadow-md p-6">
    <h2 class="text-xl font-bold text-gray-800 mb-4">🕓 Pre-Registered Customers</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Account #</th>
                    <th class="p-2 border">Name</th>
                    <th class="p-2 border">Contact</th>
                    <th class="p-2 border">Address</th>
                    <th class="p-2 border">Pre-Reg Date</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Row -->
                <tr class="hover:bg-gray-50">
                    <td class="p-2 border">PR-001</td>
                    <td class="p-2 border">Maria Santos</td>
                    <td class="p-2 border">0912-345-6789</td>
                    <td class="p-2 border">Barangay 2, Panabo City</td>
                    <td class="p-2 border">2025-08-18</td>
                    <td class="p-2 border">
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Pending</span>
                    </td>
                    <td class="p-2 border">
                        <div class="flex justify-center space-x-2">
                            <a href="/customers/pre-registered/1" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-1 rounded text-xs">
                                 View
                            </a>
                            <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-1 rounded text-xs">
                                Verify
                            </button>
                            <button class="bg-red-600 hover:bg-red-700 text-white px-5 py-1 rounded text-xs">
                                 Delete
                            </button>
                        </div>
                    </td>
                </tr>
                <!-- Repeat more rows here -->
            </tbody>
        </table>
    </div>
</div>
@endsection
