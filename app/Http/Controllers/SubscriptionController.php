<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SubscriptionController extends Controller
{
    /**
     * Fake dataset for demo
     */
    private $subscriptions;

    public function __construct()
    {
        $this->subscriptions = collect([
            (object)[
                'id' => 1,
                'customer' => 'Maria Santos',
                'email' => 'maria@example.com',
                'phone' => '+63 912 345 6789',
                'address' => '123 Main St, Quezon City',
                'plan' => 'Basic Cable Plan',
                'cycle' => 'monthly',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonth(),
                'status' => 'active',
                'invoices' => collect([
                    (object)[
                        'id' => 101,
                        'amount' => 500,
                        'status' => 'paid',
                        'created_at' => now()->subMonths(2),
                    ],
                    (object)[
                        'id' => 102,
                        'amount' => 500,
                        'status' => 'paid',
                        'created_at' => now()->subMonth(),
                    ],
                ]),
            ],
            (object)[
                'id' => 2,
                'customer' => 'Juan Dela Cruz',
                'email' => 'juan@example.com',
                'phone' => '+63 917 654 3210',
                'address' => '456 Rizal Ave, Manila',
                'plan' => 'Premium Cable + Internet',
                'cycle' => 'monthly',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->subDays(5),
                'status' => 'expired',
                'invoices' => collect([
                    (object)[
                        'id' => 201,
                        'amount' => 1200,
                        'status' => 'paid',
                        'created_at' => now()->subMonths(2),
                    ],
                    (object)[
                        'id' => 202,
                        'amount' => 1200,
                        'status' => 'unpaid',
                        'created_at' => now()->subMonth(),
                    ],
                ]),
            ],
            (object)[
                'id' => 3,
                'customer' => 'Ana Cruz',
                'email' => 'ana@example.com',
                'phone' => '+63 918 222 1111',
                'address' => '789 Lopez St, Makati',
                'plan' => 'Fiber Internet Plan',
                'cycle' => 'yearly',
                'start_date' => now()->subYear(),
                'end_date' => now()->subMonths(6),
                'status' => 'cancelled',
                'invoices' => collect([]),
            ],
        ]);
    }

    /**
     * Show all subscriptions with search, filter, pagination
     */
    public function index(Request $request)
    {
        $subscriptions = $this->subscriptions;

        // 🔎 Search
        if ($request->filled('search')) {
            $q = strtolower($request->search);
            $subscriptions = $subscriptions->filter(function ($sub) use ($q) {
                return str_contains(strtolower($sub->customer), $q) ||
                       str_contains(strtolower($sub->email), $q) ||
                       str_contains(strtolower($sub->plan), $q);
            });
        }

        // 🎛 Filter by status
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $subscriptions = $subscriptions->filter(fn($sub) => $sub->status === $status);
        }

        // 📄 Pagination
        $page = $request->get('page', 1);
        $perPage = 5;
        $paginated = new LengthAwarePaginator(
            $subscriptions->forPage($page, $perPage)->values(),
            $subscriptions->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('subscriptions.index', [
            'subscriptions' => $paginated
        ]);
    }

    /**
     * Show one subscription details
     */
    public function show($id)
    {
        $subscription = $this->subscriptions->firstWhere('id', $id);

        if (!$subscription) {
            return redirect()->route('subscriptions.index')->with('error', 'Subscription not found.');
        }

        return view('subscriptions.show', compact('subscription'));
    }

    /**
     * Edit subscription
     */
    public function edit($id)
    {
        $subscription = $this->subscriptions->firstWhere('id', $id);

        if (!$subscription) {
            return redirect()->route('subscriptions.index')->with('error', 'Subscription not found.');
        }

        return view('subscriptions.edit', compact('subscription'));
    }

    /**
     * Update subscription (demo only)
     */
    public function update(Request $request, $id)
    {
        // TODO: Save updates to DB
        return redirect()->route('subscriptions.show', $id)->with('success', 'Subscription updated (demo).');
    }

    /**
     * Cancel subscription (only active ones)
     */
    public function destroy($id)
    {
        $subscription = $this->subscriptions->firstWhere('id', $id);

        if (!$subscription) {
            return redirect()->route('subscriptions.index')->with('error', 'Subscription not found.');
        }

        if ($subscription->status !== 'active') {
            return redirect()->route('subscriptions.index')->with('error', 'Only active subscriptions can be cancelled.');
        }

        return redirect()->route('subscriptions.index')->with('success', "Subscription #{$id} has been cancelled (demo).");
    }
     /** Create form (no $subscription variable) */
    public function create()
    {
        return view('subscriptions.create'); // create view must NOT expect $subscription
    }

    /** Store (demo) */
    public function store(Request $request)
    {
        // TODO: validate & save to DB
        return redirect()->route('subscriptions.index')->with('success', 'Subscription created (demo).');
    }

    /**
     * Renew subscription (only expired ones)
     */
    public function renew($id)
    {
        $subscription = $this->subscriptions->firstWhere('id', $id);

        if (!$subscription) {
            return redirect()->route('subscriptions.index')->with('error', 'Subscription not found.');
        }

        if ($subscription->status !== 'expired') {
            return redirect()->route('subscriptions.index')->with('error', 'Only expired subscriptions can be renewed.');
        }

        return redirect()->route('subscriptions.show', $id)->with('success', "Subscription #{$id} has been renewed (demo).");
    }
}
