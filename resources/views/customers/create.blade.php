@extends('layouts.admin')

@section('title', 'Add Customer')

@section('content')
@php
    $packages = $packages ?? \App\Models\Package::where('is_active', true)->get();
@endphp

<div class="max-w-5xl mx-auto">
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-gray-200">
        <!-- Header -->
        <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
            <h1 class="text-xl font-semibold text-gray-800">Add Customer</h1>
            <p class="text-sm text-gray-500 mt-1">Fill in the details to register a new customer.</p>
        </div>

        <!-- Body -->
        <div class="p-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3">
                    <ul class="list-disc pl-5 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('customers.store') }}" class="space-y-10">
                @csrf

                <!-- Personal Info -->
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Address Info -->
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Address</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3">
                            <label for="address" class="block text-sm font-medium text-gray-700">Street Address</label>
                            <input type="text" id="address" name="address" value="{{ old('address') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                            <input type="text" id="province" name="province" value="{{ old('province') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="zip" class="block text-sm font-medium text-gray-700">ZIP</label>
                            <input type="text" id="zip" name="zip" value="{{ old('zip') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                <!-- Other Info -->
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Additional Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" id="dob" name="dob" value="{{ old('dob') }}"
                                   class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select id="gender" name="gender"
                                    class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                                <option value="" {{ old('gender') == '' ? 'selected' : '' }}>—</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Package -->
                <div>
                    <h2 class="text-lg font-medium text-gray-700 mb-4">Package</h2>
                    <div>
                        <label for="plan_id" class="block text-sm font-medium text-gray-700">Assign Package (optional)</label>
                        <select id="plan_id" name="plan_id"
                                class="mt-2 block w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200 focus:border-indigo-500">
                            <option value="">— No package —</option>
                            @foreach($packages as $pkg)
                                <option value="{{ $pkg->id }}" {{ old('plan_id') == $pkg->id ? 'selected' : '' }}>
                                    {{ $pkg->name }} — ₱{{ number_format($pkg->price, 2) }} / {{ $pkg->billing_cycle }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Choosing a package will auto-create a subscription and invoice.</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 border-t pt-6">
                    <a href="{{ route('customers.list') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-6 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:ring focus:ring-indigo-200">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
