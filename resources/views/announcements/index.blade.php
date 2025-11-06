@extends('layouts.admin')

@section('title', 'Announcements')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-indigo-50/30 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8 animate-fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="mb-4 lg:mb-0">
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2">Announcements</h1>
                    <p class="text-gray-600">Manage and publish announcements across your platform</p>
                </div>
                <a href="{{ route('announcements.create') }}" 
                   class="group relative bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Announcement
                    <div class="absolute inset-0 rounded-xl bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 animate-slide-up" style="animation-delay: 0.1s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Announcements</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $announcements->total() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9m0 0v12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 animate-slide-up" style="animation-delay: 0.2s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Active</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">
                            {{ $announcements->where('status', 'Active')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 animate-slide-up" style="animation-delay: 0.3s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Scheduled</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">
                            {{ $announcements->where('status', 'Scheduled')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-lg border border-white/50 animate-slide-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">High Priority</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">
                            {{ $announcements->where('priority', 'high')->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-white/50 overflow-hidden animate-fade-in" style="animation-delay: 0.5s">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100/50 border-b border-gray-200">
                        <tr>
                            <th class="p-4 text-left text-sm font-semibold text-gray-700">Title & Content</th>
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Audience</th>
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Priority</th>
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Schedule</th>
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Status</th>
                            <th class="p-4 text-center text-sm font-semibold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($announcements as $index => $announcement)
                        <tr class="hover:bg-gray-50/80 transition-all duration-300 animate-fade-in" style="animation-delay: {{ 0.6 + ($index * 0.1) }}s">
                            <td class="p-4">
                                <div class="max-w-xs">
                                    <div class="font-semibold text-gray-900 mb-1">{{ $announcement->title }}</div>
                                    <div class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($announcement->content, 80) }}</div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($announcement->audience) }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold transition-all duration-300
                                    {{ $announcement->priority === 'high' ? 'bg-red-100 text-red-700 shadow-sm' : 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($announcement->priority) }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="space-y-1">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $announcement->start_date ? $announcement->start_date->format('M d, Y H:i') : '—' }}
                                    </div>
                                    <div class="text-xs text-gray-500">to</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $announcement->end_date ? $announcement->end_date->format('M d, Y H:i') : '—' }}
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold transition-all duration-300
                                    @if($announcement->status === 'Active') bg-green-100 text-green-700 shadow-sm
                                    @elseif($announcement->status === 'Scheduled') bg-yellow-100 text-yellow-700
                                    @elseif($announcement->status === 'Expired') bg-gray-100 text-gray-600
                                    @endif">
                                    {{ $announcement->status }}
                                </span>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('announcements.show', $announcement) }}" 
                                       class="group relative p-2 text-gray-400 hover:text-blue-600 transition-all duration-300 transform hover:scale-110"
                                       title="View">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <div class="absolute inset-0 bg-blue-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                    </a>
                                    <a href="{{ route('announcements.edit', $announcement) }}" 
                                       class="group relative p-2 text-gray-400 hover:text-yellow-600 transition-all duration-300 transform hover:scale-110"
                                       title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <div class="absolute inset-0 bg-yellow-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                    </a>
                                    <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this announcement?');"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="group relative p-2 text-gray-400 hover:text-red-600 transition-all duration-300 transform hover:scale-110"
                                                title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            <div class="absolute inset-0 bg-red-100 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 -z-10"></div>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-lg font-medium text-gray-400 mb-2">No announcements found</p>
                                    <p class="text-sm text-gray-400">Get started by creating your first announcement</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($announcements->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
                {{ $announcements->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
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

    .animate-fade-in {
        animation: fade-in 0.6s ease-out forwards;
    }

    .animate-slide-up {
        animation: slide-up 0.6s ease-out forwards;
    }

    /* Custom pagination styling */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination li {
        display: inline-block;
    }

    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .pagination li a {
        background: white;
        border: 1px solid #e5e7eb;
        color: #6b7280;
    }

    .pagination li a:hover {
        background: #f8fafc;
        border-color: #d1d5db;
        color: #374151;
        transform: translateY(-1px);
    }

    .pagination li span {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        border: 1px solid transparent;
    }

    .pagination li:first-child a,
    .pagination li:last-child a {
        padding: 0 1rem;
    }
</style>
@endsection