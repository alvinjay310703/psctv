@extends('layouts.staff')

@section('title', 'Service Requests')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <x-lucide-clipboard-list class="w-6 h-6 text-blue-600"/> Service Requests
        </h2>
        <a href="{{ route('staff.service_requests.create') }}"
           class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2">
            <x-lucide-plus class="w-4 h-4"/> New Request
        </a>
    </div>

    <!-- Filters -->
    <form method="GET" class="flex flex-wrap gap-3 mb-5 text-sm items-center">
        <input type="text" name="search" placeholder="🔍 Search by name, ID, or service"
               value="{{ request('search') }}"
               class="border border-gray-300 rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-blue-500 focus:outline-none" />

        <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="">Status: All</option>
            <option value="pending" @selected(request('status')=='pending')>Pending</option>
            <option value="assigned" @selected(request('status')=='assigned')>Assigned</option>
            <option value="in-progress" @selected(request('status')=='in-progress')>In Progress</option>
            <option value="completed" @selected(request('status')=='completed')>Completed</option>
        </select>

        <input type="date" name="from" value="{{ request('from') }}" 
               class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        <input type="date" name="to" value="{{ request('to') }}" 
               class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />

        <button type="submit" 
                class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900 transition flex items-center gap-1">
            <x-lucide-filter class="w-4 h-4"/> Apply
        </button>
        <a href="{{ route('staff.service_requests.index') }}" 
           class="px-4 py-2 border rounded-lg hover:bg-gray-100 transition flex items-center gap-1">
            <x-lucide-refresh-cw class="w-4 h-4"/> Reset
        </a>
    </form>

    <!-- Desktop Table (removed Delete column) -->
    <div class="hidden md:block">
        <div class="overflow-x-auto rounded-2xl shadow-lg border border-gray-200">
            <table class="w-full text-sm table-auto border-collapse">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Customer</th>
                        <th class="p-3 text-left">Service</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Technician</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($serviceRequests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 font-semibold">REQ-{{ $req->id }}</td>
                        <td class="p-3">{{ $req->customer_name }}</td>
                        <td class="p-3">{{ $req->service_type }}</td>
                        <td class="p-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                @if($req->status=='pending') bg-yellow-100 text-yellow-700 animate-pulse
                                @elseif($req->status=='assigned') bg-purple-100 text-purple-700 animate-pulse
                                @elseif($req->status=='in-progress') bg-blue-100 text-blue-700 glow-blue
                                @elseif($req->status=='completed') bg-green-100 text-green-700 transition duration-500
                                @endif">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td class="p-3">
                            @if($req->technician)
                                <div>
                                    <p class="font-medium text-gray-800">{{ $req->technician->full_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $req->technician->jobsPending }} active jobs</p>
                                </div>
                            @else
                                <span class="text-gray-400 italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="p-3 text-center flex justify-center gap-2 flex-wrap">
                            <a href="{{ route('staff.service_requests.show', $req->id) }}"
                               class="px-3 py-1 border rounded-lg text-xs text-blue-600 hover:bg-blue-50 flex items-center gap-1">
                                <x-lucide-eye class="w-4 h-4"/> View
                            </a>

                            @if(!$req->technician)
                                <a href="{{ route('staff.service_requests.assignPage', $req->id) }}"
                                   class="px-3 py-1 border rounded-lg text-xs text-purple-600 hover:bg-purple-50 flex items-center gap-1">
                                    <x-lucide-user-plus class="w-4 h-4"/> Assign
                                </a>
                            @elseif(in_array($req->status, ['pending','assigned']))
                                <a href="{{ route('staff.service_requests.assignPage', $req->id) }}"
                                   class="px-3 py-1 border rounded-lg text-xs text-purple-600 hover:bg-purple-50 flex items-center gap-1">
                                    <x-lucide-user-check class="w-4 h-4"/> Reassign
                                </a>
                            @endif

                            @if(in_array($req->status, ['pending']))
                                <form method="POST" action="{{ route('staff.service_requests.destroy', $req->id) }}" class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this service request?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 border rounded-lg text-xs text-red-600 hover:bg-red-50 flex items-center gap-1">
                                        <x-lucide-trash-2 class="w-4 h-4"/> Delete
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-5 text-center text-gray-500">No service requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Cards (removed Delete) -->
    <div class="md:hidden space-y-4">
        @forelse($serviceRequests as $req)
        <div class="bg-white p-4 rounded-2xl shadow-lg border border-gray-200 space-y-2">
            <div class="flex justify-between items-center">
                <span class="font-semibold text-gray-800">REQ-{{ $req->id }}</span>
                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                    @if($req->status=='pending') bg-yellow-100 text-yellow-700 animate-pulse
                    @elseif($req->status=='assigned') bg-purple-100 text-purple-700 animate-pulse
                    @elseif($req->status=='in-progress') bg-blue-100 text-blue-700 glow-blue
                    @elseif($req->status=='completed') bg-green-100 text-green-700 transition duration-500
                    @endif">
                    {{ ucfirst($req->status) }}
                </span>
            </div>
            <p><span class="font-medium">Customer:</span> {{ $req->customer_name }}</p>
            <p><span class="font-medium">Service:</span> {{ $req->service_type }}</p>
            <p><span class="font-medium">Technician:</span> 
                @if($req->technician) {{ $req->technician->full_name }} @else <span class="italic text-gray-400">Unassigned</span> @endif
            </p>

            <div class="flex flex-wrap gap-2 mt-2">
                <a href="{{ route('staff.service_requests.show', $req->id) }}" 
                   class="px-3 py-1 border rounded-lg text-xs text-blue-600 hover:bg-blue-50 flex items-center gap-1">
                    <x-lucide-eye class="w-4 h-4"/> View
                </a>

                @if(!$req->technician)
                    <a href="{{ route('staff.service_requests.assignPage', $req->id) }}" 
                       class="px-3 py-1 border rounded-lg text-xs text-purple-600 hover:bg-purple-50 flex items-center gap-1">
                        <x-lucide-user-plus class="w-4 h-4"/> Assign
                    </a>
                @elseif(in_array($req->status, ['pending','assigned']))
                    <a href="{{ route('staff.service_requests.assignPage', $req->id) }}" 
                       class="px-3 py-1 border rounded-lg text-xs text-purple-600 hover:bg-purple-50 flex items-center gap-1">
                        <x-lucide-user-check class="w-4 h-4"/> Reassign
                    </a>
                @endif
            </div>
        </div>
        @empty
        <p class="text-center text-gray-500">No service requests found.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-4">{{ $serviceRequests->links() }}</div>

</div>

<!-- Glow animation CSS -->
<style>
@keyframes glow {
    0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
    50% { box-shadow: 0 0 15px rgba(59, 130, 246, 0.8); }
}
.glow-blue {
    animation: glow 1.5s infinite;
}
</style>
@endsection