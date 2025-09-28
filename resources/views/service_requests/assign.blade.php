@extends('layouts.admin')

@section('title', 'Assign Technician')

@section('content')
<div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">

    <!-- Banner Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-5 flex justify-between items-center">
        <h2 class="text-xl font-bold text-white">
            Assign Technician – Request #REQ-{{ $request->id }}
        </h2>
        <a href="{{ route('service_requests.index') }}" 
           class="text-sm bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg transition">
            ← Back to Service Requests
        </a>
    </div>

    <!-- Body -->
    <div class="p-6 space-y-8">
        
        <!-- Request Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Customer & Job Info -->
            <div class="space-y-4">
                <div class="bg-gray-50 p-5 rounded-lg border space-y-3">
                    <h3 class="font-semibold text-gray-700 text-lg mb-2">Request Details</h3>

                    <p><span class="font-medium text-gray-600">Customer:</span> {{ $request->customer_name }}</p>
                    <p><span class="font-medium text-gray-600">Phone:</span> {{ $request->phone ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Email:</span> {{ $request->email ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Address:</span> {{ $request->address ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Service Type:</span> {{ $request->service_type }}</p>
                    <p><span class="font-medium text-gray-600">Preferred Date:</span> {{ $request->preferred_date ?? 'Not set' }}</p>
                    <p>
                        <span class="font-medium text-gray-600">Status:</span>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            @if($request->status=='pending') bg-yellow-100 text-yellow-700
                            @elseif($request->status=='assigned') bg-purple-100 text-purple-700
                            @elseif($request->status=='in-progress') bg-blue-100 text-blue-700
                            @elseif($request->status=='completed') bg-green-100 text-green-700
                            @endif">
                            {{ ucfirst($request->status) }}
                        </span>
                    </p>
                    <p><span class="font-medium text-gray-600">Created:</span> {{ $request->created_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                    <p><span class="font-medium text-gray-600">Notes:</span> {{ $request->notes ?? 'None' }}</p>
                </div>
            </div>

            <!-- Right: Map -->
            <div class="col-span-2">
                <div class="h-80 lg:h-96 rounded-lg overflow-hidden shadow border">
                    @if($request->address)
                        <iframe
                            width="100%"
                            height="100%"
                            frameborder="0"
                            style="border:0"
                            src="https://www.google.com/maps?q={{ urlencode($request->address) }}&output=embed"
                            allowfullscreen
                            loading="lazy">
                        </iframe>
                    @else
                        <div class="flex items-center justify-center h-full bg-gray-200 text-gray-500">
                            Customer location not available
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assign Technician -->
        <div class="bg-gray-50 p-6 rounded-lg border shadow-sm">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Assign Technician</h3>
            
            <form method="POST" action="{{ route('service_requests.assign', $request->id) }}" class="space-y-5">
                @csrf

                <!-- Technician Selection -->
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Select Technician</label>
                    <select name="technician_id" required
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="">-- Choose Technician --</option>
                        @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}" {{ $request->technician_id==$tech->id?'selected':'' }}>
                                {{ $tech->name }} 
                                (Active Jobs: {{ $tech->active_jobs }} | Rating: {{ $tech->rating ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Assignment Notes (optional)</label>
                    <textarea name="notes" rows="3" placeholder="Add assignment instructions, urgency notes, etc." 
                              class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500"></textarea>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('service_requests.index') }}" 
                       class="px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200">Cancel</a>
                    <button type="submit" 
                            class="px-5 py-2 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 shadow">
                        ✅ Assign Technician
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
