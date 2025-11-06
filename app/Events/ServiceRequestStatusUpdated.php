<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ServiceRequest;

class ServiceRequestStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $serviceRequestId;
    public $status;
    public $technician_location;
    public $technician_name;
    public $updated_at;

    public function __construct(ServiceRequest $serviceRequest)
    {
        $this->serviceRequestId = $serviceRequest->id;
        $this->status = $serviceRequest->status;
        $this->technician_name = optional($serviceRequest->technician)->full_name;
        $this->technician_location = [
            'lat' => optional($serviceRequest->technician)->latitude,
            'lon' => optional($serviceRequest->technician)->longitude,
        ];
        $this->updated_at = $serviceRequest->updated_at ? $serviceRequest->updated_at->toDateTimeString() : now()->toDateTimeString();
    }

    // Use private channel for security
    public function broadcastOn()
    {
        return new PrivateChannel('service-request.' . $this->serviceRequestId);
    }

    public function broadcastAs()
    {
        return 'ServiceRequestStatusUpdated';
    }

    // Optional: specify exact payload shape sent to client
    public function broadcastWith()
    {
        return [
            'serviceRequestId' => $this->serviceRequestId,
            'status' => $this->status,
            'technician_name' => $this->technician_name,
            'technician_location' => $this->technician_location,
            'updated_at' => $this->updated_at,
        ];
    }
}
