@extends('layouts.admin')

@section('title', 'Edit Subscription')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-4">✏ Edit Subscription</h2>

    <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Customer Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                <input type="text" name="customer" value="{{ $subscription->customer }}" class="w-full mt-1 p-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ $subscription->email }}" class="w-full mt-1 p-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Phone</label>
                <input type="text" name="phone" value="{{ $subscription->phone }}" class="w-full mt-1 p-2 border rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address" value="{{ $subscription->address }}" class="w-full mt-1 p-2 border rounded-lg">
            </div>
        </div>

        <!-- Plan Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Plan</label>
                <select name="plan" class="w-full mt-1 p-2 border rounded-lg">
                    <option value="basic" {{ $subscription->plan == 'basic' ? 'selected' : '' }}>Basic (₱499 / mo)</option>
                    <option value="standard" {{ $subscription->plan == 'standard' ? 'selected' : '' }}>Standard (₱799 / mo)</option>
                    <option value="premium" {{ $subscription->plan == 'premium' ? 'selected' : '' }}>Premium (₱1299 / mo)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Billing Cycle</label>
                <select name="cycle" class="w-full mt-1 p-2 border rounded-lg">
                    <option value="monthly" {{ $subscription->cycle == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="yearly" {{ $subscription->cycle == 'yearly' ? 'selected' : '' }}>Yearly</option>
                </select>
            </div>
        </div>

        <!-- Status -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" class="w-full mt-1 p-2 border rounded-lg">
                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="expired" {{ $subscription->status == 'expired' ? 'selected' : '' }}>Expired</option>
                <option value="pending" {{ $subscription->status == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('subscriptions.index') }}" class="px-5 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                💾 Update Subscription
            </button>
        </div>
    </form>
</div>
@endsection
