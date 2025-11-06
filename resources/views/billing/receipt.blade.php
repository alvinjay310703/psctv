@extends('layouts.admin')

@section('title', 'Payment Receipt')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Main Receipt Card -->
        <div id="receiptContent" class="bg-white rounded-lg shadow-sm border border-gray-200">
            
            <!-- Header Section -->
            <div class="bg-white px-8 py-6 border-b border-gray-200">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center mr-4 border border-blue-100">
                            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="h-8 w-auto">
                        </div>
                        <div>
                            <h1 class="text-2xl font-semibold text-gray-900">Payment Receipt</h1>
                            <p class="text-gray-600 mt-1">Invoice #{{ $bill->invoice_no }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium text-gray-900">Smart Cable Service</p>
                        <p class="text-gray-600 text-sm mt-1">
                            {{ now()->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-6 space-y-6">
                <!-- Customer & Payment Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Customer Information -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Customer Name</p>
                                <p class="font-medium text-gray-900">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Email Address</p>
                                <p class="font-medium text-gray-900">{{ $bill->subscription?->customer?->user->email ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Details</h3>
                        <div class="space-y-3">
                            @if($payment)
                                <div>
                                    <p class="text-sm text-gray-600">Payment Method</p>
                                    <p class="font-medium text-gray-900">{{ $payment->method }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Reference Number</p>
                                    <p class="font-medium text-gray-900">{{ $payment->reference ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Payment Date</p>
                                    <p class="font-medium text-gray-900">{{ $payment->payment_date->format('M d, Y h:i A') }}</p>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 italic">No payment recorded</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Invoice Table -->
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Invoice Summary</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 text-gray-700 text-xs font-medium uppercase tracking-wide">
                                <tr>
                                    <th class="px-6 py-3 text-left">Description</th>
                                    <th class="px-6 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 text-gray-900">{{ $bill->description ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-right font-medium">₱{{ number_format($bill->amount_due, 2) }}</td>
                                </tr>
                                <tr class="bg-green-50">
                                    <td class="px-6 py-4 font-medium text-green-900">Amount Paid</td>
                                    <td class="px-6 py-4 text-right font-semibold text-green-700">₱{{ number_format($bill->amount_paid, 2) }}</td>
                                </tr>
                                <tr class="bg-red-50">
                                    <td class="px-6 py-4 font-medium text-red-900">Balance Remaining</td>
                                    <td class="px-6 py-4 text-right font-semibold text-red-700">₱{{ number_format($bill->balanceRemaining(), 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Success Message -->
                <div class="bg-green-50 rounded-lg p-6 border border-green-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-green-900">Payment Successful</h3>
                            <p class="text-green-700 mt-1">{{ $notes ?? 'Thank you for your payment. Your transaction has been processed successfully.' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200 no-print">
                    <button onclick="printReceipt()"
                            class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                        </svg>
                        Print Receipt
                    </button>
                    <a href="{{ route('billing.receipt.pdf', $bill->id) }}"
                       class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Download PDF
                    </a>
                    <a href="{{ route('billing.index') }}"
                       class="flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Back to Billing
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Professional Print Layout -->
<div id="printReceipt" class="hidden">
    <div class="print-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>Smart Cable Service</h1>
                <p class="tagline">Premium Connectivity Solutions</p>
            </div>
            <div class="receipt-info">
                <h2>PAYMENT RECEIPT</h2>
                <div class="receipt-meta">
                    <p><strong>Receipt #:</strong> {{ $bill->invoice_no }}</p>
                    <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
                    <p><strong>Status:</strong> <span class="status">PAID</span></p>
                </div>
            </div>
        </div>

        <!-- Customer & Payment Info -->
        <div class="info-grid">
            <div class="info-section">
                <h3>BILLED TO</h3>
                <div class="info-content">
                    <p class="customer-name">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? 'N/A' }}</p>
                    <p class="customer-email">{{ $bill->subscription?->customer?->user->email ?? '—' }}</p>
                </div>
            </div>
            
            <div class="info-section">
                <h3>PAYMENT DETAILS</h3>
                <div class="info-content">
                    @if($payment)
                        <p><strong>Method:</strong> {{ $payment->method }}</p>
                        <p><strong>Reference:</strong> {{ $payment->reference ?? 'N/A' }}</p>
                        <p><strong>Paid On:</strong> {{ $payment->payment_date->format('M d, Y \\a\\t h:i A') }}</p>
                    @else
                        <p><em>No payment recorded</em></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="invoice-section">
            <h3>INVOICE SUMMARY</h3>
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $bill->description ?? 'Monthly Service Fee' }}</td>
                        <td class="text-right">₱{{ number_format($bill->amount_due, 2) }}</td>
                    </tr>
                    <tr class="amount-paid">
                        <td><strong>Amount Paid</strong></td>
                        <td class="text-right paid">₱{{ number_format($bill->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="balance">
                        <td><strong>Balance Remaining</strong></td>
                        <td class="text-right balance-due">₱{{ number_format($bill->balanceRemaining(), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Notes & Success -->
        <div class="notes-section">
            <h3>NOTES</h3>
            <p>{{ $notes ?? 'Thank you for your payment. Your transaction has been processed successfully.' }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Smart Cable Service</strong></p>
            <p>This document serves as an official receipt for payment received.</p>
            <p class="footer-note">For billing inquiries: billing@smartcable.com | (555) 123-4567</p>
        </div>
    </div>
</div>

<style>
/* Clean Screen Styles */
#receiptContent {
    transition: all 0.3s ease;
}

#receiptContent:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

/* Professional Print Styles */
@media print {
    body * {
        visibility: hidden;
        margin: 0;
        padding: 0;
    }
    
    #printReceipt, #printReceipt * {
        visibility: visible;
    }
    
    #printReceipt {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        font-size: 12px;
        line-height: 1.4;
        color: #333;
        padding: 20px;
    }

    .no-print {
        display: none !important;
    }

    .print-container {
        max-width: 800px;
        margin: 0 auto;
    }

    /* Header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #2563eb;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .company-info h1 {
        font-size: 24px;
        font-weight: 700;
        color: #2563eb;
        margin: 0 0 5px 0;
    }

    .tagline {
        font-size: 14px;
        color: #666;
        margin: 0;
        font-weight: 500;
    }

    .receipt-info h2 {
        font-size: 28px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 10px 0;
        text-align: right;
    }

    .receipt-meta {
        text-align: right;
    }

    .receipt-meta p {
        margin: 3px 0;
        font-size: 11px;
    }

    .status {
        color: #059669;
        font-weight: 700;
        font-size: 10px;
        text-transform: uppercase;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 25px;
    }

    .info-section h3 {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 5px;
    }

    .customer-name {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
        margin: 0 0 3px 0;
    }

    .customer-email {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
    }

    .info-content p {
        margin: 5px 0;
        font-size: 11px;
    }

    /* Invoice Table */
    .invoice-section h3 {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin: 0 0 10px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 5px;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .invoice-table th {
        background: #f8fafc;
        color: #374151;
        padding: 12px 15px;
        text-align: left;
        font-size: 11px;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .invoice-table .text-right {
        text-align: right;
    }

    .invoice-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 11px;
    }

    .amount-paid {
        background: #f0fdf4;
    }

    .paid {
        color: #059669;
        font-weight: 600;
    }

    .balance {
        background: #fef2f2;
    }

    .balance-due {
        color: #dc2626;
        font-weight: 600;
    }

    /* Notes Section */
    .notes-section {
        margin: 20px 0;
        padding: 15px;
        background: #f8fafc;
        border-radius: 4px;
    }

    .notes-section h3 {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .notes-section p {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        line-height: 1.5;
    }

    /* Footer */
    .footer {
        border-top: 1px solid #e5e7eb;
        padding-top: 15px;
        margin-top: 25px;
        text-align: center;
    }

    .footer p {
        margin: 3px 0;
        font-size: 10px;
        color: #6b7280;
    }

    .footer-note {
        margin-top: 8px !important;
        font-style: italic;
    }

    /* Page Setup */
    @page {
        margin: 1cm;
        size: A4;
    }

    body {
        background: white !important;
    }
}

@media screen {
    #printReceipt {
        display: none;
    }
}
</style>

<script>
function printReceipt() {
    const printContent = document.getElementById('printReceipt').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = printContent;
    window.print();
    document.body.innerHTML = originalContent;
    window.location.reload();
}
</script>
@endsection