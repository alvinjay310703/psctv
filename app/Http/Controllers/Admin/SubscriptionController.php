<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Customer;
use App\Models\Package;
use App\Models\Invoice;
use App\Models\InvoiceLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['customer.user', 'package']);

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($mainQ) use ($q) {
                $mainQ->whereHas('customer.user', function ($userQ) use ($q) {
                    $userQ->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                })->orWhereHas('package', function ($pkgQ) use ($q) {
                    $pkgQ->where('name', 'like', "%{$q}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $subscriptions = $query->latest()->paginate(10);

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $customers = Customer::with('user')->get();
        $packages  = Package::where('is_active', true)->get();

        return view('subscriptions.create', compact('customers', 'packages'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'package_id'  => 'required|exists:packages,id',
        'start_date'  => 'required|date',
        'end_date'    => 'nullable|date|after_or_equal:start_date',
        'status'      => ['nullable', Rule::in(['active', 'pending', 'inactive', 'cancelled', 'expired'])],
    ]);

    DB::beginTransaction();

    try {
        $customer = Customer::findOrFail($validated['customer_id']); // ✅ Get customer
        $package  = Package::findOrFail($validated['package_id']);
        $start    = Carbon::parse($validated['start_date']);

        // ✅ Auto-calculate end_date if not given
        $end = !empty($validated['end_date'])
            ? Carbon::parse($validated['end_date'])
            : match (strtolower($package->billing_cycle ?? 'monthly')) {
                'yearly'    => $start->copy()->addYear(),
                'quarterly' => $start->copy()->addMonths(3),
                'weekly'    => $start->copy()->addWeek(),
                default     => $start->copy()->addMonth(),
            };

        // ✅ Determine next billing date
        $nextBilling = match (strtolower($package->billing_cycle ?? 'monthly')) {
            'yearly'    => $start->copy()->addYear(),
            'quarterly' => $start->copy()->addMonths(3),
            'weekly'    => $start->copy()->addWeek(),
            default     => $start->copy()->addMonth(),
        };

        // ✅ Create subscription with user_id from customer
        $subscription = Subscription::create([
            'customer_id'       => $customer->id,
            'user_id'           => $customer->user_id, // ✅ Link to user automatically
            'package_id'        => $validated['package_id'],
            'start_date'        => $start->toDateString(),
            'end_date'          => $end->toDateString(),
            'status'            => $validated['status'] ?? 'active',
            'next_billing_date' => $nextBilling->toDateString(),
        ]);

        // ✅ Create first invoice (due 7 days after start)
        $dueDate = $start->copy()->addDays(7);

        $invoice = $subscription->invoices()->create([
            'subscription_id'  => $subscription->id,
            'customer_id'      => $customer->id,
            'customer_name'    => $customer->user->name ?? null,
            'customer_contact' => $customer->user->email ?? null,
            'invoice_no'       => 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT),
            'period_start'     => $start->toDateString(),
            'period_end'       => $end->toDateString(),
            'amount_due'       => $package->price ?? 0,
            'amount_paid'      => 0,
            'due_date'         => $dueDate->toDateString(),
            'billing_date'     => $start->toDateString(),
            'is_recurring'     => true,
            'notes'            => "Subscription plan: {$package->name}",
            'status'           => 'unpaid',
        ]);

        // ✅ Log invoice creation
        InvoiceLog::create([
            'subscription_id' => $subscription->id,
            'invoice_id'      => $invoice->id,
            'message'         => "Invoice #{$invoice->invoice_no} created for {$package->name}",
        ]);

        DB::commit();

        return redirect()
            ->route('subscriptions.show', $subscription->id)
            ->with('success', 'Subscription created successfully with initial invoice.');

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('Subscription store error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
        ]);

        return back()
            ->withInput()
            ->withErrors(['error' => 'Failed to create subscription: ' . $e->getMessage()]);
    }
}





    public function show($id)
    {
        $subscription = Subscription::with(['customer.user', 'package', 'invoices.payments'])->findOrFail($id);
        return view('subscriptions.show', compact('subscription'));
    }

    public function edit($id)
    {
        $subscription = Subscription::with(['customer.user', 'package'])->findOrFail($id);
        $customers = Customer::with('user')->get();
        $packages  = Package::where('is_active', true)->get();

        return view('subscriptions.edit', compact('subscription', 'customers', 'packages'));
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'package_id'  => 'required|exists:packages,id',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after_or_equal:start_date',
            'status'      => ['nullable', Rule::in(['active','pending','inactive','cancelled','expired'])],
        ]);

        $package = Package::findOrFail($validated['package_id']);

        // Recalculate next billing if package changed
        $start = Carbon::parse($validated['start_date']);
        $nextBilling = match($package->billing_cycle ?? 'monthly') {
            'yearly' => $start->copy()->addYear(),
            'quarterly' => $start->copy()->addMonths(3),
            default => $start->copy()->addMonth(),
        };

        $subscription->update([
            ...$validated,
            'status'            => $validated['status'] ?? 'active',
            'next_billing_date' => $nextBilling->toDateString(),
        ]);

        return redirect()->route('subscriptions.show', $subscription->id)
            ->with('success', 'Subscription updated successfully.');
    }

    public function destroy($id)
    {
        $subscription = Subscription::findOrFail($id);

        if ($subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')->with('error', 'Only active subscriptions can be cancelled.');
        }

        $subscription->update(['status' => 'cancelled']);

        return redirect()->route('subscriptions.index')->with('success', "Subscription #{$id} cancelled.");
    }

    public function renew($id)
    {
        DB::beginTransaction();

        try {
            $subscription = Subscription::with('package')->findOrFail($id);

            if ($subscription->status !== 'expired') {
                return redirect()->route('subscriptions.index')->with('error', 'Only expired subscriptions can be renewed.');
            }

            $package = $subscription->package;
            $now = Carbon::now();

            $newEnd = match($package->billing_cycle ?? 'monthly') {
                'yearly' => $now->copy()->addYear(),
                'quarterly' => $now->copy()->addMonths(3),
                default => $now->copy()->addMonth(),
            };

            $subscription->update([
                'status'            => 'active',
                'end_date'          => $newEnd->toDateString(),
                'next_billing_date' => $newEnd->toDateString(),
            ]);

            $invoice = $subscription->invoices()->create([
                'amount_due'  => $package->price ?? 0,
                'due_date'    => $now->copy()->addDays(7)->toDateString(),
                'billing_date'=> $now->toDateString(),
                'description' => 'Renewal Invoice - ' . ($package->name ?? ''),
                'status'      => 'unpaid',
                'is_recurring'=> true,
            ]);

            InvoiceLog::create([
                'subscription_id' => $subscription->id,
                'invoice_id'      => $invoice->id,
                'message'         => "Renewal invoice #{$invoice->invoice_no} created for subscription #{$subscription->id}",
            ]);

            DB::commit();

            return redirect()->route('subscriptions.show', $id)->with('success', 'Subscription renewed and invoice created.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Subscription renew error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Failed to renew subscription: '.$e->getMessage()]);
        }
    }
}
