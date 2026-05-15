@extends('layouts.flowbite')

@section('title', 'Laporan Transaksi')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Laporan Transaksi</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Filter dan lihat laporan transaksi</p>
</div>

<!-- Filter Form -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Buku</label>
            <select name="book_id" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua Buku</option>
                @foreach($books as $book)
                <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>{{ $book->code }} - {{ $book->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Pelanggan/Supplier</label>
            <select name="party_id" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua</option>
                @foreach($parties as $party)
                <option value="{{ $party->id }}" {{ request('party_id') == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="payment_status" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Semua</option>
                <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="cicil" {{ request('payment_status') == 'cicil' ? 'selected' : '' }}>Cicil</option>
                <option value="hutang" {{ request('payment_status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
            </select>
        </div>
        <div class="flex items-end gap-2 col-span-full">
            <button type="submit" class="bg-primary-500 text-white px-3 py-1.5 rounded-lg text-sm">Tampilkan</button>
            <a href="{{ route('reports.index') }}" class="bg-gray-500 text-white px-3 py-1.5 rounded-lg text-sm">Reset</a>
        </div>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 dark:text-gray-400">Total Transaksi</p>
        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 border-l-4 border-green-500">
        <p class="text-xs text-gray-500 dark:text-gray-400">Sudah Dibayar</p>
        <p class="text-xl font-bold text-green-600 dark:text-green-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 border-l-4 border-red-500">
        <p class="text-xs text-gray-500 dark:text-gray-400">Sisa Hutang</p>
        <p class="text-xl font-bold text-red-600 dark:text-red-400">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</p>
    </div>
</div>

<!-- Tabel Laporan -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Buku</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Keterangan</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dibayar</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sisa</th>
                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                <td class="px-3 py-2 text-xs dark:text-gray-300">{{ $transaction->date->format('d/m/Y') }}
                    @if($transaction->due_date)<br><span class="text-red-500 text-xs">JT: {{ $transaction->due_date->format('d/m/Y') }}</span>@endif
                </td>
                <td class="px-3 py-2 text-xs dark:text-gray-300">{{ $transaction->book->code ?? '-' }}<br><span class="text-gray-400">{{ Str::limit($transaction->book->name ?? '-', 20) }}</span></td>
                <td class="px-3 py-2 text-xs font-medium dark:text-gray-300">{{ $transaction->party->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($transaction->description, 30) ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-right font-bold dark:text-gray-300">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                <td class="px-3 py-2 text-xs text-right text-green-600 dark:text-green-400">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</td>
                <td class="px-3 py-2 text-xs text-right font-bold text-red-600 dark:text-red-400">Rp {{ number_format($transaction->amount - $transaction->paid_amount, 0, ',', '.') }}</td>
                <td class="px-3 py-2 text-xs text-center">
                    @if($transaction->payment_status == 'lunas')
                        <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">Lunas</span>
                    @elseif($transaction->payment_status == 'cicil')
                        <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400">Cicil</span>
                    @else
                        <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">Hutang</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-3 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">Tidak ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3 text-xs text-gray-500 dark:text-gray-400">Menampilkan {{ $transactions->count() }} transaksi</div>
@endsection