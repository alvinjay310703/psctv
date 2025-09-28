<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TechnicianController;
use App\Http\Controllers\AssignJobController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\Api\CustomerApiController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('admin.landing'); // ✅ updated from welcome to landing
})->name('landing');

// Authentication
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| subscriptions
|--------------------------------------------------------------------------
*/

Route::resource('subscriptions', SubscriptionController::class);
Route::post('/subscriptions/{id}/renew', [SubscriptionController::class, 'renew'])
    ->name('subscriptions.renew');



/*
|--------------------------------------------------------------------------
| Staff Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('staff.dashboard');
    Route::get('/requests', [StaffController::class, 'requests'])->name('staff.requests');
    Route::get('/pre-registered', [StaffController::class, 'preRegistered'])->name('staff.preRegistered');
    Route::get('/reports', [StaffController::class, 'reports'])->name('staff.reports');
    Route::get('/announcements', [StaffController::class, 'announcements'])->name('staff.announcements');
});


/*
|--------------------------------------------------------------------------
| Customer Module
|--------------------------------------------------------------------------
*/

// Customer List (custom URL /customers/list)
Route::get('/customers/list', [CustomerController::class, 'index'])->name('customers.list');

// Resource routes (create, store, show, edit, update, destroy)
Route::resource('customers', CustomerController::class);






/*
|--------------------------------------------------------------------------
| User Module
|--------------------------------------------------------------------------
*/
Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');   // List
    Route::get('/add', [UserController::class, 'create'])->name('users.create'); // Add form
    Route::post('/store', [UserController::class, 'store'])->name('users.store'); // Save new user
});
Route::resource('users', UserController::class);


/*
|--------------------------------------------------------------------------
| technician Module
|--------------------------------------------------------------------------
*/


Route::prefix('technicians')->group(function () {
    Route::get('/list', [TechnicianController::class, 'index'])->name('technicians.list');
    Route::get('/add', [TechnicianController::class, 'create'])->name('technicians.add');
    Route::get('/show/{id}', [TechnicianController::class, 'view'])->name('technicians.show');
    Route::get('/edit/{id}', [TechnicianController::class, 'edit'])->name('technicians.edit');

    
});




// Service Requests Resource
Route::resource('service_requests', ServiceRequestController::class);

// Assign technician (GET page + POST action)
Route::get('/service_requests/{id}/assign', [ServiceRequestController::class, 'assignPage'])
    ->name('service_requests.assignPage');

Route::post('/service_requests/{id}/assign', [ServiceRequestController::class, 'assignTechnician'])
    ->name('service_requests.assign');

// Update status
Route::post('/service_requests/{id}/status', [ServiceRequestController::class, 'updateStatus'])
    ->name('service_requests.updateStatus');



/*
|--------------------------------------------------------------------------
| Billingt
|--------------------------------------------------------------------------
*/

Route::prefix('billing')->name('billing.')->group(function () {
    Route::get('/', [BillingController::class, 'index'])->name('index');      
    Route::get('/create', [BillingController::class, 'create'])->name('create'); 
    Route::post('/', [BillingController::class, 'store'])->name('store');        
    Route::get('/{id}', [BillingController::class, 'show'])->name('show');       
    Route::post('/{id}/pay', [BillingController::class, 'markPaid'])->name('pay'); 
    Route::delete('/{id}', [BillingController::class, 'destroy'])->name('delete'); 

    // ✅ Correct receipt route inside prefix
    Route::get('/{id}/receipt', [BillingController::class, 'receipt'])->name('receipt');
});

/*
|--------------------------------------------------------------------------
| Announcement
|--------------------------------------------------------------------------
*/

Route::get('/admin/announcements', [AnnouncementController::class, 'index'])
    ->name('announcements.index')
    ->middleware('auth'); // keep it protected in real app


/*
|--------------------------------------------------------------------------
|reports
|--------------------------------------------------------------------------
*/



Route::get('/admin/reports', [ReportController::class, 'index'])
    ->name('reports.index')
    ->middleware('auth'); // keep it protected in production
