<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TechnicianController extends Controller
{
    // List Technicians
    public function index()
    {
        // Mock data (replace with DB later)
        $technicians = [
            [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '09123456789',
                'specialization' => 'Cable Installation',
                'status' => 'Active'
            ],
            [
                'id' => 2,
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '09876543210',
                'specialization' => 'Fiber Repair',
                'status' => 'Inactive'
            ],
        ];

        return view('technicians.list', compact('technicians'));
    }

    // Add Technician form
    public function create()
    {
        return view('technicians.add');
    }

   // ✅ Show technician profile
public function view ($id)
{
    $technician = (object) [
        'id' => $id,
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '09123456789',
        'specialization' => 'Cable Installation',
        'service_area' => 'Quezon City',
        'status' => 'active',
        'date_hire' => '2024-02-01',
        'address' => '123 Main Street, Manila',
        'emergency_name' => 'Jane Doe',
        'emergency_phone' => '09987654321',
        'profile_picture' => '/images/sample-tech.jpg',
    ];

    return view('technicians.show', compact('technician'));
}



    // Edit Technician form
    public function edit($id)
    {
        $technician = [
            'id' => $id,
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'phone' => '09876543210',
            'specialization' => 'Fiber Repair',
            'status' => 'Inactive'
        ];

        return view('technicians.edit', compact('technician'));
    }


}
