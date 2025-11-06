<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\ServiceRequestCreated;
use App\Models\User;

class CustomerServiceRequestController extends Controller
{
    /**
     * List all service requests for the logged-in customer
     */
    public function index()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $requests = $customer->serviceRequests()->with('technician')->latest()->get();

        return response()->json([
            'success' => true,
            'service_requests' => $requests,
        ]);
    }

    /**
     * Submit a new service request
     */
    public function store(Request $request)
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found',
            ], 404);
        }

        $validated = $request->validate([
            'service_type' => 'required|string|max:100',
            'notes'        => 'nullable|string|max:1000',
            'address'      => 'required|string|max:255',
            'phone'        => 'nullable|string|max:50',
            'email'        => 'nullable|email|max:255',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
        ]);

        $validated['customer_id'] = $customer->id;
        $validated['customer_name'] = $customer->user->name ?? 'Unknown';
        $validated['status'] = 'pending';
        $validated['created_by'] = $customer->user->id ?? null;

        $serviceRequest = ServiceRequest::create($validated);

        // Auto-geocode if lat/lon missing
        if ((empty($validated['latitude']) || empty($validated['longitude'])) && !empty($validated['address'])) {
            try {
                $serviceRequest->geocodeAddress();
            } catch (\Throwable $e) {
                Log::warning("Geocoding skipped for ServiceRequest #{$serviceRequest->id}: " . $e->getMessage());
            }
        }

        // Notify admins
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new ServiceRequestCreated($serviceRequest, $customer->user->name ?? 'Walk-in Customer'));
            }
        } catch (\Throwable $e) {
            Log::error("Failed to notify admins for ServiceRequest #{$serviceRequest->id}: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Service request submitted successfully',
            'service_request' => $serviceRequest,
        ]);
    }

    /**
     * Show a single service request
     */
    public function show($id)
    {
        $customer = Auth::user()->customer;

        $serviceRequest = ServiceRequest::with('technician')
            ->where('customer_id', $customer->id)
            ->find($id);

        if (!$serviceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Service request not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'service_request' => $serviceRequest,
        ]);
    }
}
