<aside id="sidebar"  
    class="sidebar-expanded bg-gray-800 text-white h-screen p-5 fixed top-0 left-0 transition-all duration-300 overflow-hidden">

    <!-- Sidebar Logo -->
    <div class="flex items-center justify-center mb-6 border-b border-gray-700 pb-1">
        <img src="{{ asset('images/logo3.png') }}" alt="PCTVS Logo" class="h-14 w-auto pb-3">
    </div>
   
    <nav>
        <ul class="space-y-1">

            <!-- Dashboard -->
            <li>
                <a href="/dashboard" data-label="Dashboard"
                   class="flex items-center py-2 px-3 rounded transition 
                   {{ request()->is('dashboard') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 7-7 7 7-2 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2v-8z"/>
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <!-- Customers -->
            <li>
                <button type="button" data-label="Customers"
                    class="flex items-center justify-between w-full py-2 px-3 rounded submenu-toggle transition
                    {{ request()->is('customers*') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}" 
                    data-target="customersMenu">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M5.5 20v-2a4.5 4.5 0 019 0v2M9 11a4 4 0 100-8 4 4 0 000 8zM17 11a4 4 0 110-8 4 4 0 010 8zM19.5 20v-2a4.5 4.5 0 00-4.5-4.5"/>
                        </svg>
                        <span class="sidebar-text">Customers</span>
                    </span>
                    <span class="caret transform transition-transform duration-300 {{ request()->is('customers*') ? 'rotate-90' : '' }}">▸</span>
                </button>
                <ul id="customersMenu" class="space-y-1 mt-1 pl-3 {{ request()->is('customers*') ? '' : 'hidden' }}">
                    <li>
                        <a href="/customers/list" class="flex items-center py-1 px-8 text-sm rounded transition
                           {{ request()->is('customers/list') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700 hover:text-blue-300' }}">
                            <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 2H5a2 2 0 00-2 2v16a2 2 0 002 2h14a2 2 0 002-2V8l-6-6H9z"/>
                            </svg>
                            Customer List
                        </a>
                    </li>
                    <li>
                        <a href="/customers/pre-registered" class="flex items-center py-1 px-8 text-sm rounded transition
                           {{ request()->is('customers/pre-registered') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700 hover:text-blue-300' }}">
                            <svg class="h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6l4 2M12 22a10 10 0 100-20 10 10 0 000 20z"/>
                            </svg>
                            Pre-Registered
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Subscriptions -->
            <li>
                <a href="{{ route('subscriptions.index') }}" data-label="Subscriptions"
                   class="flex items-center py-2 px-3 rounded transition
                   {{ request()->is('subscriptions*') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 2l9 4.5-9 4.5-9-4.5L12 2zm0 9l9 4.5-9 4.5-9-4.5L12 11z"/>
                    </svg>
                    <span class="sidebar-text">Subscriptions</span>
                </a>
            </li>

            <!-- Service Requests -->
            <li>
                <a href="{{ route('service_requests.index') }}" data-label="Service Requests"
                   class="flex items-center py-2 px-3 rounded transition
                   {{ request()->is('service-requests*') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5h6M9 9h6M9 13h6M9 17h6M5 7h.01M5 11h.01M5 15h.01M5 19h.01" />
                    </svg>
                    <span class="sidebar-text">Service Requests</span>
                </a>
            </li>

            <!-- Technicians -->
            <li>
                <a href="/technicians/list" class="flex items-center py-2 px-3 rounded transition
                   {{ request()->is('technicians*') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <span class="sidebar-text">Technicians</span>
                </a>
            </li>

            <!-- Billing -->
            <li>
                <a href="{{ route('billing.index') }}" 
                   class="flex items-center py-2 px-3 rounded transition
                   {{ request()->is('billing*') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 14h6M9 10h6M5 21l2-2 2 2 2-2 2 2 2-2 2 2 2-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16z"/>
                    </svg>
                    <span class="sidebar-text">Billing</span>
                </a>
            </li>

            <!-- Announcements -->
            <li>
                <a href="{{ route('announcements.index') }}" data-label="Announcements"
                   class="flex items-center py-2 px-3 rounded-lg transition 
                          {{ request()->is('announcements*') 
                              ? 'bg-indigo-600 text-white font-medium shadow' 
                              : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                         class="h-5 w-5 mr-3 flex-shrink-0" 
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5L6 8H4a2 2 0 00-2 2v4a2 2 0 002 2h2l5 3V5zM15 9a5 5 0 010 6" />
                    </svg>
                    <span class="sidebar-text">Announcements</span>
                </a>
            </li>

            <!-- Reports -->
            <li>
                <a href="{{ route('reports.index') }}" data-label="Reports"
                   class="flex items-center py-2 px-3 rounded transition 
                   {{ request()->is('reports') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    <span class="sidebar-text">Reports</span>
                </a>
            </li>

            <!-- Users -->
            <li>
                <a href="{{ route('users.index') }}" data-label="Users"
                   class="flex items-center py-2 px-3 rounded transition {{ request()->is('users*') ? 'bg-gray-700 text-blue-300 font-semibold' : 'hover:bg-gray-700' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 12a5 5 0 100-10 5 5 0 000 10zm0 2c-4.418 0-8 2.239-8 5v3h16v-3c0-2.761-3.582-5-8-5z"/>
                    </svg>
                    <span class="sidebar-text">Users</span>
                </a>
            </li>

        </ul>
    </nav>
</aside>
