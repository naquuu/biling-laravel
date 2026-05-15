@extends('layouts.flowbite')

@section('title', 'Edit Data')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Edit Data</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Edit data: <strong>{{ $party->name }}</strong></p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow max-w-2xl">
    <form action="{{ route('parties.update', $party) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tipe <span class="text-red-500">*</span></label>
            <select name="type" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="customer" {{ old('type', $party->type) == 'customer' ? 'selected' : '' }}>Pelanggan</option>
                <option value="supplier" {{ old('type', $party->type) == 'supplier' ? 'selected' : '' }}>Supplier</option>
                <option value="employee" {{ old('type', $party->type) == 'employee' ? 'selected' : '' }}>Karyawan</option>
                <option value="driver" {{ old('type', $party->type) == 'driver' ? 'selected' : '' }}>Sopir</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $party->name) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Telepon</label>
            <input type="text" name="phone" value="{{ old('phone', $party->phone) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Alamat</label>
            <textarea name="address" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('address', $party->address) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Catatan</label>
            <textarea name="notes" rows="2" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('notes', $party->notes) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $party->is_active ? 'checked' : '' }} class="mr-2 w-4 h-4 text-primary-500 focus:ring-primary-400 dark:bg-gray-700">
                <span class="text-gray-700 dark:text-gray-300 text-sm">Aktifkan data ini</span>
            </label>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('parties.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Batal</a>
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Update</button>
        </div>
    </form>
</div>
@endsection