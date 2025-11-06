<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
        'next_billing_date',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'next_billing_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatusAttribute($value)
    {
        // Prevent repeated DB writes during reads
        static $checkingStatus = false;

        if ($checkingStatus) {
            return $value;
        }

        if ($value === 'cancelled') {
            return $value;
        }

        // Automatically mark as expired when end_date is past
        if ($this->end_date && $this->end_date->isPast() && $value !== 'expired') {
            $checkingStatus = true; // Prevent recursion
            $this->updateQuietly(['status' => 'expired']);
            $checkingStatus = false;
            return 'expired';
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function generateInvoice()
    {
        if (!$this->package) {
            return null;
        }

        return $this->invoices()->create([
            'subscription_id' => $this->id,
            'customer_id'     => $this->customer_id,
            'customer_name'   => $this->customer->user->name ?? null,
            'invoice_no'      => 'INV-' . str_pad((Invoice::max('id') + 1), 6, '0', STR_PAD_LEFT),
            'amount_due'      => $this->package->price ?? 0,
            'billing_date'    => now(),
            'due_date'        => now()->addDays(7),
            'status'          => 'unpaid',
            'description'     => "Recurring billing for {$this->package->name}",
            'is_recurring'    => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Model Events
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        // ✅ Automatically link user_id to customer.user_id if missing
        static::creating(function ($subscription) {
            if (empty($subscription->user_id) && !empty($subscription->customer_id)) {
                $customer = Customer::find($subscription->customer_id);
                if ($customer) {
                    $subscription->user_id = $customer->user_id;
                }
            }
        });
    }
}
