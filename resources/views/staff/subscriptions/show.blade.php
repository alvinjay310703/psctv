@extends('layouts.staff')

@section('title', 'Subscription Details')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-xl shadow-sm border p-6 space-y-6">
    <h1 class="text-lg font-semibold text-gray-800 border-b pb-2">Subscription Details</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
        <div>
            <p class="font-semibold text-gray-700">Customer</p>
            <p>{{ $subscription->customer->user->name ?? 'N/A' }}</p>
            <p class="text-gray-500 text-sm">{{ $subscription->customer->user->email ?? '' }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-700">Plan</p>
            <p>{{ $subscription->plan_name ?? '—' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-700">Start Date</p>
            <p>{{ $subscription->start_date ? $subscription->start_date->format('M d, Y') : '—' }}</p>
        </div>
        <div>
            <p class="font-semibold text-gray-700">End Date</p>
            <p>{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : '—' }}</p>
        </div>

        <div>
            <p class="font-semibold text-gray-700">Status</p>
            <p>
                <span class="px-2 py-1 rounded text-xs 
                    {{ $subscription->status === 'active' ? 'bg-green-100 text-green-700' : 
                       ($subscription->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst($subscription->status) }}
                </span>
            </p>
        </div>
    </div>

    <div class="pt-4 border-t">
        <a href="{{ route('staff.subscriptions.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700">
           ← Back to Subscriptions
        </a>
    </div>
</div>
@endsection
