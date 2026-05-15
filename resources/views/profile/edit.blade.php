@extends('layouts.flowbite')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-4">
        <h1 class="text-xl font-bold text-gray-800">👤 Edit Profile</h1>
        <p class="text-sm text-gray-500">Perbarui informasi akun Anda</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <!-- Foto Profil -->
            <div class="mb-6 flex items-center gap-4">
                <div class="relative">
                    @php
                        $photoPath = isset($user->photo) ? Storage::url('public/photos/' . $user->photo) : null;
                    @endphp
                    @if($photoPath && file_exists(storage_path('app/public/photos/' . $user->photo)))
                        <img id="photoPreview" src="{{ asset('storage/photos/' . $user->photo) }}" 
                             class="w-20 h-20 rounded-full object-cover border-2 border-gray-300">
                    @else
                        <div id="photoPreview" class="w-20 h-20 rounded-full bg-primary-100 flex items-center justify-center text-primary-500 text-2xl font-bold border-2 border-gray-300">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    <label for="photoInput" class="absolute bottom-0 right-0 bg-primary-500 text-white p-1 rounded-full cursor-pointer hover:bg-primary-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </label>
                    <input type="file" name="photo" id="photoInput" class="hidden" accept="image/*" onchange="previewPhoto(this)">
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Foto Profil</p>
                    <p class="text-xs text-gray-500">Klik ikon kamera untuk upload foto (jpg, png, max 2MB)</p>
                </div>
            </div>

            <!-- Nama Lengkap -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email (Read Only - Tidak Bisa Diedit) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" value="{{ $user->email }}" 
                       class="w-full px-3 py-2 border rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                       readonly disabled>
                <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah</p>
            </div>

            <!-- Role (Read Only) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <div class="px-3 py-2 bg-gray-100 rounded-lg text-sm">
                    @if($user->role == 'super_admin')
                        <span class="bg-purple-100 text-purple-800 px-2 py-0.5 rounded-full">Super Admin</span>
                    @else
                        <span class="bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">Admin</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">Role tidak dapat diubah</p>
            </div>

            <!-- Status (Read Only) -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <div class="px-3 py-2 bg-gray-100 rounded-lg text-sm">
                    @if($user->is_active ?? true)
                        <span class="bg-green-100 text-green-800 px-2 py-0.5 rounded-full">✅ Aktif</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-2 py-0.5 rounded-full">❌ Nonaktif</span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-1">Status dapat diubah oleh Super Admin di halaman Pengaturan</p>
            </div>

            <!-- Password Baru -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password Baru</label>
                <input type="password" name="password" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 @error('password') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-2 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg transition-all">
                    💾 Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('photoPreview');
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace div with img
                    const img = document.createElement('img');
                    img.id = 'photoPreview';
                    img.className = 'w-20 h-20 rounded-full object-cover border-2 border-gray-300';
                    img.src = e.target.result;
                    preview.parentNode.replaceChild(img, preview);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection