<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\TechnicianAssigned;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ServiceCompleted;
use App\Events\TechnicianLocationUpdated;

class StaffServiceRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:staff'); // Adjust to your staff middleware (e.g., 'can:view-staff-service-requests')
    }

    /** 🧭 List all service requests (staff view) */
    public function index(Request $request)
    {
        $query = ServiceRequest::with(['technician', 'creator', 'customer']);

        // Optional: Filter to requests staff can access (e.g., by region or assigned)
        // $query->where('region_id', Auth::user()->region_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $serviceRequests = $query->latest()->paginate(10);

        return view('staff.service_request.index', compact('serviceRequests')); // Note: singular 'service_request' as per your folder
    }

    /** 🔍 Show single request */
    public function show(ServiceRequest $serviceRequest)
    {
        // Optional: Check if staff can view this request
        // if (!Auth::user()->canViewRequest($serviceRequest)) abort(403);

        $serviceRequest->load(['technician', 'creator', 'photos', 'customer']);
        return view('staff.service_request.show', compact('serviceRequest'));
    }

    /** 👨‍🔧 Show technician assignment page */
    public function assignPage(ServiceRequest $serviceRequest)
    {
        // Optional: Permission check
        // if (!Auth::user()->canAssignTechnicians()) abort(403);

        $technicians = Technician::available(5)->get();
        return view('staff.service_request.assign', compact('serviceRequest', 'technicians'));
    }

    /** ⚙️ Assign technician to service request */
    public function assignTechnician(Request $request, ServiceRequest $serviceRequest)
    {
        // Optional: Permission check
        // if (!Auth::user()->canAssignTechnicians()) abort(403);

        $validated = $request->validate([
            'technician_id' => 'required|exists:technicians,id',
            'notes'         => 'nullable|string|max:1000',
        ]);

        $technician = Technician::findOrFail($validated['technician_id']);

        if ($technician->status !== 'active') {
            return back()->withErrors(['technician_id' => "Technician {$technician->full_name} is not active."]);
        }

        $maxActive = config('service.tech_max_active', 5);
        if ($technician->active_jobs_count >= $maxActive) {
            return back()->withErrors(['technician_id' => "Technician {$technician->full_name} already has maximum active jobs."])->withInput();
        }

        $serviceRequest->assignTo($technician->id);

        if (!$serviceRequest->latitude || !$serviceRequest->longitude) {
            try { 
                $serviceRequest->geocodeAddress(); 
            } catch (\Throwable $e) {
                Log::warning("Geocoding failed for ServiceRequest #{$serviceRequest->id}: {$e->getMessage()}");
            }
        }

        $serviceRequest->load(['technician.user', 'customer.user']);

        $notification = new \App\Notifications\TechnicianAssigned($serviceRequest, Auth::user());

        if ($serviceRequest->technician?->user) {
            try {
                $serviceRequest->technician->user->notify($notification);
            } catch (\Throwable $e) {
                Log::error("Failed to notify technician (User ID: {$serviceRequest->technician->user->id}): {$e->getMessage()}");
            }
        }

        if ($serviceRequest->customer?->user) {
            try {
                $serviceRequest->customer->user->notify($notification);
            } catch (\Throwable $e) {
                Log::error("Failed to notify customer (User ID: {$serviceRequest->customer->user->id}): {$e->getMessage()}");
            }
        }

        try {
            event(new \App\Events\ServiceRequestStatusUpdated($serviceRequest));
        } catch (\Throwable $e) {
            Log::error("Failed to broadcast ServiceRequestStatusUpdated for ServiceRequest #{$serviceRequest->id}: {$e->getMessage()}");
        }

        return redirect()
            ->route('staff.service_requests.show', $serviceRequest->id)
            ->with('success', "Technician {$technician->full_name} assigned successfully and notifications sent!");
    }

    /** 🔁 Update request status */
    public function updateStatus(Request $request, ServiceRequest $serviceRequest)
    {
        // Optional: Permission check
        // if (!Auth::user()->canUpdateStatus($serviceRequest)) abort(403);

        $request->validate([
            'status'   => 'required|string|in:pending,assigned,in-progress,completed',
            'report'   => 'nullable|string',
            'photos.*' => 'nullable|image|max:5120',
        ]);

        $serviceRequest->status = $request->status;

        if ($request->status === 'completed') {
            $serviceRequest->completed_at = now();
            $serviceRequest->report       = $request->report ?? $serviceRequest->report;

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('service_photos', 'public');
                    $serviceRequest->photos()->create([
                        'path'  => $path,
                        'mime'  => $photo->getMimeType(),
                        'label' => 'Completion Proof',
                    ]);
                }
            }

            $serviceRequest->load(['technician.user', 'customer.user']);

            $notification = new \App\Notifications\ServiceCompleted($serviceRequest);

            if ($serviceRequest->customer && $serviceRequest->customer->user) {
                $serviceRequest->customer->user->notify($notification);
            }

            if ($serviceRequest->technician && $serviceRequest->technician->user) {
                $serviceRequest->technician->user->notify($notification);
            }

            $admins = \App\Models\User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, $notification);

            $staff = \App\Models\User::where('role', 'staff')->get();
            \Illuminate\Support\Facades\Notification::send($staff, $notification);
        }

        $serviceRequest->save();

        broadcast(new \App\Events\ServiceRequestStatusUpdated($serviceRequest))->toOthers();

        return redirect()
            ->route('staff.service_requests.show', $serviceRequest->id)
            ->with('success', 'Service request updated successfully!');
    }

    /** ⭐ Rate service request */
    public function rate(Request $request, ServiceRequest $serviceRequest)
    {
        // Optional: Permission check
        // if (!Auth::user()->canRateServiceRequest($serviceRequest)) abort(403);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $serviceRequest->rating = $request->rating;
        $serviceRequest->rated_at = now();
        $serviceRequest->save();

        return redirect()
            ->route('staff.service_requests.show', $serviceRequest->id)
            ->with('success', 'Service request rated successfully!');
    }

    /** 📝 Show create form */
    public function create()
    {
        return view('staff.service_request.create');
    }

    /** 💾 Store new service request */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'service_type'  => 'required|string|max:255',
            'notes'         => 'nullable|string|max:1000',
            'latitude'      => 'nullable|numeric|between:-90,90',
            'longitude'     => 'nullable|numeric|between:-180,180',
        ]);

        $serviceRequest = ServiceRequest::create([
            'customer_name' => $validated['customer_name'],
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'service_type'  => $validated['service_type'],
            'notes'         => $validated['notes'] ?? null,
            'latitude'      => $validated['latitude'] ?? null,
            'longitude'     => $validated['longitude'] ?? null,
            'status'        => 'pending',
            'created_by'    => Auth::id(),
        ]);

        // Optional: Geocode if coordinates not provided
        if (!$serviceRequest->latitude || !$serviceRequest->longitude) {
            try {
                $serviceRequest->geocodeAddress();
            } catch (\Throwable $e) {
                Log::warning("Geocoding failed for new ServiceRequest #{$serviceRequest->id}: {$e->getMessage()}");
            }
        }

        // Notify admins and staff about new request
        $notification = new \App\Notifications\ServiceRequestCreated($serviceRequest, $validated['customer_name']);

        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, $notification);

        $staff = \App\Models\User::where('role', 'staff')->get();
        \Illuminate\Support\Facades\Notification::send($staff, $notification);

        try {
            event(new \App\Events\ServiceRequestStatusUpdated($serviceRequest));
        } catch (\Throwable $e) {
            Log::error("Failed to broadcast ServiceRequestStatusUpdated for new ServiceRequest #{$serviceRequest->id}: {$e->getMessage()}");
        }

        return redirect()
            ->route('staff.service_requests.index')
            ->with('success', 'Service request created successfully!');
    }

    /** 🗑️ Delete service request */
    public function destroy(ServiceRequest $serviceRequest)
    {
        // Optional: Permission check
        // if (!Auth::user()->canDeleteServiceRequest($serviceRequest)) abort(403);

        // Prevent deletion if completed or in progress
        if (in_array($serviceRequest->status, ['in-progress', 'completed'])) {
            return redirect()
                ->route('staff.service_requests.index')
                ->with('error', 'Cannot delete service request that is in progress or completed.');
        }

        $serviceRequest->delete();

        return redirect()
            ->route('staff.service_requests.index')
            ->with('success', 'Service request deleted successfully!');
    }
}
