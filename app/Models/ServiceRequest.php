<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = ['customer_id','subscription_id','type','description','status','requested_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function technicianJobs()
    {
        return $this->hasMany(TechnicianJob::class);
    }
}
