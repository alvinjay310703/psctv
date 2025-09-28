<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    // Front-end demo only: returns the Blade view
    public function index()
    {
        return view('announcements.index');
    }
}
