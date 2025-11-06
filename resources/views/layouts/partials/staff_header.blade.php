<header id="mainHeader" class="fixed top-0 left-60 right-0 z-40 flex items-center bg-white shadow-md px-6 py-3 border-b border-gray-200 transition-all duration-300">

    <!-- Sidebar Toggle -->
    <button onclick="toggleSidebar()" 
        class="flex items-center justify-center w-10 h-10 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition duration-200"
        id="sidebarToggle">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <h1 class="text-2xl font-semibold text-gray-800 ml-4">@yield('title')</h1>

    <div class="ml-auto flex items-center space-x-6">

        <!-- 🔍 Staff Search (assigned requests/customers) -->
        <div x-data="{
            open: false,
            query: '',
            results: [],
            activeIndex: 0,

            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    this.open = false;
                    return;
                }

                const res = await fetch(`/staff/search?q=${this.query}`);
                this.results = await res.json();
                this.open = true;
                this.activeIndex = 0;
            },

            navigate(direction) {
                if (!this.open || this.results.length === 0) return;
                const max = this.results.length - 1;
                if (direction === 'up') {
                    this.activeIndex = this.activeIndex > 0 ? this.activeIndex - 1 : max;
                } else {
                    this.activeIndex = this.activeIndex < max ? this.activeIndex + 1 : 0;
                }
            },

            selectActive() {
                if (this.results[this.activeIndex]) {
                    window.location.href = this.results[this.activeIndex].url;
                }
            }
        }"
        class="relative hidden sm:block"
        >
            <input 
                type="text"
                placeholder="Search assigned requests or customers..." 
                x-model="query"
                @input.debounce.400ms="search()"
                @keydown.arrow-down.prevent="navigate('down')"
                @keydown.arrow-up.prevent="navigate('up')"
                @keydown.enter.prevent="selectActive()"
                @click.away="open = false"
                class="pl-10 pr-4 py-2 w-72 rounded-lg border border-gray-300 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300 shadow-sm hover:shadow-md"
            />
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 1110.5 3a7.5 7.5 0 016.15 13.65z" />
                </svg>
            </span>

            <!-- Dropdown Results -->
            <div x-show="open && results.length > 0" x-transition class="absolute top-full left-0 mt-2 w-full bg-white border rounded-lg shadow-lg z-50 max-h-64 overflow-y-auto">
                <template x-for="(item, index) in results" :key="item.type + item.id">
                    <a :href="item.url"
                       :class="{
                           'bg-blue-50 text-blue-700': index === activeIndex,
                           'hover:bg-gray-100': index !== activeIndex
                       }"
                       class="flex items-center px-4 py-2 text-sm text-gray-700 transition cursor-pointer">
                        <span x-text="item.icon" class="mr-2"></span>
                        <div class="flex-1">
                            <span class="font-semibold" x-text="item.name"></span>
                            <p class="text-xs text-gray-500" x-text="item.type"></p>
                        </div>
                    </a>
                </template>
            </div>

            <div x-show="open && results.length === 0 && query.length > 1"
                 class="absolute top-full left-0 mt-2 w-full bg-white border rounded-lg shadow p-3 text-gray-500 text-sm">
                No results found.
            </div>
        </div>

        <!-- 🔔 Staff Notifications -->
        @php
            $user = Auth::user();
            $notifications = $user ? $user->unreadNotifications()->take(5)->get() : collect();
        @endphp
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="relative text-gray-600 hover:text-gray-800 transition duration-200" id="notifBell">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>

                <span id="notifCount"
                      class="absolute -top-1 -right-2 bg-red-500 text-white text-xs rounded-full px-1 {{ $notifications->count() > 0 ? '' : 'hidden' }}">
                    {{ $notifications->count() }}
                </span>
            </button>

            <!-- Notification Dropdown -->
            <div x-show="open" @click.away="open = false" x-transition.opacity
                class="absolute right-0 mt-3 w-80 bg-white shadow-lg rounded-md border z-50 overflow-hidden transition-all duration-200">
                
                <div class="p-3 border-b font-semibold text-gray-700 flex justify-between items-center">
                    <span>Notifications</span>
                    <a href="{{ route('staff.notifications.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                </div>

                <ul id="notifList" class="max-h-64 overflow-y-auto">
                    @forelse($notifications as $note)
                        @php
                            $isRead = !is_null($note->read_at);
                            $type = class_basename($note->type);
                            $icon = match($type) {
                                'NewAnnouncementNotification' => ['📢', 'text-orange-600'],
                                'TechnicianAssigned' => ['🧰', 'text-blue-600'],
                                'ServiceCompleted' => ['✅', 'text-green-600'],
                                default => ['🔔', 'text-gray-600'],
                            };
                        @endphp
                        <li class="px-4 py-3 hover:bg-gray-50 border-b {{ $isRead ? '' : 'bg-blue-50 shadow-sm border border-blue-100' }}"
                            @if(!empty($note->data['url'])) onclick="window.location='{{ $note->data['url'] }}'" style="cursor:pointer;" @endif>
                            <p class="text-sm font-semibold text-gray-800">{{ $icon[0] }} 
                                @switch($type)
                                    @case('NewAnnouncementNotification')
                                        New Announcement: {{ $note->data['title'] ?? 'Untitled' }}
                                        @break
                                    @case('TechnicianAssigned')
                                        Service Request Assigned
                                        @break
                                    @case('ServiceCompleted')
                                        Service Request Completed
                                        @break
                                    @default
                                        {{ $note->data['message'] ?? 'Notification' }}
                                @endswitch
                            </p>
                            @if(!empty($note->data['message']))
                                <p class="text-xs text-gray-600 line-clamp-2">{{ Str::limit(strip_tags($note->data['message']), 120) }}</p>
                            @endif
                            <span class="block text-[10px] text-gray-400 mt-1">{{ $note->created_at->diffForHumans() }}</span>
                        </li>
                    @empty
                        <li class="px-4 py-3 text-sm text-gray-500 text-center">No new notifications</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- 🌙 Dark Mode Toggle -->
        <button @click="theme = theme === 'light' ? 'dark' : 'light'; applyTheme();"
            class="flex items-center justify-center w-10 h-10 bg-gray-50 hover:bg-gray-100 text-gray-600 hover:text-gray-800 rounded-lg transition-all duration-200 shadow-sm hover:shadow-md"
            x-data="{ theme: localStorage.getItem('theme') || 'light', applyTheme() { document.documentElement.setAttribute('data-theme', this.theme); localStorage.setItem('theme', this.theme); } }"
            x-tooltip="Toggle theme">
            <svg x-show="theme === 'light'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <svg x-show="theme === 'dark'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
        </button>

        <!-- 👤 User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open"
                class="flex items-center space-x-3 bg-white px-4 py-2 rounded-full shadow hover:shadow-lg border transition duration-200">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                     alt="Avatar" class="w-9 h-9 rounded-full" />
                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4 text-gray-500 transition-transform duration-200"
                     :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition.opacity
                 class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                <a href="{{ route('staff.profile.show') }}"
                   class="block px-4 py-2 text-gray-700 hover:bg-gray-100 transition">👤 Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 transition">
                        🚪 Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>

