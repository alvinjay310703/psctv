<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Front-end demo only: returns the Blade view
    public function index()
    {
        return view('reports.index');
    }
}
