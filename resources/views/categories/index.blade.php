@extends('layouts.flowbite')

@section('title', 'Kategori Buku')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Kategori Buku</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Detail transaksi per buku</p>
</div>

@if($firstBook)
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
    <!-- Header Buku -->
    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
        <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ $firstBook->name }}</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kode: {{ $firstBook->code }}</p>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Transaksi</p>
            <p class="text-xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
            <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $transactionCount }}</p>
        </div>
    </div>

    <!-- Tabel Transaksi Terbaru -->
    <h3 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">Transaksi Terbaru</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700/50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-3 py-2 text-xs dark:text-gray-300">{{ $transaction->date->format('d/m/Y') }}</td>
                    <td class="px-3 py-2 text-xs font-medium dark:text-gray-300">{{ $transaction->party->name ?? '-' }}</td>
                    <td class="px-3 py-2 text-xs text-right font-bold dark:text-gray-300">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                    <td class="px-3 py-2 text-center">
                        @if($transaction->payment_status == 'lunas')
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs">Lunas</span>
                        @elseif($transaction->payment_status == 'cicil')
                            <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full text-xs">Cicil</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full text-xs">Hutang</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-center">
                        <a href="{{ route('transactions.edit', $transaction) }}" class="text-yellow-500 dark:text-yellow-400 hover:text-yellow-700 text-xs">Edit</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">Belum ada transaksi untuk buku ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
    <p class="text-gray-500 dark:text-gray-400">Belum ada buku yang tersedia. Silakan tambah buku terlebih dahulu.</p>
    <a href="{{ route('books.create') }}" class="inline-block mt-2 text-primary-500 dark:text-primary-400 hover:text-primary-600">Tambah Buku</a>
</div>
@endif
@endsection