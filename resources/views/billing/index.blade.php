@extends('layouts.admin')

@section('title', 'Billing Records')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30 font-sans text-gray-800">
    <!-- Summary Cards -->
    <section class="bg-white/80 backdrop-blur-xl p-6 rounded-2xl shadow-sm border border-gray-200/60 mb-8">
        <h2 class="text-xl font-bold mb-6 flex items-center text-gray-800">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            Financial Summary
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Total Collected -->
            <div class="bg-gradient-to-br from-green-50 to-green-100/50 p-5 rounded-xl border border-green-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-green-600 mb-1">Total Collected</p>
                        <p class="text-2xl font-bold text-green-700">
                            ₱{{ number_format($summary['total_collected'] ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Bills -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100/50 p-5 rounded-xl border border-yellow-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-yellow-600 mb-1">Pending Bills</p>
                        <p class="text-2xl font-bold text-yellow-700">
                            ₱{{ number_format($summary['pending'] ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="bg-gradient-to-br from-red-50 to-red-100/50 p-5 rounded-xl border border-red-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1 group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-red-600 mb-1">Overdue</p>
                        <p class="text-2xl font-bold text-red-700">
                            ₱{{ number_format($summary['overdue']['amount'] ?? 0, 2) }}
                        </p>
                        <p class="text-sm text-red-500 mt-1">
                            {{ $summary['overdue']['customers'] ?? 0 }} customer{{ ($summary['overdue']['customers'] ?? 0) == 1 ? '' : 's' }}
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Billing Records -->
    <section class="bg-white/80 backdrop-blur-xl p-6 rounded-2xl shadow-sm border border-gray-200/60">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-md">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800">Billing Records</h2>
            </div>
            <a href="{{ route('billing.create') }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl shadow-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl font-semibold text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                New Bill
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="flex flex-wrap items-center gap-3 mb-6 text-sm bg-gray-50/50 p-4 rounded-xl border border-gray-200/60">
            <div class="relative w-full max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" name="search" placeholder="Search customer or bill #" 
                       value="{{ request('search') }}"
                       class="border border-gray-300 rounded-xl pl-10 pr-3 py-2.5 w-full focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-300" />
            </div>

            <select name="status" 
                    class="border border-gray-300 rounded-xl px-3 py-2.5 w-40 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-300">
                <option value="">Status: All</option>
                <option value="paid" @selected(request('status') === 'paid')>Paid</option>
                <option value="unpaid" @selected(request('status') === 'unpaid')>Unpaid</option>
                <option value="overdue" @selected(request('status') === 'overdue')>Overdue</option>
            </select>

            <div class="flex items-center gap-2">
                <input type="date" name="from" value="{{ request('from') }}"
                       class="border border-gray-300 rounded-xl px-3 py-2.5 w-40 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-300" />
                <span class="text-gray-400">to</span>
                <input type="date" name="to" value="{{ request('to') }}"
                       class="border border-gray-300 rounded-xl px-3 py-2.5 w-40 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all duration-300" />
            </div>

            <button type="submit" 
                    class="bg-gradient-to-r from-gray-800 to-gray-900 text-white px-5 py-2.5 rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 font-semibold">Apply</button>
            <a href="{{ route('billing.index') }}" 
               class="px-5 py-2.5 border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5">Reset</a>
        </form>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-200/60 shadow-sm bg-white">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50 text-gray-600 uppercase text-xs tracking-wide font-semibold">
                    <tr>
                        <th class="p-4 text-left">Bill #</th>
                        <th class="p-4 text-left">Customer</th>
                        <th class="p-4 text-left">Description</th>
                        <th class="p-4 text-left">Amount</th>
                        <th class="p-4 text-left">Due Date</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/60">
                    @forelse($bills as $bill)
                        @php
                            $status = strtolower($bill->status ?? 'unpaid');
                            $statusClasses = [
                                'paid' => 'bg-green-100 text-green-700 ring-1 ring-green-200',
                                'unpaid' => 'bg-yellow-100 text-yellow-700 ring-1 ring-yellow-200',
                                'overdue' => 'bg-red-100 text-red-700 ring-1 ring-red-200',
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition-all duration-200 group" x-data="{ showActions: false }" 
                            @mouseenter="showActions = true" @mouseleave="showActions = false">
                            <td class="p-4 font-medium text-gray-900 group-hover:text-blue-600 transition-colors">
                                {{ $bill->invoice_no ?? 'N/A' }}
                            </td>
                            <td class="p-4">
                                {{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-600">{{ $bill->description ?? 'N/A' }}</td>
                            <td class="p-4 font-semibold text-gray-900">₱{{ number_format($bill->amount_due ?? 0, 2) }}</td>
                            <td class="p-4 text-gray-600">
                                {{ $bill->due_date ? \Carbon\Carbon::parse($bill->due_date)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold shadow-sm transition-all duration-300 {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                    {{ ucfirst($bill->status ?? 'unpaid') }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center gap-2 transition-all duration-300" 
                                     :class="showActions ? 'opacity-100 transform translate-x-0' : 'opacity-70'">
                                    {{-- Mark as Paid --}}
                                    @if($status !== 'paid')
                                        <button 
                                            onclick="openMarkPaidModal('{{ $bill->id }}','{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}','{{ $bill->description ?? 'N/A' }}','{{ $bill->amount_due ?? 0 }}')"
                                            class="group relative flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg text-green-600 bg-green-50 hover:bg-green-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                            title="Mark as Paid"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.656 0 3 1.343 3 3v1a3 3 0 11-6 0V9a3 3 0 013-3z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14v2m0 0h3m-3 0H9" />
                                            </svg>
                                            <span>Pay</span>
                                        </button>
                                    @endif

                                    {{-- View Bill --}}
                                    <a href="{{ route('billing.show', $bill->id) }}" 
                                       class="group flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg text-gray-600 bg-gray-50 hover:bg-gray-700 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                       title="View Bill">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>View</span>
                                    </a>

                                    {{-- View Receipt --}}
                                    @if($status === 'paid')
                                        <a href="{{ route('billing.receipt', $bill->id) }}" 
                                           class="group flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                           title="View Receipt">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 6h13M8 12h13m-7 6h7" />
                                            </svg>
                                            <span>Receipt</span>
                                        </a>
                                    @endif

                                    {{-- Delete --}}
                                    <button 
                                        onclick="confirmDelete('{{ $bill->id }}')" 
                                        class="group flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg text-red-600 bg-red-50 hover:bg-red-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5"
                                        title="Delete Bill">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4a1 1 0 011 1v1H9V4a1 1 0 011-1z" />
                                        </svg>
                                        <span>Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-8">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-400 mb-2">No bills found</p>
                                    <p class="text-sm text-gray-500">Get started by creating your first bill</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        @if($bills->count())
        <div class="flex flex-col md:flex-row justify-between items-center mt-6 text-sm text-gray-600 border-t border-gray-200/60 pt-4 gap-3">
            <p class="text-gray-500">
                Showing <span class="font-semibold text-gray-700">{{ $bills->firstItem() }}</span>–<span class="font-semibold text-gray-700">{{ $bills->lastItem() }}</span> of <span class="font-semibold text-gray-700">{{ $bills->total() }}</span> results
            </p>
            <div class="bg-white rounded-lg border border-gray-200/60 p-1 shadow-sm">
                {{ $bills->links('vendor.pagination.tailwind') }}
            </div>
        </div>
        @endif

    </section>
</div>

<!-- Mark as Paid Modal -->
<div id="markPaidModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50 px-4 backdrop-blur-sm transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                Confirm Payment
            </h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="markPaidForm" method="POST">
            @csrf
            <input type="hidden" name="amount" id="modalAmountInput">

            <div class="space-y-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Customer</p>
                    <p class="font-semibold text-gray-800" id="modalCustomer"></p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                    <p class="text-sm text-gray-600 mb-1">Description</p>
                    <p class="font-semibold text-gray-800" id="modalReason"></p>
                </div>
                <div class="bg-gradient-to-r from-green-50 to-green-100/50 rounded-xl p-4 border border-green-200">
                    <p class="text-sm text-green-600 mb-1">Amount Due</p>
                    <p class="text-xl font-bold text-green-700">₱<span id="modalAmount"></span></p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                    <select name="method" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition-all duration-300" required>
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference No (optional)</label>
                    <input type="text" name="reference" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition-all duration-300">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (optional)</label>
                    <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:ring-2 focus:ring-green-500/50 focus:border-green-500 transition-all duration-300"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeModal()" 
                        class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 transform hover:-translate-y-0.5 font-medium">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 font-medium">
                    Confirm Payment
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete(billId) {
    if (confirm(`Are you sure you want to delete Bill #${billId}? This cannot be undone.`)) {
        document.getElementById(`deleteForm-${billId}`).submit();
    }
}

function openMarkPaidModal(invoiceId, customer, reason, amount) {
    const form = document.getElementById('markPaidForm');
    form.action = `/billing/${invoiceId}/mark-paid`; 

    document.getElementById('modalCustomer').textContent = customer;
    document.getElementById('modalReason').textContent = reason;
    document.getElementById('modalAmount').textContent = amount;
    document.getElementById('modalAmountInput').value = amount;

    const modal = document.getElementById('markPaidModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger reflow for animation
    modal.offsetHeight;
    
    modal.classList.add('opacity-100');
    modalContent.classList.remove('scale-95', 'opacity-0');
    modalContent.classList.add('scale-100', 'opacity-100');
}

function closeModal() {
    const modal = document.getElementById('markPaidModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('opacity-100');
    }, 300);
}

// Close modal when clicking outside
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target.id === 'markPaidModal') {
        closeModal();
    }
});
</script>

<style>
.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Custom scrollbar for table */
.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
@endsection