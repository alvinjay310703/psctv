<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PCTVS Login</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <style>
    /* Shake animation for failed login */
    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      20%, 60% { transform: translateX(-8px); }
      40%, 80% { transform: translateX(8px); }
    }
    .shake { animation: shake 0.4s ease-in-out; }

    /* Bounce animation */
    @keyframes bounceSlow {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-15px); }
    }
    .animate-bounceSlow {
      animation: bounceSlow 3s ease-in-out infinite;
    }

    /* Shimmer animation */
    @keyframes shimmer {
      0% { background-position: -200px 0; }
      100% { background-position: 200px 0; }
    }
    .animate-shimmer {
      background-size: 200% auto;
      animation: shimmer 3s linear infinite;
    }

    /* Pulse Glow */
    @keyframes pulseGlow {
      0%, 100% { box-shadow: 0 0 30px rgba(34,197,94,0.3); }
      50% { box-shadow: 0 0 50px rgba(34,197,94,0.5); }
    }
    .animate-pulseGlow { animation: pulseGlow 2.5s ease-in-out infinite; }
  </style>
</head>

<body class="min-h-screen flex bg-gradient-to-br from-green-50 via-white to-green-100">

  <!-- Left Branding Panel -->
  <div class="hidden md:flex w-1/2 bg-gradient-to-br from-green-600 to-green-800 text-white flex-col justify-center items-center p-12 relative overflow-hidden">
    <!-- Circle Logo with Bounce + Glow -->
    <div class="w-36 h-36 rounded-full bg-white flex items-center justify-center shadow-2xl ring-8 ring-green-500/30 relative z-10 animate-bounceSlow">
      <img src="/images/logo.png" alt="PCTVS Logo" class="w-28 h-28 object-contain" />
      <div class="absolute inset-0 rounded-full bg-green-400/30 blur-2xl animate-pulseGlow"></div>
    </div>

    <!-- Title -->
    <h1 class="text-5xl font-extrabold mt-6 bg-gradient-to-r from-white to-green-200 bg-clip-text text-transparent relative z-10">
      Welcome to PCTVS
    </h1>

    <!-- Slogan -->
    <p class="max-w-sm text-center italic text-green-100 mt-2 relative z-10">
      Login to access your dashboard
    </p>

    <!-- Gradient Accent Line -->
    <div class="mt-4 h-1 w-28 rounded-full bg-gradient-to-r from-green-300 via-white to-green-500 shadow-lg animate-shimmer relative z-10"></div>
  </div>

  <!-- Wave Divider -->
  <div class="hidden md:block relative w-16">
    <svg class="absolute inset-0 h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
      <defs>
        <linearGradient id="waveGradient" x1="0" y1="0" x2="1" y2="0">
          <stop offset="0%" stop-color="rgba(22,101,52,0.95)" />
          <stop offset="100%" stop-color="transparent" />
        </linearGradient>
      </defs>
      <path d="M0,0 C30,50 70,50 100,100 L100,0 Z" fill="url(#waveGradient)" />
    </svg>
  </div>

  <!-- Right Login Form -->
  <div class="w-full md:w-1/2 flex items-center justify-center p-8">
    <div class="w-full max-w-md bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-green-200 p-10 relative @if ($errors->any()) shake @endif">

      <!-- Exit Button -->
      <a href="{{ route('landing') }}" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </a>

      <!-- Title -->
      <h2 class="text-3xl font-extrabold text-green-700 mb-2">Sign In</h2>
      <p class="text-sm text-gray-500 mb-6"></p>

      <!-- Error message -->
      @if ($errors->any())
        <div class="mb-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg shadow-sm">
          <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Login Form -->
      <form method="POST" action="/login" class="space-y-5" x-data="{ loading: false, caps: false }" @submit="loading = true">
        @csrf

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
          <div class="relative mt-1">
            <input type="email" id="email" name="email" required
                   class="w-full pl-11 pr-3 py-3 rounded-xl border shadow-inner text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-green-50 placeholder:text-gray-400" placeholder="you@example.com"/>
            <span class="absolute inset-y-0 left-3 flex items-center text-green-600">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                <rect x="3" y="6" width="18" height="12" rx="2" ry="2" stroke-width="2"></rect>
                <path d="M3 6l9 7 9-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
            </span>
          </div>
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
          <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
          <div class="relative mt-1">
            <input :type="show ? 'text' : 'password'" id="password" name="password" required
                   @keyup="caps = $event.getModifierState('CapsLock')"
                   class="w-full pl-11 pr-12 py-3 rounded-xl border shadow-inner text-sm
                          focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:bg-green-50 placeholder:text-gray-400" placeholder="Enter your password"/>
            <span class="absolute inset-y-0 left-3 flex items-center text-green-600">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                <path d="M8 10V8a4 4 0 118 0v2"></path>
                <rect x="6" y="10" width="12" height="10" rx="2" ry="2"></rect>
              </svg>
            </span>
            <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600 transition">
              <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                <circle cx="12" cy="12" r="3" stroke-width="2"></circle>
              </svg>
              <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M13.875 18.825A9.966 9.966 0 0112 19c-4.695 0-8.576-3.108-9.542-7.5A9.978 9.978 0 013 6.75" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
              </svg>
            </button>
          </div>
          <p x-show="caps" class="text-xs text-red-600 mt-1">⚠️ Caps Lock is on</p>
        </div>

        <!-- Submit -->
        <button type="submit"
                class="relative w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800
                       text-white py-3 rounded-full font-semibold text-sm shadow-lg transition duration-200 transform hover:scale-[1.03]">
          <span x-show="!loading">Login</span>
          <svg x-show="loading" class="animate-spin h-5 w-5 mx-auto text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
          </svg>
        </button>
      </form>

      <!-- Footer -->
      <p class="text-xs text-gray-500 text-center mt-6">
        Powered by <span class="text-green-600 font-semibold">PCTVS</span> © 2025
      </p>
    </div>
  </div>
</body>
</html>
