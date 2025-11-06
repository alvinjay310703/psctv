<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\CustomerServiceRequestController;
use App\Http\Controllers\Api\TechnicianAuthController;
use App\Http\Controllers\Api\TechnicianProfileController;
use App\Http\Controllers\Api\TechnicianServiceRequestController;
use App\Http\Controllers\SystemStatusController;
use App\Http\Controllers\Admin\AnnouncementController; // ✅ Add this import

// -----------------------------
// DEBUG & TESTING ROUTES
// -----------------------------
Route::get('/debug/admins', function() {
    $admins = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();
    
    return response()->json([
        'total_admins_staff' => $admins->count(),
        'users' => $admins->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
                'has_notifications' => $user->notifications()->count(),
            ];
        })
    ]);
});

// Test service request completion notification
Route::get('/debug/test-notification/{id}', function($id) {
    $serviceRequest = \App\Models\ServiceRequest::find($id);
    
    if (!$serviceRequest) {
        return response()->json(['status' => 'error', 'message' => 'Service request not found'], 404);
    }

    $results = $serviceRequest->triggerCompletionNotifications();
    
    return response()->json([
        'status' => 'success',
        'message' => 'Test notifications triggered manually',
        'results' => $results,
        'service_request' => [
            'id' => $serviceRequest->id,
            'formatted_id' => $serviceRequest->formatted_id,
            'customer' => $serviceRequest->display_customer_name,
            'status' => $serviceRequest->status,
            'technician_id' => $serviceRequest->technician_id,
        ]
    ]);
});

// -----------------------------
// CUSTOMER AUTH
// -----------------------------
Route::post('/customer/login', [CustomerController::class, 'login']);
Route::post('/customer/logout', [CustomerController::class, 'logout']);

// Fetch customer info by user ID
Route::get('/customer/{id}', [CustomerController::class, 'show']);

// -----------------------------
// CUSTOMER SERVICE REQUESTS
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {
    // Submit a new service request
    Route::post('/customer/service-requests', [CustomerServiceRequestController::class, 'store']);

    // List all requests for this customer
    Route::get('/customer/service-requests', [CustomerServiceRequestController::class, 'index']);

    // Fetch a single service request
    Route::get('/customer/service-requests/{id}', [CustomerServiceRequestController::class, 'show']);
});

// -----------------------------
// SYSTEM STATUS
// -----------------------------
Route::get('/system/status', [SystemStatusController::class, 'status']);

// -----------------------------
// TECHNICIAN AUTH
// -----------------------------
Route::post('/technician/login', [TechnicianAuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/technician/logout', [TechnicianAuthController::class, 'logout']);

// -----------------------------
// TECHNICIAN PROFILE
// -----------------------------
Route::middleware('auth:sanctum')->prefix('technician')->group(function () {
    // Fetch logged-in technician details
    Route::get('/profile', [TechnicianProfileController::class, 'details']);

    // Update logged-in technician profile
    Route::post('/profile/update', [TechnicianProfileController::class, 'update']);

    // Get technician stats
    Route::get('/profile/stats', [TechnicianProfileController::class, 'stats']);

    // Update technician location
    Route::post('/profile/location', [TechnicianProfileController::class, 'updateLocation']);
});

// -----------------------------
// TECHNICIAN SERVICE REQUESTS
// -----------------------------
Route::middleware('auth:sanctum')->prefix('technician')->group(function () {
    // List assigned service requests
    Route::get('/service-requests', [TechnicianServiceRequestController::class, 'index']);

    // JSON endpoint for dashboard map
    Route::get('/service-requests/map-jobs', [TechnicianServiceRequestController::class, 'apiMyJobs']);

    // Fetch a single service request
    Route::get('/service-requests/{id}', [TechnicianServiceRequestController::class, 'show']);

    // Update service request status
    Route::post('/service-requests/{id}/status', [TechnicianServiceRequestController::class, 'updateStatus']);

    // Upload photo for service request
    Route::post('/service-requests/{id}/upload-photo', [TechnicianServiceRequestController::class, 'uploadPhoto']);

    // Delete photo from service request
    Route::delete('/service-requests/{id}/photos/{photoId}', [TechnicianServiceRequestController::class, 'deletePhoto']);

    // Test notification endpoint (for debugging)
    Route::get('/service-requests/{id}/test-notification', [TechnicianServiceRequestController::class, 'testNotification']);

    // ✅ TECHNICIAN ANNOUNCEMENTS
    Route::get('/announcements', [AnnouncementController::class, 'apiIndex']);
});

// -----------------------------
// SERVICE REQUEST STATISTICS
// -----------------------------
Route::middleware('auth:sanctum')->group(function () {
    // Get technician statistics
    Route::get('/technician/statistics', [TechnicianServiceRequestController::class, 'statistics']);
});

// -----------------------------
// HEALTH CHECK
// -----------------------------
Route::get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});

// -----------------------------
// FALLBACK ROUTE
// -----------------------------
Route::fallback(function () {
    return response()->json([
        'status' => 'error',
        'message' => 'Endpoint not found. Please check the API documentation.',
        'available_endpoints' => [
            'auth' => [
                'POST /api/customer/login',
                'POST /api/customer/logout',
                'POST /api/technician/login',
                'POST /api/technician/logout',
            ],
            'customer' => [
                'GET /api/customer/{id}',
                'POST /api/customer/service-requests',
                'GET /api/customer/service-requests',
                'GET /api/customer/service-requests/{id}',
            ],
            'technician' => [
                'GET /api/technician/profile',
                'POST /api/technician/profile/update',
                'GET /api/technician/profile/stats',
                'POST /api/technician/profile/location',
                'GET /api/technician/service-requests',
                'GET /api/technician/service-requests/map-jobs',
                'GET /api/technician/service-requests/{id}',
                'POST /api/technician/service-requests/{id}/status',
                'POST /api/technician/service-requests/{id}/upload-photo',
                'DELETE /api/technician/service-requests/{id}/photos/{photoId}',
                'GET /api/technician/statistics',
                'GET /api/technician/announcements', // ✅ Added
            ],
            'debug' => [
                'GET /api/debug/admins',
                'GET /api/debug/test-notification/{id}',
                'GET /api/technician/service-requests/{id}/test-notification',
            ],
            'system' => [
                'GET /api/system/status',
                'GET /api/health',
            ]
        ]
    ], 404);
});
