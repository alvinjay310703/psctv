@extends('layouts.admin')

@section('title', 'Packages')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-8 rounded-2xl shadow-md">
    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">📦 Manage Packages</h2>
        <a href="{{ route('packages.create') }}" 
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow text-sm">
            ➕ Add Package
        </a>
    </div>

    <!-- Flash -->
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-gray-700 text-xs uppercase tracking-wider">
                    <th class="p-3">Code</th>
                    <th class="p-3">Name</th>
                    <th class="p-3">Speed</th>
                    <th class="p-3">Channels</th>
                    <th class="p-3">Price</th>
                    <th class="p-3">Cycle</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($packages as $package)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 font-mono">{{ $package->code }}</td>
                    <td class="p-3 font-medium text-gray-800">{{ $package->name }}</td>
                    <td class="p-3">{{ $package->speed_mbps ? $package->speed_mbps . ' Mbps' : '—' }}</td>
                    <td class="p-3">{{ $package->channels ?? '—' }}</td>
                    <td class="p-3">₱{{ number_format($package->price, 2) }}</td>
                    <td class="p-3 capitalize">{{ $package->billing_cycle }}</td>
                    <td class="p-3">
                        @if($package->is_active)
                            <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-700 rounded">Active</span>
                        @else
                            <span class="px-2 py-1 text-xs font-semibold bg-red-100 text-red-700 rounded">Inactive</span>
                        @endif
                    </td>
                    <td class="p-3 text-right flex justify-end gap-2">
                        <a href="{{ route('packages.show', $package->id) }}" 
                           class="px-3 py-1 text-xs bg-indigo-50 text-indigo-600 rounded hover:bg-indigo-100">
                            View
                        </a>
                        <a href="{{ route('packages.edit', $package->id) }}" 
                           class="px-3 py-1 text-xs bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100">
                            Edit
                        </a>
                        <form action="{{ route('packages.destroy', $package->id) }}" method="POST" 
                              onsubmit="return confirm('Delete this package?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="px-3 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="p-6 text-center text-gray-500">No packages found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($packages->count())
    <div class="flex flex-col md:flex-row justify-between items-center mt-6 text-sm text-gray-600 border-t border-gray-200 pt-4 gap-3">
        <p>
            Showing {{ $packages->firstItem() }}–{{ $packages->lastItem() }} of {{ $packages->total() }} results
        </p>
        <div>
            {{ $packages->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endif

</div>
@endsection
