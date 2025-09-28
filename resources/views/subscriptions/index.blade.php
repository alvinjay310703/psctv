@extends('layouts.admin')

@section('title', 'Subscriptions')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-md">
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Customer Subscriptions</h2>
        <a href="{{ route('subscriptions.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow text-sm">
            ➕ New Subscription
        </a>
    </div>

    <!-- Search + Filter -->
    <form method="GET" action="{{ route('subscriptions.index') }}" class="flex flex-wrap items-center gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by customer, email, or plan..."
               class="flex-1 border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
        
        <select name="status" class="border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
            <option value="">All Status</option>
            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
            <option value="expired" {{ request('status')=='expired' ? 'selected' : '' }}>Expired</option>
            <option value="cancelled" {{ request('status')=='cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button type="submit" 
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
            🔎 Search
        </button>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-xs uppercase tracking-wider">
                    <th class="p-3">Customer</th>
                    <th class="p-3">Plan</th>
                    <th class="p-3">Cycle</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Start Date</th>
                    <th class="p-3">End Date</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($subscriptions as $sub)
                <tr class="hover:bg-gray-50">
                    <td class="p-3">
                        <p class="font-medium text-gray-800">{{ $sub->customer }}</p>
                        <p class="text-xs text-gray-500">{{ $sub->email }}</p>
                    </td>
                    <td class="p-3">{{ $sub->plan }}</td>
                    <td class="p-3 capitalize">{{ $sub->cycle }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($sub->status === 'active') bg-green-100 text-green-700
                            @elseif($sub->status === 'expired') bg-red-100 text-red-600
                            @elseif($sub->status === 'cancelled') bg-gray-200 text-gray-600
                            @endif">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </td>
                    <td class="p-3">{{ $sub->start_date->format('M d, Y') }}</td>
                    <td class="p-3">{{ $sub->end_date->format('M d, Y') }}</td>
                    <td class="p-3 text-right flex justify-end gap-2">
                        <a href="{{ route('subscriptions.show', $sub->id) }}" 
                           class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100">
                            View
                        </a>

                        @if($sub->status === 'active')
                            <a href="{{ route('subscriptions.edit', $sub->id) }}" 
                               class="px-3 py-1 text-xs bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100">
                                Edit
                            </a>
                           <form id="cancel-form-{{ $sub->id }}" action="{{ route('subscriptions.destroy', $sub->id) }}" method="POST" class="inline">
    @csrf @method('DELETE')
    <button type="button"
            class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100"
            onclick="confirmCancel({{ $sub->id }}, '{{ $sub->customer }}')">
        Cancel
    </button>
</form>

                        @elseif($sub->status === 'expired')
                            <form action="{{ route('subscriptions.renew', $sub->id) }}" method="POST"
                                  onsubmit="return confirm('Renew this subscription for {{ $sub->customer }}?')">
                                @csrf
                                <button type="submit" 
                                        class="px-3 py-1 text-xs bg-green-50 text-green-600 rounded hover:bg-green-100">
                                    Renew
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-6 text-center text-gray-500">No subscriptions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $subscriptions->links() }}
    </div>
</div>
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
        cancelButtonText: 'No, keep it'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`cancel-form-${id}`).submit();
        }
    });
}
</script>

@endsection


