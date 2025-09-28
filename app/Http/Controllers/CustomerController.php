<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\Invoice;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers with search & filter.
     */
    public function index(Request $request)
    {
        $query = Customer::with('user');

        // Search by name or account #
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%");
                })->orWhere('account_number', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('customers.list', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create()
{
    $packages = Package::all();
    return view('customers.create', compact('packages'));
}


    /**
     * Store a newly created customer.
     */
   public function store(Request $request)
{
    $validated = $request->validate([
        'name'  => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive,suspended,pending',
    ]);

    DB::transaction(function () use ($validated, $request) {
        // 1️⃣ Create the User
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => bcrypt('password'), // default or random
            'role'     => 'customer',
        ]);

        // 2️⃣ Create the Customer profile
        $customer = Customer::create([
            'user_id'        => $user->id,
            'account_number' => 'ACC-' . strtoupper(uniqid()),
            'status'         => $validated['status'],
            'address'        => $request->address,
            'city'           => $request->city,
            'province'       => $request->province,
            'zip_code'       => $request->zip,
            'phone'          => $validated['phone'] ?? null,
            'dob'            => $request->dob,
            'gender'         => $request->gender,
        ]);

        // 3️⃣ (Optional) Auto-create subscription & first invoice if plan chosen
        if ($request->filled('plan_id')) {
            $subscription = $customer->subscriptions()->create([
                'package_id'   => $request->plan_id,
                'status'       => 'active',
                'start_date'   => now(),
                'next_billing_date' => now()->addMonth(),
                
            ]);
                $subscription->invoices()->create([
                    'amount_due'   => $subscription->package->price,
                    'status'       => 'unpaid',
                    'due_date'     => now()->addDays(7),
                    'description'  => 'Billing Charge for ' . $subscription->package->name,
                ]);


        }
    });

    return redirect()->route('customers.list')->with('success', '✅ Customer created successfully.');
}



    /**
     * Display the specified customer profile.
     */
    public function show(Customer $customer)
    {
        $customer->load(['user', 'subscriptions.package', 'subscriptions.invoices.payments', 'serviceRequests.technician.user']);

        // Stats
        $totalPaid = $customer->subscriptions->flatMap->invoices->flatMap->payments->sum('amount');
        $outstandingBalance = $customer->subscriptions->flatMap->invoices
            ->where('status', 'unpaid')->sum('amount_due');
        $activeSubs = $customer->subscriptions->where('status', 'active')->count();
        $serviceRequests = $customer->serviceRequests->count();

        return view('customers.view', compact('customer', 'totalPaid', 'outstandingBalance', 'activeSubs', 'serviceRequests'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer)
    {
        $customer->load('user');
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $customer->user_id,
            'status'   => 'required|in:active,inactive,suspended,pending',
            'phone'    => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $customer) {
            // Update User
            $customer->user->update([
                'name'  => $request->name,
                'email' => $request->email,
            ]);

            // Optional password reset
            if ($request->filled('password')) {
                $customer->user->update(['password' => Hash::make($request->password)]);
            }

            // Update Customer
            $customer->update([
                'status'   => $request->status,
                'address'  => $request->address,
                'city'     => $request->city,
                'province' => $request->province,
                'zip_code' => $request->zip,
                'phone'    => $request->phone,
                'dob'      => $request->dob,
                'gender'   => $request->gender,
            ]);
        });

        return redirect()->route('customers.list')->with('success', '✏️ Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        DB::transaction(function () use ($customer) {
            // Delete user first
            $customer->user->delete();

            // Delete customer
            $customer->delete();
        });

        return redirect()->route('customers.list')->with('success', '🗑️ Customer deleted.');
    }
}
