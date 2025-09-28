<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    // Show all bills
    public function index()
    {
        $bills = [
            ['id' => 1001, 'customer' => 'Maria Santos', 'amount' => 500, 'due' => '2025-09-30', 'status' => 'Unpaid', 'reason' => 'Monthly Subscription'],
            ['id' => 1002, 'customer' => 'Juan Dela Cruz', 'amount' => 700, 'due' => '2025-09-25', 'status' => 'Paid', 'reason' => 'Installation Fee'],
            ['id' => 1003, 'customer' => 'Pedro Lopez', 'amount' => 600, 'due' => '2025-09-10', 'status' => 'Overdue', 'reason' => 'Repair Service'],
            ['id' => 1004, 'customer' => 'Ana Rivera', 'amount' => 800, 'due' => '2025-10-05', 'status' => 'Unpaid', 'reason' => 'Monthly Subscription'],
        ];

        return view('billing.index', compact('bills'));
    }

    // Show form to create new bill
    public function create()
    {
        $customers = ['Maria Santos', 'Juan Dela Cruz'];
        return view('billing.create', compact('customers'));
    }

    // Store new bill (demo only)
    public function store(Request $request)
    {
        return redirect()->route('billing.index')->with('success', 'Bill created (demo mode).');
    }

    // Show single bill
    public function show($id)
    {
        $bill = [
            'id' => $id,
            'customer' => 'Maria Santos',
            'amount' => 500,
            'due' => '2025-09-30',
            'status' => 'Unpaid',
            'reason' => 'Monthly Subscription', // added reason
        ];

        return view('billing.show', compact('bill'));
    }

    // Mark bill as paid
    public function markPaid($id)
    {
        return redirect()->route('billing.index')->with('success', "Bill #{$id} marked as Paid (demo).");
    }

    // Delete bill
    public function destroy($id)
    {
        return redirect()->route('billing.index')->with('success', "Bill #{$id} deleted (demo).");
    }

    // Show receipt
    public function receipt(Request $request, $id)
    {
        $bill = [
            'id' => $id,
            'customer' => 'Maria Santos',
            'amount' => 500,
            'due' => '2025-09-30',
            'status' => 'Paid',
            'reason' => 'Monthly Subscription',
            'payment_method' => 'GCash',
            'notes' => $request->query('notes') ?? 'Thank you for your payment.',
        ];

        return view('billing.receipt', compact('bill'));
    }
}
