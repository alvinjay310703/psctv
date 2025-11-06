<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceRequestPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_request_id',
        'path',
        'label',
        'mime',
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
