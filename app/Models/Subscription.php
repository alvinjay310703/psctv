<?php

// app/Models/Subscription.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'customer_id',
        'package_id',
        'start_date',
        'end_date',
        'status',
        'next_billing_date',
    ];

    protected $dates = [
        'start_date',
        'end_date',
        'next_billing_date',
    ];

    // Auto-update status whenever accessed
    public function getStatusAttribute($value)
    {
        // If already cancelled manually, keep it
        if ($value === 'cancelled') {
            return $value;
        }

        // If expired
        if ($this->end_date && Carbon::parse($this->end_date)->isPast()) {
            if ($value !== 'expired') {
                // Auto-update in DB too
                $this->updateQuietly(['status' => 'expired']);
            }
            return 'expired';
        }

        // If still valid
        return $value;
    }

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}