<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Login endpoint
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();

        // Create Sanctum token for mobile use
        $token = $user->createToken('mobile')->plainTextToken;

        $customer = $user->customer;
        $activeSubscription = $customer->activeSubscription();

        $subscriptionData = $activeSubscription ? [
            'id' => $activeSubscription->id,
            'plan' => $activeSubscription->package->name ?? null,
            'price' => $activeSubscription->package->price ?? null,
            'start_date' => $activeSubscription->start_date,
            'end_date' => $activeSubscription->end_date,
            'status' => $activeSubscription->status,
            'next_billing_date' => $activeSubscription->next_billing_date,
        ] : null;

        // Fetch all invoices from subscriptions
        $invoices = $customer->subscriptions()
            ->with('invoices')
            ->get()
            ->flatMap->invoices
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'description' => $invoice->description,
                    'amount_due' => (float) $invoice->amount_due,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'billing_date' => $invoice->billing_date,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token, // ✅ Sanctum token returned
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'customer' => [
                ...$customer->toArray(),
                'active_subscription' => $subscriptionData,
                'invoices' => $invoices,
            ],
        ]);
    }

    /**
     * Fetch a single customer by user ID
     */
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
            ], 404);
        }

        $customer = $user->customer;

        $activeSubscription = $customer->activeSubscription();
        $subscriptionData = $activeSubscription ? [
            'id' => $activeSubscription->id,
            'plan' => $activeSubscription->package->name ?? null,
            'price' => $activeSubscription->package->price ?? null,
            'start_date' => $activeSubscription->start_date,
            'end_date' => $activeSubscription->end_date,
            'status' => $activeSubscription->status,
            'next_billing_date' => $activeSubscription->next_billing_date,
        ] : null;

        $invoices = $customer->subscriptions()
            ->with('invoices')
            ->get()
            ->flatMap->invoices
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_no' => $invoice->invoice_no,
                    'description' => $invoice->description,
                    'amount_due' => (float) $invoice->amount_due,
                    'amount_paid' => (float) $invoice->amount_paid,
                    'status' => $invoice->status,
                    'due_date' => $invoice->due_date,
                    'billing_date' => $invoice->billing_date,
                ];
            });

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'customer' => [
                ...$customer->toArray(),
                'active_subscription' => $subscriptionData,
                'invoices' => $invoices,
            ],
        ]);
    }

    /**
     * Logout endpoint (revokes token)
     */
    public function logout(Request $request)
    {
        // Revoke the token used for the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
