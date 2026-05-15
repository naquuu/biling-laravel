@extends('layouts.flowbite')

@section('title', 'Pelanggan & Supplier')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Pelanggan & Supplier</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola data pelanggan, supplier, karyawan, dan sopir</p>
</div>

<div class="flex justify-between items-center flex-wrap gap-2 mb-4">
    <a href="{{ route('parties.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Data
    </a>
</div>

<!-- Search Bar -->
<div class="mb-4">
    <form method="GET" action="{{ route('parties.index') }}" id="searchForm">
        <div class="relative">
            <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                   placeholder="Cari berdasarkan nama atau nomor telepon..." 
                   class="w-full md:w-96 px-4 py-2 pl-10 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="hidden" name="type" id="typeInput" value="{{ request('type') }}">
    </form>
</div>

<!-- Filter Tabs -->
<div class="mb-4 flex flex-wrap gap-2">
    <a href="#" data-type="" class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all {{ !request('type') ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
        Semua
    </a>
    <a href="#" data-type="customer" class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('type') == 'customer' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
        Pelanggan
    </a>
    <a href="#" data-type="supplier" class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('type') == 'supplier' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
        Supplier
    </a>
    <a href="#" data-type="employee" class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('type') == 'employee' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
        Karyawan
    </a>
    <a href="#" data-type="driver" class="filter-tab px-4 py-2 rounded-lg text-sm font-medium transition-all {{ request('type') == 'driver' ? 'bg-primary-500 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600' }}">
        Sopir
    </a>
</div>

<!-- Tabel Data -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipe</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telepon</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Alamat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($parties as $party)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-4 py-3 whitespace-nowrap">
                        @switch($party->type)
                            @case('customer')
                                <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-xs">Pelanggan</span>
                                @break
                            @case('supplier')
                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full text-xs">Supplier</span>
                                @break
                            @case('employee')
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs">Karyawan</span>
                                @break
                            @case('driver')
                                <span class="px-2 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-400 rounded-full text-xs">Sopir</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $party->name }}</td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $party->phone ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ Str::limit($party->address, 30) ?? '-' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if($party->is_active)
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs">Aktif</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full text-xs">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('parties.edit', $party) }}" class="text-yellow-500 dark:text-yellow-400 hover:text-yellow-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form action="{{ route('parties.destroy', $party) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus ' + '{{ $party->name }}' + '?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 dark:text-red-400 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">Tidak ada data ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $parties->withQueryString()->links() }}
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const typeInput = document.getElementById('typeInput');
    const searchForm = document.getElementById('searchForm');
    
    let typingTimer;
    const doneTypingInterval = 500;
    
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            searchForm.submit();
        }, doneTypingInterval);
    });
    
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            typeInput.value = this.dataset.type;
            searchForm.submit();
        });
    });
</script>
@endsection