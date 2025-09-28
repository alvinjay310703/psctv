<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'status',
        'address',
        'city',
        'province',
        'zip_code',
        'gender',
        'dob',
        'phone',
        'plan',
        'meta',
    ];

   protected $casts = [
    'start_date' => 'date',
    'end_date' => 'date',
    'next_billing_date' => 'date',
];

    public function getOutstandingBalanceAttribute()
{
    return $this->subscriptions
        ->flatMap->invoices
        ->whereIn('status', ['unpaid', 'overdue'])
        ->sum('amount_due');
}


    public function getTotalPaidAttribute()
{
    return $this->subscriptions
        ->flatMap->invoices
        ->flatMap->payments
        ->sum('amount_paid');
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class);
    }
}
