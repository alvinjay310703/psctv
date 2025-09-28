@extends('layouts.admin')

@section('title', 'Dashboard')



@section('content')

<!-- 🔹 Top Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
    <!-- Total Customers -->
    <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center space-x-4">
            <div class="text-3xl">👥</div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">Total Customers</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">1,250</p>
            </div>
        </div>
        <span class="text-green-600 text-sm font-semibold">+5%</span>
    </div>

    <!-- Active Technicians -->
    <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center space-x-4">
            <div class="text-3xl">🛠️</div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">Active Technicians</h3>
                <p class="text-2xl font-bold text-gray-900 mt-1">18</p>
            </div>
        </div>
        <span class="text-red-600 text-sm font-semibold">-2%</span>
    </div>

    <!-- Service Requests -->
    <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center space-x-4">
            <div class="text-3xl">📋</div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">Service Requests</h3>
                <p class="text-2xl font-bold text-blue-600 mt-1">85</p>
            </div>
        </div>
        <span class="text-green-600 text-sm font-semibold">+12%</span>
    </div>

    <!-- Active Subscriptions -->
    <div class="flex items-center justify-between p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center space-x-4">
            <div class="text-3xl">📄</div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium">Active Subscriptions</h3>
                <p class="text-2xl font-bold text-purple-600 mt-1">320</p>
            </div>
        </div>
        <span class="text-green-600 text-sm font-semibold">+8%</span>
    </div>
</div>

<!-- 🔹 Revenue + Requests Breakdown -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
    <!-- Revenue Chart -->
    <div class="lg:col-span-2 p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg font-semibold mb-4 flex items-center text-gray-800">
            📈 <span class="ml-2">Monthly Revenue</span>
        </h3>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Service Request Breakdown -->
    <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">📊 Requests Breakdown</h3>
        <div class="h-64">
            <canvas id="requestsChart"></canvas>
        </div>
    </div>
</div>

<!-- 🔹 Pending Requests -->
<div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition mb-10">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <!-- Heroicon: Clock -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Pending Service Requests
        </h3>
        <a href="#" class="text-sm text-blue-600 hover:underline">View All</a>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                <tr>
                    <th class="py-3 px-4 text-left">Customer</th>
                    <th class="py-3 px-4 text-left">Type</th>
                    <th class="py-3 px-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-medium text-gray-800">Jamie mejia</td>
                    <td class="px-4">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Repair</span>
                    </td>
                    <td class="px-4">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-medium text-gray-800">Akang alladin</td>
                    <td class="px-4">
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Repair</span>
                    </td>
                    <td class="px-4">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 font-medium text-gray-800">Alvin olvido</td>
                    <td class="px-4">
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Upgrade</span>
                    </td>
                    <td class="px-4">
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<!-- 🔹 Payments + Reports + Announcements -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

    <!-- Latest Payments -->
    <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                <!-- Heroicon: Credit Card -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 11h16M4 15h16m-6 4h6v2h-6v-2z" />
                </svg>
                Latest Payments
            </h3>
            <a href="#" class="text-sm text-blue-600 hover:underline">View All</a>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wide">
                    <tr>
                        <th class="py-3 px-4 text-left">Customer</th>
                        <th class="py-3 px-4 text-right">Amount</th>
                        <th class="py-3 px-4 text-right">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium text-gray-800">Alvin</td>
                        <td class="py-3 px-4 text-right text-green-600 font-semibold">₱1,200</td>
                        <td class="py-3 px-4 text-right text-gray-500">Aug 15</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 font-medium text-gray-800">Alvinz</td>
                        <td class="py-3 px-4 text-right text-green-600 font-semibold">₱2,500</td>
                        <td class="py-3 px-4 text-right text-gray-500">Aug 14</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

   <!-- Technician Reports -->
<div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
            <!-- Heroicon: Document Report -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2h6v2m-6-6h6m-2-7h-4a2 2 0 00-2 2v14a2 2 0 002 2h4a2 2 0 002-2V6a2 2 0 00-2-2z" />
            </svg>
            Technician Reports
        </h3>
        <a href="#" class="text-sm text-green-600 hover:underline">View All</a>
    </div>

    <ul class="space-y-3 text-sm text-gray-600">
        <li class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-blue-100 text-blue-600 font-bold">A</span>
            <div>
                <p class="font-medium text-gray-800">Alvin Olvido</p>
                <p class="text-gray-600 text-xs">Installation completed at Zone 5</p>
                <span class="text-gray-400 text-xs">Aug 18 • 10:30 AM</span>
            </div>
        </li>
        <li class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
            <span class="w-8 h-8 flex items-center justify-center rounded-full bg-green-100 text-green-600 font-bold">J</span>
            <div>
                <p class="font-medium text-gray-800">Jamie</p>
                <p class="text-gray-600 text-xs">Encountered signal issue in Zone 3</p>
                <span class="text-gray-400 text-xs">Aug 17 • 4:15 PM</span>
            </div>
        </li>
    </ul>
</div>


    
    <!-- Announcements -->
    <div class="p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition">
        <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center gap-2">
            <!-- Heroicon: Speakerphone -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h10M11 9h7m-7 4h10M4 19h16M4 15h4m-4-4h4m-4-4h4m-4-4h4" />
            </svg>
            Announcements
        </h3>
        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
            <li class="text-red-600 font-medium">⚠️ System maintenance on Aug 20.</li>
            <li>New features added to requests module.</li>
            <li>Submit monthly technician reports.</li>
            <li>Holiday schedule: Aug 30 – Sep 1.</li>
        </ul>
    </div>
</div>

<!-- ⚠️ System Alerts -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="flex items-center justify-between p-6 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-2xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center space-x-4">
            <div class="text-4xl text-yellow-500">⚠️</div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Overdue Bills</h3>
                <p class="text-gray-700">5 customers have overdue bills.</p>
            </div>
        </div>
        <a href="#" class="text-sm font-semibold text-yellow-600 hover:underline">View →</a>
    </div>

    <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl shadow-md hover:shadow-lg transition">
        <div class="flex items-center space-x-4">
            <div class="text-4xl text-blue-500">🛠️</div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Technicians Assigned</h3>
                <p class="text-gray-700">2 technicians currently assigned.</p>
            </div>
        </div>
        <a href="#" class="text-sm font-semibold text-blue-600 hover:underline">View →</a>
    </div>
</div>

@endsection

@push('scripts')
<!-- Load Chart.js before using it -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug'],
                datasets: [{
                    label: 'Revenue',
                    data: [120000, 135000, 150000, 160000, 180000, 200000, 220000, 250000],
                    borderColor: 'rgb(34,197,94)',
                    backgroundColor: 'rgba(34,197,94,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgb(34,197,94)'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    // Requests Breakdown Pie Chart
    const reqCtx = document.getElementById('requestsChart');
    if (reqCtx) {
        new Chart(reqCtx, {
            type: 'doughnut',
            data: {
                labels: ['Repair', 'Upgrade', 'Installation'],
                datasets: [{
                    label: 'Requests',
                    data: [45, 25, 15],
                    backgroundColor: ['#facc15', '#60a5fa', '#34d399'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
});
</script>
@endpush