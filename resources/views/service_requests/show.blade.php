@extends('layouts.admin')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 space-y-6">

    <!-- Header & Status -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <a href="{{ route('service_requests.index') }}" 
        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
            ← Back to Requests
        </a>

        <span class="px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-2
            @if($serviceRequest->status=='pending') bg-yellow-100 text-yellow-700
            @elseif($serviceRequest->status=='assigned') bg-purple-100 text-purple-700
            @elseif($serviceRequest->status=='in-progress') bg-blue-100 text-blue-700
            @elseif($serviceRequest->status=='completed') bg-green-100 text-green-700
            @endif">
            <x-lucide-circle class="w-3 h-3"/>
            {{ ucfirst($serviceRequest->status) }}
        </span>
    </div>

    <!-- Overview + Timeline -->
    <div class="bg-white shadow-sm rounded-2xl border p-5 mt-4">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
            <!-- Request Info -->
            <div class="flex-1 text-gray-700 text-sm space-y-1">
                <h1 class="text-lg sm:text-xl font-bold flex items-center gap-2">
                    <x-lucide-clipboard-list class="w-5 h-5 text-blue-600"/>
                    Service Request #REQ-{{ $serviceRequest->id }}
                </h1>
                <p class="flex items-center gap-1"><x-lucide-calendar class="w-4 h-4"/> Created: {{ $serviceRequest->created_at->format('M d, Y h:i A') }}</p>
                @if($serviceRequest->assigned_at)
                <p class="flex items-center gap-1"><x-lucide-user-check class="w-4 h-4"/> Assigned: {{ $serviceRequest->assigned_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($serviceRequest->started_at)
                <p class="flex items-center gap-1"><x-lucide-play class="w-4 h-4"/> Started: {{ $serviceRequest->started_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($serviceRequest->completed_at)
                <p class="flex items-center gap-1"><x-lucide-check class="w-4 h-4"/> Completed: {{ $serviceRequest->completed_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($serviceRequest->creator)
                <p class="flex items-center gap-1"><x-lucide-user class="w-4 h-4"/> Created by: {{ $serviceRequest->creator->name }}</p>
                @endif
            </div>

        <!-- Redesigned Horizontal Timeline (Visual Progress Only - No Dates) -->
<div class="flex-1 lg:max-w-md mt-3 lg:mt-0">
    @php
        $steps = [
            [
                'label' => 'Created',
                'icon' => 'calendar',
                'done' => true,
                'time' => $serviceRequest->created_at,
            ],
            [
                'label' => 'Assigned',
                'icon' => 'user-check',
                'done' => in_array($serviceRequest->status, ['assigned', 'in-progress', 'completed']),
                'time' => $serviceRequest->assigned_at,
            ],
            [
                'label' => 'In Progress',
                'icon' => 'play',
                'done' => in_array($serviceRequest->status, ['in-progress', 'completed']),
                'time' => $serviceRequest->started_at,
            ],
            [
                'label' => 'Completed',
                'icon' => 'check',
                'done' => $serviceRequest->status === 'completed',
                'time' => $serviceRequest->completed_at,
            ],
        ];

        $lastCompletedIndex = -1;
        for ($i = 0; $i < count($steps); $i++) {
            if ($steps[$i]['done']) {
                $lastCompletedIndex = $i;
            } else {
                break;
            }
        }

        $lineWidthPercent = $lastCompletedIndex > 0 ? round(($lastCompletedIndex / (count($steps) - 1)) * 100) : 0;
    @endphp

    <div class="relative">
        <!-- Background Progress Line -->
        <div class="absolute top-6 left-0 right-0 h-1 bg-gray-200 rounded-full"></div>

        <!-- Active Progress Line -->
        @if($lineWidthPercent > 0)
            <div class="absolute top-6 left-0 h-1 bg-blue-600 rounded-full" style="width: {{ $lineWidthPercent }}%;"></div>
        @endif

        <div class="flex items-center justify-between relative z-10">
            @foreach($steps as $index => $step)
                <div class="flex flex-col items-center">
                    <!-- Step Marker -->
                    <div class="relative w-10 h-10 rounded-full flex items-center justify-center shadow-md flex-shrink-0 transition-colors duration-300"
                        style="background-color: {{ $step['done'] ? '#EFF6FF' : '#F9FAFB' }}; border: 3px solid {{ $step['done'] ? '#3B82F6' : '#D1D5DB' }};"
                        title="{{ $step['time'] ? $step['label'].': '.$step['time']->format('M d, Y h:i A') : $step['label'] }}">
                        @if($step['done'])
                            <x-lucide-{{ $step['icon'] }} class="w-5 h-5 text-blue-600" />
                        @else
                            <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                        @endif
                    </div>

                    <!-- Step Label -->
                    <div class="mt-3 text-center">
                        <span class="text-xs font-medium text-gray-700 block">{{ $step['label'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

        </div>
    </div>

    <!-- Customer & Technician -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mt-4">
        <!-- Customer -->
        <div class="lg:col-span-2 bg-white p-5 rounded-2xl shadow-sm border space-y-4">
            <h3 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2">
                <x-lucide-user class="w-4 h-4 text-blue-500"/> Customer Details
            </h3>
            <div class="grid sm:grid-cols-2 gap-3 text-gray-700 text-xs sm:text-sm">
                <p class="flex items-center gap-2">
                    <x-lucide-user class="w-4 h-4 text-gray-400 flex-shrink-0"/>
                    <span class="font-medium">Name:</span> {{ $serviceRequest->customer_name ?? 'N/A' }}
                </p>
                <p class="flex items-center gap-2">
                    <x-lucide-phone class="w-4 h-4 text-gray-400 flex-shrink-0"/>
                    <span class="font-medium">Phone:</span> {{ $serviceRequest->phone ?? 'N/A' }}
                </p>
                <p class="sm:col-span-2 flex items-start gap-2">
                    <x-lucide-map-pin class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"/>
                    <span class="font-medium">Address:</span> {{ $serviceRequest->address ?? 'N/A' }}
                </p>
                <p class="flex items-center gap-2">
                    <x-lucide-wrench class="w-4 h-4 text-gray-400 flex-shrink-0"/>
                    <span class="font-medium">Service:</span> {{ $serviceRequest->service_type ?? 'N/A' }}
                </p>
                <p class="sm:col-span-2 flex items-start gap-2">
                    <x-lucide-file-text class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5"/>
                    <span class="font-medium">Notes:</span> 
                    @if($serviceRequest->notes)
                        {{ $serviceRequest->notes }}
                    @else
                        <span class="text-gray-400 italic">None</span>
                    @endif
                </p>
            </div>
        </div>

        <!-- Technician -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border space-y-4 h-full">
            <h3 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2">
                <x-lucide-user-check class="w-4 h-4 text-purple-600"/> Technician
            </h3>
            @if($serviceRequest->technician)
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <img src="{{ $serviceRequest->technician->profile_picture ?? 'https://via.placeholder.com/48?text=👤' }}" 
                            alt="{{ $serviceRequest->technician->full_name ?? 'Technician' }}" 
                            class="w-12 h-12 rounded-full border-2 border-gray-200 shadow-sm object-cover bg-gray-100"
                            onerror="this.src='https://via.placeholder.com/48?text=👤'">
                    </div>
                    <div class="flex-1 min-w-0 text-xs sm:text-sm">
                        <p class="font-semibold text-gray-900 truncate">{{ $serviceRequest->technician->full_name }}</p>
                        <p class="text-gray-600">{{ $serviceRequest->technician->specialization ?? 'General Technician' }}</p>
                        <p class="text-gray-500">Active Jobs: {{ $serviceRequest->technician->jobs_pending ?? 0 }}</p>
                    </div>
                </div>
                @if($serviceRequest->status !== 'completed')
                    <a href="{{ route('service_requests.assignPage', $serviceRequest->id) }}"
                    class="inline-flex items-center gap-1 mt-3 px-3 py-2 text-xs bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-sm transition-colors duration-200">
                        <x-lucide-refresh-cw class="w-3 h-3"/> Re-assign
                    </a>
                @endif
            @else
                <div class="text-center py-6">
                    <x-lucide-user-x class="w-8 h-8 text-gray-400 mx-auto mb-2"/>
                    <p class="text-gray-500 italic text-sm">No technician assigned yet.</p>
                </div>
                @if($serviceRequest->status !== 'completed')
                    <a href="{{ route('service_requests.assignPage', $serviceRequest->id) }}"
                    class="w-full flex items-center justify-center gap-1 py-2 px-3 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm transition-colors duration-200">
                        <x-lucide-user-plus class="w-3 h-3"/> Assign Technician
                    </a>
                @endif
            @endif
        </div>
    </div>

<!-- Map Section -->
<div class="bg-white rounded-2xl shadow-sm border mb-6 overflow-hidden">
    <h2 class="p-3 font-semibold text-gray-800 flex items-center gap-2 border-b text-sm sm:text-base">
        <x-lucide-map class="w-4 h-4 text-red-600"/> Technician → Customer Route
    </h2>
    <div class="relative p-4">
        <div id="map" class="w-full h-64 sm:h-80 md:h-96" aria-label="Route from technician to customer location"></div>
        <div id="mapLoading" class="absolute inset-0 bg-white/95 flex items-center justify-center z-1001">
            <div class="text-center">
                <div class="w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
                <p class="text-blue-600 font-medium">Loading route...</p>
            </div>
        </div>
        <div id="mapError" class="absolute inset-0 bg-white/95 flex items-center justify-center z-1001 hidden">
            <div class="text-center p-6">
                <x-lucide-alert-triangle class="w-12 h-12 text-red-500 mx-auto mb-3"/>
                <p class="text-red-600 font-medium mb-2">Unable to load route</p>
                <p class="text-gray-600 text-sm mb-4" id="errorMessage"></p>
                <button id="retryRoute" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Retry
                </button>
            </div>
        </div>
    </div>
    
    <!-- Premium Directions Panel -->
    <div class="border-t bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="p-4">
            <h3 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <x-lucide-navigation class="w-4 h-4 text-blue-600"/> Turn-by-Turn Directions
            </h3>
            <div id="directionsPanel" class="space-y-2 max-h-60 overflow-y-auto pr-2">
                <div class="text-center py-8 text-gray-500">
                    <x-lucide-route class="w-8 h-8 mx-auto mb-2 opacity-50"/>
                    <p class="text-sm">Calculating directions...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 border-t bg-gray-50 rounded-b-2xl">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div id="routeInfo" class="font-semibold text-green-600 text-sm mb-2">Route Info: Calculating...</div>
                <div id="routeDetails" class="text-sm text-gray-600 space-y-1 hidden">
                    <div>Distance: <span id="routeDistance">-</span></div>
                    <div>Estimated Time: <span id="routeTime">-</span></div>
                </div>
            </div>
            <div class="flex gap-2">
                <button id="refreshRoute" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow-sm hover:bg-blue-700 transition">Refresh Route</button>
                <button id="fitRouteButton" class="px-4 py-2 bg-green-600 text-white rounded-md shadow-sm hover:bg-green-700 transition">Fit to Route</button>
            </div>
        </div>
    </div>
</div>

    <!-- Completion Details -->
    @if($serviceRequest->status === 'completed')
        <div class="bg-white rounded-2xl shadow-sm border p-5 space-y-4 mb-4">
            <h2 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2">
                <x-lucide-check-circle class="w-4 h-4 text-green-600"/> Completion Details
            </h2>
            
            <div>
                <p class="text-gray-700 text-xs sm:text-sm font-medium flex items-center gap-1 mb-2">
                    <x-lucide-file-text class="w-4 h-4 text-gray-400"/> Report:
                </p>
                @if($serviceRequest->report)
                    <p class="text-gray-600 text-xs sm:text-sm mt-1 whitespace-pre-line bg-gray-50 p-3 rounded-lg border">{{ $serviceRequest->report }}</p>
                @else
                    <div class="flex items-center gap-2 text-gray-500 italic text-sm py-3">
                        <x-lucide-file class="w-4 h-4"/> No report submitted.
                    </div>
                @endif
            </div>

            <div>
                <p class="text-gray-700 text-xs sm:text-sm font-medium flex items-center gap-1 mb-2">
                    <x-lucide-image class="w-4 h-4 text-gray-400"/> Proof Photos:
                </p>
                @if($serviceRequest->photos && $serviceRequest->photos->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @foreach($serviceRequest->photos as $photo)
                            <div class="relative group cursor-pointer">
                                <img src="{{ asset('storage/' . ($photo->path ?? $photo->photo_path)) }}" 
                                    alt="Proof photo {{ $loop->iteration }}" 
                                    class="w-full h-28 sm:h-32 aspect-square object-cover rounded-lg border shadow-sm transition-transform group-hover:scale-105"
                                    loading="lazy"
                                    onclick="openPhotoModal('{{ asset('storage/' . ($photo->path ?? $photo->photo_path)) }}')">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-lucide-zoom-in class="w-5 h-5 text-white"/>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="flex items-center gap-2 text-gray-500 italic text-sm py-3">
                        <x-lucide-image-off class="w-4 h-4"/> No proof photos uploaded.
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Update Status Form -->
    @if($serviceRequest->status !== 'completed')
        <div class="bg-white rounded-2xl shadow-sm border p-5 mt-3">
            <h2 class="text-sm sm:text-base font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <x-lucide-activity class="w-4 h-4 text-blue-600"/> Update Status
            </h2>

            <form action="{{ route('service_requests.updateStatus', $serviceRequest->id) }}" 
                method="POST" enctype="multipart/form-data" class="space-y-4" id="statusForm">
                @csrf
                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ old('status', $serviceRequest->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="assigned" {{ old('status', $serviceRequest->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                        <option value="in-progress" {{ old('status', $serviceRequest->status) == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ old('status', $serviceRequest->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="completionFields" class="space-y-3 pt-3 border-t border-gray-100" style="display:none;">
                    <div>
                        <label for="report" class="block text-xs font-medium text-gray-700 mb-1">Completion Report <span class="text-red-500">*</span></label>
                        <textarea name="report" id="report" rows="4" placeholder="Describe the work completed, issues resolved, etc..." 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('report') border-red-500 @enderror resize-vertical"
                                required>{{ old('report', $serviceRequest->report) }}</textarea>
                        @error('report')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="photos" class="block text-xs font-medium text-gray-700 mb-1">Proof Photos (Optional, multiple allowed)</label>
                        <input type="file" name="photos[]" id="photos" multiple accept="image/*" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('photos') border-red-500 @enderror file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        @error('photos')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Upload images to verify completion (JPEG, PNG up to 5MB each).</p>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm text-sm font-medium transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <x-lucide-save class="w-4 h-4"/> Save Changes
                    </button>
                </div>
            </form>
        </div>
    @endif

</div>

<!-- Leaflet and Routing Machine -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<!-- Pusher and Echo scripts -->
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="{{ asset('js/echo.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('Script loaded and initializing map...');

    const status = document.getElementById('status');
    const completionFields = document.getElementById('completionFields');
    if (status) {
        const toggle = () => completionFields.style.display = status.value === 'completed' ? 'block' : 'none';
        toggle();
        status.addEventListener('change', toggle);
    }

    window.openPhotoModal = (src) => window.open(src, '_blank', 'noopener,noreferrer');

    // Coordinates
    let custLat = parseFloat(@json($serviceRequest->latitude)) || 12.8797;
    let custLon = parseFloat(@json($serviceRequest->longitude)) || 121.7740;
    let techLat = parseFloat(@json(optional($serviceRequest->technician)->latitude)) || null;
    let techLon = parseFloat(@json(optional($serviceRequest->technician)->longitude)) || null;

    const customerName = @json(optional($serviceRequest->customer)->user->name ?? 'Customer');
    const technicianName = @json(optional($serviceRequest->technician)->full_name ?? 'Technician');
    const customerAddress = @json($serviceRequest->address ?? 'Unknown Location');
    const technicianAddress = @json(optional($serviceRequest->technician)->address ?? 'Technician Location');
    const technicianId = @json(optional($serviceRequest->technician)->id);

    // Validate coordinates
    if (isNaN(custLat) || isNaN(custLon)) {
        console.log('Using default customer coordinates: [12.8797, 121.7740]');
        custLat = 12.8797;
        custLon = 121.7740;
    }
    
    // If technician has no coordinates, use a default location
    if (!techLat || !techLon || isNaN(techLat) || isNaN(techLon)) {
        console.log('No technician coordinates; using default location 10km away');
        techLat = custLat + 0.09; // ~10km
        techLon = custLon + 0.09; // ~10km
    }

    let map = null;
    let routeControl = null;
    let customerMarker = null;
    let technicianMarker = null;
    let routeCalculationTimeout = null;

    function getDirectionIcon(instruction) {
        const lowerInstruction = instruction.toLowerCase();
        
        if (lowerInstruction.includes('left')) {
            return `<div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center border border-blue-300">
                <svg class="w-4 h-4 text-blue-600 transform -rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </div>`;
        } else if (lowerInstruction.includes('right')) {
            return `<div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center border border-blue-300">
                <svg class="w-4 h-4 text-blue-600 transform rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>`;
        } else if (lowerInstruction.includes('roundabout') || lowerInstruction.includes('circle')) {
            return `<div class="w-6 h-6 bg-purple-100 rounded-full flex items-center justify-center border border-purple-300">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>`;
        } else if (lowerInstruction.includes('arrive') || lowerInstruction.includes('destination')) {
            return `<div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center border border-green-300">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>`;
        } else if (lowerInstruction.includes('uturn') || lowerInstruction.includes('u-turn')) {
            return `<div class="w-6 h-6 bg-orange-100 rounded-full flex items-center justify-center border border-orange-300">
                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>`;
        } else {
            return `<div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center border border-gray-300">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </div>`;
        }
    }

    function formatDistance(meters) {
        if (meters < 1000) {
            return `${Math.round(meters)}m`;
        } else {
            return `${(meters / 1000).toFixed(1)}km`;
        }
    }

    function updateDirectionsPanel(instructions) {
        const directionsPanel = document.getElementById('directionsPanel');
        
        if (!instructions || instructions.length === 0) {
            directionsPanel.innerHTML = `
                <div class="text-center py-4 text-gray-500">
                    <x-lucide-route class="w-6 h-6 mx-auto mb-2 opacity-50"/>
                    <p class="text-sm">No turn-by-turn directions available</p>
                </div>
            `;
            return;
        }

        let directionsHTML = '';
        
        instructions.forEach((instruction, index) => {
            const distance = formatDistance(instruction.distance);
            const icon = getDirectionIcon(instruction.text);
            
            directionsHTML += `
                <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex-shrink-0">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900">${instruction.text}</div>
                        <div class="text-xs text-gray-500 mt-1">${distance}</div>
                    </div>
                    <div class="flex-shrink-0 text-sm font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded">
                        ${distance}
                    </div>
                </div>
            `;
        });

        directionsPanel.innerHTML = directionsHTML;
    }

    function initMap() {
        try {
            // Hide loading and error
            document.getElementById('mapLoading').style.display = 'none';
            document.getElementById('mapError').style.display = 'none';

            // Calculate center point
            const centerLat = (custLat + techLat) / 2;
            const centerLon = (custLon + techLon) / 2;
            
            // Create map with proper z-index
            map = L.map('map', { 
                zoomControl: false,
                zoomSnap: 0.5,
                zoomDelta: 0.5
            }).setView([centerLat, centerLon], 12);
            
            L.control.zoom({ 
                position: 'topright'
            }).addTo(map);
            
            L.control.scale({
                imperial: false,
                metric: true
            }).addTo(map);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Create custom icons
            const customerIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div style="background: #ef4444; padding: 8px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 010-5 2.5 2.5 0 010 5z"/>
                        </svg>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });

            const technicianIcon = L.divIcon({
                className: 'custom-marker',
                html: `
                    <div style="background: #3b82f6; padding: 8px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3); display: flex; align-items: center; justify-content: center;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="white">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                `,
                iconSize: [40, 40],
                iconAnchor: [20, 40]
            });

            // Add markers
            customerMarker = L.marker([custLat, custLon], { icon: customerIcon })
                .addTo(map)
                .bindPopup(`<b>Customer Location</b><br>${customerName}<br>${customerAddress}`);

            technicianMarker = L.marker([techLat, techLon], { icon: technicianIcon })
                .addTo(map)
                .bindPopup(`<b>Technician Location</b><br>${technicianName}<br>${technicianAddress}`);

            // Show initial bounds
            const group = new L.featureGroup([customerMarker, technicianMarker]);
            map.fitBounds(group.getBounds(), { padding: [20, 20] });

            // Calculate route with timeout
            calculateRoute();

        } catch (error) {
            console.error('Map initialization error:', error);
            showMapError('Failed to initialize map: ' + error.message);
        }
    }

    function calculateRoute() {
        try {
            // Clear any existing timeout
            if (routeCalculationTimeout) {
                clearTimeout(routeCalculationTimeout);
            }

            // Remove existing route control
            if (routeControl) {
                map.removeControl(routeControl);
                routeControl = null;
            }

            // Show loading
            document.getElementById('mapLoading').style.display = 'flex';
            document.getElementById('routeInfo').textContent = 'Route Info: Calculating route...';
            document.getElementById('routeDetails').style.display = 'none';

            // Set timeout for route calculation (5 seconds)
            routeCalculationTimeout = setTimeout(() => {
                if (document.getElementById('routeInfo').textContent === 'Route Info: Calculating route...') {
                    console.log('Route calculation timeout, using fallback');
                    showFallbackRoute();
                }
            }, 5000);

            // Create routing control with error handling
            routeControl = L.Routing.control({
                waypoints: [
                    L.latLng(techLat, techLon), // Technician
                    L.latLng(custLat, custLon)  // Customer
                ],
                routeWhileDragging: false,
                showAlternatives: false,
                fitSelectedRoutes: true,
                show: false, // Hide instructions panel
                createMarker: function() { return null; }, // Don't create default markers
                lineOptions: {
                    styles: [
                        {
                            color: '#3b82f6',
                            opacity: 0.8,
                            weight: 6
                        }
                    ]
                },
                router: L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    timeout: 3000 // 3 second timeout for OSRM
                })
            }).addTo(map);

            // Handle successful route calculation
            routeControl.on('routesfound', function(e) {
                clearTimeout(routeCalculationTimeout);
                document.getElementById('mapLoading').style.display = 'none';
                
                const routes = e.routes;
                if (routes && routes.length > 0) {
                    const route = routes[0];
                    const distance = (route.summary.totalDistance / 1000).toFixed(1); // Convert to km
                    const time = Math.round(route.summary.totalTime / 60); // Convert to minutes

                    // Update route info
                    document.getElementById('routeInfo').textContent = 'Route Found!';
                    document.getElementById('routeDistance').textContent = `${distance} km`;
                    document.getElementById('routeTime').textContent = `${time} minutes`;
                    document.getElementById('routeDetails').style.display = 'block';

                    // Extract instructions for directions panel
                    const instructions = route.instructions || [];
                    updateDirectionsPanel(instructions);

                    console.log('Route calculated successfully:', { distance: `${distance} km`, time: `${time} minutes` });
                } else {
                    showFallbackRoute();
                }
            });

            // Handle routing errors
            routeControl.on('routingerror', function(e) {
                clearTimeout(routeCalculationTimeout);
                document.getElementById('mapLoading').style.display = 'none';
                console.error('Routing error:', e.error);
                showFallbackRoute();
            });

        } catch (error) {
            clearTimeout(routeCalculationTimeout);
            document.getElementById('mapLoading').style.display = 'none';
            console.error('Route calculation error:', error);
            showFallbackRoute();
        }
    }

    function showFallbackRoute() {
        // Remove existing route control if any
        if (routeControl) {
            map.removeControl(routeControl);
            routeControl = null;
        }

        // Draw a straight line as fallback
        const fallbackLine = L.polyline([[techLat, techLon], [custLat, custLon]], {
            color: '#ef4444',
            weight: 4,
            opacity: 0.7,
            dashArray: '10, 10'
        }).addTo(map);

        // Calculate straight-line distance
        const distance = calculateDistance(techLat, techLon, custLat, custLon);
        const estimatedTime = Math.round(distance * 2); // Rough estimate: 2 minutes per km

        document.getElementById('routeInfo').textContent = 'Route Info: Straight-line route (no roads)';
        document.getElementById('routeDistance').textContent = `${distance} km`;
        document.getElementById('routeTime').textContent = `~${estimatedTime} minutes (estimate)`;
        document.getElementById('routeDetails').style.display = 'block';

        // Create simple directions for fallback
        const fallbackDirections = [
            { text: 'Start from technician location', distance: 0 },
            { text: 'Head towards customer location', distance: distance * 1000 },
            { text: 'Arrive at destination', distance: 0 }
        ];
        updateDirectionsPanel(fallbackDirections);

        // Fit map to show both markers and the line
        const group = new L.featureGroup([customerMarker, technicianMarker, fallbackLine]);
        map.fitBounds(group.getBounds(), { padding: [20, 20] });
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Earth's radius in km
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return (R * c).toFixed(1);
    }

    function showMapError(message) {
        document.getElementById('mapLoading').style.display = 'none';
        document.getElementById('mapError').style.display = 'flex';
        document.getElementById('errorMessage').textContent = message;
    }

    // Event listeners
    document.getElementById('refreshRoute').addEventListener('click', function() {
        document.getElementById('routeInfo').textContent = 'Route Info: Refreshing...';
        document.getElementById('routeDetails').style.display = 'none';
        calculateRoute();
    });

    document.getElementById('fitRouteButton').addEventListener('click', function() {
        if (routeControl && routeControl.getPlan().getWaypoints().length > 0) {
            map.fitBounds(routeControl.getPlan().getWaypoints(), { padding: [20, 20] });
        } else {
            const group = new L.featureGroup([customerMarker, technicianMarker]);
            map.fitBounds(group.getBounds(), { padding: [20, 20] });
        }
    });

    document.getElementById('retryRoute').addEventListener('click', function() {
        document.getElementById('mapError').style.display = 'none';
        document.getElementById('mapLoading').style.display = 'flex';
        setTimeout(initMap, 500);
    });

    // Initialize map immediately
    initMap();

    // Real-time location updates (if technician has ID)
    if (technicianId) {
        Echo.channel('technician-location.' + technicianId)
            .listen('TechnicianLocationUpdated', (e) => {
                if (e.latitude && e.longitude) {
                    techLat = e.latitude;
                    techLon = e.longitude;
                    
                    // Update technician marker
                    if (technicianMarker) {
                        technicianMarker.setLatLng([techLat, techLon]);
                    }
                    
                    // Recalculate route
                    calculateRoute();
                } else {
                    console.error('Invalid location data from event.');
                }
            })
            .error((error) => console.error('Pusher error:', error));
    }
});
</script>

<style>
/* Fix z-index issues and layout */
#map {
    position: relative;
    z-index: 1;
}

#mapLoading, #mapError {
    z-index: 1000;
}

.leaflet-container {
    font-family: inherit;
}

.leaflet-control-container {
    z-index: 1000;
}

/* Ensure proper spacing */
.bg-white.rounded-2xl {
    margin-bottom: 1.5rem;
}

/* Fix loading overlay positioning */
#mapLoading, #mapError {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Custom scrollbar for directions panel */
#directionsPanel::-webkit-scrollbar {
    width: 4px;
}

#directionsPanel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

#directionsPanel::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

#directionsPanel::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Premium styling for direction items */
.direction-item {
    transition: all 0.2s ease-in-out;
}

.direction-item:hover {
    transform: translateY(-1px);
}
</style>

@endsection