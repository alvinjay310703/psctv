@extends('layouts.staff')

@section('title', 'Customer Subscriptions')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
        <h1 class="text-lg font-semibold text-gray-800">Customer Subscriptions</h1>
        <form method="GET" class="flex items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Search by name or email"
                class="px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 w-64">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3 text-left border">#</th>
                    <th class="p-3 text-left border">Customer</th>
                    <th class="p-3 text-left border">Plan</th>
                    <th class="p-3 text-left border">Status</th>
                    <th class="p-3 text-left border">Start Date</th>
                    <th class="p-3 text-left border">End Date</th>
                    <th class="p-3 text-center border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3">{{ $sub->id }}</td>
                    <td class="p-3">
                        {{ $sub->customer->user->name ?? 'N/A' }}<br>
                        <small class="text-gray-500">{{ $sub->customer->user->email ?? '' }}</small>
                    </td>
                    <td class="p-3">{{ $sub->plan_name ?? '—' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded text-xs 
                            {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : 
                               ($sub->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </td>
                    <td class="p-3">{{ $sub->start_date ? $sub->start_date->format('M d, Y') : '—' }}</td>
                    <td class="p-3">{{ $sub->end_date ? $sub->end_date->format('M d, Y') : '—' }}</td>
                    <td class="p-3 text-center">
                        <a href="{{ route('staff.subscriptions.show', $sub->id) }}" 
                           class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-4 text-center text-gray-500">No subscriptions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection
