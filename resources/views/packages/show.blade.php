@extends('layouts.admin')

@section('title', 'Package Details')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">
    <h2 class="text-2xl font-bold text-gray-800">📦 Package Details</h2>

    <div class="space-y-3 text-gray-700">
        <p><strong>Code:</strong> {{ $package->code }}</p>
        <p><strong>Name:</strong> {{ $package->name }}</p>
        <p><strong>Description:</strong> {{ $package->description ?? '—' }}</p>
        <p><strong>Speed:</strong> {{ $package->speed_mbps ? $package->speed_mbps . ' Mbps' : '—' }}</p>
        <p><strong>Channels:</strong> {{ $package->channels ?? '—' }}</p>
        <p><strong>Price:</strong> ₱{{ number_format($package->price, 2) }}</p>
        <p><strong>Billing Cycle:</strong> {{ ucfirst($package->billing_cycle) }}</p>
        <p><strong>Status:</strong> 
            @if($package->is_active)
                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Active</span>
            @else
                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Inactive</span>
            @endif
        </p>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('packages.index') }}" 
           class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">← Back</a>
        <a href="{{ route('packages.edit', $package) }}" 
           class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">✏️ Edit</a>
    </div>
</div>
@endsection
