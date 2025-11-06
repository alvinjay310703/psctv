<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Technician;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceRequestPhoto;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceRequest;
use App\Events\TechnicianLocationUpdated;

class TechnicianController extends Controller
{
    /** 🔹 List all technicians */
    public function index()
    {
        $technicians = Technician::orderBy('id', 'desc')->paginate(10);
        return view('technicians.index', compact('technicians'));
    }

    /** 🔹 Show create form */
    public function create()
    {
        return view('technicians.create');
    }

    /** 🔹 Store new technician and auto-link to User */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:technicians,email|unique:users,email',
            'password'         => 'required|string|min:6',
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string|max:255',
            'service_area'     => 'nullable|string|max:255',
            'date_hire'        => 'nullable|date',
            'specialization'   => 'nullable|string|max:255',
            'emergency_name'   => 'nullable|string|max:255',
            'emergency_phone'  => 'nullable|string|max:20',
            'status'           => 'required|in:active,inactive,suspended',
            'profile_picture'  => 'nullable|image|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('technicians', 'public');
        }

        // Generate unique technician_id
        $validated['technician_id'] = 'TECH-' . strtoupper(Str::random(6));

        // ✅ Create linked user account for login
        $user = \App\Models\User::create([
            'name'     => $validated['full_name'],
            'email'    => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role'     => 'technician',
        ]);

        // Assign user_id to technician record
        $validated['user_id'] = $user->id;

        // Remove password before saving technician (not needed in this table)
        unset($validated['password']);

        $technician = Technician::create($validated);

        // If geocoding failed during creation, try again
        if ((!$technician->latitude || !$technician->longitude) && !empty($technician->address)) {
            Log::info("Retrying geocoding for newly created technician: {$technician->id}");
            $technician->geocodeAddress();
        }

        return redirect()
            ->route('technicians.index')
            ->with('success', 'Technician account created successfully with login credentials!');
    }

    /** 🔹 Show technician details */
    public function show(Technician $technician)
    {
        $technician->load(['serviceRequests.customer.user']);

        $technician->serviceRequests->each(function ($sr) {
            $registered = $sr->customer?->user?->name;
            $walkIn = $sr->customer_name;

            if ($registered && $walkIn) {
                $sr->display_customer_name = "{$registered} ({$walkIn})";
            } elseif ($registered) {
                $sr->display_customer_name = $registered;
            } elseif ($walkIn) {
                $sr->display_customer_name = "{$walkIn} (Walk-in)";
            } else {
                $sr->display_customer_name = 'Unknown Customer';
            }
        });

        if (!Schema::hasColumn('service_requests', 'rating')) {
            $technician->serviceRequests->each(function ($sr) {
                $sr->rating = null;
            });
        }

        $technician->jobs_completed = $technician->serviceRequests->where('status', 'completed')->count();
        $technician->jobs_pending = $technician->serviceRequests->where('status', 'pending')->count();
        $technician->average_rating = round(
            $technician->serviceRequests->whereNotNull('rating')->avg('rating') ?? 0,
            1
        );

        return view('technicians.show', compact('technician'));
    }

    /** 🔹 Edit technician */
    public function edit(Technician $technician)
    {
        return view('technicians.edit', compact('technician'));
    }

    /** 🔹 Update technician */
    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'full_name'        => 'required|string|max:255',
            'email'            => 'required|email|unique:technicians,email,' . $technician->id,
            'phone'            => 'nullable|string|max:20',
            'address'          => 'nullable|string|max:255',
            'service_area'     => 'nullable|string|max:255',
            'date_hire'        => 'nullable|date',
            'specialization'   => 'nullable|string|max:255',
            'emergency_name'   => 'nullable|string|max:255',
            'emergency_phone'  => 'nullable|string|max:20',
            'status'           => 'required|in:active,inactive,suspended',
            'profile_picture'  => 'nullable|image|max:2048',
        ]);

        // Profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('technicians', 'public');

            if ($technician->profile_picture && $technician->profile_picture !== 'images/default-avatar.png') {
                Storage::disk('public')->delete($technician->profile_picture);
            }

            $validated['profile_picture'] = $path;
        }

        $technician->update($validated);

        // If address was updated and geocoding might be needed
        if ($technician->wasChanged('address') && !empty($technician->address)) {
            Log::info("Address changed, re-geocoding for technician: {$technician->id}");
            $technician->geocodeAddress();
        }

        return redirect()
            ->route('technicians.index')
            ->with('success', 'Technician updated successfully!');
    }

    /** 🔹 Technician coordinates (for map API) */
    public function location(Technician $technician)
    {
        return response()->json([
            'latitude' => $technician->latitude ?? 12.8797,
            'longitude' => $technician->longitude ?? 121.7740,
        ]);
    }

    /** 🔹 Technician's assigned jobs */
    public function myJobs()
    {
        $technician = Auth::user()->technician ?? null;

        if (!$technician) {
            return back()->withErrors(['error' => 'No technician profile found for this account.']);
        }

        $jobs = ServiceRequest::where('technician_id', $technician->id)
            ->with('photos')
            ->latest()
            ->get();

        return view('technicians.my_jobs', compact('jobs'));
    }

    /** 🔹 Update technician's live location and broadcast */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        // Get logged-in technician
        $technician = Auth::user()?->technician;

        if (!$technician) {
            return response()->json([
                'success' => false,
                'message' => 'Technician profile not found for this account.',
            ], 404);
        }

        // Update database
        $technician->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Broadcast event for real-time updates
        try {
            broadcast(new TechnicianLocationUpdated($technician))->toOthers();
        } catch (\Throwable $e) {
            Log::error("Broadcast failed for Technician #{$technician->id}: {$e->getMessage()}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Technician location updated successfully!',
            'data' => [
                'id' => $technician->id,
                'full_name' => $technician->full_name,
                'latitude' => $technician->latitude,
                'longitude' => $technician->longitude,
            ],
        ]);
    }

    /** 🔹 Geocode all technicians (for fixing existing records) */
    public function geocodeAll()
    {
        $technicians = Technician::whereNull('latitude')
            ->orWhereNull('longitude')
            ->orWhere('latitude', 0)
            ->orWhere('longitude', 0)
            ->get();

        $successCount = 0;
        $failCount = 0;

        foreach ($technicians as $technician) {
            if ($technician->geocodeAddress()) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        return redirect()
            ->route('technicians.index')
            ->with('success', "Geocoding completed: {$successCount} successful, {$failCount} failed.");
    }
}