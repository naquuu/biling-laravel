<!DOCTYPE html>
<html lang="id" x-data="{ theme: localStorage.getItem('theme') || '{{ $settings["theme"] ?? "light" }}' }" x-init="$watch('theme', val => { localStorage.setItem('theme', val); document.documentElement.classList.toggle('dark', val === 'dark'); })" :class="{ 'dark': theme === 'dark' }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Billing')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-gray-900">

<div x-data="{ sidebarOpen: false, kategoriOpen: false }" class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform duration-300 -translate-x-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 md:translate-x-0" :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }">
        <div class="h-full px-3 py-4 overflow-y-auto bg-white dark:bg-gray-800">
            <div class="mb-5 flex items-center pl-2.5">
                <span class="self-center text-xl font-semibold text-primary-600 dark:text-primary-400">Aplikasi Billing</span>
            </div>
            
            <ul class="space-y-2">
                <!-- Dashboard -->
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-gray-900 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('dashboard') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                        <svg class="w-6 h-6 {{ request()->routeIs('dashboard') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/></svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>

                <!-- Kategori dengan Submenu -->
                <li>
                    <button @click="kategoriOpen = !kategoriOpen" class="flex items-center w-full p-2 text-gray-900 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group">
                        <svg class="w-6 h-6 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 6a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm2 4h8M8 4v4m4-4v4"/>
                        </svg>
                        <span class="ml-3 flex-1 text-left">Kategori</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': kategoriOpen }" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                    
                    <ul x-show="kategoriOpen" x-collapse class="pl-8 mt-1 space-y-1">
                        @php
                            $books = App\Models\Book::where('is_active', true)->orderBy('code')->get();
                        @endphp
                        @forelse($books as $book)
                        <li>
                            <a href="{{ route('categories.index', ['book' => $book->id]) }}" 
                               class="flex items-center p-2 text-sm text-gray-700 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded mr-2">{{ $book->code }}</span>
                                <span class="truncate">{{ $book->name }}</span>
                            </a>
                        </li>
                        @empty
                        <li class="px-2 py-2 text-sm text-gray-500 dark:text-gray-500">Belum ada buku</li>
                        @endforelse
                        
                        <li class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('books.create') }}" class="flex items-center p-2 text-sm text-primary-600 dark:text-primary-400 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Buku Baru
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Pelanggan & Supplier -->
                <li>
                    <a href="{{ route('parties.index') }}" class="flex items-center p-2 text-gray-900 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('parties.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                        <svg class="w-6 h-6 {{ request()->routeIs('parties.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                        <span class="ml-3">Pelanggan & Supplier</span>
                    </a>
                </li>

                <!-- Transaksi -->
                <li>
                    <a href="{{ route('transactions.index') }}" class="flex items-center p-2 text-gray-900 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('transactions.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                        <svg class="w-6 h-6 {{ request()->routeIs('transactions.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/></svg>
                        <span class="ml-3">Transaksi</span>
                    </a>
                </li>

                <!-- Laporan -->
                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center p-2 text-gray-900 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('reports.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                        <svg class="w-6 h-6 {{ request()->routeIs('reports.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
                        <span class="ml-3">Laporan</span>
                    </a>
                </li>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Pengaturan (Super Admin only) -->
                @if(auth()->user() && auth()->user()->role === 'super_admin')
                <li class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('settings.index') }}" class="flex items-center p-2 text-gray-900 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 group {{ request()->routeIs('settings.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : '' }}">
                        <svg class="w-6 h-6 {{ request()->routeIs('settings.*') ? 'text-primary-600 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z"/>
                        </svg>
                        <span class="ml-3">Pengaturan</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-0 md:ml-64">
        <!-- Navbar -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed top-0 right-0 left-0 z-30 md:left-64">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-end">
                    <!-- Sidebar toggle button (mobile) - DI KIRI -->
                    <button @click="sidebarOpen = !sidebarOpen" class="absolute left-3 md:hidden p-2 text-gray-600 dark:text-gray-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                        </svg>
                    </button>

                    <!-- Profile Dropdown (DI KANAN) -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center gap-3 focus:outline-none">
                            <span class="text-sm text-gray-700 dark:text-gray-300">{{ Auth::user()->name ?? 'Guest' }}</span>
                            <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">
                                {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': profileOpen }" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                            </svg>
                        </button>
                        
                        <!-- Dropdown menu profile -->
                        <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak class="absolute right-0 z-50 w-64 mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-100 dark:border-gray-700">
                            <div class="p-3 border-b border-gray-100 dark:border-gray-700">
                                <p class="font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                                <div class="mt-1">
                                    @if(Auth::user()->role === 'super_admin')
                                        <span class="px-2 py-0.5 text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full">Super Admin</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">Admin</span>
                                    @endif
                                    @if(Auth::user()->is_active ?? true)
                                        <span class="px-2 py-0.5 text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full ml-1">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full ml-1">Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Edit Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-16 p-4">
            <div class="container mx-auto">
                @if(session('success'))
                <div class="p-4 mb-4 text-green-800 dark:text-green-400 bg-green-50 dark:bg-green-900/20 rounded-lg">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="p-4 mb-4 text-red-800 dark:text-red-400 bg-red-50 dark:bg-red-900/20 rounded-lg">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@stack('scripts')

<script>
    // Set tema dari localStorage atau database
    const savedTheme = localStorage.getItem('theme');
    const dbTheme = '{{ $settings["theme"] ?? "light" }}';
    
    if (savedTheme) {
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    } else if (dbTheme === 'dark') {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
</script>
</body>
</html>