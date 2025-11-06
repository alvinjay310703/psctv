<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Customer;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Fetch all subscriptions for a given customer.
     * GET /api/customers/{id}/subscriptions
     */
    public function index($id)
    {
        $customer = Customer::with('subscriptions.package')->find($id);

        if (!$customer) {
            return response()->json([
                'status'  => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        $subscriptions = $customer->subscriptions()
            ->with([
                'package:id,name,price,billing_cycle',
                'invoices:id,subscription_id,amount_due,status',
                'invoices.payments:id,invoice_id,amount_paid,created_at'
            ])
            ->latest('start_date')
            ->get();

        return response()->json([
            'status'        => true,
            'customer_id'   => $customer->id,
            'customer_name' => $customer->user->name ?? null,
            'subscriptions' => $subscriptions,
        ]);
    }

    /**
     * Fetch a single active subscription for a customer.
     * GET /api/customers/{id}/subscription
     */
    public function show($id)
    {
        $subscription = Subscription::with(['package', 'invoices.payments'])
            ->where('customer_id', $id)
            ->where('status', 'active')
            ->latest('start_date')
            ->first();

        if (!$subscription) {
            return response()->json([
                'status'  => false,
                'message' => 'No active subscription found for this customer.',
            ]);
        }

        return response()->json([
            'status'       => true,
            'subscription' => $subscription,
        ]);
    }
}
