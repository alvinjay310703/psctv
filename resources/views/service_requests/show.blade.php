@extends('layouts.admin')

@section('title', 'Service Request Details')

@section('content')
<div class="max-w-7xl mx-auto py-8 space-y-8">

    <!-- Notifications -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded shadow-sm">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded shadow-sm">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    <!-- Header: Request Title & Status -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 border-b pb-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
                <x-lucide-clipboard-list class="w-7 h-7 text-blue-600"/>
                Service Request #REQ-{{ $request->id }}
            </h1>
            <p class="text-sm text-gray-500 mt-1 flex items-center gap-1">
                <x-lucide-calendar class="w-4 h-4"/>
                Created: {{ $request->created_at->format('M d, Y h:i A') }}
            </p>
        </div>

        <span class="px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2
            @if($request->status=='pending') bg-yellow-100 text-yellow-700
            @elseif($request->status=='assigned') bg-purple-100 text-purple-700
            @elseif($request->status=='in-progress') bg-blue-100 text-blue-700
            @elseif($request->status=='completed') bg-green-100 text-green-700
            @endif">
            <x-lucide-circle class="w-3 h-3"/>
            {{ ucfirst($request->status) }}
        </span>
    </div>

    <div class="relative flex justify-between items-center w-full mt-6">
    @php
        $steps = [
            ['label'=>'Created', 'time'=>$request->created_at],
            ['label'=>'Assigned', 'time'=>$request->assigned_at],
            ['label'=>'In Progress', 'time'=>$request->started_at],
            ['label'=>'Completed', 'time'=>$request->completed_at],
        ];
        $completedSteps = collect($steps)->filter(fn($s)=>$s['time'])->count();
        $progressPercent = ($completedSteps-1)/ (count($steps)-1)*100;
    @endphp

    <!-- Background line -->
    <div class="absolute top-4 left-4 right-4 h-1 bg-gray-300 rounded z-0"></div>

    <!-- Completed line -->
    <div class="absolute top-4 left-4 h-1 bg-green-500 rounded z-0 transition-all duration-700"
         style="width: {{ $progressPercent }}%"></div>

    @foreach($steps as $i => $step)
    <div class="flex flex-col items-center relative z-10 group">
        <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold
            text-white
            @if($step['time'])
                @if($step['label']=='In Progress' && !$request->completed_at)
                    bg-blue-500 animate-pulse
                @else
                    bg-green-500
                @endif
            @else
                bg-gray-300
            @endif">
            {{ $i+1 }}
        </div>
        <span class="text-xs mt-2 text-gray-700">{{ $step['label'] }}</span>
        @if($step['time'])
        <span class="absolute top-10 left-1/2 -translate-x-1/2 text-[10px] text-gray-500 opacity-0 group-hover:opacity-100 transition">
            {{ $step['time']->format('M d, Y h:i A') }}
        </span>
        @endif
    </div>
    @endforeach
</div>




    <!-- Main Info Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Column: Request Info + Attachments -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Request Info Card -->
            <div class="bg-white p-6 rounded-xl shadow border">
                <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <x-lucide-info class="w-5 h-5 text-blue-500"/> Request Information
                </h3>
                <ul class="space-y-2 text-gray-700">
                    <li><span class="font-medium">👤 Customer:</span> {{ $request->customer_name }}</li>
                    <li><span class="font-medium">📞 Phone:</span> {{ $request->phone ?? 'N/A' }}</li>
                    <li><span class="font-medium">📍 Address:</span> {{ $request->address ?? 'N/A' }}</li>
                    <li><span class="font-medium">🛠 Service Type:</span> {{ $request->service_type }}</li>
                    <li><span class="font-medium">📝 Notes:</span> {{ $request->notes ?? 'None' }}</li>
                </ul>
            </div>

           <!-- Completion Photos / Before & After Gallery -->
