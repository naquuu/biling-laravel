@extends('layouts.flowbite')

@section('title', 'Transaksi')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Transaksi</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola semua transaksi pemasukan dan pengeluaran</p>
</div>

<div class="flex justify-between items-center flex-wrap gap-2 mb-4">
    <a href="{{ route('transactions.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Transaksi
    </a>
</div>

<!-- Filter Form -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-3 mb-4">
    <form method="GET" action="{{ route('transactions.index') }}" id="filterForm">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-2">
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Buku</label>
                <select name="book_id" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Buku</option>
                    @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ request('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->code }} - {{ $book->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="payment_status" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="lunas" {{ request('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="cicil" {{ request('payment_status') == 'cicil' ? 'selected' : '' }}>Cicil</option>
                    <option value="hutang" {{ request('payment_status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded-lg px-2 py-1.5 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="relative">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Cari Nama/Telepon/Alamat</label>
                <div class="relative">
                    <input type="text" name="search" id="searchInput" value="{{ request('search') }}" 
                           placeholder="Ketik nama, telepon, atau alamat..." 
                           autocomplete="off"
                           class="w-full px-3 py-1.5 pl-8 border rounded-lg text-sm focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:placeholder-gray-400">
                    <svg class="absolute left-2 top-2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    
                    <!-- Dropdown Suggestions -->
                    <div id="suggestionsDropdown" class="absolute z-50 hidden w-full mt-1 bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                    </div>
                </div>
            </div>
            <div class="flex items-end gap-1">
                <button type="submit" class="bg-primary-500 text-white px-3 py-1.5 rounded-lg text-sm w-full">Filter</button>
                <a href="{{ route('transactions.index') }}" class="bg-gray-500 text-white px-3 py-1.5 rounded-lg text-sm text-center">Reset</a>
            </div>
        </div>
        
        <input type="hidden" name="sort_by" id="sortByInput" value="{{ request('sort_by', 'date') }}">
        <input type="hidden" name="sort_order" id="sortOrderInput" value="{{ request('sort_order', 'desc') }}">
    </form>
</div>

<!-- Sorting -->
<div class="mb-3 flex justify-end">
    <div class="flex items-center gap-2 text-sm">
        <span class="text-gray-600 dark:text-gray-400">Urutkan:</span>
        <select id="sortBySelect" class="border rounded-lg px-2 py-1 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="date" {{ request('sort_by', 'date') == 'date' ? 'selected' : '' }}>Tanggal</option>
            <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>Jumlah</option>
            <option value="payment_status" {{ request('sort_by') == 'payment_status' ? 'selected' : '' }}>Status</option>
        </select>
        <select id="sortOrderSelect" class="border rounded-lg px-2 py-1 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Terbaru</option>
            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Terlama</option>
        </select>
    </div>
</div>

<!-- Tabel Transaksi -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tanggal</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Buku</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Nama</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Telepon</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Keterangan</th>
                <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Jumlah</th>
                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transaction-row" 
                data-id="{{ $transaction->id }}"
                data-book="{{ $transaction->book->name ?? '-' }}"
                data-party="{{ $transaction->party->name ?? '-' }}"
                data-party-phone="{{ $transaction->party->phone ?? '-' }}"
                data-party-address="{{ $transaction->party->address ?? '-' }}"
                data-date="{{ $transaction->date->format('d/m/Y') }}"
                data-due-date="{{ $transaction->due_date ? $transaction->due_date->format('d/m/Y') : '-' }}"
                data-description="{{ $transaction->description ?? '-' }}"
                data-amount="{{ number_format($transaction->amount, 0, ',', '.') }}"
                data-paid-amount="{{ number_format($transaction->paid_amount, 0, ',', '.') }}"
                data-status="{{ $transaction->payment_status }}"
                data-method="{{ $transaction->payment_method == 'cash' ? 'Tunai' : ($transaction->payment_method == 'transfer' ? 'Transfer' : '-') }}"
                data-proof-image="{{ $transaction->proof_image ?? '' }}"
                data-edit-url="{{ route('transactions.edit', $transaction) }}">
                <td class="px-3 py-2 text-xs dark:text-gray-300">
                    {{ $transaction->date->format('d/m/Y') }}
                    @if($transaction->due_date)
                    <br><span class="text-red-500 text-xs">JT: {{ $transaction->due_date->format('d/m/Y') }}</span>
                    @endif
                </td>
                <td class="px-3 py-2 text-xs">
                    <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded dark:text-gray-300">{{ $transaction->book->code ?? '-' }}</span>
                </td>
                <td class="px-3 py-2 text-xs font-medium dark:text-gray-300 party-name">{{ $transaction->party->name ?? '-' }}</td>
                <td class="px-3 py-2 text-xs dark:text-gray-400">{{ $transaction->party->phone ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($transaction->description, 25) ?? '-' }}</td>
                <td class="px-3 py-2 text-xs text-right font-bold dark:text-gray-300">
                    Rp {{ number_format($transaction->amount, 0, ',', '.') }}
                    @if($transaction->paid_amount > 0)
                    <br><span class="text-green-600 dark:text-green-400 text-xs">Dibayar: Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                    @endif
                </td>
                <td class="px-3 py-2 text-xs">
                    @php
                        $statusColors = [
                            'lunas' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400',
                            'cicil' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400',
                            'hutang' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400',
                        ];
                    @endphp
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $statusColors[$transaction->payment_status] }}">
                        {{ $transaction->payment_status == 'lunas' ? 'Lunas' : ($transaction->payment_status == 'cicil' ? 'Cicil' : 'Hutang') }}
                    </span>
                </td>
                <td class="px-3 py-2 text-xs text-center">
                    <a href="{{ route('transactions.edit', $transaction) }}" class="text-yellow-500 dark:text-yellow-400 hover:text-yellow-700" onclick="event.stopPropagation()">
                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    Tidak ada data transaksi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $transactions->withQueryString()->links() }}
