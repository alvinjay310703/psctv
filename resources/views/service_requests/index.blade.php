@extends('layouts.admin')

@section('title', 'Service Requests')

@section('content')
<div class="bg-white p-6 rounded-2xl shadow-md relative">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
            📋 Service Requests
        </h2>
        <a href="{{ url('service-requests/create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2 shadow">
            ➕ New Request
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" class="flex flex-wrap items-center gap-3 mb-5 text-sm">
        <div class="relative">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">🔍</span>
            <input type="text" name="search" placeholder="Search by ID or Customer"
                value="{{ request('search') }}"
                class="border rounded-lg pl-9 pr-3 py-2 w-72 focus:ring-2 focus:ring-blue-300 focus:outline-none">
        </div>
        <select name="status" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
            <option value="">Status: All</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
            <option value="assigned" {{ request('status')=='assigned'?'selected':'' }}>Assigned</option>
            <option value="in-progress" {{ request('status')=='in-progress'?'selected':'' }}>In Progress</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Completed</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Filter</button>
    </form>

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
                @forelse ($serviceRequests as $req)
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-3 font-medium">REQ-{{ $req->id }}</td>
                    <td class="p-3">{{ $req->customer_name }}</td>
                    <td class="p-3">{{ $req->service_type }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($req->status=='pending') bg-yellow-100 text-yellow-700
                            @elseif($req->status=='assigned') bg-purple-100 text-purple-700
                            @elseif($req->status=='in-progress') bg-blue-100 text-blue-700
                            @elseif($req->status=='completed') bg-green-100 text-green-700
                            @endif">
                            {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td class="p-3">{{ $req->technician->name ?? 'Unassigned' }}</td>

                  <td class="p-3 text-center">
    <div class="flex justify-center gap-2">

        <!-- Always available -->
        <a href="{{ route('service_requests.show', $req->id) }}" 
   class="px-3 py-1 flex items-center gap-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
   <x-lucide-eye class="w-3 h-3"/> View
</a>


        @if($req->status === 'pending')
            <button type="button"
                onclick="openAssignModal({{ $req->id }}, '{{ $req->customer_name }}', '{{ $req->service_type }}', '{{ $req->status }}')"
                class="px-3 py-1 text-xs rounded-lg font-medium 
                       bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100">
                Assign
            </button>
            <form action="{{ route('service_requests.destroy', $req->id) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="px-3 py-1 text-xs rounded-lg font-medium 
                               bg-red-50 text-red-700 border border-red-200 hover:bg-red-100"
                        onclick="return confirm('Delete this pending request?')">
                    Delete
                </button>
            </form>
        @elseif($req->status === 'assigned')
            <button type="button"
                onclick="openAssignModal({{ $req->id }}, '{{ $req->customer_name }}', '{{ $req->service_type }}', '{{ $req->status }}')"
                class="px-3 py-1 text-xs rounded-lg font-medium 
                       bg-purple-50 text-purple-700 border border-purple-200 hover:bg-purple-100">
                Reassign
            </button>
        @elseif($req->status === 'in-progress')
            <form action="{{ route('service_requests.updateStatus', $req->id) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="status" value="completed">
                <button type="submit" 
                        class="px-3 py-1 text-xs rounded-lg font-medium 
                               bg-green-50 text-green-700 border border-green-200 hover:bg-green-100"
                        onclick="return confirm('Mark this request as completed?')">
                    Complete
                </button>
            </form>
        @elseif($req->status === 'completed')
            <form action="{{ route('service_requests.destroy', $req->id) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="px-3 py-1 text-xs rounded-lg font-medium 
                               bg-red-50 text-red-700 border border-red-200 hover:bg-red-100"
                        onclick="return confirm('Delete this completed request?')">
                    Delete
                </button>
            </form>
        @endif
    </div>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">No requests found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $serviceRequests->links() }}
    </div>
</div>
<!-- Assign Modal -->
<div id="assignModal" class="hidden fixed inset-0 bg-black/30 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-[28rem] p-6 relative">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Assign Technician</h3>
        <form method="POST" id="assignForm" class="space-y-4">
            @csrf
            <div class="text-sm text-gray-600 space-y-1">
                <p><b>Request ID:</b> <span id="modalRequestId"></span></p>
                <p><b>Customer:</b> <span id="modalCustomer"></span></p>
                <p><b>Service:</b> <span id="modalService"></span></p>
                <p><b>Status:</b> <span id="modalStatus"></span></p>
            </div>

            <div>
                <label class="text-sm text-gray-600">Technician</label>
                <select name="technician_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-purple-500">
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}">{{ $tech->name }} (Active Jobs: {{ $tech->active_jobs }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-sm text-gray-600">Notes (optional)</label>
                <textarea name="notes" rows="2" placeholder="Add assignment notes..."
                          class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500"></textarea>
            </div>

            <!-- Success Message -->
            <div id="assignSuccess" class="hidden bg-green-50 border border-green-200 rounded p-3 text-sm text-green-700">
                ✅ Technician successfully assigned!
            </div>

            <div class="flex justify-between items-center">
                <!-- Full assign page link -->
                <a id="fullAssignLink" href="#" class="text-sm text-purple-600 hover:underline">
                    Go to full assign page →
                </a>

                <div class="flex gap-2">
                    <button type="button" onclick="closeAssignModal()" 
                            class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Cancel</button>
                    <button type="button" onclick="finishAssign()" 
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">Assign</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

function openAssignModal(id, customer, service, status) {
    const form = document.getElementById('assignForm');
    form.action = `/service_requests/${id}/assign`;

    // Fill in modal data
    document.getElementById('modalRequestId').innerText = "REQ-" + id;
    document.getElementById('modalCustomer').innerText = customer;
    document.getElementById('modalService').innerText = service;
    document.getElementById('modalStatus').innerText = status;

    // Use the correct assign route
    document.getElementById('fullAssignLink').href = `/service_requests/${id}/assign`;

    document.getElementById('assignModal').classList.remove('hidden');
}




function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.getElementById('assignSuccess').classList.add('hidden');
}

function finishAssign() {
    // simulate a success feedback
    document.getElementById('assignSuccess').classList.remove('hidden');

    // auto-close after delay
    setTimeout(() => {
        closeAssignModal();
        window.location.reload(); // refresh to show updated technician
    }, 1500);
}
</script>
    
@endsection