@extends('layouts.staff')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="p-6 bg-blue-50 rounded-xl shadow">
        <h3 class="text-lg font-bold">📋 My Requests</h3>
        <p class="text-2xl font-semibold text-gray-800">12</p>
    </div>
    <div class="p-6 bg-green-50 rounded-xl shadow">
        <h3 class="text-lg font-bold">🕓 Pending Verifications</h3>
        <p class="text-2xl font-semibold text-gray-800">5</p>
    </div>
    <div class="p-6 bg-yellow-50 rounded-xl shadow">
        <h3 class="text-lg font-bold">📢 Announcements</h3>
        <p class="text-2xl font-semibold text-gray-800">2</p>
    </div>
</div>
@endsection
