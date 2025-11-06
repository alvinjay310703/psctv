@extends('layouts.admin')

@section('title', 'View Bill')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50/50 to-indigo-100/50 py-8 font-sans">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Main Invoice Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/60 transform transition-all duration-500 hover:shadow-3xl">
            
            <!-- Header Section with Gradient -->
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 px-8 py-8 text-white relative overflow-hidden">
                <!-- Animated background elements -->
                <div class="absolute top-0 left-0 w-full h-full opacity-10">
                    <div class="absolute top-10 right-10 w-20 h-20 bg-white rounded-full animate-pulse-slow"></div>
                    <div class="absolute bottom-5 left-5 w-16 h-16 bg-white rounded-full animate-pulse-slow" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mr-4 shadow-lg backdrop-blur-sm">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold flex items-center animate-fade-in">
                                    Invoice #{{ $bill->invoice_no ?? $bill->id }}
                                </h1>
                                <p class="text-blue-100/90 mt-2 text-lg">Billing Details & Payment Information</p>
                            </div>
                        </div>
                        <button onclick="window.print()"
                                class="group flex items-center px-6 py-3 bg-white/20 backdrop-blur-sm rounded-xl hover:bg-white/30 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg no-print">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                            </svg>
                            Print Invoice
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="px-8 py-8 space-y-8">
                <!-- Customer & Invoice Info -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-data="{ loaded: false }" x-init="setTimeout(() => loaded = true, 100)">
                    <!-- Customer Information -->
                    <div class="bg-gradient-to-br from-blue-50/80 to-blue-100/50 rounded-2xl p-6 border border-blue-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-10"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         :class="loaded ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Customer Information
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-blue-100/60">
                                <span class="text-sm font-medium text-gray-500">Customer Name</span>
                                <span class="font-semibold text-gray-800 text-right">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? '—' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium text-gray-500">Due Date</span>
                                <span class="font-semibold text-gray-800">{{ $bill->due_date?->format('M d, Y') ?? '—' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Invoice Details -->
                    <div class="bg-gradient-to-br from-green-50/80 to-green-100/50 rounded-2xl p-6 border border-green-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 transform translate-x-10"
                         x-transition:enter-end="opacity-100 transform translate-x-0"
                         x-transition:delay="100"
                         :class="loaded ? 'opacity-100 transform translate-x-0' : 'opacity-0 transform translate-x-10'">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            Invoice Details
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-2 border-b border-green-100/60">
                                <span class="text-sm font-medium text-gray-500">Description</span>
                                <span class="font-semibold text-gray-800 text-right">{{ $bill->description ?? 'N/A' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-sm font-medium text-gray-500">Status</span>
                                @php
                                    $status = strtolower($bill->status ?? 'unpaid');
                                    $statusClasses = [
                                        'paid' => 'bg-green-100 text-green-800 border-green-200 shadow-green-200/50',
                                        'overdue' => 'bg-red-100 text-red-800 border-red-200 shadow-red-200/50',
                                        'unpaid' => 'bg-yellow-100 text-yellow-800 border-yellow-200 shadow-yellow-200/50',
                                    ];
                                    $statusIcons = [
                                        'paid' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                        'overdue' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
                                        'unpaid' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-xl border-2 shadow-sm {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }} animate-pulse">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="{{ $statusIcons[$status] ?? 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }}" clip-rule="evenodd"/>
                                    </svg>
                                    {{ ucfirst($bill->status ?? 'unpaid') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount Section -->
                <div class="bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl p-8 border-0 shadow-2xl transform transition-all duration-700 hover:scale-[1.02] hover:shadow-3xl relative overflow-hidden">
                    <!-- Animated background pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full -translate-y-16 translate-x-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full translate-y-12 -translate-x-12"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="flex flex-col lg:flex-row items-center justify-between">
                            <div class="text-center lg:text-left mb-6 lg:mb-0">
                                <p class="text-green-100 text-lg mb-2">Total Amount Due</p>
                                <p class="text-5xl lg:text-6xl font-bold text-white tracking-tight animate-bounce-in">
                                    ₱{{ number_format($bill->amount_due ?? 0, 2) }}
                                </p>
                            </div>
                            <div class="transform transition-transform duration-500 hover:scale-110">
                                <svg class="w-20 h-20 lg:w-24 lg:h-24 text-white/90" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                @if($bill->notes)
                <div class="bg-gradient-to-br from-amber-50/80 to-amber-100/50 rounded-2xl p-6 border border-amber-200/60 shadow-sm hover:shadow-md transition-all duration-500 transform hover:-translate-y-1"
                     x-data="{ showNotes: false }"
                     x-init="setTimeout(() => showNotes = true, 300)">
                    <div class="flex items-start">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mr-4 flex-shrink-0">
                            <svg class="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Additional Notes</h3>
                            <p class="text-gray-700 leading-relaxed" x-show="showNotes" x-transition:enter="transition ease-out duration-500"
                               x-transition:enter-start="opacity-0 transform translate-y-4"
                               x-transition:enter-end="opacity-100 transform translate-y-0">
                                {{ $bill->notes }}
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Actions Section -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8 border-t border-gray-200/60 no-print"
                     x-data="{ loaded: false }"
                     x-init="setTimeout(() => loaded = true, 500)">
                    @if(strtolower($bill->status ?? 'unpaid') !== 'paid')
                    <form action="{{ route('billing.markPaid',$bill->id) }}" method="POST" class="inline"
                          x-transition:enter="transition ease-out duration-500"
                          x-transition:enter-start="opacity-0 transform translate-y-4"
                          x-transition:enter-end="opacity-100 transform translate-y-0"
                          :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        @csrf
                        <button class="group flex items-center px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl shadow-lg">
                            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Mark as Paid
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('billing.index') }}"
                       class="group flex items-center px-8 py-4 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-2xl hover:from-gray-700 hover:to-gray-800 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl shadow-lg"
                       x-transition:enter="transition ease-out duration-500"
                       x-transition:enter-start="opacity-0 transform translate-y-4"
                       x-transition:enter-end="opacity-100 transform translate-y-0"
                       x-transition:delay="100"
                       :class="loaded ? 'opacity-100 transform translate-y-0' : 'opacity-0 transform translate-y-4'">
                        <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                        </svg>
                        Back to Bills
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print-only invoice design -->
<div class="print-invoice" style="display: none;">
    <!-- Same print content as before -->
    <div class="invoice-header">
        <div class="company-info">
            <div class="logo-section">
                <div class="logo-container">
                    <img src="{{ asset('images/logo.png') }}" alt="PSCTV Logo" class="logo">
                </div>
                <div class="company-details">
                    <h1 class="company-name">PSCTV</h1>
                    <p class="company-tagline">Panabo Cable and Fiber Services</p>
                </div>
            </div>
            <div class="contact-info">
                <p>Panabo City, Davao del Norte</p>
                <p>Phone: (084) 123-4567</p>
                <p>Email: billing@pscablefiber.com</p>
            </div>
        </div>
        <div class="invoice-title-section">
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <div class="invoice-meta">
                    <p><strong>Invoice #:</strong> {{ $bill->invoice_no ?? $bill->id }}</p>
                    <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $bill->due_date?->format('F d, Y') ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-body">
        <div class="billing-info-grid">
            <div class="bill-to-section">
                <h3 class="section-title">BILL TO</h3>
                <div class="bill-to-content">
                    <p class="customer-name">{{ $bill->subscription?->customer?->user->name ?? $bill->customer_name ?? '—' }}</p>
                    <p class="customer-details">PSCTV Subscriber</p>
                </div>
            </div>
            
            <div class="invoice-status-section">
                <h3 class="section-title">INVOICE STATUS</h3>
                <div class="status-badge {{ strtolower($bill->status ?? 'unpaid') }}">
                    {{ ucfirst($bill->status ?? 'unpaid') }}
                </div>
            </div>
        </div>

        <div class="services-section">
            <h3 class="section-title">SERVICE DETAILS</h3>
            <table class="services-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="service-description">
                                <strong>{{ $bill->description ?? 'Monthly Cable & Internet Service' }}</strong>
                                <p class="service-period">Billing Period: {{ now()->subMonth()->format('M d, Y') }} - {{ now()->format('M d, Y') }}</p>
                            </div>
                        </td>
                        <td class="amount-cell">₱{{ number_format($bill->amount_due ?? 0, 2) }}</td>
                    </tr>
                    @if($bill->notes)
                    <tr class="notes-row">
                        <td colspan="2">
                            <div class="notes-content">
                                <strong>Additional Notes:</strong> {{ $bill->notes }}
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="total-section">
            <div class="total-container">
                <div class="total-label">TOTAL DUE</div>
                <div class="total-amount">₱{{ number_format($bill->amount_due ?? 0, 2) }}</div>
            </div>
        </div>

        <div class="payment-info-section">
            <h3 class="section-title">PAYMENT INFORMATION</h3>
            <div class="payment-grid">
                <div class="payment-methods">
                    <h4>Payment Methods Accepted:</h4>
                    <ul>
                        <li>Bank Transfer</li>
                        <li>Credit/Debit Card</li>
                        <li>Cash Payment</li>
                        <li>Online Payment</li>
                    </ul>
                </div>
                <div class="payment-instructions">
                    <h4>Payment Instructions:</h4>
                    <p>Please make payment payable to <strong>PSCTV - Panabo Cable and Fiber Services</strong></p>
                    <p>Payment is due upon receipt. Late payments may incur additional fees.</p>
                </div>
            </div>
        </div>

        <div class="thank-you-section">
            <p>Thank you for choosing PSCTV for your cable and internet needs!</p>
        </div>

        <div class="footer-section">
            <div class="footer-content">
                <p><strong>PSCTV - Panabo Cable and Fiber Services</strong></p>
                <p>Panabo City, Davao del Norte | Phone: (084) 123-4567 | Email: billing@pscablefiber.com</p>
                <p class="footer-note">&copy; {{ date('Y') }} PSCTV. All rights reserved.</p>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes bounceIn {
    0% { opacity: 0; transform: scale(0.3); }
    50% { opacity: 1; transform: scale(1.05); }
    70% { transform: scale(0.9); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes pulseSlow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-bounce-in {
    animation: bounceIn 1s ease-out forwards;
}

.animate-pulse-slow {
    animation: pulseSlow 3s ease-in-out infinite;
}

/* Enhanced hover effects */
.hover-lift {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Glass morphism effects */
.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Print Styles (same as before) */
@media print {
    body * {
        visibility: hidden;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .print-invoice, .print-invoice * {
        visibility: visible;
    }
    
    .print-invoice {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        font-size: 14px;
        line-height: 1.6;
        color: #1a1a1a;
        padding: 20px;
    }

    .no-print, .no-print * {
        display: none !important;
    }

    /* ... rest of print styles remain the same ... */
}

@media screen {
    .print-invoice {
        display: none;
    }
}

/* Enhanced responsive design */
@media (max-width: 768px) {
    .max-w-4xl {
        margin-left: 1rem;
        margin-right: 1rem;
    }
    
    .px-8 {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    
    .text-5xl {
        font-size: 2.5rem;
    }
    
    .text-6xl {
        font-size: 3rem;
    }
}

/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
// Additional interactive enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Observe all cards for scroll animations
    document.querySelectorAll('.bg-gradient-to-br').forEach(card => {
        observer.observe(card);
    });

    // Enhanced print button interaction
    const printButton = document.querySelector('[onclick="window.print()"]');
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            // Add a little bounce effect when clicked
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        });
    }

    // Add parallax effect to header background
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const header = document.querySelector('.bg-gradient-to-r');
        if (header) {
            const rate = scrolled * -0.5;
            header.style.transform = `translateY(${rate}px)`;
        }
    });
});
</script>
@endsection