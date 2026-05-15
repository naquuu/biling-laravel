@extends('layouts.flowbite')

@section('title', 'Tambah Transaksi')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Tambah Transaksi Baru</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Catat transaksi pemasukan atau pengeluaran</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow max-w-2xl">
    <form action="{{ route('transactions.store') }}" method="POST" class="p-6" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Buku <span class="text-red-500">*</span></label>
                <select name="book_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('book_id') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Buku</option>
                    @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                        {{ $book->code }} - {{ $book->name }}
                    </option>
                    @endforeach
                </select>
                @error('book_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Pelanggan/Supplier <span class="text-red-500">*</span></label>
                <select name="party_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('party_id') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih</option>
                    @foreach($parties as $party)
                    <option value="{{ $party->id }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>
                        [{{ ucfirst($party->type) }}] {{ $party->name }}
                    </option>
                    @endforeach
                </select>
                @error('party_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('date') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Jatuh Tempo</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan jika tidak ada</p>
            </div>

            <div class="mb-4 col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Keterangan</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description') }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" value="{{ old('amount') }}" step="1000"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('amount') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @error('amount') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Status Pembayaran <span class="text-red-500">*</span></label>
                <select name="payment_status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="payment_status">
                    <option value="lunas" {{ old('payment_status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="cicil" {{ old('payment_status') == 'cicil' ? 'selected' : '' }}>Cicil</option>
                    <option value="hutang" {{ old('payment_status') == 'hutang' ? 'selected' : '' }}>Hutang</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Metode Pembayaran</label>
                <select name="payment_method" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih</option>
                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>

            <div class="mb-4 col-span-2" id="paid_amount_div" style="display: none;">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Sudah Dibayar (Rp)</label>
                <input type="number" name="paid_amount" value="{{ old('paid_amount', 0) }}" step="1000"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Untuk transaksi cicilan</p>
            </div>

            <div class="mb-4 col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Bukti Transaksi</label>
                <input type="file" name="proof_image" accept="image/*" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload bukti transaksi (jpg, jpeg, png, max 2MB)</p>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('transactions.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Batal</a>
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Simpan Transaksi</button>
        </div>
    </form>
</div>

<script>
    const statusSelect = document.getElementById('payment_status');
    const paidAmountDiv = document.getElementById('paid_amount_div');
    
    function togglePaidAmount() {
        paidAmountDiv.style.display = statusSelect.value === 'cicil' ? 'block' : 'none';
    }
    
    statusSelect.addEventListener('change', togglePaidAmount);
    togglePaidAmount();
</script>
@endsection