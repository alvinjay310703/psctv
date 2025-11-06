<aside class="sidebar-expanded bg-gray-900 text-gray-200 h-screen w-64 p-5 fixed top-0 left-0 transition-all duration-300 ease-in-out overflow-y-auto shadow-lg z-50">

    <!-- Logo -->
    <div class="flex items-center justify-center mb-6 border-b border-gray-700 pb-3">
        <img src="{{ asset('images/logo3.png') }}" alt="PCTVS Logo" class="h-14 w-auto">
    </div>

    <!-- Navigation -->
    <nav>
        <ul class="space-y-2 text-sm">

            <!-- 🏠 Dashboard -->
            <li>
                <a href="{{ route('staff.dashboard') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.dashboard') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-home class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <!-- 🧾 Service Requests -->
            <li>
                <a href="{{ route('staff.service_requests.index') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.service_requests.*') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-clipboard-document-list class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Service Requests</span>
                </a>
            </li>

            <!-- 👥 Customers -->
            <li>
                <a href="{{ route('staff.customers.index') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.customers.*') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-user-group class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Customers</span>
                </a>
            </li>

            <!-- 🔧 Technicians -->
            <li>
                <a href="{{ route('staff.technicians.index') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.technicians.*') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-wrench-screwdriver class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Technicians</span>
                </a>
            </li>

            <!-- 💵 Billing / Invoices -->
            <li>
                <a href="{{ route('staff.billing.index') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.billing.*') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-banknotes class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Billing / Invoices</span>
                </a>
            </li>

           

            <!-- 📢 Announcements -->
            <li>
                <a href="{{ route('staff.announcements.index') }}"
                   class="flex items-center py-2 px-3 rounded-md transition 
                   {{ request()->routeIs('staff.announcements.*') ? 'bg-indigo-600 text-white font-semibold shadow' : 'hover:bg-gray-700 hover:text-white' }}">
                    <x-heroicon-o-megaphone class="h-5 w-5 mr-3"/>
                    <span class="sidebar-text">Announcements</span>
                </a>
            </li>

        </ul>
    </nav>

</aside>
