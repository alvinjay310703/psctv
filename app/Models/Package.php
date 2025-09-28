<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = ['code','name','description','speed_mbps','channels','price','billing_cycle','is_active'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
