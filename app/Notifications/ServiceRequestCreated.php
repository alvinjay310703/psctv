<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ServiceRequest;
use App\Models\User;

class ServiceRequestCreated extends Notification
{
    use Queueable;

    protected $serviceRequest;
    protected $customerName;

    public function __construct(ServiceRequest $serviceRequest, string $customerName = null)
    {
        $this->serviceRequest = $serviceRequest;
        $this->customerName = $customerName ?? $serviceRequest->customer_name ?? 'Walk-in Customer';
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Service Request Submitted')
            ->line("A new service request (#{$this->serviceRequest->id}) has been submitted by {$this->customerName}.")
            ->action('View Request', url("/admin/service_requests/{$this->serviceRequest->id}"))
            ->line('Please check the admin dashboard for details.');
    }

    public function toArray($notifiable)
    {
        return [
            'service_request_id' => $this->serviceRequest->id,
            'customer_name' => $this->customerName,
            'status' => $this->serviceRequest->status,
        ];
    }
}
