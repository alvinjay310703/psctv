@extends('layouts.admin')

@section('title', 'Create Bill')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-2xl shadow space-y-6">

    <!-- Header -->
    <h1 class="text-xl font-semibold border-b pb-3">Create New Bill</h1>

    <!-- Form -->
    <form method="POST" action="{{ route('billing.store') }}" class="space-y-4">
        @csrf

        <!-- Customer -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Customer Name</label>
            <input type="text" name="customer_name" required
                   class="w-full border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500">
        </div>

        <!-- Reason -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Reason</label>
            <input type="text" name="reason" required
                   placeholder="e.g. Monthly Subscription, Installation Fee"
                   class="w-full border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500">
        </div>

        <!-- Amount -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Amount (₱)</label>
            <input type="number" step="0.01" name="amount" required
                   class="w-full border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500">
        </div>

        <!-- Due Date -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Due Date</label>
            <input type="date" name="due_date" required
                   class="w-full border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500">
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium text-gray-600 mb-1">Notes (optional)</label>
            <textarea name="notes" rows="3"
                      class="w-full border px-3 py-2 rounded focus:ring-1 focus:ring-purple-500"
                      placeholder="Additional details (e.g. payment method, remarks)"></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-2 pt-3">
            <a href="{{ route('billing.index') }}" 
               class="bg-gray-100 px-4 py-2 rounded hover:bg-gray-200 text-sm">Cancel</a>
            <button type="submit" 
                    class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 text-sm">Save</button>
        </div>
    </form>
</div>
@endsection
