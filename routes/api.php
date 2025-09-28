<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

// Register customer
Route::post('/customer-signup', [CustomerController::class, 'apiRegister']);

// Login customer
Route::post('/customer-login', [CustomerController::class, 'apiLogin']);

// Get all customers
Route::get('/customers', [CustomerController::class, 'apiList']);