@if($request->status === 'completed')
<div class="bg-gray-50 p-6 rounded-lg border shadow-sm mt-6">
    <h3 class="font-semibold text-gray-700 text-lg mb-4 flex items-center gap-2">
        <x-lucide-image class="w-5 h-5 text-green-600"/>
        Completion Photos (Before & After)
    </h3>

    @php
        // Demo photos if no real attachments
        $photos = $request->completion_photos ?? [
            ['label' => 'Before', 'url' => 'https://via.placeholder.com/300x200?text=Before'],
            ['label' => 'After', 'url' => 'https://via.placeholder.com/300x200?text=After'],
        ];
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($photos as $photo)
            <div class="relative rounded-lg overflow-hidden border hover:shadow-lg transition-shadow cursor-pointer">
                <img src="{{ $photo['url'] }}" alt="{{ $photo['label'] }}" class="w-full h-48 object-cover">
                <div class="absolute bottom-0 left-0 w-full bg-black/50 text-white text-sm py-1 text-center">
                    {{ $photo['label'] }}
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

            <!-- Attachments Card -->
            @if(!empty($request->attachments))
            <div class="bg-white p-6 rounded-xl shadow border">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <x-lucide-paperclip class="w-5 h-5 text-gray-600"/> Attachments
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($request->attachments as $file)
                    <div class="rounded-lg border overflow-hidden shadow-sm hover:shadow-md transition">
                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" class="w-full h-32 object-cover" />
                        <div class="p-2 text-sm text-gray-600 text-center truncate">{{ $file['name'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Technician + Map -->
        <div class="space-y-6">
            <!-- Technician Info -->
            <div class="bg-white p-6 rounded-xl shadow border">
                <h3 class="text-lg font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <x-lucide-user class="w-5 h-5 text-purple-600"/> Technician
                </h3>
                @if($request->technician)
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-lg">
                        {{ strtoupper(substr($request->technician->name,0,1)) }}
                    </div>
                    <div>
                        <p class="font-medium text-gray-800">{{ $request->technician->name }}</p>
                        <p class="text-sm text-gray-500">Active Jobs: {{ $request->technician->active_jobs ?? 0 }}</p>
                    </div>
                </div>
                @else
                <p class="text-sm text-gray-500 italic">No technician assigned yet.</p>
                @endif
            </div>

            <!-- Map Card -->
            <div class="h-80 rounded-xl overflow-hidden border shadow-sm">
                @if($request->address)
                <iframe
                    src="https://www.google.com/maps?q={{ urlencode($request->address) }}&output=embed"
                    width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen loading="lazy">
                </iframe>
                @else
                <div class="flex items-center justify-center h-full text-gray-500">
                    No location available
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white p-6 rounded-xl shadow border">
        <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center gap-2">
            <x-lucide-history class="w-5 h-5 text-gray-600"/> Activity Timeline
        </h3>
        <ol class="relative border-l border-gray-200 ml-4 space-y-4">
            @foreach([
                ['icon'=>'📝','label'=>'Request Created','time'=>$request->created_at],
                ['icon'=>'👷','label'=>'Technician Assigned','time'=>$request->assigned_at],
                ['icon'=>'🔧','label'=>'Work Started','time'=>$request->started_at],
                ['icon'=>'✅','label'=>'Job Completed','time'=>$request->completed_at],
            ] as $event)
            <li class="ml-6">
                <span class="absolute -left-3 flex items-center justify-center w-6 h-6 rounded-full 
                    {{ $event['time'] ? 'bg-green-500 text-white' : 'bg-gray-300' }}">
                    {{ $event['icon'] }}
                </span>
                <p class="font-medium text-gray-800">{{ $event['label'] }}</p>
                <p class="text-sm text-gray-500">{{ $event['time'] ? $event['time']->format('M d, Y h:i A') : '—' }}</p>
            </li>
            @endforeach
        </ol>
    </div>
<!-- Footer Actions -->
<div class="flex flex-col lg:flex-row justify-end gap-3 pt-4 border-t">
    <a href="{{ route('service_requests.index') }}" 
       class="bg-gray-100 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-200 text-sm shadow flex items-center gap-2">
       <x-lucide-arrow-left class="w-4 h-4"/> Back to Requests
    </a>

    {{-- ✅ Pending --}}
    @if($request->status === 'pending')
    <a href="{{ route('service_requests.assignPage', $request->id) }}" 
       class="bg-purple-600 text-white px-5 py-2 rounded-lg hover:bg-purple-700 text-sm shadow flex items-center gap-2">
        <x-lucide-user-plus class="w-4 h-4"/> Assign Technician
    </a>
    @endif

    {{-- ✅ Assigned --}}
    @if($request->status === 'assigned')
    <form method="POST" action="{{ route('service_requests.updateStatus', $request->id) }}" 
          onsubmit="return confirm('Start work on this request now?')">
        @csrf
        <input type="hidden" name="status" value="in-progress">
        <button type="submit" 
                class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 text-sm shadow flex items-center gap-2">
            <x-lucide-play class="w-4 h-4"/> Start Work
        </button>
    </form>
    @endif

    {{-- ✅ In Progress --}}
    @if($request->status === 'in-progress')
    <button type="button" 
            onclick="document.getElementById('completeJobModal').classList.remove('hidden')"
            class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 text-sm shadow flex items-center gap-2">
        <x-lucide-check class="w-4 h-4"/> Mark as Completed
    </button>
    @endif

    {{-- ✅ Completed --}}
    @if($request->status === 'completed')
    <span class="bg-green-100 text-green-700 px-5 py-2 rounded-lg text-sm flex items-center gap-2">
        <x-lucide-badge-check class="w-4 h-4"/> Job Completed
    </span>
    @endif
</div>


<!-- ✅ Modal (outside footer div, still inside content) -->
@if($request->status === 'in-progress')
<div id="completeJobModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-lg w-[32rem] p-6 space-y-5 relative">
        <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <x-lucide-check-circle class="w-5 h-5 text-green-600"/>
            Complete Job Confirmation
        </h2>
        <p class="text-sm text-gray-600">Provide a report and proof photos before marking this request as completed.</p>

        <form method="POST" action="{{ route('service_requests.updateStatus', $request->id) }}" 
              enctype="multipart/form-data" class="space-y-4">
            @csrf
            <input type="hidden" name="status" value="completed">

            <div>
                <label class="block text-sm font-medium text-gray-700">Completion Report</label>
                <textarea name="report" rows="3"
                          class="w-full border rounded px-3 py-2 text-sm focus:ring-2 focus:ring-green-500"
                          placeholder="Describe what was done..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Upload Proof Photos</label>
                <input type="file" name="photos[]" multiple 
                       class="block w-full text-sm text-gray-600 border rounded px-3 py-2">
                <p class="text-xs text-gray-400 mt-1">You may upload multiple before/after photos.</p>
            </div>

            <div class="flex justify-end gap-2 pt-3">
                <button type="button" 
                        onclick="document.getElementById('completeJobModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 text-sm">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm flex items-center gap-2">
                    <x-lucide-check class="w-4 h-4"/>
                    Confirm & Complete
                </button>
            </div>
        </form>
    </div>
</div>
@endif


    </div>

</div>
@endsection