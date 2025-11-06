<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Payment Receipt - {{ $bill->invoice_no }}</title>
    <style>
        @page { margin: 0.5in; }
        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #1a1a1a;
            line-height: 1.4;
        }
        .receipt-container {
            max-width: 8.5in;
            margin: 0 auto;
            background: white;
            position: relative;
            border: 1px solid #e0e0e0;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120px;
            color: rgba(0, 0, 0, 0.03);
            z-index: 0;
            pointer-events: none;
            font-weight: bold;
        }

        /* Letterhead Header */
        .letterhead {
            border-bottom: 3px solid #1e40af;
            padding: 30px 40px;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            position: relative;
            z-index: 1;
        }

        .company-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-section {
            flex: 1;
        }

        .logo {
            max-width: 200px;
            height: auto;
        }

        .company-details {
            flex: 2;
            text-align: center;
            padding: 0 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1e40af;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .company-tagline {
            font-size: 14px;
            color: #64748b;
            margin: 5px 0;
            font-style: italic;
        }

        .company-info {
            font-size: 12px;
            color: #475569;
            line-height: 1.5;
        }

        .contact-section {
            flex: 1;
            text-align: right;
            font-size: 11px;
            color: #64748b;
        }

        /* Receipt Title */
        .receipt-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin: 30px 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            border: 2px solid #1e40af;
            padding: 15px;
            background: #f8fafc;
            position: relative;
            z-index: 1;
        }

        /* Main Content */
        .content {
            padding: 0 40px;
            position: relative;
            z-index: 1;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .info-box {
            border: 2px solid #e2e8f0;
            padding: 25px;
            background: #fafbfc;
        }

        .info-box h3 {
            font-size: 16px;
            color: #1e40af;
            margin: 0 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #1e40af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-box p {
            margin: 8px 0;
            font-size: 13px;
            color: #374151;
        }

        .info-box strong {
            color: #1f2937;
            font-weight: 600;
        }

        /* Amount Section */
        .amount-section {
            text-align: center;
            margin: 40px 0;
            padding: 30px;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            color: white;
            border-radius: 8px;
            position: relative;
            z-index: 1;
        }

        .amount-label {
            font-size: 16px;
            margin-bottom: 10px;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .amount-value {
            font-size: 36px;
            font-weight: bold;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* Payment Details Table */
        .payment-table {
            margin: 40px 0;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }

        .payment-table h3 {
            background: #1e40af;
            color: white;
            margin: 0;
            padding: 15px 25px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .payment-table table {
            width: 100%;
            border-collapse: collapse;
        }

        .payment-table th {
            background: #f1f5f9;
            padding: 15px 25px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        .payment-table td {
            padding: 15px 25px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 13px;
            color: #475569;
        }

        .payment-table tr:last-child td {
            border-bottom: none;
        }

        /* Notes Section */
        .notes-section {
            margin: 30px 0;
            padding: 20px;
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            position: relative;
            z-index: 1;
        }

        .notes-section strong {
            color: #92400e;
            font-size: 14px;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding: 30px 40px;
            background: #f8fafc;
            border-top: 3px solid #1e40af;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .footer p {
            margin: 8px 0;
            font-size: 12px;
            color: #64748b;
        }

        .footer strong {
            color: #374151;
        }

        .security-notice {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            padding: 15px;
            margin: 20px 0;
            border-radius: 6px;
            font-size: 11px;
            color: #991b1b;
        }

        /* Print Styles */
        @media print {
            body { background: white; }
            .receipt-container { border: none; box-shadow: none; }
            .watermark { color: rgba(0, 0, 0, 0.02); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .company-header { flex-direction: column; text-align: center; }
            .contact-section { text-align: center; margin-top: 20px; }
            .info-grid { grid-template-columns: 1fr; gap: 20px; }
            .receipt-title { font-size: 20px; }
            .amount-value { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">PAID</div>

        <!-- Letterhead Header -->
        <div class="letterhead">
            <div class="company-header">
                <div class="logo-section">
                    <img src="{{ asset('images/logo.png') }}" alt="PSCTV Logo" class="logo">
                </div>

                <div class="company-details">
                    <h1 class="company-name">PSCTV System</h1>
                    <p class="company-tagline">Premium Cable Television Services</p>
                    <div class="company-info">
                        <p><strong>VAT Registered:</strong> 123-456-789-000</p>
                        <p><strong>TIN:</strong> 001-234-567-890</p>
                        <p><strong>SEC Registration:</strong> CS20250012345</p>
                    </div>
                </div>

                <div class="contact-section">
                    <p><strong>Corporate Office</strong></p>
                    <p>123 Technology Avenue</p>
                    <p>Makati City, Metro Manila 1200</p>
                    <p>Philippines</p>
                    <p>📞 +63 (2) 123-4567</p>
                    <p>📧 info@psctv.com.ph</p>
                    <p>🌐 www.psctv.com.ph</p>
                </div>
            </div>
        </div>

        <!-- Receipt Title -->
        <div class="receipt-title">
            Official Payment Receipt
        </div>

        <!-- Main Content -->
        <div class="content">
            <div class="info-grid">
                <div class="info-box">
                    <h3>Bill Information</h3>
                    <p><strong>Invoice Number:</strong> {{ $bill->invoice_no }}</p>
                    <p><strong>Billing Date:</strong> {{ $bill->created_at->format('F d, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $bill->due_date ? $bill->due_date->format('F d, Y') : 'N/A' }}</p>
                    <p><strong>Billing Period:</strong> {{ $bill->created_at->format('M Y') }}</p>
                </div>

                <div class="info-box">
                    <h3>Customer Details</h3>
                    <p><strong>Customer Name:</strong> {{ $bill->subscription->customer->user->name ?? 'N/A' }}</p>
                    <p><strong>Account Number:</strong> {{ $bill->subscription->customer->account_number ?? 'N/A' }}</p>
                    <p><strong>Email Address:</strong> {{ $bill->subscription->customer->user->email ?? 'N/A' }}</p>
                    <p><strong>Service Package:</strong> {{ $bill->subscription->package->name ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Amount Paid -->
            <div class="amount-section">
                <p class="amount-label">Amount Paid</p>
                <p class="amount-value">₱{{ number_format($payment->amount_paid, 2) }}</p>
            </div>

            <!-- Payment Details -->
            <div class="payment-table">
                <h3>Payment Transaction Details</h3>
                <table>
                    <tr>
                        <th>Payment Date & Time</th>
                        <td>{{ $payment->payment_date->format('F d, Y \a\t h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ $payment->method ?? 'Cash Payment' }}</td>
                    </tr>
                    <tr>
                        <th>Reference Number</th>
                        <td>{{ $payment->reference ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Transaction Status</th>
                        <td>{{ ucfirst($payment->status) }}</td>
                    </tr>
                    <tr>
                        <th>Processed By</th>
                        <td>{{ $payment->processed_by ?? 'System' }}</td>
                    </tr>
                </table>
            </div>

            @if($notes)
            <div class="notes-section">
                <strong>Additional Notes:</strong><br>
                {{ $notes }}
            </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="security-notice">
                <strong>Security Notice:</strong> This is an official computer-generated receipt. Keep this document for your records.
                All payments are subject to verification and final reconciliation.
            </div>

            <p><strong>Thank you for choosing PSCTV System - Your Trusted Cable Television Partner</strong></p>
            <p>This receipt serves as proof of payment and is valid for warranty claims and service disputes.</p>
            <p>For billing inquiries, please contact our Customer Service Center at +63 (2) 123-4567 or visit www.psctv.com.ph</p>
            <p><em>Generated on {{ now()->format('F d, Y \a\t h:i A') }} - Receipt ID: {{ $bill->invoice_no }}-{{ $payment->id }}</em></p>
        </div>
    </div>
</body>
</html>
