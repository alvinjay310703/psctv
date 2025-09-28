@extends('layouts.admin')

@section('title', 'Edit Service Request')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-md">
    <h2 class="text-xl font-bold text-gray-800 mb-4">✏ Edit Service Request</h2>

    <form method="POST" action="{{ route('service_requests.update', $request->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Customer Name</label>
            <input type="text" name="customer_name" value="{{ $request->customer_name }}" class="w-full border rounded px-3 py-2">
        </div>

        <div>
            <label class="block text-sm font-medium">Service Type</label>
            <select name="service_type" class="w-full border rounded px-3 py-2">
                <option value="Installation" {{ $request->service_type=='Installation'?'selected':'' }}>Installation</option>
                <option value="Repair" {{ $request->service_type=='Repair'?'selected':'' }}>Repair</option>
                <option value="Upgrade" {{ $request->service_type=='Upgrade'?'selected':'' }}>Upgrade</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Notes</label>
            <textarea name="notes" class="w-full border rounded px-3 py-2">{{ $request->notes }}</textarea>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Update</button>
        <a href="{{ route('service_requests.index') }}" class="ml-2 text-gray-600">Cancel</a>
    </form>
</div>
@endsection
