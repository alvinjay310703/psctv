@extends('layouts.staff')

@section('title', 'Create Service Request')

@section('content')
<div class="max-w-4xl mx-auto py-8 space-y-8">

    <!-- Back -->
    <div>
        <a href="{{ route('staff.service_requests.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition">
            ← Back to Requests
        </a>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center border-b pb-4">
        <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
            <x-lucide-plus class="w-6 h-6 text-green-600"/>
            Create New Service Request
        </h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Form -->
        <div class="bg-white p-6 rounded-xl shadow border space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                <x-lucide-file-text class="w-5 h-5 text-blue-500"/> Request Details
            </h2>

            <form id="createForm" method="POST" action="{{ route('staff.service_requests.store') }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">Customer Name <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" id="customer_name" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('customer_name') border-red-500 @enderror"
                               value="{{ old('customer_name') }}" placeholder="Enter customer full name">
                        @error('customer_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                               value="{{ old('phone') }}" placeholder="+63 9XX XXX XXXX">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address <span class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="3" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('address') border-red-500 @enderror resize-vertical"
                                  placeholder="Enter full address">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-1">Service Type <span class="text-red-500">*</span></label>
                        <select name="service_type" id="service_type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('service_type') border-red-500 @enderror">
                            <option value="">-- Select Service Type --</option>
                            <option value="Internet Installation" {{ old('service_type') == 'Internet Installation' ? 'selected' : '' }}>Internet Installation</option>
                            <option value="Cable TV Setup" {{ old('service_type') == 'Cable TV Setup' ? 'selected' : '' }}>Cable TV Setup</option>
                            <option value="Network Troubleshooting" {{ old('service_type') == 'Network Troubleshooting' ? 'selected' : '' }}>Network Troubleshooting</option>
                            <option value="Equipment Repair" {{ old('service_type') == 'Equipment Repair' ? 'selected' : '' }}>Equipment Repair</option>
                            <option value="Signal Issues" {{ old('service_type') == 'Signal Issues' ? 'selected' : '' }}>Signal Issues</option>
                            <option value="Billing Inquiry" {{ old('service_type') == 'Billing Inquiry' ? 'selected' : '' }}>Billing Inquiry</option>
                            <option value="Other" {{ old('service_type') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('service_type')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror resize-vertical"
                                  placeholder="Any additional details or special instructions">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hidden coordinates -->
                    <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('staff.service_requests.index') }}"
                       class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                        Create Request
                    </button>
                </div>
            </form>
        </div>

        <!-- Map Preview -->
        <div class="bg-white p-6 rounded-xl shadow border space-y-4">
            <h2 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                <x-lucide-map-pin class="w-5 h-5 text-red-600"/> Location Preview
            </h2>
            <div id="map" class="w-full h-64 rounded-lg border"></div>
            <p class="text-xs text-gray-500">Click on the map to set the exact location, or leave blank for automatic geocoding.</p>
        </div>
    </div>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize map
    const defaultLat = 12.8797;
    const defaultLon = 121.7740;
    const map = L.map('map').setView([defaultLat, defaultLon], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    // Function to update marker
    function updateMarker(lat, lon) {
        if (marker) {
            marker.setLatLng([lat, lon]);
        } else {
            marker = L.marker([lat, lon], { draggable: true }).addTo(map);
            marker.on('dragend', (e) => {
                const { lat, lng } = e.target.getLatLng();
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        }
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        map.setView([lat, lon], 15);
    }

    // Click to set location
    map.on('click', (e) => {
        updateMarker(e.latlng.lat, e.latlng.lng);
    });

    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Geocode address with debounce on input
    const geocodeAddress = debounce(async () => {
        const address = document.getElementById('address').value.trim();
        console.log('Geocoding address:', address);
        if (!address || address.length < 3) return; // Reduced minimum length

        try {
            console.log('Fetching geocoding data...');
            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`);
            const data = await response.json();
            console.log('Geocoding response:', data);
            if (data.length > 0) {
                const { lat, lon } = data[0];
                console.log('Updating marker to:', lat, lon);
                updateMarker(parseFloat(lat), parseFloat(lon));
            } else {
                console.log('No geocoding results found');
            }
        } catch (error) {
            console.error('Geocoding error:', error);
        }
    }, 500); // Reduced debounce time to 0.5 seconds

    document.getElementById('address').addEventListener('input', geocodeAddress);

    // Set default marker if coordinates exist
    const lat = document.getElementById('latitude').value;
    const lon = document.getElementById('longitude').value;
    if (lat && lon) {
        updateMarker(parseFloat(lat), parseFloat(lon));
    }
});
</script>

@endsection
