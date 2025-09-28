@extends('layouts.admin')

@section('title', 'Subscription Details')

@section('content')
<div class="max-w-5xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-8">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Subscription Details</h2>
            <p class="text-sm text-gray-500">#SUB-{{ $subscription->id }}</p>
        </div>
        <a href="{{ route('subscriptions.index') }}" 
           class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            ← Back to List
        </a>
    </div>

    <!-- Customer Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gray-50 p-5 rounded-lg border space-y-2">
            <h3 class="font-semibold text-gray-700 text-lg">👤 Customer Information</h3>
            <p><span class="font-medium">Name:</span> {{ $subscription->customer }}</p>
            <p><span class="font-medium">Email:</span> {{ $subscription->email }}</p>
            <p><span class="font-medium">Phone:</span> {{ $subscription->phone }}</p>
            <p><span class="font-medium">Address:</span> {{ $subscription->address }}</p>
        </div>

        <!-- Subscription Info -->
        <div class="bg-gray-50 p-5 rounded-lg border space-y-2">
            <h3 class="font-semibold text-gray-700 text-lg">📦 Subscription Info</h3>
            <p><span class="font-medium">Plan:</span> {{ $subscription->plan }}</p>
            <p><span class="font-medium">Cycle:</span> {{ ucfirst($subscription->cycle) }}</p>
            <p><span class="font-medium">Start:</span> {{ $subscription->start_date->format('M d, Y') }}</p>
            <p><span class="font-medium">End:</span> {{ $subscription->end_date->format('M d, Y') }}</p>
            <p>
                <span class="font-medium">Status:</span>
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    @if($subscription->status === 'active') bg-green-100 text-green-700
                    @elseif($subscription->status === 'expired') bg-red-100 text-red-600
                    @elseif($subscription->status === 'cancelled') bg-gray-200 text-gray-600
                    @endif">
                    {{ ucfirst($subscription->status) }}
                </span>
            </p>
        </div>
    </div>

    <!-- Invoice History -->
    <div class="bg-gray-50 p-5 rounded-lg border">
        <h3 class="font-semibold text-gray-700 text-lg mb-4">🧾 Billing History</h3>
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-xs uppercase">
                    <th class="p-2">Invoice #</th>
                    <th class="p-2">Amount</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($subscription->invoices as $inv)
                <tr>
                    <td class="p-2">INV-{{ $inv->id }}</td>
                    <td class="p-2">₱{{ number_format($inv->amount, 2) }}</td>
                    <td class="p-2">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($inv->status === 'paid') bg-green-100 text-green-700
                            @else bg-red-100 text-red-600 @endif">
                            {{ ucfirst($inv->status) }}
                        </span>
                    </td>
                    <td class="p-2">{{ $inv->created_at->format('M d, Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 pt-4 border-t">
        @if($subscription->status === 'active')
            <a href="{{ route('subscriptions.edit', $subscription->id) }}" 
               class="bg-yellow-500 text-white px-5 py-2 rounded-lg hover:bg-yellow-600 text-sm shadow">
                ✏ Edit
            </a>
            <form action="{{ route('subscriptions.destroy', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to cancel this subscription?')">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 text-sm shadow">
                    ❌ Cancel
                </button>
            </form>
        @elseif($subscription->status === 'expired')
            <form action="{{ route('subscriptions.renew', $subscription->id) }}" method="POST"
                  onsubmit="return confirm('Renew this subscription now?')">
                @csrf
                <button type="submit" 
                        class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
                    🔄 Renew Subscription
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
