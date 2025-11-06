<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('staff.reports.index'); // create this blade
    }
}
