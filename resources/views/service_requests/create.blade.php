@extends('layouts.admin')

@section('title', 'New Service Request')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">➕ Create New Request</h2>

    <form>
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-gray-600">Customer Name</label>
                <input type="text" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Contact Number</label>
                <input type="text" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block text-sm text-gray-600">Service Type</label>
                <select class="w-full border rounded px-3 py-2">
                    <option>Installation</option>
                    <option>Repair</option>
                    <option>Upgrade</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-600">Notes</label>
                <textarea class="w-full border rounded px-3 py-2"></textarea>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded shadow">
                Save Request
            </button>
        </div>
    </form>
</div>
@endsection
