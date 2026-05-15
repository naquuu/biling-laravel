<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Aplikasi Billing')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">

<div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0" aria-label="Sidenav">
        <div class="h-full px-3 py-4 overflow-y-auto">
            <!-- Logo -->
            <div class="mb-5 flex items-center pl-2.5">
                <span class="self-center text-xl font-semibold whitespace-nowrap text-primary-600">Aplikasi Billing</span>
            </div>
            
            <!-- Menu -->
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('dashboard') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ request()->routeIs('dashboard') ? 'text-primary-600' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                        </svg>
                        <span class="ml-3">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('books.index') }}" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('books.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ request()->routeIs('books.*') ? 'text-primary-600' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6h16v2H4V6zm2-4h12v2H6V2zm16 4v12H2V6h20zm-6 6H6v2h10v-2z"/>
                        </svg>
                        <span class="ml-3">Buku</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('parties.index') }}" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('parties.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ request()->routeIs('parties.*') ? 'text-primary-600' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span class="ml-3">Pelanggan & Supplier</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('transactions.index') }}" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('transactions.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ request()->routeIs('transactions.*') ? 'text-primary-600' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14h-2v-4H8v-2h2V9h2v2h2v2h-2v4z"/>
                        </svg>
                        <span class="ml-3">Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('reports.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ request()->routeIs('reports.*') ? 'text-primary-600' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-8 14H7v-2h4v2zm0-4H7v-2h4v2zm0-4H7V7h4v2zm6 6h-4v-2h4v2zm0-4h-4v-2h4v2zm0-4h-4V7h4v2z"/>
                        </svg>
                        <span class="ml-3">Laporan</span>
                    </a>
                </li>
                @if(auth()->user() && auth()->user()->role === 'super_admin')
                <li>
                    <hr class="my-4 border-gray-200">
                    <a href="{{ route('settings.index') }}" class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group {{ request()->routeIs('settings.*') ? 'bg-primary-50 text-primary-600' : '' }}">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 {{ request()->routeIs('settings.*') ? 'text-primary-600' : '' }}" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94 0 .31.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.57 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-2 0-3.6-1.6-3.6-3.6s1.6-3.6 3.6-3.6 3.6 1.6 3.6 3.6-1.6 3.6-3.6 3.6z"/>
                        </svg>
                        <span class="ml-3">Pengaturan</span>
                    </a>
                </li>
                @endif
            </ul>
            
            <!-- Logout -->
            <div class="absolute bottom-0 left-0 w-full p-4 border-t border-gray-200">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full p-2 text-base font-medium text-gray-900 rounded-lg hover:bg-gray-100 group">
                        <svg class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
                        </svg>
                        <span class="ml-3">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-0 sm:ml-64">
        <!-- Navbar -->
        <nav class="bg-white border-b border-gray-200 fixed top-0 right-0 left-0 z-30 sm:left-64">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start">
                        <button class="sm:hidden p-2 text-gray-600 rounded-lg hover:bg-gray-100" id="sidebarToggle">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h16v2H4v-2z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                        <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-16 p-4">
            <div class="container mx-auto">
                @if(session('success'))
                    <div class="flex items-center p-4 mb-4 text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.5 9.5 0 0 0 10 .5zm-2 14L4 10l1.5-1.5L8 11l6-6 1.5 1.5L8 14.5z"/>
                        </svg>
                        <div class="ml-3 text-sm font-medium">{{ session('success') }}</div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="flex items-center p-4 mb-4 text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                        <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.5 9.5 0 0 0 10 .5zm1 14h-2v-2h2v2zm0-4h-2V5h2v5.5z"/>
                        </svg>
                        <div class="ml-3 text-sm font-medium">{{ session('error') }}</div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</div>

<script>
    // Sidebar toggle untuk mobile
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('aside');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }
</script>

@stack('scripts')
</body>
</html>