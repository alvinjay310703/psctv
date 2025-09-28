@extends('layouts.admin')

@section('title', 'New Subscription')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-md space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">➕ New Subscription</h2>
        <a href="{{ route('subscriptions.index') }}" 
           class="text-sm px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
            ← Back
        </a>
    </div>

    <!-- Form -->
    <form action="{{ route('subscriptions.store') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Customer Name -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Customer Name</label>
            <input type="text" name="customer" value="{{ old('customer') }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Email -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Phone -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" 
                   class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Address -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Address</label>
            <textarea name="address" rows="2" 
                      class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('address') }}</textarea>
        </div>

        <!-- Plan -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Plan</label>
            <select name="plan" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="">-- Select Plan --</option>
                <option value="Basic Cable Plan" {{ old('plan') == 'Basic Cable Plan' ? 'selected' : '' }}>Basic Cable Plan</option>
                <option value="Premium Cable + Internet" {{ old('plan') == 'Premium Cable + Internet' ? 'selected' : '' }}>Premium Cable + Internet</option>
            </select>
        </div>

        <!-- Cycle -->
        <div>
            <label class="block text-sm text-gray-600 mb-1">Billing Cycle</label>
            <select name="cycle" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option value="monthly" {{ old('cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ old('cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
        </div>

        <!-- Buttons -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('subscriptions.index') }}" 
               class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
            <button type="submit" 
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow">
                ✅ Save Subscription
            </button>
        </div>
    </form>
</div>
@endsection
