@extends('layouts.flowbite')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

<!-- Filter Tabs untuk Grafik -->
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <div class="flex items-center justify-between flex-wrap gap-2">
        <div class="flex gap-2">
            <a href="{{ route('dashboard', ['filter' => 'week']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter == 'week' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Minggu Ini
            </a>
            <a href="{{ route('dashboard', ['filter' => 'month']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter == 'month' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Bulan Ini
            </a>
            <a href="{{ route('dashboard', ['filter' => 'year']) }}" 
               class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $filter == 'year' ? 'bg-primary-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Tahun Ini
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-green-500">
        <div class="flex justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Pemasukan</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalIncome ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3 h-12 w-12 flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-red-500">
        <div class="flex justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalExpense ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-100 rounded-full p-3 h-12 w-12 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-blue-500">
        <div class="flex justify-between">
            <div>
                <p class="text-gray-500 text-sm">Saldo</p>
                <p class="text-2xl font-bold {{ ($balance ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    Rp {{ number_format($balance ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-3 h-12 w-12 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-5 border-l-4 border-purple-500">
        <div class="flex justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Transaksi</p>
                <p class="text-2xl font-bold text-gray-800">{{ $totalTransactions ?? 0 }}</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3 h-12 w-12 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-6 3v-3m-6 3h18M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Chart Area -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            Grafik Transaksi - 
            @if($filter == 'week') 7 Hari Terakhir
            @elseif($filter == 'year') 12 Bulan Terakhir
            @else {{ Carbon\Carbon::now()->format('F Y') }} @endif
        </h3>
        <div id="transactionChart" style="height: 350px;"></div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">Transaksi Terbaru</h3>
            <a href="{{ route('transactions.index') }}" class="text-primary-600 hover:text-primary-700 text-sm">Lihat semua →</a>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentTransactions ?? [] as $transaction)
                <div class="flex justify-between items-center border-b pb-3 last:border-0">
                    <div>
                        <!-- Nama di atas (bold) -->
                        <p class="font-bold text-gray-800">{{ $transaction->party->name ?? 'N/A' }}</p>
                        <!-- Tanggal dulu, baru Buku -->
                        <p class="text-sm text-gray-500">{{ $transaction->date->format('d/m/Y') }} • {{ $transaction->book->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $amountColor = match($transaction->payment_status) {
                                'lunas' => 'text-green-600',
                                'cicil' => 'text-yellow-600',
                                'hutang' => 'text-red-600',
                                default => 'text-gray-600'
                            };
                        @endphp
                        <p class="font-bold {{ $amountColor }}">
                            Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $transaction->payment_status }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">Belum ada transaksi</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Overdue Payments -->
<div class="mt-6 bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-red-600 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Pembayaran Terlambat
        </h3>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jatuh Tempo</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Sisa Hutang</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($overduePayments ?? [] as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $payment->party->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $payment->book->code ?? 'N/A' }}</td>
                        <td class="px-4 py-3 text-sm text-red-600 font-medium">
                            {{ $payment->due_date ? $payment->due_date->format('d/m/Y') : '-' }}
                        </td>
                        <td class="px-4 py-3 text-sm text-right font-bold text-red-600">
                            Rp {{ number_format($payment->amount - $payment->paid_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-800">
                                {{ $payment->payment_status == 'cicil' ? 'Cicilan' : 'Hutang' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var options = {
            series: [
                {
                    name: 'Pemasukan',
                    data: {{ json_encode($chartData['income'] ?? []) }}
                },
                {
                    name: 'Pengeluaran',
                    data: {{ json_encode($chartData['expense'] ?? []) }}
                }
            ],
            chart: {
                type: 'area',
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
            colors: ['#10b981', '#ef4444'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: {{ json_encode($chartData['labels'] ?? []) }},
                title: {
                    text: @if($filter == 'week') 'Hari' @elseif($filter == 'year') 'Bulan' @else 'Tanggal' @endif,
                    style: {
                        fontSize: '12px'
                    }
                },
                labels: {
                    rotate: -45,
                    style: {
                        fontSize: '10px'
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Jumlah (Rp)',
                    style: {
                        fontSize: '12px'
                    }
                },
                labels: {
                    formatter: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            },
            grid: {
                borderColor: '#e5e7eb',
                strokeDashArray: 5
            }
        };
        
        var chart = new ApexCharts(document.querySelector("#transactionChart"), options);
        chart.render();
    });
</script>
@endpush