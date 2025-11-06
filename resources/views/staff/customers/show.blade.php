@extends('layouts.staff')

@section('title', 'Customer Profile')

@section('content')
<div class="bg-white rounded-2xl shadow-md p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-2xl font-bold text-blue-600">
                {{ strtoupper(substr($customer->user->name,0,2)) }}
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $customer->user->name }}</h2>
                <p class="text-sm text-gray-500">Account #: {{ $customer->account_number }}</p>
                <p class="text-xs font-medium 
                    {{ $customer->status == 'active' ? 'text-green-600' : ($customer->status == 'suspended' ? 'text-red-600' : 'text-gray-500') }}">
                    ● {{ ucfirst($customer->status) }}
                </p>
            </div>
        </div>
        <a href="{{ route('staff.customers.index') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow text-sm">
            ← Back to List
        </a>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-blue-50 rounded-xl text-center shadow-sm">
            <p class="text-sm text-gray-600">Total Paid</p>
            <p class="text-lg font-bold text-blue-700">₱{{ number_format($totalPaid, 2) }}</p>
        </div>
        <div class="p-4 bg-red-50 rounded-xl text-center shadow-sm">
            <p class="text-sm text-gray-600">Outstanding Balance</p>
            <p class="text-lg font-bold text-red-600">₱{{ number_format($outstandingBalance, 2) }}</p>
        </div>
        <div class="p-4 bg-green-50 rounded-xl text-center shadow-sm">
            <p class="text-sm text-gray-600">Active Subscriptions</p>
            <p class="text-lg font-bold text-green-600">
                {{ $customer->subscriptions->where('status','active')->count() }}
            </p>
        </div>
        <div class="p-4 bg-yellow-50 rounded-xl text-center shadow-sm">
            <p class="text-sm text-gray-600">Service Requests</p>
            <p class="text-lg font-bold text-yellow-600">{{ $customer->serviceRequests->count() }}</p>
        </div>
    </div>

    <!-- Tabs -->
    <div x-data="{ tab: 'overview' }" class="mt-4">
        <!-- Tab Buttons -->
        <div class="border-b mb-4 flex space-x-6 text-sm font-medium">
            <button @click="tab = 'overview'" 
                :class="tab === 'overview' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500'" 
                class="pb-2 transition">Overview</button>
            <button @click="tab = 'subscriptions'" 
                :class="tab === 'subscriptions' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500'" 
                class="pb-2 transition">Subscriptions</button>
            <button @click="tab = 'billing'" 
                :class="tab === 'billing' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500'" 
                class="pb-2 transition">Billing</button>
            <button @click="tab = 'requests'" 
                :class="tab === 'requests' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-500'" 
                class="pb-2 transition">Service Requests</button>
        </div>

        <!-- Overview -->
        <div x-show="tab === 'overview'" x-transition class="space-y-2 text-gray-700">
            <p><strong>Email:</strong> {{ $customer->user->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Address:</strong> {{ $customer->address }}, {{ $customer->city }}, {{ $customer->province }} {{ $customer->zip_code }}</p>
            <p><strong>Joined:</strong> {{ $customer->created_at->format('M d, Y') }}</p>
        </div>

        <!-- Subscriptions -->
        <div x-show="tab === 'subscriptions'" x-transition class="overflow-x-auto">
            <table class="w-full text-sm border-collapse shadow-sm rounded-xl">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">Plan</th>
                        <th class="p-2 border">Start</th>
                        <th class="p-2 border">End</th>
                        <th class="p-2 border">Monthly Fee</th>
                        <th class="p-2 border">Next Billing</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->subscriptions as $sub)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $sub->package->name ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('M d, Y') : '—' }}</td>
                            <td class="p-2 border">{{ $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('M d, Y') : 'Ongoing' }}</td>
                            <td class="                            <td class="p-2 border">₱{{ number_format($sub->package->price ?? 0, 2) }}</td>
                            <td class="p-2 border">{{ $sub->next_billing_date ? \Carbon\Carbon::parse($sub->next_billing_date)->format('M d, Y') : '—' }}</td>
                            <td class="p-2 border">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $sub->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 p-3">No subscriptions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Billing -->
        <div x-show="tab === 'billing'" x-transition class="overflow-x-auto">
            <table class="w-full text-sm border-collapse shadow-sm rounded-xl">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">Bill ID</th>
                        <th class="p-2 border">Description</th>
                        <th class="p-2 border">Amount</th>
                        <th class="p-2 border">Due Date</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->subscriptions->flatMap->invoices as $invoice)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $invoice->invoice_no }}</td>
                            <td class="p-2 border">{{ $invoice->description }}</td>
                            <td class="p-2 border">₱{{ number_format($invoice->amount_due, 2) }}</td>
                            <td class="p-2 border">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</td>
                            <td class="p-2 border">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-3">No billing records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Service Requests -->
        <div x-show="tab === 'requests'" x-transition class="overflow-x-auto">
            <table class="w-full text-sm border-collapse shadow-sm rounded-xl">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="p-2 border">Request ID</th>
                        <th class="p-2 border">Type</th>
                        <th class="p-2 border">Date</th>
                        <th class="p-2 border">Technician</th>
                        <th class="p-2 border">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customer->serviceRequests as $req)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">REQ-{{ $req->id }}</td>
                            <td class="p-2 border">{{ ucfirst($req->service_type) }}</td>
                            <td class="p-2 border">{{ $req->created_at->format('M d, Y') }}</td>
                            <td class="p-2 border">{{ $req->technician->full_name ?? 'Unassigned' }}</td>
                            <td class="p-2 border">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $req->status == 'completed' ? 'bg-green-100 text-green-700' : ($req->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 p-3">No service requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>
@endsection