<script>
window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ config("broadcasting.connections.pusher.key") }}',
    cluster: '{{ config("broadcasting.connections.pusher.options.cluster") }}',
    forceTLS: true,
});

const staffId = {{ Auth::id() }};

window.Echo.private(`App.Models.User.${staffId}`)
    .notification((data) => {
        console.log("🔔 Staff Notification:", data);

        const badge = document.querySelector('#notifCount');
        const notifList = document.querySelector('#notifList');

        // Update badge
        if(badge){
            const count = parseInt(badge.innerText||0) + 1;
            badge.innerText = count;
            badge.classList.remove('hidden');
        }

        // Prepend new notification
        if(notifList){
            const li = document.createElement('li');
            li.className = "px-4 py-3 hover:bg-gray-50 border-b bg-blue-50 shadow-sm border border-blue-100";
            li.style.cursor = data.url ? "pointer" : "default";
            li.innerHTML = `
                <p class="text-sm font-semibold text-gray-800">${data.icon ?? '📢'} ${data.title ?? 'Notification'}</p>
                <p class="text-xs text-gray-600 truncate">${data.message ?? ''}</p>
                <span class="block text-[10px] text-gray-400 mt-1">Just now</span>
            `;
            if(data.url) li.addEventListener('click',()=> window.location.href=data.url);
            notifList.prepend(li);
        }

        // Animate bell
        const bell = document.querySelector('#notifBell');
        if(bell) bell.classList.add('animate-pulse');
        setTimeout(()=> bell.classList.remove('animate-pulse'),1500);
    });
</script>

</header>
    