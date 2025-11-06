<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Display a listing of the packages.
     */
    public function index(Request $request)
    {
        $packages = Package::orderBy('created_at', 'desc')->paginate(10);
        return view('packages.index', compact('packages'));
    }

    /**
     * Show the form for creating a new package.
     */
    public function create()
    {
        return view('packages.create');
    }

    /**
     * Store a newly created package in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'speed_mbps'    => 'nullable|integer',
            'channels'      => 'nullable|integer',
            'price'         => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'is_active'     => 'boolean',
        ]);

        Package::create($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Package created successfully.');
    }

    /**
     * Display the specified package.
     */
    public function show(Package $package)
    {
        return view('packages.show', compact('package'));
    }

    /**
     * Show the form for editing the specified package.
     */
    public function edit(Package $package)
    {
        return view('packages.edit', compact('package'));
    }

    /**
     * Update the specified package in storage.
     */
    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'speed_mbps'    => 'nullable|integer',
            'channels'      => 'nullable|integer',
            'price'         => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,yearly',
            'is_active'     => 'boolean',
        ]);

        $package->update($validated);

        return redirect()->route('packages.index')
            ->with('success', 'Package updated successfully.');
    }

    /**
     * Remove the specified package from storage.
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return redirect()->route('packages.index')
            ->with('success', 'Package deleted successfully.');
    }
}
