<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ServiceRequestController extends Controller
{
    protected $technicians;

    public function __construct()
    {
        // Demo technicians with active job count
        $this->technicians = collect([
            (object)['id' => 1, 'name' => 'John Doe', 'active_jobs' => 2],
            (object)['id' => 2, 'name' => 'Jane Smith', 'active_jobs' => 1],
            (object)['id' => 3, 'name' => 'Mark Reyes', 'active_jobs' => 0],
        ]);
    }

    /**
     * List all service requests (with search, filter, pagination).
     */
    public function index(Request $request)
    {
        // Demo dataset
        $data = collect([
            [
                'id' => 1001,
                'customer_name' => 'Maria Santos',
                'service_type' => 'Installation',
                'status' => 'pending',
                'technician_id' => null,
                'created_at' => now(),
            ],
            [
                'id' => 1002,
                'customer_name' => 'Juan Dela Cruz',
                'service_type' => 'Repair',
                'status' => 'assigned',
                'technician_id' => 1,
                'created_at' => now()->subDay(),
            ],
            [
                'id' => 1003,
                'customer_name' => 'Alvin Olvido',
                'service_type' => 'Upgrade',
                'status' => 'in-progress',
                'technician_id' => 2,
                'created_at' => now()->subDays(2),
            ],
            [
                'id' => 1004,
                'customer_name' => 'Carla Reyes',
                'service_type' => 'Maintenance',
                'status' => 'completed',
                'technician_id' => 3,
                'created_at' => now()->subDays(3),
            ],
        ]);

        // 🔍 Search
        if ($request->filled('search')) {
            $q = strtolower($request->search);
            $data = $data->filter(fn($row) =>
                str_contains(strtolower($row['customer_name']), $q) ||
                str_contains(strtolower("REQ-{$row['id']}"), $q)
            );
        }

        // 🔍 Filter by status
        if ($request->filled('status')) {
            $status = strtolower($request->status);
            $data = $data->filter(fn($row) => $row['status'] === $status);
        }

        // Pagination
        $page    = max(1, (int) $request->get('page', 1));
        $perPage = 10;
        $items   = $data->forPage($page, $perPage)->values();
        $paginator = new LengthAwarePaginator($items, $data->count(), $perPage, $page, [
            'path'  => $request->url(),
            'query' => $request->query(),
        ]);

        // Cast to objects for blade
        $serviceRequests = $paginator->through(function ($row) {
            return (object) array_merge($row, [
                'technician' => $this->technicians->firstWhere('id', $row['technician_id']),
                'created_at' => $row['created_at'],
            ]);
        });

        return view('service_requests.index', [
            'serviceRequests' => $serviceRequests,
            'technicians' => $this->technicians,
        ]);
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('service_requests.create');
    }

    /**
     * Store new request (demo only).
     */
    public function store(Request $request)
    {
        // TODO: Save to DB
        return redirect()->route('service_requests.index')->with('success', 'Service request created (demo).');
    }

    /**
     * Show details for one request.
     */
   public function show($id)
{
    // Use the same dataset as index()
    $data = collect([
        [
            'id' => 1001,
            'customer_name' => 'Maria Santos',
            'service_type' => 'Installation',
            'status' => 'pending',
            'technician_id' => null,
            'created_at' => now()->subDays(3),
            'assigned_at' => null,
            'started_at' => null,
            'completed_at' => null,
            'phone' => '+63 912 345 6789',
            'address' => '123 Main St, Manila',
            'notes' => 'Requested urgent installation',
            'attachments' => [],
        ],
        [
            'id' => 1002,
            'customer_name' => 'Juan Dela Cruz',
            'service_type' => 'Repair',
            'status' => 'assigned',
            'technician_id' => 1,
            'created_at' => now()->subDays(4),
            'assigned_at' => now()->subDays(3),
            'started_at' => null,
            'completed_at' => null,
            'phone' => '+63 917 654 3210',
            'address' => '456 Rizal Ave, Manila',
            'notes' => 'Cable issue reported',
            'attachments' => [],
        ],
        [
            'id' => 1003,
            'customer_name' => 'Alvin Olvido',
            'service_type' => 'Upgrade',
            'status' => 'in-progress',
            'technician_id' => 2,
            'created_at' => now()->subDays(5),
            'assigned_at' => now()->subDays(4),
            'started_at' => now()->subDay(),
            'completed_at' => null,
            'phone' => '+63 918 222 1111',
            'address' => '789 Lopez St, Makati',
            'notes' => 'Upgrade requested',
            'attachments' => [],
        ],
        [
            'id' => 1004,
            'customer_name' => 'Carla Reyes',
            'service_type' => 'Maintenance',
            'status' => 'completed',
            'technician_id' => 3,
            'created_at' => now()->subDays(7),
            'assigned_at' => now()->subDays(6),
            'started_at' => now()->subDays(5),
            'completed_at' => now()->subDays(4),
            'phone' => '+63 916 888 9999',
            'address' => '101 Ayala Ave, Makati',
            'notes' => 'Routine maintenance',
            'attachments' => [],
        ],
    ]);

    // Find the record
    $row = $data->firstWhere('id', (int) $id);

    if (!$row) {
        return redirect()
            ->route('service_requests.index')
            ->with('error', 'Service request not found.');
    }

    // Cast to object and attach technician
    $request = (object) array_merge($row, [
        'technician' => $this->technicians->firstWhere('id', $row['technician_id']),
    ]);

    return view('service_requests.show', compact('request'));
}


    /**
     * Assign Page.
     */
    public function assignPage($id)
    {
        $request = (object)[
            'id' => $id,
            'customer_name' => 'Demo Customer',
            'service_type' => 'Installation',
            'status' => 'pending',
            'technician_id' => null,
            'phone' => '+63 912 345 6789',
            'address' => '123 Main St, Manila',
            'notes' => 'Customer requested urgent installation',
            'created_at' => now(),
            'assigned_at' => null,
            'started_at' => null,
            'completed_at' => null,
        ];

        return view('service_requests.assign', [
            'request' => $request,
            'technicians' => $this->technicians,
        ]);
    }

    /**
     * Assign technician (demo only).
     */
    public function assignTechnician(Request $request, $id)
    {
        $techId = $request->input('technician_id');

        // TODO: Update DB
        return redirect()->route('service_requests.show', $id)
            ->with('success', "Technician #$techId assigned to request $id (demo).");
    }

    /**
     * Update status (demo only).
     */
    public function updateStatus(Request $request, $id)
    {
        $status = $request->input('status');
        $allowed = ['pending', 'assigned', 'in-progress', 'completed'];

        if (!in_array($status, $allowed)) {
            return redirect()->route('service_requests.show', $id)
                ->with('error', "Invalid status update.");
        }

        // TODO: Save to DB
        return redirect()->route('service_requests.show', $id)
            ->with('success', "Status updated to {$status} (demo).");
    }

    /**
     * Edit form.
     */
    public function edit($id)
    {
        $request = (object)[
            'id' => $id,
            'customer_name' => 'Demo Customer',
            'service_type' => 'Repair',
            'status' => 'in-progress',
            'technician_id' => 2,
            'notes' => 'Replace cable splitter.',
        ];

        return view('service_requests.edit', compact('request'));
    }

    /**
     * Update request (demo).
     */
    public function update(Request $req, $id)
    {
        // TODO: Save updates to DB
        return redirect()->route('service_requests.show', $id)
            ->with('success', 'Request updated (demo).');
    }

    /**
     * Delete request (demo).
     */
    public function destroy($id)
    {
        // TODO: Delete in DB
        return redirect()->route('service_requests.index')
            ->with('success', 'Request deleted (demo).');
    }
}
