@extends('layouts.admin')

@section('title', 'Edit Customer')

@section('content')
<div class="bg-white rounded-2xl shadow-md p-6">
    <h1 class="text-xl font-bold mb-4">✏️ Edit Customer</h1>

    <form method="POST" action="{{ route('customers.update', $customer) }}" class="space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block">Name</label>
            <input type="text" name="name" value="{{ old('name', $customer->user->name) }}" class="border rounded-lg p-2 w-full" required>
        </div>
        <div>
            <label class="block">Email</label>
            <input type="email" name="email" value="{{ old('email', $customer->user->email) }}" class="border rounded-lg p-2 w-full" required>
        </div>
        <div>
            <label class="block">Status</label>
            <select name="status" class="border rounded-lg p-2 w-full" required>
                <option value="active" {{ $customer->status=='active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $customer->status=='inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ $customer->status=='suspended' ? 'selected' : '' }}>Suspended</option>
                <option value="pending" {{ $customer->status=='pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
        <div>
            <label class="block">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="border rounded-lg p-2 w-full">
        </div>
        <div>
            <label class="block">Address</label>
            <input type="text" name="address" value="{{ old('address', $customer->address) }}" class="border rounded-lg p-2 w-full">
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block">City</label>
                <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="border rounded-lg p-2 w-full">
            </div>
            <div>
                <label class="block">Province</label>
                <input type="text" name="province" value="{{ old('province', $customer->province) }}" class="border rounded-lg p-2 w-full">
            </div>
            <div>
                <label class="block">Zip Code</label>
                <input type="text" name="zip" value="{{ old('zip', $customer->zip_code) }}" class="border rounded-lg p-2 w-full">
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block">Date of Birth</label>
                <input type="date" name="dob" value="{{ old('dob', $customer->dob) }}" class="border rounded-lg p-2 w-full">
            </div>
            <div>
                <label class="block">Gender</label>
                <select name="gender" class="border rounded-lg p-2 w-full">
                    <option value="" {{ !$customer->gender ? 'selected' : '' }}>-- Select --</option>
                    <option value="male" {{ $customer->gender=='male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $customer->gender=='female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block">Reset Password (optional)</label>
            <input type="password" name="password" class="border rounded-lg p-2 w-full">
        </div>

        <button type="submit" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Update</button>
    </form>
</div>
@endsection
