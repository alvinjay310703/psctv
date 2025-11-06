@extends('layouts.admin')

@section('title', 'View Announcement')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50/30 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in-down">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                            <img src="{{ asset('images/logo.png') }}" alt="Company Logo" class="w-10 h-10 object-contain">
                        </div>
                        <div class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl blur opacity-20"></div>
                    </div>
                    <div>
                        <h1 class="text-3xl lg:text-4xl font-bold text-slate-900">Announcement Details</h1>
                        <p class="text-slate-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            Published on {{ $announcement->created_at->format('F d, Y') }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($announcement->status === 'Active') bg-emerald-100 text-emerald-700 border border-emerald-200
                        @elseif($announcement->status === 'Scheduled') bg-amber-100 text-amber-700 border border-amber-200
                        @elseif($announcement->status === 'Expired') bg-slate-100 text-slate-600 border border-slate-200
                        @endif shadow-sm">
                        {{ $announcement->status }}
                    </span>
                    <span class="px-3 py-2 rounded-full text-sm font-semibold
                        {{ $announcement->priority === 'high' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }} shadow-sm">
                        {{ ucfirst($announcement->priority) }} Priority
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-white/50 overflow-hidden animate-slide-up">
            
            <!-- Content Header -->
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-8 py-6 text-white">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold">{{ $announcement->title }}</h2>
                            <p class="text-slate-300 flex items-center text-sm">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Created by {{ $announcement->user->name ?? 'System' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-300 text-sm">Smart Cable Service</p>
                        <p class="text-white font-semibold">Internal Announcement</p>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <div class="p-8 space-y-8">
                
                <!-- Announcement Content -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-8 border border-slate-200/50 shadow-sm">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-800">Announcement Content</h3>
                    </div>
                    <div class="prose prose-lg max-w-none text-slate-700 leading-relaxed bg-white/50 rounded-xl p-6 border border-slate-200/50">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Audience Card -->
                    <div class="group bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-fade-in" style="animation-delay: 0.1s">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">Target Audience</h3>
                                <p class="text-2xl font-bold text-slate-900 capitalize mt-1">{{ $announcement->audience }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Priority Card -->
                    <div class="group bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-fade-in" style="animation-delay: 0.2s">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">Priority Level</h3>
                                <p class="text-2xl font-bold text-slate-900 capitalize mt-1">{{ $announcement->priority }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="group bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-fade-in" style="animation-delay: 0.3s">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">Current Status</h3>
                                <p class="text-2xl font-bold text-slate-900 capitalize mt-1">{{ $announcement->status }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Start Date Card -->
                    <div class="group bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-fade-in" style="animation-delay: 0.4s">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">Start Date</h3>
                                <p class="text-lg font-semibold text-slate-900 mt-1">
                                    {{ $announcement->start_date ? $announcement->start_date->format('M d, Y \\a\\t H:i') : 'Not scheduled' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- End Date Card -->
                    <div class="group bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-fade-in" style="animation-delay: 0.5s">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">End Date</h3>
                                <p class="text-lg font-semibold text-slate-900 mt-1">
                                    {{ $announcement->end_date ? $announcement->end_date->format('M d, Y \\a\\t H:i') : 'Not scheduled' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Created By Card -->
                    <div class="group bg-white rounded-2xl p-6 border border-slate-200/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:scale-105 animate-fade-in" style="animation-delay: 0.6s">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-800">Created By</h3>
                                <p class="text-lg font-semibold text-slate-900 mt-1">
                                    {{ $announcement->user->name ?? 'System Administrator' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline Section -->
                <div class="bg-gradient-to-br from-slate-50 to-blue-50/30 rounded-2xl p-8 border border-slate-200/50 shadow-sm">
                    <h3 class="text-xl font-semibold text-slate-800 mb-6 flex items-center gap-3">
                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        Announcement Timeline
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
                        <div class="space-y-2">
                            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-800">Created</p>
                            <p class="text-sm text-slate-600">{{ $announcement->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="space-y-2">
                            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-800">Last Updated</p>
                            <p class="text-sm text-slate-600">{{ $announcement->updated_at->format('M d, Y') }}</p>
                        </div>
                        <div class="space-y-2">
                            <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto">
                                <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-slate-800">Duration</p>
                            <p class="text-sm text-slate-600">
                                @if($announcement->start_date && $announcement->end_date)
                                    {{ $announcement->start_date->diffInDays($announcement->end_date) }} days
                                @else
                                    Not set
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-200">
                    <a href="{{ route('announcements.edit', $announcement) }}"
                       class="group relative bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-8 py-4 rounded-2xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Announcement
                        <div class="absolute inset-0 rounded-2xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </a>
                    
                    <a href="{{ route('announcements.index') }}"
                       class="group bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 hover:border-slate-300 px-8 py-4 rounded-2xl font-semibold shadow-sm hover:shadow transition-all duration-300 transform hover:scale-105 flex items-center justify-center gap-3">
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Announcements
                    </a>
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
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
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

    .prose {
        line-height: 1.75;
    }

    .prose p {
        margin-bottom: 1rem;
    }

    .prose p:last-child {
        margin-bottom: 0;
    }
</style>
@endsection