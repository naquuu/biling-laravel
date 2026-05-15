@extends('layouts.flowbite')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Dashboard</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-green-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Pemasukan</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-100 dark:bg-green-900/30 rounded-full p-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-red-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Pengeluaran</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-100 dark:bg-red-900/30 rounded-full p-2">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-blue-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Total Transaksi</p>
                <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalTransactions ?? 0 }}</p>
            </div>
            <div class="bg-blue-100 dark:bg-blue-900/30 rounded-full p-2">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 border-purple-500">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-gray-500 dark:text-gray-400 text-xs">Saldo</p>
                <p class="text-xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-purple-100 dark:bg-purple-900/30 rounded-full p-2">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filter Tabs -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 mb-6">
    <div class="flex gap-2">
        <a href="{{ route('dashboard', ['filter' => 'week']) }}" 
           class="px-3 py-1 rounded-lg text-sm transition-all {{ $filter == 'week' ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Minggu Ini
        </a>
        <a href="{{ route('dashboard', ['filter' => 'month']) }}" 
           class="px-3 py-1 rounded-lg text-sm transition-all {{ $filter == 'month' ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Bulan Ini
        </a>
        <a href="{{ route('dashboard', ['filter' => 'year']) }}" 
           class="px-3 py-1 rounded-lg text-sm transition-all {{ $filter == 'year' ? 'bg-primary-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Tahun Ini
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart Area -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-3">
            Grafik Transaksi - 
            @if($filter == 'week') 7 Hari Terakhir
            @elseif($filter == 'year') 12 Bulan Terakhir
            @else {{ Carbon\Carbon::now()->format('F Y') }} @endif
        </h3>
        <div id="transactionChart" style="height: 320px;"></div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-md font-semibold text-gray-800 dark:text-white">Transaksi Terbaru</h3>
            <a href="{{ route('transactions.index') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 text-sm">Lihat semua →</a>
        </div>
        <div class="p-4">
            <div class="space-y-3">
                @forelse($recentTransactions ?? [] as $transaction)
                <div class="flex justify-between items-center border-b dark:border-gray-700 pb-3 last:border-0">
                    <div>
                        <p class="font-semibold text-sm text-gray-800 dark:text-white">{{ $transaction->party->name ?? 'N/A' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->date->format('d/m/Y') }} • {{ $transaction->book->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $amountColor = match($transaction->payment_status) {
                                'lunas' => 'text-green-600 dark:text-green-400',
                                'cicil' => 'text-yellow-600 dark:text-yellow-400',
                                'hutang' => 'text-red-600 dark:text-red-400',
                                default => 'text-gray-600 dark:text-gray-400'
                            };
                        @endphp
                        <p class="font-bold text-sm {{ $amountColor }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->payment_status }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-gray-400 text-center text-sm py-4">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Overdue Payments -->
<div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <h3 class="text-md font-semibold text-red-600 dark:text-red-400 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Pembayaran Terlambat
        </h3>
    </div>
    <div class="p-4">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Buku</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jatuh Tempo</th>
                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Sisa Hutang</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($overduePayments ?? [] as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-3 py-2 text-sm dark:text-gray-300">{{ $payment->party->name ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-sm dark:text-gray-300">{{ $payment->book->code ?? 'N/A' }}</td>
                        <td class="px-3 py-2 text-sm text-red-600 dark:text-red-400 font-medium">
                            {{ $payment->due_date ? $payment->due_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-3 py-2 text-sm text-right font-bold text-red-600 dark:text-red-400">
                            Rp {{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-3 py-2 text-center">
                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400">
                                {{ $payment->payment_status == 'cicil' ? 'Cicilan' : 'Hutang' }}
                            </span>
                        </td>
                        <td class="px-3 py-2 text-center">
                            <a href="{{ route('transactions.edit', $payment) }}" class="text-yellow-500 dark:text-yellow-400 hover:text-yellow-700 text-sm">Bayar</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-3 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada pembayaran terlambat
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari server
        const chartLabels = {{ json_encode($chartData['labels'] ?? []) }};
        const chartIncome = {{ json_encode($chartData['income'] ?? []) }};
        const chartExpense = {{ json_encode($chartData['expense'] ?? []) }};
        
        // Cek apakah ada data
        if (chartLabels.length === 0) {
            console.log('No chart data available');
            document.getElementById('transactionChart').innerHTML = '<div class="flex items-center justify-center h-full text-gray-500">Belum ada data transaksi</div>';
            return;
        }
        
        var options = {
            series: [
                {
                    name: 'Pemasukan',
                    data: chartIncome,
                    color: '#10b981'
                },
                {
                    name: 'Pengeluaran',
                    data: chartExpense,
                    color: '#f97316'
                }
            ],
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: true,
                    tools: {
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        reset: true
                    }
                },
                background: 'transparent'
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            markers: {
                size: 4,
                hover: {
                    size: 6
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: chartLabels,
                title: {
                    text: @if($filter == 'week') 'Hari' @elseif($filter == 'year') 'Bulan' @else 'Tanggal' @endif,
                    style: {
                        fontSize: '12px',
                        fontWeight: '500'
                    }
                },
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px',
                        colors: '#6b7280'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah (Rp)',
                    style: {
                        fontSize: '12px',
                        fontWeight: '500'
                    }
                },
                labels: {
                    formatter: function(value) {
                        if (value >= 1000000) {
                            return (value / 1000000).toFixed(1) + 'JT';
                        } else if (value >= 1000) {
                            return (value / 1000).toFixed(0) + 'K';
                        }
                        return value;
                    },
                    style: {
                        fontSize: '10px',
                        colors: '#6b7280'
                    }
                },
                forceNiceScale: true,
                tickAmount: 6
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                },
                shared: true,
                intersect: false
            },
            legend: {
                position: 'top',
                horizontalAlign: 'center',
                fontSize: '12px',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 5
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 5
            },
            colors: ['#10b981', '#f97316']
        };
        
        var chart = new ApexCharts(document.querySelector("#transactionChart"), options);
        chart.render();
        
        // Update chart theme when dark mode changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    const isDark = document.documentElement.classList.contains('dark');
                    chart.updateOptions({
                        grid: { borderColor: isDark ? '#374151' : '#e5e7eb' },
                        xaxis: { labels: { style: { colors: isDark ? '#9ca3af' : '#6b7280' } } },
                        yaxis: { labels: { style: { colors: isDark ? '#9ca3af' : '#6b7280' } } }
                    });
                }
            });
        });
        
        observer.observe(document.documentElement, { attributes: true });
    });
</script>
@endpush