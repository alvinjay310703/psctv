<?php

use Illuminate\Support\Facades\Broadcast;

// Private user channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});



use App\Models\ServiceRequest;

Broadcast::channel('service-request.{id}', function ($user, $id) {
    $sr = ServiceRequest::with(['customer.user','technician.user'])->find($id);
    if (!$sr) return false;

    // Adjust conditions to your auth model
    // Allow admin, the customer user, or the technician user
    if ($user->role === 'admin') return true;

    // If you store customer user id in relation:
    if ($sr->creator && $user->id === $sr->creator->id) return true;

    if ($sr->technician && $sr->technician->user_id && $user->id === $sr->technician->user_id) return true;

    return false;
});
