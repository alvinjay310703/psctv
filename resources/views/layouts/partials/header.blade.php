<header id="mainHeader" class="fixed top-0 left-60 right-0 z-40 flex items-center bg-white shadow-md px-6 py-3 border-b border-gray-200 transition-all duration-300">
    <!-- Sidebar Toggle -->
    <button onclick="toggleSidebar()" 
        class="flex items-center justify-center w-10 h-10 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition duration-200"
        id="sidebarToggle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Page Title -->
    <h1 class="text-2xl font-semibold text-gray-800 ml-4">@yield('title')</h1>

    <!-- Right Side -->
    <div class="ml-auto flex items-center space-x-6">

        <!-- Search -->
        <div class="relative">
            <input type="text" placeholder="Search..." 
                class="pl-10 pr-4 py-2 w-64 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 shadow-sm hover:shadow-md" />
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </span>
        </div>

        <!-- Notifications -->
        <button class="relative text-gray-600 hover:text-gray-800 transition duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1">3</span>
        </button>

        <!-- User Dropdown -->
        <div class="relative">
            <button id="userMenuButton" onclick="toggleUserMenu(event)" 
                class="flex items-center space-x-3 bg-white px-4 py-2 rounded-full shadow hover:shadow-lg border transition duration-200">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" 
                    alt="Avatar" class="w-9 h-9 rounded-full" />
                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="userMenu" 
                 class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md border py-2 opacity-0 pointer-events-none transform -translate-y-2 transition-all duration-200">
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">Profile</a>
                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150">Settings</a>
                <form action="{{ route('logout') }}" method="POST" class="block">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition duration-150">Logout</button>
                </form>
            </div>
        </div>

    </div>
</header>
