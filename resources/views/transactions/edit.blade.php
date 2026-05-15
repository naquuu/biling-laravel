@extends('layouts.flowbite')

@section('title', 'Edit Transaksi')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Edit Transaksi</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Ubah data transaksi</p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow max-w-2xl">
    <form action="{{ route('transactions.update', $transaction) }}" method="POST" class="p-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Buku <span class="text-red-500">*</span></label>
                <select name="book_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($books as $book)
                    <option value="{{ $book->id }}" {{ old('book_id', $transaction->book_id) == $book->id ? 'selected' : '' }}>
                        {{ $book->code }} - {{ $book->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Pelanggan/Supplier <span class="text-red-500">*</span></label>
                <select name="party_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @foreach($parties as $party)
                    <option value="{{ $party->id }}" {{ old('party_id', $transaction->party_id) == $party->id ? 'selected' : '' }}>
                        [{{ ucfirst($party->type) }}] {{ $party->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tanggal <span class="text-red-500">*</span></label>
                <input type="date" name="date" value="{{ old('date', $transaction->date->format('Y-m-d')) }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Jatuh Tempo</label>
                <input type="date" name="due_date" value="{{ old('due_date', $transaction->due_date ? $transaction->due_date->format('Y-m-d') : '') }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4 col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Keterangan</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $transaction->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Jumlah (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" step="1000"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Status Pembayaran <span class="text-red-500">*</span></label>
                <select name="payment_status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" id="payment_status">
                    <option value="lunas" {{ old('payment_status', $transaction->payment_status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="cicil" {{ old('payment_status', $transaction->payment_status) == 'cicil' ? 'selected' : '' }}>Cicil</option>
                    <option value="hutang" {{ old('payment_status', $transaction->payment_status) == 'hutang' ? 'selected' : '' }}>Hutang</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Metode Pembayaran</label>
                <select name="payment_method" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih</option>
                    <option value="cash" {{ old('payment_method', $transaction->payment_method) == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                    <option value="transfer" {{ old('payment_method', $transaction->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                </select>
            </div>

            <div class="mb-4 col-span-2" id="paid_amount_div" style="{{ old('payment_status', $transaction->payment_status) == 'cicil' ? 'display: block' : 'display: none' }}">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Sudah Dibayar (Rp)</label>
                <input type="number" name="paid_amount" value="{{ old('paid_amount', $transaction->paid_amount) }}" step="1000"
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sisa: Rp {{ number_format($transaction->amount - $transaction->paid_amount, 0, ',', '.') }}</p>
            </div>

            <div class="mb-4 col-span-2">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Bukti Transaksi</label>
                @if($transaction->proof_image)
                <div class="mb-3 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Gambar saat ini:</p>
                    <img src="{{ asset('storage/proofs/' . $transaction->proof_image) }}" 
                         class="w-32 h-32 object-cover rounded border cursor-pointer dark:border-gray-600" 
                         onclick="window.open(this.src, '_blank')">
                </div>
                @endif
                <input type="file" name="proof_image" accept="image/*" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Upload bukti transaksi baru (jpg, jpeg, png, max 2MB)</p>
                @error('proof_image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('transactions.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Batal</a>
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Update Transaksi</button>
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
</script>
@endsection