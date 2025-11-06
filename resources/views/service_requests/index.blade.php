@extends('layouts.admin')

@section('title', 'Service Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        <!-- Header Section -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="p-3 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 shadow-lg">
                    <x-lucide-clipboard-list class="w-8 h-8 text-white"/>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Service Requests</h1>
                    <p class="text-gray-600 mt-1">Manage and track all service requests efficiently</p>
                </div>
            </div>
            <a href="{{ route('service_requests.create') }}" 
               class="group relative bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white px-6 py-3 rounded-xl shadow-lg text-sm font-semibold transition-all duration-300 flex items-center gap-2 overflow-hidden transform hover:-translate-y-0.5">
                <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                <x-lucide-plus class="w-5 h-5 transition-transform duration-300 group-hover:scale-110"/>
                <span>New Request</span>
            </a>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Requests</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $serviceRequests->total() }}</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <x-lucide-clipboard-list class="w-6 h-6 text-blue-600"/>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pending</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $serviceRequests->where('status', 'pending')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-xl">
                        <x-lucide-clock class="w-6 h-6 text-yellow-600"/>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $serviceRequests->where('status', 'in-progress')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-xl">
                        <x-lucide-settings class="w-6 h-6 text-purple-600"/>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            {{ $serviceRequests->where('status', 'completed')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <x-lucide-check-circle class="w-6 h-6 text-green-600"/>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 transition-all duration-300 hover:shadow-xl">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-lucide-search class="h-4 w-4 text-gray-400"/>
                    </div>
                    <input type="text" name="search" placeholder="Search by name, ID, or service"
                           value="{{ request('search') }}"
                           class="pl-10 w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" />
                </div>

                <select name="status" class="border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md">
                    <option value="">Status: All</option>
                    <option value="pending" @selected(request('status')=='pending')>Pending</option>
                    <option value="assigned" @selected(request('status')=='assigned')>Assigned</option>
                    <option value="in-progress" @selected(request('status')=='in-progress')>In Progress</option>
                    <option value="completed" @selected(request('status')=='completed')>Completed</option>
                </select>

                <input type="date" name="from" value="{{ request('from') }}" 
                       class="border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" />
                <input type="date" name="to" value="{{ request('to') }}" 
                       class="border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm hover:shadow-md" />

                <div class="flex gap-2">
                    <button type="submit" 
                            class="flex-1 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-900 hover:to-black text-white px-4 py-3 rounded-xl shadow transition-all duration-300 flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <x-lucide-filter class="w-4 h-4"/>
                        <span>Apply</span>
                    </button>
                    <a href="{{ route('service_requests.index') }}" 
                       class="px-4 py-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center justify-center transform hover:-translate-y-0.5">
                        <x-lucide-refresh-cw class="w-4 h-4"/>
                    </a>
                </div>
            </form>
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden transition-all duration-300 hover:shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-600 uppercase text-xs border-b border-gray-200">
                        <tr>
                            <th class="p-4 text-left font-semibold">ID</th>
                            <th class="p-4 text-left font-semibold">Customer</th>
                            <th class="p-4 text-left font-semibold">Service</th>
                            <th class="p-4 text-left font-semibold">Status</th>
                            <th class="p-4 text-left font-semibold">Technician</th>
                            <th class="p-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($serviceRequests as $req)
                        <tr class="hover:bg-gray-50 transition-all duration-200 group transform hover:translate-x-1">
                            <td class="p-4 font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                REQ-{{ $req->id }}
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium text-sm">
                                        {{ substr($req->display_customer_name, 0, 1) }}
                                    </div>
                                    <span class="font-medium">{{ $req->display_customer_name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <x-lucide-wrench class="w-4 h-4 text-gray-400"/>
                                    {{ $req->service_type }}
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300 
                                    @if($req->status=='pending') bg-yellow-100 text-yellow-800 shadow-sm
                                    @elseif($req->status=='assigned') bg-purple-100 text-purple-800 shadow-sm
                                    @elseif($req->status=='in-progress') bg-blue-100 text-blue-800 shadow-sm glow-blue
                                    @elseif($req->status=='completed') bg-green-100 text-green-800 shadow-sm
                                    @endif">
                                    @if($req->status=='pending')
                                    <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5 animate-pulse"></span>
                                    @elseif($req->status=='assigned')
                                    <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1.5 animate-pulse"></span>
                                    @elseif($req->status=='in-progress')
                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1.5 animate-pulse"></span>
                                    @elseif($req->status=='completed')
                                    <x-lucide-check class="w-3 h-3 mr-1"/>
                                    @endif
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($req->technician)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center text-purple-600 font-medium text-sm">
                                            {{ substr($req->technician->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $req->technician->full_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $req->technician->jobsPending }} active jobs</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic flex items-center gap-1">
                                        <x-lucide-user-x class="w-4 h-4"/>
                                        Unassigned
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('service_requests.show', $req->id) }}" 
                                       class="p-2 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition-all duration-200 group/action tooltip" data-tooltip="View Details">
                                        <x-lucide-eye class="w-4 h-4"/>
                                    </a>

                                    @if(!$req->technician)
                                        <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                                           class="p-2 rounded-lg text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 group/action tooltip" data-tooltip="Assign Technician">
                                            <x-lucide-user-plus class="w-4 h-4"/>
                                        </a>
                                    @elseif(in_array($req->status, ['pending','assigned']))
                                        <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                                           class="p-2 rounded-lg text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition-all duration-200 group/action tooltip" data-tooltip="Reassign Technician">
                                            <x-lucide-user-check class="w-4 h-4"/>
                                        </a>
                                    @endif

                                    <form action="{{ route('service_requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-red-50 transition-all duration-200 group/action tooltip" data-tooltip="Delete Request">
                                            <x-lucide-trash class="w-4 h-4"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center gap-3">
                                    <x-lucide-clipboard-x class="w-12 h-12 text-gray-300"/>
                                    <p class="text-lg">No service requests found</p>
                                    <a href="{{ route('service_requests.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 mt-2">
                                        <x-lucide-plus class="w-4 h-4"/>
                                        Create your first request
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-4">
            @forelse($serviceRequests as $req)
            <div class="bg-white p-5 rounded-2xl shadow-lg border border-gray-200 transition-all duration-300 hover:shadow-xl transform hover:-translate-y-1">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-medium">
                            {{ substr($req->display_customer_name, 0, 1) }}
                        </div>
                        <div>
                            <span class="font-semibold text-gray-900">REQ-{{ $req->id }}</span>
                            <p class="text-sm text-gray-500">{{ $req->display_customer_name }}</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold 
                        @if($req->status=='pending') bg-yellow-100 text-yellow-800
                        @elseif($req->status=='assigned') bg-purple-100 text-purple-800
                        @elseif($req->status=='in-progress') bg-blue-100 text-blue-800 glow-blue
                        @elseif($req->status=='completed') bg-green-100 text-green-800
                        @endif">
                        @if($req->status=='pending')
                        <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1 animate-pulse"></span>
                        @elseif($req->status=='assigned')
                        <span class="w-1.5 h-1.5 bg-purple-500 rounded-full mr-1 animate-pulse"></span>
                        @elseif($req->status=='in-progress')
                        <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-1 animate-pulse"></span>
                        @elseif($req->status=='completed')
                        <x-lucide-check class="w-3 h-3 mr-0.5"/>
                        @endif
                        {{ ucfirst($req->status) }}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-500">Service Type</p>
                        <p class="font-medium flex items-center gap-1">
                            <x-lucide-wrench class="w-3 h-3 text-gray-400"/>
                            {{ $req->service_type }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Technician</p>
                        <p class="font-medium">
                            @if($req->technician) 
                                <div class="flex items-center gap-1">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center text-purple-600 text-xs font-medium">
                                        {{ substr($req->technician->full_name, 0, 1) }}
                                    </div>
                                    {{ $req->technician->full_name }}
                                </div>
                            @else 
                                <span class="italic text-gray-400 flex items-center gap-1">
                                    <x-lucide-user-x class="w-3 h-3"/>
                                    Unassigned
                                </span> 
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex justify-between pt-4 border-t border-gray-100">
                    <a href="{{ route('service_requests.show', $req->id) }}" 
                       class="px-3 py-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                        <x-lucide-eye class="w-4 h-4"/>
                        View
                    </a>

                    @if(!$req->technician)
                        <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                           class="px-3 py-2 rounded-lg text-purple-600 hover:bg-purple-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <x-lucide-user-plus class="w-4 h-4"/>
                            Assign
                        </a>
                    @elseif(in_array($req->status, ['pending','assigned']))
                        <a href="{{ route('service_requests.assignPage',$req->id) }}" 
                           class="px-3 py-2 rounded-lg text-purple-600 hover:bg-purple-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <x-lucide-user-check class="w-4 h-4"/>
                            Reassign
                        </a>
                    @endif

                    <form action="{{ route('service_requests.destroy', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this request?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200 flex items-center gap-1 text-sm font-medium">
                            <x-lucide-trash class="w-4 h-4"/>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 text-center">
                <x-lucide-clipboard-x class="w-12 h-12 text-gray-300 mx-auto mb-3"/>
                <p class="text-gray-500 mb-4">No service requests found</p>
                <a href="{{ route('service_requests.create') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center gap-1">
                    <x-lucide-plus class="w-4 h-4"/>
                    Create your first request
                </a>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <div class="bg-white rounded-2xl shadow-lg px-4 py-3 border border-gray-100">
                {{ $serviceRequests->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Glow Animation CSS -->
<style>
@keyframes glow {
    0%, 100% { 
        box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); 
    }
    50% { 
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.8), 0 0 20px rgba(59, 130, 246, 0.6); 
    }
}
.glow-blue {
    animation: glow 2s infinite;
}

/* Tooltip styles */
.tooltip {
    position: relative;
}

.tooltip::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
    z-index: 10;
}

.tooltip::after {
    content: '';
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: rgba(0, 0, 0, 0.8);
    opacity: 0;
    visibility: hidden;
    transition: all 0.2s ease;
}

.tooltip:hover::before,
.tooltip:hover::after {
    opacity: 1;
    visibility: visible;
    bottom: calc(100% + 4px);
}

/* Smooth transitions for all elements */
* {
    transition-property: color, background-color, border-color, transform, box-shadow;
    transition-duration: 200ms;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Staggered animation for table rows */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

tbody tr {
    animation: slideInUp 0.5s ease-out;
}
tbody tr:nth-child(1) { animation-delay: 0.05s; }
tbody tr:nth-child(2) { animation-delay: 0.1s; }
tbody tr:nth-child(3) { animation-delay: 0.15s; }
tbody tr:nth-child(4) { animation-delay: 0.2s; }
tbody tr:nth-child(5) { animation-delay: 0.25s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add subtle animation to cards on load
    const cards = document.querySelectorAll('.bg-white');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // Add hover effect to table rows
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(8px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Add loading state to filter button
    const filterForm = document.querySelector('form');
    const applyButton = filterForm.querySelector('button[type="submit"]');
    
    filterForm.addEventListener('submit', function() {
        applyButton.innerHTML = `
            <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
            Applying...
        `;
        applyButton.disabled = true;
    });
});
</script>
@endsection