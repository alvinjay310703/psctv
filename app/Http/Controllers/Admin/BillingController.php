<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\Package;
use App\Models\Customer;
use App\Mail\PaymentReceiptMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Services\InvoiceService;
use App\Services\PaymentService;

class BillingController extends Controller
{
    protected $invoiceService;
    protected $paymentService;

    public function __construct(InvoiceService $invoiceService, PaymentService $paymentService)
    {
        $this->invoiceService = $invoiceService;
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of bills with filters, sorting, pagination, and summary.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['subscription.customer.user']);

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'like', "%{$search}%")
                    ->orWhereHas('subscription.customer.user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        if ($request->filled('from')) {
            $query->whereDate('due_date', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('due_date', '<=', $request->to);
        }

        // Sorting
        $allowedSorts = ['id', 'invoice_no', 'amount_due', 'due_date', 'status', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'id';
        $order = $request->get('order', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $order);

        $bills = $query->paginate(10)->appends($request->query());

        // ✅ FIXED SUMMARY LOGIC
        $summary = [
            'total_collected' => Invoice::where('status', 'paid')->sum('amount_due'),

            // Pending = unpaid but not yet overdue
            'pending' => Invoice::where('status', 'unpaid')
                ->whereDate('due_date', '>=', now())
                ->sum('amount_due'),

            // Overdue = count of users + total amount
            'overdue' => [
                'amount' => Invoice::where(function ($q) {
                    $q->where('status', 'overdue')
                      ->orWhere(function ($sub) {
                          $sub->where('status', 'unpaid')
                              ->whereDate('due_date', '<', now());
                      });
                })->sum('amount_due'),

                'customers' => Invoice::where(function ($q) {
                    $q->where('status', 'overdue')
                      ->orWhere(function ($sub) {
                          $sub->where('status', 'unpaid')
                              ->whereDate('due_date', '<', now());
                      });
                })->distinct('customer_id')->count('customer_id'),
            ],
        ];

        return view('billing.index', compact('bills', 'summary'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $subscriptions = Subscription::with(['customer.user', 'package'])->get();
        $customers = Customer::with('user')->get();
        $plans = Package::where('is_active', true)->orderBy('price')->get();

        return view('billing.create', compact('subscriptions', 'customers', 'plans'));
    }

    /**
     * Store a newly created bill.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'customer_name'   => 'nullable|string|max:255',
            'reason'          => 'nullable|string|max:255',
            'amount'          => 'required|numeric|min:0',
            'due_date'        => 'required|date',
            'notes'           => 'nullable|string|max:500',
        ]);

        $description = $validated['reason'] ?? null;

        // If linked to subscription, use package name as description
        if ($validated['subscription_id']) {
            $subscription = Subscription::with('package')->find($validated['subscription_id']);
            $description = $subscription?->package?->name ?? 'Subscription Bill';
        }

        // If no subscription and no reason, fallback
        if (!$validated['subscription_id'] && !$description) {
            $description = 'Manual Bill';
        }

        // Invoice number handled automatically by model boot()
        $invoice = Invoice::create([
            'subscription_id' => $validated['subscription_id'] ?? null,
            'customer_name'   => $validated['customer_name'] ?? null,
            'description'     => $description,
            'amount_due'      => $validated['amount'],
            'due_date'        => $validated['due_date'],
            'billing_date'    => now(),
            'status'          => 'unpaid',
            'notes'           => $validated['notes'] ?? null,
        ]);

        return redirect()->route('billing.show', $invoice->id)
            ->with('success', "Bill #{$invoice->invoice_no} created successfully.");
    }

    /**
     * Display a single bill.
     */
    public function show($id)
    {
        $bill = Invoice::with(['subscription.customer.user', 'payments'])->findOrFail($id);
        return view('billing.show', compact('bill'));
    }

    /**
     * Mark a bill as paid.
     */
    public function markPaid(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if (strtolower($invoice->status) === 'paid') {
            return back()->with('info', "Bill #{$invoice->invoice_no} is already marked as paid.");
        }

        $validated = $request->validate([
            'amount'    => 'nullable|numeric|min:0',
            'method'    => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:100',
            'notes'     => 'nullable|string|max:500',
        ]);

        $amount = $validated['amount'] ?? $invoice->amount_due;

        // Create Payment
        $payment = Payment::create([
            'invoice_id'   => $invoice->id,
            'amount_paid'  => $amount,
            'payment_date' => now(),
            'method'       => $validated['method'] ?? 'Cash',
            'reference'    => $validated['reference'] ?? null,
            'status'       => 'paid',
            'payload'      => ['notes' => $validated['notes'] ?? null],
            'created_by'   => Auth::id(),
        ]);

        // Update invoice
        $invoice->update([
            'amount_paid' => $amount,
            'status'      => 'paid',
        ]);

        // Send Receipt Email
        if ($invoice->subscription && $invoice->subscription->customer && $invoice->subscription->customer->user) {
            $customerEmail = $invoice->subscription->customer->user->email;

            if ($customerEmail) {
                try {
                    Mail::to($customerEmail)->send(new PaymentReceiptMail($invoice, $payment));

                    if (count(Mail::failures()) > 0) {
                        Log::error("❌ Failed to send receipt email to: {$customerEmail}");
                        return redirect()->route('billing.show', $invoice->id)
                            ->with('error', "Bill marked as paid, but email failed.");
                    }

                    Log::info("✅ Receipt email sent to {$customerEmail}");
                    return redirect()->route('billing.show', $invoice->id)
                        ->with('success', "Bill #{$invoice->invoice_no} marked as paid and receipt emailed.");
                } catch (\Throwable $e) {
                    Log::error("❌ Email send exception: " . $e->getMessage());
                    return redirect()->route('billing.show', $invoice->id)
                        ->with('error', "Bill marked as paid, but failed to send receipt email.");
                }
            }
        }

        return redirect()->route('billing.show', $invoice->id)
            ->with('success', "Bill #{$invoice->invoice_no} marked as paid (no email sent).");
    }

    /**
     * Delete or cancel a bill.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoiceNo = $invoice->invoice_no;

        $this->invoiceService->cancelInvoice($invoice);

        return redirect()->route('billing.index')
            ->with('success', "Bill #{$invoiceNo} cancelled successfully.");
    }

    /**
     * View receipt (web).
     */
    public function receipt(Request $request, $id)
    {
        $bill = Invoice::with(['subscription.customer.user', 'payments'])->findOrFail($id);
        $payment = $bill->payments->last();
        $notes = $request->query('notes') ?? 'Thank you for your payment.';

        return view('billing.receipt', compact('bill', 'payment', 'notes'));
    }

    /**
     * Download receipt as PDF.
     */
    public function downloadReceipt($id)
    {
        $bill = Invoice::with(['subscription.customer.user', 'payments'])->findOrFail($id);
        $payment = $bill->payments->last();
        $notes = 'Thank you for your payment.';

        $pdf = Pdf::loadView('billing.receipt_pdf', compact('bill', 'payment', 'notes'));
        return $pdf->download("Receipt-{$bill->invoice_no}.pdf");
    }
}