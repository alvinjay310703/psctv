@extends('layouts.admin')

@section('title', 'Receipt')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-8 rounded-2xl shadow-lg space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-6">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="h-14">
            <div>
                <h2 class="text-2xl font-bold">PSCTVS.</h2>
                <p class="text-gray-500 text-sm">Official Payment Receipt</p>
            </div>
        </div>
        <div class="text-right">
            <h1 class="text-3xl font-extrabold">RECEIPT</h1>
            <p class="text-gray-500 mt-1">Receipt #{{ $bill['id'] }}</p>
        </div>
    </div>

    <!-- Payment Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
        <div>
            <p class="text-gray-500 font-semibold">Paid By:</p>
            <p class="font-medium text-lg">{{ $bill['customer'] }}</p>

            <p class="text-gray-500 font-semibold mt-3">Payment Method:</p>
            <p class="font-medium">{{ $bill['payment_method'] ?? 'Cash' }}</p>
        </div>
        <div>
            <p class="text-gray-500 font-semibold">Payment Date:</p>
            <p class="font-medium">{{ date('Y-m-d H:i') }}</p>

            <p class="text-gray-500 font-semibold mt-3">Invoice #:</p>
            <p class="font-medium">{{ $bill['id'] }}</p>

            <p class="text-gray-500 font-semibold mt-3">Status:</p>
            <span class="px-3 py-1 text-xs rounded-full font-semibold 
                bg-green-100 text-green-700">PAID</span>
        </div>
    </div>

    <!-- Amount Table -->
    <div class="overflow-x-auto mt-4">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="p-3 text-left">Description</th>
                    <th class="p-3 text-right">Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b">
                    <td class="p-3">{{ $bill['reason'] ?? 'Monthly Subscription' }}</td>
                    <td class="p-3 text-right">₱{{ number_format($bill['amount'], 2) }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td class="p-3 font-semibold text-right">Total:</td>
                    <td class="p-3 text-right text-2xl font-bold">₱{{ number_format($bill['amount'], 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Notes -->
    <div class="text-sm mt-4">
        <p class="text-gray-500 font-semibold">Notes:</p>
        <p class="mt-1 text-gray-700">{{ $bill['notes'] ?? 'Thank you for your payment.' }}</p>
    </div>

    <!-- Actions -->
    <div class="flex justify-end gap-3 mt-6">
        <button onclick="window.print()" 
                class="bg-gray-100 px-5 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium">🖨 Print</button>
        <a href="{{ url('billing') }}" 
           class="bg-gray-100 px-5 py-2 rounded-lg hover:bg-gray-200 text-sm font-medium">Back</a>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #app, #app * { visibility: visible; }
        #app { position: absolute; top: 0; left: 0; width: 100%; }
    }
</style>
@endsection
