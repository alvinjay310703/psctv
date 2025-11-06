@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg">
                    <x-lucide-credit-card class="w-8 h-8 text-white"/>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Customer Subscriptions</h1>
                    <p class="text-gray-600 mt-1">Manage and track all customer subscription plans</p>
                </div>
            </div>
            <a href="{{ route('subscriptions.create') }}" 
               class="group relative bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <x-lucide-plus class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"/>
                <span>New Subscription</span>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Subscriptions</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $subscriptions->total() }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <x-lucide-credit-card class="w-6 h-6 text-blue-600"/>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $subscriptions->where('status', 'active')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <x-lucide-check-circle class="w-6 h-6 text-green-600"/>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-red-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Expired</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $subscriptions->where('status', 'expired')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-red-50 rounded-xl">
                        <x-lucide-x-circle class="w-6 h-6 text-red-600"/>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-gray-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cancelled</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $subscriptions->where('status', 'cancelled')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <x-lucide-ban class="w-6 h-6 text-gray-600"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
            <form method="GET" action="{{ route('subscriptions.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-lucide-search class="h-4 w-4 text-gray-400"/>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by customer, email, or plan..."
                           class="pl-10 w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" />
                </div>

                <select name="status" class="border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>Expired</option>
                    <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white px-4 py-3 rounded-xl shadow transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <x-lucide-search class="w-4 h-4"/>
                        <span>Search</span>
                    </button>
                    <a href="{{ route('subscriptions.index') }}" 
                       class="px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center justify-center transform hover:-translate-y-0.5">
                        <x-lucide-refresh-cw class="w-4 h-4"/>
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="p-4 text-left font-semibold">Customer</th>
                            <th class="p-4 text-left font-semibold">Plan</th>
                            <th class="p-4 text-left font-semibold">Price</th>
                            <th class="p-4 text-left font-semibold">Cycle</th>
                            <th class="p-4 text-left font-semibold">Next Billing</th>
                            <th class="p-4 text-left font-semibold">Status</th>
                            <th class="p-4 text-left font-semibold">Start Date</th>
                            <th class="p-4 text-left font-semibold">End Date</th>
                            <th class="p-4 text-right font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subscriptions as $sub)
                        <tr class="hover:bg-gray-50 transition-all duration-200 group">
                            <!-- Customer -->
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium text-sm">
                                        {{ substr($sub->customer->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $sub->customer->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $sub->customer->user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Plan -->
                            <td class="p-4 font-medium text-gray-900">{{ $sub->package->name }}</td>
                            <td class="p-4 font-semibold text-gray-900">₱{{ number_format($sub->package->price, 2) }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 capitalize">
                                    {{ $sub->package->billing_cycle }}
                                </span>
                            </td>

                            <!-- Next Billing -->
                            <td class="p-4">
                                @if($sub->next_billing_date)
                                    <div class="flex items-center gap-2">
                                        <x-lucide-calendar class="w-4 h-4 text-gray-400"/>
                                        <span class="text-gray-700">{{ $sub->next_billing_date->format('M d, Y') }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <!-- Status -->
                            <td class="p-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold transition-all duration-300
                                    @if($sub->status === 'active') bg-green-100 text-green-700 shadow-sm
                                    @elseif($sub->status === 'expired') bg-red-100 text-red-600 shadow-sm
                                    @elseif($sub->status === 'cancelled') bg-gray-200 text-gray-600 shadow-sm
                                    @else bg-yellow-100 text-yellow-600 shadow-sm @endif">
                                    @if($sub->status === 'active')
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
                                    @elseif($sub->status === 'expired')
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                    @elseif($sub->status === 'cancelled')
                                    <x-lucide-ban class="w-3 h-3 mr-1"/>
                                    @endif
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>

                            <!-- Dates -->
                            <td class="p-4 text-gray-600">{{ $sub->start_date->format('M d, Y') }}</td>
                            <td class="p-4 text-gray-600">{{ $sub->end_date?->format('M d, Y') ?? '—' }}</td>

                            <!-- Actions -->
                            <td class="p-4">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('subscriptions.show', $sub->id) }}" 
                                       class="p-2 rounded-lg text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 group/action tooltip" data-tooltip="View Details">
                                        <x-lucide-eye class="w-4 h-4"/>
                                    </a>

                                    @if($sub->status === 'active')
                                        <a href="{{ route('subscriptions.edit', $sub->id) }}" 
                                           class="p-2 rounded-lg text-gray-500 hover:text-yellow-600 hover:bg-yellow-50 transition-all duration-200 group/action tooltip" data-tooltip="Edit Subscription">
                                            <x-lucide-edit class="w-4 h-4"/>
                                        </a>
                                        <button type="button"
                                                class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip"
                                                onclick="confirmCancel({{ $sub->id }}, '{{ $sub->customer->user->name }}')"
                                                data-tooltip="Cancel Subscription">
                                            <x-lucide-x class="w-4 h-4"/>
                                        </button>
                                        <form id="cancel-form-{{ $sub->id }}" action="{{ route('subscriptions.destroy', $sub->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    @elseif($sub->status === 'expired')
                                        <form action="{{ route('subscriptions.renew', $sub->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" 
                                                    class="p-2 rounded-lg text-gray-500 hover:text-green-600 hover:bg-green-50 transition-all duration-200 group/action tooltip"
                                                    data-tooltip="Renew Subscription"
                                                    onclick="return confirm('Renew this subscription for {{ $sub->customer->user->name }}?')">
                                                <x-lucide-refresh-cw class="w-4 h-4"/>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <x-lucide-credit-card class="w-12 h-12 text-gray-300"/>
                                    <p class="text-lg">No subscriptions found</p>
                                    <a href="{{ route('subscriptions.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mt-2">
                                        <x-lucide-plus class="w-4 h-4"/>
                                        Create your first subscription
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            <div class="bg-white rounded-2xl shadow-lg px-4 py-3 border border-gray-100">
                {{ $subscriptions->links() }}
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Tooltip styles */
.tooltip {
    position: relative;
}

.tooltip::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    z-index: 10;
}

.tooltip::after {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.8);
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
}

.tooltip:hover::before,
.tooltip:hover::after {
    opacity: 1;
    visibility: visible;
    bottom: calc(100% + 4px);
}

tbody tr {
    animation: slideInUp 0.5s ease-out;
}
tbody tr:nth-child(1) { animation-delay: 0.05s; }
tbody tr:nth-child(2) { animation-delay: 0.1s; }
tbody tr:nth-child(3) { animation-delay: 0.15s; }
tbody tr:nth-child(4) { animation-delay: 0.2s; }
tbody tr:nth-child(5) { animation-delay: 0.25s; }
</style>

<script>
function confirmCancel(id, customer) {
    Swal.fire({
        title: 'Cancel Subscription?',
        text: `You are about to cancel subscription #${id} for ${customer}. This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, cancel it',
        cancelButtonText: 'No, keep it',
        background: '#fff',
        backdrop: 'rgba(0,0,0,0.4)'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`cancel-form-${id}`).submit();
        }
    });
}

// Add loading state to search button
const searchForm = document.querySelector('form');
const searchButton = searchForm.querySelector('button[type="submit"]');

searchForm.addEventListener('submit', function() {
    searchButton.innerHTML = `
        <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
        Searching...
    `;
    searchButton.disabled = true;
});
</script>
@endsection