@extends('layouts.admin')
@section('title', 'Pre-Registered Profile')
@section('content')
<div class="bg-white p-6 rounded-2xl shadow">
    <a href="/customers/pre-registered" class="text-sm text-blue-600 hover:underline">← Back</a>

    <h2 class="text-xl font-bold text-gray-800 mb-4">👤 Pre-Registered Profile</h2>

    <div class="space-y-2 text-sm text-gray-700">
        <p><strong>Account #:</strong> PR-001</p>
        <p><strong>Name:</strong> Juan Dela Cruz</p>
        <p><strong>Contact:</strong> 0999-888-7777</p>
        <p><strong>Address:</strong> Barangay 3, Panabo City</p>
        <p><strong>Pre-Registered Date:</strong> 2025-08-19</p>
        <p><strong>Status:</strong> 
            <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full text-xs">Pending</span>
        </p>
    </div>

    <div class="mt-6 flex space-x-3">
        <button class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700">✅ Verify</button>
        <button class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700">❌ Delete</button>
    </div>
</div>
@endsection
