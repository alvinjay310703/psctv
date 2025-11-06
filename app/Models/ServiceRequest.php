<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\TechnicianAssigned;
use App\Notifications\ServiceCompleted;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'customer_name',//walkin
        'phone',
        'email',
        'address',
        'latitude',
        'longitude',
        'service_type',
        'notes',
        'status',
        'technician_id',
        'assigned_at',
        'started_at',
        'completed_at',
        'report',
        'rating',
        'rated_at',
        'created_by',
    ];

    protected $casts = [
        'assigned_at'  => 'datetime',
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
        'latitude'     => 'decimal:7',
        'longitude'    => 'decimal:7',
    ];

    // Always eager-load technician for quick display
    protected $with = ['technician'];

    // Append computed attributes when converting to array/json
    protected $appends = ['progress_percent', 'display_customer_name'];

    /* ------------------------------------------------------------
     | Relationships
     |------------------------------------------------------------ */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }

    public function photos()
    {
        return $this->hasMany(ServiceRequestPhoto::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ------------------------------------------------------------
     | Assignment Logic
     |------------------------------------------------------------ */
    public function assignTo(int $technicianId, ?int $assignerId = null): void
    {
        // Prevent duplicate assignment
        if ($this->technician_id === $technicianId && $this->status === 'assigned') {
            return;
        }

        $this->update([
            'technician_id' => $technicianId,
            'status'        => 'assigned',
            'assigned_at'   => now(),
        ]);

        // Notify technician
        try {
            $technician = $this->technician;
            if ($technician) {
                $technician->notify(new TechnicianAssigned($this, Auth::user()));
            }
        } catch (\Throwable $e) {
            Log::error("❌ Failed to notify Technician #{$technicianId}: " . $e->getMessage());
        }
    }

    /* ------------------------------------------------------------
     | Status Transitions
     |------------------------------------------------------------ */
    public function startWork(?int $userId = null): void
    {
        $this->update([
            'status'     => 'in-progress',
            'started_at' => now(),
        ]);
    }

    public function completeWork(?string $report = null, ?int $userId = null): void
    {
        $this->update([
            'status'       => 'completed',
            'report'       => $report,
            'completed_at' => now(),
        ]);

        // Trigger completion notifications
        $this->notifyCompletion();
    }

    /* ------------------------------------------------------------
     | Notification Methods
     |------------------------------------------------------------ */
    
    /**
     * Notify all relevant parties when service is completed
     */
    public function notifyCompletion(): int
    {
        Log::info("🔔 notifyCompletion() CALLED for service request #{$this->id}", [
            'service_request_id' => $this->id,
            'current_status' => $this->status,
            'completed_at' => $this->completed_at,
            'has_customer' => !is_null($this->customer),
            'has_technician' => !is_null($this->technician),
            'customer_user_exists' => $this->customer?->user ? true : false,
            'technician_user_exists' => $this->technician?->user ? true : false
        ]);

        $this->load(['technician.user', 'customer.user']);
        
        $adminsNotified = 0;

        // Notify customer
        if ($this->customer?->user) {
            try {
                Log::info("👤 Attempting to notify customer #{$this->customer->user->id}");
                $this->customer->user->notify(new ServiceCompleted($this));
                Log::info("✅ Successfully notified customer #{$this->customer->user->id} about completion of request #{$this->id}");
            } catch (\Throwable $e) {
                Log::error("❌ Failed to notify customer for request #{$this->id}: " . $e->getMessage());
            }
        } else {
            Log::warning("⚠️ No customer user found for request #{$this->id}. Customer: " . ($this->customer ? 'exists but no user' : 'null'));
        }

        // Notify technician
        if ($this->technician?->user) {
            try {
                Log::info("👤 Attempting to notify technician #{$this->technician->user->id}");
                $this->technician->user->notify(new ServiceCompleted($this));
                Log::info("✅ Successfully notified technician #{$this->technician->user->id} about completion of request #{$this->id}");
            } catch (\Throwable $e) {
                Log::error("❌ Failed to notify technician for request #{$this->id}: " . $e->getMessage());
            }
        } else {
            Log::warning("⚠️ No technician user found for request #{$this->id}. Technician: " . ($this->technician ? 'exists but no user' : 'null'));
        }

        // Notify all admins and staff
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        
        Log::info("👥 Admin notification - Found {$admins->count()} admin/staff users");
        
        if ($admins->count() > 0) {
            try {
                Log::info("🔔 Attempting to notify {$admins->count()} admin/staff users");
                
                // 🐛 DEBUG: List admin details
                foreach ($admins as $admin) {
                    Log::info("   👤 Admin found: #{$admin->id} - {$admin->name} ({$admin->role}) - Email: {$admin->email}");
                }
                
                \Illuminate\Support\Facades\Notification::send($admins, new ServiceCompleted($this));
                $adminsNotified = $admins->count();
                Log::info("✅ Successfully notified {$adminsNotified} admin/staff users about completion of request #{$this->id}");
                
            } catch (\Throwable $e) {
                Log::error("❌ Failed to notify admins for request #{$this->id}: " . $e->getMessage());
                Log::error("❌ Exception details: " . $e->getFile() . ":" . $e->getLine() . " - " . $e->getTraceAsString());
            }
        } else {
            Log::warning("⚠️ No admin/staff users found to notify for request #{$this->id}");
            // 🐛 DEBUG: Check what users exist
            $allUsers = User::all();
            Log::info("👥 All users in system: " . $allUsers->count());
            foreach ($allUsers as $user) {
                Log::info("   👤 User: #{$user->id} - {$user->name} ({$user->role}) - Email: {$user->email}");
            }
        }

        Log::info("🔔 notifyCompletion() COMPLETED for request #{$this->id} - Admins notified: {$adminsNotified}");

        return $adminsNotified;
    }

    /**
     * Manual method to trigger completion notifications (useful for testing)
     */
    public function triggerCompletionNotifications(): array
    {
        Log::info("🧪 MANUAL triggerCompletionNotifications() called for request #{$this->id}");

        $results = [
            'customer_notified' => false,
            'technician_notified' => false,
            'admins_notified' => 0,
            'total_notifications' => 0
        ];

        $this->load(['technician.user', 'customer.user']);
        $notification = new ServiceCompleted($this);

        // Notify customer
        if ($this->customer?->user) {
            try {
                $this->customer->user->notify($notification);
                $results['customer_notified'] = true;
                Log::info("🔔 Manual: Notified customer #{$this->customer->user->id} about completion of request #{$this->id}");
            } catch (\Throwable $e) {
                Log::error("❌ Manual: Failed to notify customer for request #{$this->id}: " . $e->getMessage());
            }
        }

        // Notify technician
        if ($this->technician?->user) {
            try {
                $this->technician->user->notify($notification);
                $results['technician_notified'] = true;
                Log::info("🔔 Manual: Notified technician #{$this->technician->user->id} about completion of request #{$this->id}");
            } catch (\Throwable $e) {
                Log::error("❌ Manual: Failed to notify technician for request #{$this->id}: " . $e->getMessage());
            }
        }

        // Notify all admins and staff
        $admins = User::whereIn('role', ['admin', 'staff'])->get();
        
        if ($admins->count() > 0) {
            try {
                \Illuminate\Support\Facades\Notification::send($admins, $notification);
                $results['admins_notified'] = $admins->count();
                Log::info("🔔 Manual: Notified {$results['admins_notified']} admin/staff users about completion of request #{$this->id}");
            } catch (\Throwable $e) {
                Log::error("❌ Manual: Failed to notify admins for request #{$this->id}: " . $e->getMessage());
            }
        }

        $results['total_notifications'] = ($results['customer_notified'] ? 1 : 0) + 
                                         ($results['technician_notified'] ? 1 : 0) + 
                                         $results['admins_notified'];

        Log::info("🧪 MANUAL TEST RESULTS for request #{$this->id}: " . json_encode($results));

        return $results;
    }

    // ... rest of your existing model methods remain the same ...
    /* ------------------------------------------------------------
     | Scopes
     |------------------------------------------------------------ */
    
    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for active requests (assigned or in progress)
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['assigned', 'in-progress']);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for requests assigned to specific technician
     */
    public function scopeAssignedTo($query, $technicianId)
    {
        return $query->where('technician_id', $technicianId);
    }

    /* ------------------------------------------------------------
     | UI Helpers
     |------------------------------------------------------------ */
    public function getProgressPercentAttribute(): int
    {
        return match ($this->status) {
            'pending'     => 0,
            'assigned'    => 33,
            'in-progress' => 66,
            'completed'   => 100,
            default       => 0,
        };
    }

    /**
     * Get status with badge color
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending' => ['color' => 'gray', 'text' => 'Pending'],
            'assigned' => ['color' => 'blue', 'text' => 'Assigned'],
            'in-progress' => ['color' => 'yellow', 'text' => 'In Progress'],
            'completed' => ['color' => 'green', 'text' => 'Completed'],
            'cancelled' => ['color' => 'red', 'text' => 'Cancelled'],
            default => ['color' => 'gray', 'text' => $this->status],
        };
    }

    /**
     * Check if request can be completed
     */
    public function canBeCompleted(): bool
    {
        return in_array($this->status, ['assigned', 'in-progress']);
    }

    /**
     * Check if request is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /* ------------------------------------------------------------
     | Computed Helper for Displaying Customer Name
     |------------------------------------------------------------ */
    public function getDisplayCustomerNameAttribute(): string
    {
        // 1️⃣ If linked to registered customer
        if ($this->customer && $this->customer->user) {
            return $this->customer->user->name;
        }

        // 2️⃣ If walk-in customer name is provided
        if (!empty($this->customer_name)) {
            return "{$this->customer_name} (Walk-in)";
        }

        // 3️⃣ If no customer linked but it's a walk-in (customer_id null)
        if (!$this->customer_id) {
            return 'Walk-in Customer';
        }

        // 4️⃣ Otherwise fallback
        return '— No Customer Linked —';
    }

    /**
     * Get formatted request ID
     */
    public function getFormattedIdAttribute(): string
    {
        return 'REQ-' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    /* ------------------------------------------------------------
     | Address Geocoding (OpenStreetMap Nominatim)
     |------------------------------------------------------------ */
    public function geocodeAddress(): void
    {
        if (empty($this->address)) {
            return;
        }

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'PCTVS-System/1.0 (admin@pctvs.local)',
                    'Accept-Language' => 'en',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $this->address,
                    'format' => 'json',
                    'limit' => 1,
                ]);

            if ($response->successful() && !empty($response->json())) {
                $data = $response->json()[0];

                $this->latitude = (float) $data['lat'];
                $this->longitude = (float) $data['lon'];
                $this->save();

                Log::info("✅ Geocoded '{$this->address}' → [{$this->latitude}, {$this->longitude}]");
            } else {
                Log::warning("⚠️ Geocoding failed for '{$this->address}'. Response: " . $response->body());
            }
        } catch (\Throwable $e) {
            Log::error("❌ Geocoding exception for '{$this->address}': " . $e->getMessage());
        }
    }

    /**
     * Calculate distance to coordinates (in kilometers)
     */
    public function distanceTo($latitude, $longitude): float
    {
        if (!$this->latitude || !$this->longitude) {
            return 0;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }
}