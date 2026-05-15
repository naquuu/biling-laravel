@extends('layouts.flowbite')

@section('title', 'Edit Buku')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Edit Buku</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Ubah data buku: <strong>{{ $book->code }} - {{ $book->name }}</strong></p>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow max-w-2xl">
    <form action="{{ route('books.update', $book) }}" method="POST" class="p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Kode Buku <span class="text-red-500">*</span></label>
            <input type="text" name="code" value="{{ old('code', $book->code) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('code') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('code')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama Buku <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $book->name) }}" 
                   class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('description', $book->description) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ $book->is_active ? 'checked' : '' }} class="mr-2 w-4 h-4 text-primary-500 focus:ring-primary-400 dark:bg-gray-700">
                <span class="text-gray-700 dark:text-gray-300 text-sm">Aktifkan buku ini</span>
            </label>
        </div>

        <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('books.index') }}" class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-800 dark:text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Batal</a>
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">Update Buku</button>
        </div>
    </form>
</div>
@endsection