<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Customer;

class StaffSubscriptionController extends Controller
{
    /** 📋 Show all subscriptions */
    public function index(Request $request)
    {
        $query = Subscription::with(['customer.user'])->latest();

        // Optional search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $subscriptions = $query->paginate(10);

        return view('staff.subscriptions.index', compact('subscriptions'));
    }

    /** 🔍 Show a single subscription */
    public function show($id)
    {
        $subscription = Subscription::with(['customer.user'])->findOrFail($id);
        return view('staff.subscriptions.show', compact('subscription'));
    }
}
