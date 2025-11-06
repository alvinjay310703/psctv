<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceLog;
use Illuminate\Http\Request;

use App\Models\Invoice;

class InvoiceLogController extends Controller
{
    public function index()
    {
        $logs = InvoiceLog::latest()->paginate(15);

        return view('invoices.logs', compact('logs'));
    }

    public function show($id)
{
    $invoice = Invoice::with(['subscription.customer.user', 'payments'])->findOrFail($id);
    return view('invoices.show_invoice', compact('invoice')); // <-- change here
}

}
