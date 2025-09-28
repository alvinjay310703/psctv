@extends('layouts.admin')

@section('title', 'View Bill')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-3">
        <h1 class="text-xl font-semibold">Bill #1001</h1>
        <button onclick="window.print()" 
                class="bg-gray-100 px-4 py-2 rounded-md hover:bg-gray-200 text-sm">
            🖨 Print
        </button>
    </div>

    <!-- Customer Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <p class="text-gray-500">Customer</p>
            <p class="font-medium">Maria Santos</p>
        </div>
        <div>
            <p class="text-gray-500">Due Date</p>
            <p class="font-medium">2025-09-30</p>
        </div>
        <div>
            <p class="text-gray-500">Reason</p>
            <p class="font-medium">Monthly Subscription</p>
        </div>
        <div>
            <p class="text-gray-500">Status</p>
            <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">
                Unpaid
            </span>
        </div>
    </div>

    <!-- Amount -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-gray-500">Amount</p>
        <p class="text-2xl font-bold text-gray-800">₱500.00</p>
    </div>

    <!-- Notes -->
    <div>
        <p class="text-gray-500 text-sm">Notes</p>
        <p class="mt-1 text-gray-700">This is a test bill for front-end demo only.</p>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-2">
        <a href="#" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Edit</a>
        <a href="{{ url('billing') }}" 
           class="bg-gray-100 px-4 py-2 rounded hover:bg-gray-200 text-sm">Back</a>
    </div>
</div>
@endsection
