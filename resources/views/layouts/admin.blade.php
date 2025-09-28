<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PCTVS Admin - @yield('title')</title>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    

    @vite([
        'resources/css/app.css',
        'resources/css/admin.css',
        'resources/js/app.js',
        'resources/js/admin.js'
    ])

    

</head>
<body class="bg-gray-100 flex">

    


    <!-- Sidebar -->
    @include('layouts.partials.sidebar')

    <!-- Main Content -->
    <div id="mainContent"    class="flex-1 pt-24 px-6 content-expanded transition-all duration-300">

        <!-- Header -->
        @include('layouts.partials.header')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        window.toggleSubmenu = function(id) {
            const menu = document.getElementById(id);
            if (!menu) return;
            menu.classList.toggle('hidden');

            const button = document.querySelector(`[data-target="${id}"] .caret`);
            if (button) button.classList.toggle('rotate-90');
        };

      function toggleUserMenu(event) {
    if (event) event.stopPropagation(); // prevent document click

    const menu = document.getElementById('userMenu');
    if (!menu) return;
    menu.classList.toggle('show');
}

// Close dropdown if clicked outside
document.addEventListener('click', function(e) {
    const menu = document.getElementById('userMenu');
    const button = document.getElementById('userMenuButton');
    if (!menu) return;

    if (button.contains(e.target)) return; // clicking button already handled

    if (!menu.contains(e.target)) {
        menu.classList.remove('show');
    }
});

    </script>

    {{-- ✅ Render all scripts pushed from child views --}}
    @stack('scripts')
</body>
</html>
