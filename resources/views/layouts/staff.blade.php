<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCTVS Staff - @yield('title')</title>

    @vite([
        'resources/css/app.css',
        'resources/css/admin.css',
        'resources/js/app.js',
        'resources/js/admin.js'
    ])
</head>
<body class="bg-gray-100 flex">

<!-- Sidebar -->
<aside id="sidebar" 
       class="sidebar-expanded bg-gray-800 text-white h-screen p-5 fixed top-0 left-0 transition-all duration-300 overflow-hidden">
    <h2 id="sidebarTitle" class="text-xl font-bold mb-6">PCTVS Staff</h2>

    <nav>
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="/staff/dashboard" 
                   class="flex items-center py-2 px-3 hover:bg-gray-700 rounded">
                    <span class="text-xl mr-3">📊</span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <!-- Service Requests -->
            <li>
                <a href="/staff/requests" 
                   class="flex items-center py-2 px-3 hover:bg-gray-700 rounded">
                    <span class="text-xl mr-3">📋</span>
                    <span class="sidebar-text">My Requests</span>
                </a>
            </li>

            <!-- Pre-Registered Customers -->
            <li>
                <a href="/staff/pre-registered" 
                   class="flex items-center py-2 px-3 hover:bg-gray-700 rounded">
                    <span class="text-xl mr-3">🕓</span>
                    <span class="sidebar-text">Pre-Registered</span>
                </a>
            </li>

            <!-- My Reports -->
            <li>
                <a href="/staff/reports" 
                   class="flex items-center py-2 px-3 hover:bg-gray-700 rounded">
                    <span class="text-xl mr-3">📝</span>
                    <span class="sidebar-text">My Reports</span>
                </a>
            </li>

            <!-- Announcements -->
            <li>
                <a href="/staff/announcements" 
                   class="flex items-center py-2 px-3 hover:bg-gray-700 rounded">
                    <span class="text-xl mr-3">📢</span>
                    <span class="sidebar-text">Announcements</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>

<!-- Main Content -->
<div id="mainContent" class="flex-1 p-8 ml-60 transition-all duration-300">
    <!-- Header -->
    <header class="flex items-center mb-6 border-b pb-3 space-x-4">
        <!-- Toggle Button -->
        <button onclick="toggleSidebar()" 
            class="bg-gray-800 text-white px-3 py-1 rounded transition-all duration-300"
            id="sidebarToggle">
            ☰
        </button>

        <h1 class="text-2xl font-bold">@yield('title')</h1>

        <!-- Account Section -->
        <div class="ml-auto flex items-center space-x-3 bg-white px-4 py-2 rounded-full shadow-md">
            <span class="text-xl">👤</span>
            <div>
                <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500">Staff</p>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="text-red-600 hover:text-red-800">Logout</button>
            </form>
        </div>
    </header>

    <!-- Content -->
    <main>
        @yield('content')   
    </main>
</div>

<!-- Sidebar Toggle Script -->
@push('scripts')
<script>
    window.toggleSidebar = function () {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('mainContent');
        const texts = document.querySelectorAll('.sidebar-text');
        const title = document.getElementById('sidebarTitle');

        sidebar.classList.toggle('sidebar-expanded');
        sidebar.classList.toggle('sidebar-collapsed');

        content.classList.toggle('ml-60');
        content.classList.toggle('ml-20');

        texts.forEach(el => {
            el.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'inline';
        });

        title.style.display = sidebar.classList.contains('sidebar-collapsed') ? 'none' : 'block';
    };
</script>
@endpush

</body>
</html>