</div>

<!-- Modal Detail Transaksi - Dark Mode Support -->
<div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full mx-auto z-10">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white">Detail Transaksi</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="px-6 py-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Transaksi</p>
                        <p id="modalDate" class="text-sm font-medium dark:text-white">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jatuh Tempo</p>
                        <p id="modalDueDate" class="text-sm font-medium dark:text-white">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Buku</p>
                        <p id="modalBook" class="text-sm font-medium dark:text-white">-</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Metode Pembayaran</p>
                        <p id="modalMethod" class="text-sm font-medium dark:text-white">-</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Keterangan</p>
                        <p id="modalDescription" class="text-sm dark:text-white">-</p>
                    </div>
                </div>
                
                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Informasi Pelanggan/Supplier</p>
                    <p id="modalPartyName" class="text-sm font-medium dark:text-white">-</p>
                    <p id="modalPartyPhone" class="text-xs text-gray-500 dark:text-gray-400">-</p>
                    <p id="modalPartyAddress" class="text-xs text-gray-500 dark:text-gray-400">-</p>
                </div>
                
                <div class="mt-4 grid grid-cols-3 gap-3 text-center">
                    <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                        <p id="modalAmount" class="text-lg font-bold text-green-600 dark:text-green-400">-</p>
                    </div>
                    <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Dibayar</p>
                        <p id="modalPaidAmount" class="text-lg font-bold text-blue-600 dark:text-blue-400">-</p>
                    </div>
                    <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Sisa</p>
                        <p id="modalRemaining" class="text-lg font-bold text-red-600 dark:text-red-400">-</p>
                    </div>
                </div>
                
                <!-- Bukti Transaksi -->
                <div id="modalProofContainer" class="mt-4" style="display: none;">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Bukti Transaksi</p>
                    <img id="modalProofImage" src="" class="max-w-full max-h-64 rounded border cursor-pointer dark:border-gray-700" onclick="openImageModal(this.src)">
                </div>
                
                <div class="mt-4 flex justify-end gap-2">
                    <button onclick="closeModal()" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white px-4 py-2 rounded-lg text-sm">Tutup</button>
                    <a id="modalEditLink" href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm">Edit Transaksi</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const suggestionsDropdown = document.getElementById('suggestionsDropdown');
    const filterForm = document.getElementById('filterForm');
    const sortBySelect = document.getElementById('sortBySelect');
    const sortOrderSelect = document.getElementById('sortOrderSelect');
    const sortByInput = document.getElementById('sortByInput');
    const sortOrderInput = document.getElementById('sortOrderInput');
    
    let debounceTimer;
    
    // Modal functions
    const modal = document.getElementById('transactionModal');
    
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
    
    function openModal(data) {
        document.getElementById('modalDate').textContent = data.date;
        document.getElementById('modalDueDate').textContent = data.dueDate;
        document.getElementById('modalBook').textContent = data.book;
        document.getElementById('modalMethod').textContent = data.method;
        document.getElementById('modalDescription').textContent = data.description;
        document.getElementById('modalPartyName').textContent = data.party;
        document.getElementById('modalPartyPhone').textContent = data.partyPhone;
        document.getElementById('modalPartyAddress').textContent = data.partyAddress;
        document.getElementById('modalAmount').textContent = 'Rp ' + data.amount;
        document.getElementById('modalPaidAmount').textContent = 'Rp ' + data.paidAmount;
        
        let amountNum = parseInt(data.amount.replace(/\./g, '')) || 0;
        let paidNum = parseInt(data.paidAmount.replace(/\./g, '')) || 0;
        let sisa = amountNum - paidNum;
        document.getElementById('modalRemaining').textContent = 'Rp ' + formatRupiah(sisa);
        
        const proofContainer = document.getElementById('modalProofContainer');
        const proofImage = document.getElementById('modalProofImage');
        if (data.proofImage && data.proofImage !== '') {
            proofImage.src = '/storage/proofs/' + data.proofImage;
            proofContainer.style.display = 'block';
        } else {
            proofContainer.style.display = 'none';
        }
        
        const editLink = document.getElementById('modalEditLink');
        if (editLink) editLink.href = data.editUrl;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    function openImageModal(src) {
        window.open(src, '_blank');
    }
    
    // Klik row transaksi
    document.querySelectorAll('.transaction-row').forEach(row => {
        row.addEventListener('click', function(e) {
            if (e.target.tagName === 'A' || e.target.closest('a')) return;
            
            const data = {
                date: this.dataset.date,
                dueDate: this.dataset.dueDate,
                book: this.dataset.book,
                method: this.dataset.method,
                description: this.dataset.description,
                party: this.dataset.party,
                partyPhone: this.dataset.partyPhone,
                partyAddress: this.dataset.partyAddress,
                amount: this.dataset.amount,
                paidAmount: this.dataset.paidAmount,
                proofImage: this.dataset.proofImage,
                editUrl: this.dataset.editUrl
            };
            openModal(data);
        });
    });
    
    // Tutup modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
    
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal || e.target.classList.contains('bg-gray-500')) {
                closeModal();
            }
        });
    }
    
    // Search suggestions
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value;
            clearTimeout(debounceTimer);
            if (query.length < 2) {
                if (suggestionsDropdown) suggestionsDropdown.classList.add('hidden');
                return;
            }
            debounceTimer = setTimeout(() => {
                fetch(`/transactions/suggestions?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (suggestionsDropdown) {
                            if (data.length > 0) {
                                suggestionsDropdown.innerHTML = data.map(item => `
                                    <div class="suggestion-item px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b last:border-b-0 dark:border-gray-700" 
                                         data-name="${item.name.replace(/'/g, "\\'")}" data-id="${item.id}">
                                        <div class="font-medium text-sm dark:text-white">${escapeHtml(item.name)}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">${escapeHtml(item.phone || '-')} • ${escapeHtml(item.address ? item.address.substring(0, 30) : '-')}</div>
                                    </div>
                                `).join('');
                                suggestionsDropdown.classList.remove('hidden');
                            } else {
                                suggestionsDropdown.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">Tidak ada hasil</div>';
                                suggestionsDropdown.classList.remove('hidden');
                            }
                        }
                    });
            }, 300);
        });
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    document.addEventListener('click', function(e) {
        const suggestion = e.target.closest('.suggestion-item');
        if (suggestion) {
            const name = suggestion.dataset.name;
            if (searchInput) searchInput.value = name;
            if (suggestionsDropdown) suggestionsDropdown.classList.add('hidden');
            if (filterForm) filterForm.submit();
        } else if (searchInput && suggestionsDropdown && !searchInput.contains(e.target)) {
            suggestionsDropdown.classList.add('hidden');
        }
    });
    
    function submitSorting() {
        if (sortByInput) sortByInput.value = sortBySelect.value;
        if (sortOrderInput) sortOrderInput.value = sortOrderSelect.value;
        if (filterForm) filterForm.submit();
    }
    
    if (sortBySelect) sortBySelect.addEventListener('change', submitSorting);
    if (sortOrderSelect) sortOrderSelect.addEventListener('change', submitSorting);
    
    document.querySelectorAll('#filterForm select[name="book_id"], #filterForm select[name="payment_status"], #filterForm input[name="start_date"], #filterForm input[name="end_date"]').forEach(el => {
        el.addEventListener('change', () => { if (filterForm) filterForm.submit(); });
    });
</script>
@endsection