<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard() {
        return view('staff.dashboard');
    }

    public function requests() {
        return view('staff.requests');
    }

    public function preRegistered() {
        return view('staff.pre_registered');
    }

    public function reports() {
        return view('staff.reports');
    }

    public function announcements() {
        return view('staff.announcements');
    }
}
