



@extends('layouts.admin')

@section('title', 'Billing Records')

@section('content')
<div class="space-y-6">

    <!-- Summary Cards Box -->
    <div class="bg-white p-6 rounded-2xl shadow space-y-4">
        <h2 class="text-lg font-semibold text-gray-800">Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
            <div class="bg-green-50 p-4 rounded-md border text-green-700">
                <p class="text-xs font-medium tracking-wide">Total Collected</p>
                <p class="text-lg font-bold mt-1">₱45,000</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-md border text-yellow-700">
                <p class="text-xs font-medium tracking-wide">Pending Bills</p>
                <p class="text-lg font-bold mt-1">₱8,500</p>
            </div>
            <div class="bg-red-50 p-4 rounded-md border text-red-700">
                <p class="text-xs font-medium tracking-wide">Overdue</p>
                <p class="text-lg font-bold mt-1">3</p>
            </div>
        </div>
    </div>

    <!-- Billing Table Box -->
    <div class="bg-white p-6 rounded-2xl shadow space-y-4">

        <!-- Table Header + Actions -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Billing Records</h2>
            <div class="flex space-x-2">
                <a href="{{ route('billing.create') }}" 
                   class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition text-sm">
                    + New Bill
                </a>
                <button onclick="window.print()" 
                   class="bg-gray-100 px-4 py-2 rounded-md hover:bg-gray-200 transition text-sm">
                    🖨 Print All
                </button>
            </div>
        </div>
<!-- Filters -->
<div class="flex flex-wrap items-center gap-3 text-sm mb-3">
    <div class="relative">
        <span class="absolute inset-y-0 left-2 flex items-center text-gray-400">🔍</span>
        <input type="text" name="search" placeholder="Search customer or bill #" 
               class="border rounded-lg pl-9 pr-3 py-2 w-64 focus:ring-2 focus:ring-purple-300 text-sm">
    </div>

    <select name="status" class="border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-300">
        <option value="">Status: All</option>
        <option value="Paid">Paid</option>
        <option value="Unpaid">Unpaid</option>
        <option value="Overdue">Overdue</option>
    </select>

    <div class="flex items-center gap-2">
        <input type="date" name="from" class="border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500">
        <span class="text-gray-400">–</span>
        <input type="date" name="to" class="border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500">
        <button type="reset" class="bg-gray-100 px-3 py-2 rounded hover:bg-gray-200 text-sm">Reset</button>
        <button type="submit" class="bg-purple-600 text-white px-3 py-2 rounded hover:bg-purple-700 text-sm">Apply</button>
    </div>
</div>


        <!-- Table -->
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="p-3 text-left">Bill #</th>
                        <th class="p-3 text-left">Customer</th>
                        <th class="p-3 text-left">Description</th>
                        <th class="p-3 text-left">Amount</th>
                        <th class="p-3 text-left">Due Date</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    @foreach($bills as $bill)
    <tr class="hover:bg-gray-50 transition">
        <td class="p-3 md:p-4 font-medium text-gray-800">{{ $bill['id'] }}</td>
        <td class="p-3 md:p-4">{{ $bill['customer'] }}</td>
        <td class="p-3 md:p-4 text-gray-600">{{ $bill['reason'] ?? 'N/A' }}</td>
        <td class="p-3 md:p-4 text-gray-600">₱{{ number_format($bill['amount'],2) }}</td>
        <td class="p-3 md:p-4 text-gray-600">{{ $bill['due'] ?? 'N/A' }}</td>
        <td class="p-3 md:p-4">
            @php
                $color = $bill['status'] === 'Paid' ? 'green' : ($bill['status'] === 'Overdue' ? 'red' : 'yellow');
            @endphp
            <span class="bg-{{ $color }}-100 text-{{ $color }}-700 px-2 py-1 rounded-full text-xs font-semibold">{{ $bill['status'] }}</span>
        </td>
        <td class="p-3 md:p-4 text-center">
            <div class="flex justify-center items-center space-x-3">
               @if($bill['status'] !== 'Paid')
    <button 
        onclick="openModal('{{ $bill['id'] }}','{{ $bill['customer'] }}','{{ $bill['reason'] ?? 'N/A' }}','₱{{ $bill['amount'] }}')" 
        class="flex items-center gap-1 text-green-700 bg-green-100 px-3 py-1.5 rounded hover:bg-green-200 transition text-sm font-medium"
        title="Mark this bill as Paid">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        Mark as Paid
    </button>
