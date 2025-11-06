@extends('layouts.admin')

@section('title', 'Add User')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/20 to-indigo-50/10 py-8 px-4 sm:px-6 lg:px-8">
    <!-- Background Decoration -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -right-40 w-80 h-80 rounded-full bg-blue-200/20 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full bg-indigo-200/20 blur-3xl"></div>
    </div>

    <div class="max-w-md mx-auto relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-8 animate-fade-in-down">
            <div class="flex justify-center mb-4">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="absolute -inset-2 bg-gradient-to-r from-blue-500 to-purple-600 rounded-3xl blur-xl opacity-30 animate-pulse-slow"></div>
                </div>
            </div>
            <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent mb-3">Add New User</h1>
            <p class="text-slate-600 text-lg">Create a new user account with specific role permissions</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/60 p-8 animate-slide-up relative overflow-hidden">
            <!-- Card Background Pattern -->
            <div class="absolute inset-0 opacity-5">
                <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23000000\" fill-opacity=\"0.1\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="mb-6 bg-gradient-to-r from-rose-50 to-pink-50 border border-rose-200/60 rounded-2xl p-4 animate-shake relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-rose-500/5 to-pink-500/5"></div>
                    <div class="relative flex items-start gap-3">
                        <div class="w-6 h-6 bg-rose-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-rose-800 font-medium text-sm mb-2">Please fix the following errors:</p>
                            <ul class="text-rose-700 text-sm space-y-1">
                                @foreach($errors->all() as $error)
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 bg-rose-400 rounded-full"></span>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors hover:scale-110 transform duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('users.store') }}" method="POST" autocomplete="off" class="space-y-6 relative" 
                  x-data="{ 
                      loading: false,
                      showPassword: false,
                      showConfirmPassword: false,
                      password: '',
                      passwordStrength: 0,
                      passwordSuggestions: [],
                      checkPasswordStrength() {
                          let strength = 0;
                          let suggestions = [];
                          
                          // Length check
                          if (this.password.length >= 8) strength += 25;
                          else suggestions.push('Use at least 8 characters');
                          
                          // Lowercase check
                          if (/[a-z]/.test(this.password)) strength += 25;
                          else suggestions.push('Include lowercase letters');
                          
                          // Uppercase check
                          if (/[A-Z]/.test(this.password)) strength += 25;
                          else suggestions.push('Include uppercase letters');
                          
                          // Numbers check
                          if (/[0-9]/.test(this.password)) strength += 15;
                          else suggestions.push('Include numbers');
                          
                          // Special characters check
                          if (/[^A-Za-z0-9]/.test(this.password)) strength += 10;
                          else suggestions.push('Include special characters');
                          
                          this.passwordStrength = strength;
                          this.passwordSuggestions = suggestions;
                      }
                  }" 
                  @submit="loading = true">
                @csrf

                <!-- Name Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        Full Name
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm"
                           placeholder="Enter full name"
                           required>
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-emerald-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        Email Address
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="new-email"
                           class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-green-500/20 focus:border-green-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm"
                           placeholder="Enter email address"
                           required>
                </div>

                <!-- Password Field -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        Password
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" 
                               name="password" 
                               x-model="password"
                               @input="checkPasswordStrength()"
                               autocomplete="new-password"
                               class="w-full px-4 pr-12 py-3.5 border-2 border-slate-200/80 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm"
                               placeholder="Create a strong password"
                               required>
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-amber-600 transition-colors duration-200 hover:scale-110">
                            <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Password Strength Meter -->
                    <div x-show="password.length > 0" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-slate-600">Password Strength</span>
                            <span class="text-xs font-semibold" 
                                  :class="{
                                      'text-rose-600': passwordStrength < 40,
                                      'text-amber-600': passwordStrength >= 40 && passwordStrength < 70,
                                      'text-lime-600': passwordStrength >= 70 && passwordStrength < 90,
                                      'text-emerald-600': passwordStrength >= 90
                                  }">
                                <template x-if="passwordStrength < 40">Weak</template>
                                <template x-if="passwordStrength >= 40 && passwordStrength < 70">Fair</template>
                                <template x-if="passwordStrength >= 70 && passwordStrength < 90">Good</template>
                                <template x-if="passwordStrength >= 90">Strong</template>
                            </span>
                        </div>
                        
                        <!-- Strength Bar -->
                        <div class="w-full bg-slate-200 rounded-full h-2 mb-3 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500 ease-out" 
                                 :class="{
                                     'bg-rose-500': passwordStrength < 40,
                                     'bg-amber-500': passwordStrength >= 40 && passwordStrength < 70,
                                     'bg-lime-500': passwordStrength >= 70 && passwordStrength < 90,
                                     'bg-emerald-500': passwordStrength >= 90
                                 }"
                                 :style="`width: ${passwordStrength}%`">
                            </div>
                        </div>

                        <!-- Password Suggestions -->
                        <div x-show="passwordSuggestions.length > 0" x-transition:enter="transition ease-out duration-300" 
                             class="bg-amber-50/80 border border-amber-200/60 rounded-xl p-3">
                            <p class="text-xs font-medium text-amber-800 mb-2 flex items-center gap-2">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Suggestions to improve your password:
                            </p>
                            <ul class="text-xs text-amber-700 space-y-1">
                                <template x-for="suggestion in passwordSuggestions" :key="suggestion">
                                    <li class="flex items-center gap-2">
                                        <span class="w-1 h-1 bg-amber-400 rounded-full"></span>
                                        <span x-text="suggestion"></span>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input :type="showConfirmPassword ? 'text' : 'password'" 
                               name="password_confirmation" 
                               autocomplete="new-password"
                               class="w-full px-4 pr-12 py-3.5 border-2 border-slate-200/80 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-purple-500/20 focus:border-purple-500 transition-all duration-300 bg-white/70 placeholder:text-slate-400 backdrop-blur-sm"
                               placeholder="Confirm your password"
                               required>
                        <button type="button" 
                                @click="showConfirmPassword = !showConfirmPassword"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-purple-600 transition-colors duration-200 hover:scale-110">
                            <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Role Field -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700 flex items-center gap-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        User Role
                    </label>
                    <select name="role" 
                            class="w-full px-4 py-3.5 border-2 border-slate-200/80 rounded-2xl text-sm focus:outline-none focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-300 bg-white/70 appearance-none cursor-pointer backdrop-blur-sm">
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="staff" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff Member</option>
                        <option value="technician" {{ old('role') === 'technician' ? 'selected' : '' }}>Technician</option>
                        <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <!-- Role Description -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-4 border border-slate-200/60 backdrop-blur-sm">
                    <h4 class="text-sm font-semibold text-slate-800 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Role Permissions
                    </h4>
                    <div class="text-xs text-slate-600 space-y-1">
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-purple-400 rounded-full"></span> <strong>Admin:</strong> Full system access & user management</p>
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> <strong>Staff:</strong> Limited administrative access</p>
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span> <strong>Technician:</strong> Service job management only</p>
                        <p class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span> <strong>Customer:</strong> Basic user access</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-200/60">
                    <a href="{{ route('users.index') }}" 
                       class="group bg-white/80 border-2 border-slate-200/60 text-slate-700 hover:bg-white hover:border-slate-300 px-6 py-3.5 rounded-2xl text-sm font-semibold shadow-sm hover:shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-2 sm:order-1 backdrop-blur-sm">
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Users
                    </a>
                    
                    <button type="submit" 
                            class="group relative bg-gradient-to-r from-blue-500 via-indigo-500 to-purple-600 hover:from-blue-600 hover:via-indigo-600 hover:to-purple-700 text-white px-6 py-3.5 rounded-2xl text-sm font-semibold shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-2 order-1 sm:order-2 overflow-hidden"
                            :disabled="loading">
                        <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <svg x-show="!loading" class="w-5 h-5 relative z-10 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <svg x-show="loading" class="animate-spin h-5 w-5 text-white relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16 8 8 0 01-8-8z"></path>
                        </svg>
                        <span class="relative z-10" x-text="loading ? 'Creating User...' : 'Create User'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Tips -->
        <div class="mt-6 bg-gradient-to-br from-blue-50/80 to-indigo-50/60 border border-blue-200/60 rounded-2xl p-6 animate-fade-in backdrop-blur-sm" style="animation-delay: 0.3s">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="space-y-2">
                    <h3 class="text-sm font-semibold text-blue-900">Security Best Practices</h3>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li class="flex items-center gap-2">• Use strong, unique passwords with mixed characters</li>
                        <li class="flex items-center gap-2">• Assign minimal required role permissions</li>
                        <li class="flex items-center gap-2">• Enable two-factor authentication when available</li>
                        <li class="flex items-center gap-2">• Regularly audit user access and permissions</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slide-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fade-in {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }

    @keyframes pulse-slow {
        0%, 100% { opacity: 0.3; }
        50% { opacity: 0.5; }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }

    .animate-pulse-slow {
        animation: pulse-slow 3s ease-in-out infinite;
    }

    /* Custom select arrow */
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 1rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 6px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endsection