@endif

                @if($bill['status'] === 'Paid')
                    <a href="{{ route('billing.receipt', ['id' => $bill['id']]) }}" class="flex items-center text-blue-600 hover:text-blue-800 transition text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View Receipt
                    </a>
                @else
                    <a href="{{ url('billing/' . $bill['id']) }}" class="flex items-center text-blue-600 hover:text-blue-800 transition text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </a>
                @endif

                <span class="border-l h-4 border-gray-300"></span>

                <button class="flex items-center text-red-600 hover:text-red-800 transition text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h-2m-8 0H5" />
                    </svg>
                </button>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>

            </table>
        </div>

        <!-- Table Footer -->
        <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
            <p>Showing 1–{{ count($bills) }} of {{ count($bills) }} results</p>
        </div>
    </div>

   <div id="markPaidModal" 
         class="hidden absolute top-1/4 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-xl w-96 p-6 z-20 transition-all scale-95 opacity-0">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Confirm Payment</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        <div class="space-y-2 text-sm text-gray-700">
            <p><b>Customer:</b> <span id="modalCustomer"></span></p>
            <p><b>Bill #:</b> <span id="modalBill"></span></p>
            <p><b>Description:</b> <span id="modalReason"></span></p>
            <p><b>Amount:</b> <span id="modalAmount" class="font-semibold text-gray-800"></span></p>
            <div class="mt-3">
                <label class="block text-xs text-gray-500 mb-1">Notes (optional)</label>
                <textarea id="modalNotes" rows="2" 
                          class="w-full border rounded px-2 py-1 text-sm focus:ring-1 focus:ring-purple-500 focus:outline-none"
                          placeholder="e.g. Paid via GCash, Partial Payment..."></textarea>
            </div>
        </div>
       <!-- Modal Buttons -->
<div class="flex justify-end gap-2 mt-5">
    <button onclick="closeModal()" class="bg-gray-100 px-4 py-2 rounded hover:bg-gray-200 text-sm">Cancel</button>
    <button onclick="confirmPayment()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-sm">Confirm</button>
</div>

    </div>
</div>

<script>
  function openModal(billId, customer, reason, amount) {
    document.getElementById('modalBill').innerText = billId;
    document.getElementById('modalCustomer').innerText = customer;
    document.getElementById('modalReason').innerText = reason;
    document.getElementById('modalAmount').innerText = amount;

    const modal = document.getElementById('markPaidModal');
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.remove('scale-95', 'opacity-0');
        modal.classList.add('scale-100', 'opacity-100');
    }, 50);
}

function closeModal() {
    const modal = document.getElementById('markPaidModal');
    modal.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 200);
}

function confirmPayment() {
    const billId = document.getElementById('modalBill').innerText;
    const notes = document.getElementById('modalNotes').value;

    // Close the modal
    closeModal();

    // Show confirmation prompt with choice
    const viewReceipt = confirm("Payment confirmed! \n\nDo you want to view the receipt?");
    let url = `/billing/${billId}/receipt`;
    if (notes) url += `?notes=${encodeURIComponent(notes)}`;

    if (viewReceipt) {
        // Redirect to receipt
        window.location.href = url;
    } else {
        // Stay on page, maybe refresh table to show updated status
        // You could call an AJAX function here to update the row without full refresh
        alert("Payment recorded. You can view the receipt later.");
    }
}

</script>
@endsection
