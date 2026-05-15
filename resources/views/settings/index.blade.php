@extends('layouts.flowbite')

@section('title', 'Pengaturan')

@section('content')
<div class="mb-4">
    <h1 class="text-xl font-bold text-gray-800 dark:text-white">Pengaturan Aplikasi</h1>
    <p class="text-sm text-gray-500 dark:text-gray-400">Hanya Super Admin yang dapat mengakses halaman ini.</p>
</div>

<!-- Tombol Logout -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
    <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-md font-semibold text-gray-700 dark:text-gray-300">Keluar dari Aplikasi</h2>
    </div>
    <div class="p-4">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Logout dari akun {{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-500">Pastikan Anda telah menyimpan semua pekerjaan sebelum logout</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Pengaturan Umum & Tema -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-md font-semibold text-gray-700 dark:text-gray-300">Pengaturan Umum & Tema</h2>
        </div>
        <div class="p-4">
            <form action="{{ route('settings.update') }}" method="POST" id="settingsForm">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama Aplikasi</label>
                    <input type="text" name="app_name" value="{{ old('app_name', $settings['app_name'] ?? 'Aplikasi Billing') }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Default Jatuh Tempo (hari)</label>
                    <input type="number" name="default_payment_term_days" value="{{ old('default_payment_term_days', $settings['default_payment_term_days'] ?? 30) }}" 
                           class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Untuk transaksi hutang/cicil</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Mata Uang</label>
                    <select name="currency" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="IDR" {{ ($settings['currency'] ?? 'IDR') == 'IDR' ? 'selected' : '' }}>IDR - Rupiah (Rp)</option>
                        <option value="USD" {{ ($settings['currency'] ?? 'IDR') == 'USD' ? 'selected' : '' }}>USD - Dollar ($)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Tema Aplikasi</label>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="theme" value="light" {{ ($settings['theme'] ?? 'light') == 'light' ? 'checked' : '' }} class="mr-2 theme-radio">
                            <span class="text-sm dark:text-gray-300">☀️ Terang</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="theme" value="dark" {{ ($settings['theme'] ?? 'light') == 'dark' ? 'checked' : '' }} class="mr-2 theme-radio">
                            <span class="text-sm dark:text-gray-300">🌙 Gelap</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition-all">
                    Simpan Pengaturan
                </button>
            </form>
        </div>
    </div>

    <!-- Manajemen User (Super Admin only) -->
    @if(Auth::user()->role === 'super_admin')
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-md font-semibold text-gray-700 dark:text-gray-300">Manajemen User</h2>
            <button onclick="openUserModal()" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-sm">
                + Tambah User
            </button>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Nama</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Role</th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($users as $user)
                        <tr>
                            <td class="px-3 py-2 text-sm dark:text-gray-300">{{ $user->name }}@if($user->id === auth()->id()) <span class="text-xs text-gray-400">(Anda)</span>@endif</td>
                            <td class="px-3 py-2 text-sm dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-3 py-2 text-sm">
                                @if($user->role == 'super_admin')
                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full text-xs">Super Admin</span>
                                @else
                                    <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-xs">Admin</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-sm">
                                @if($user->is_active)
                                    <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs">Aktif</span>
                                @else
                                    <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full text-xs">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-sm text-center">
                                <button onclick="editUser({{ $user->id }})" class="text-yellow-500 hover:text-yellow-700 mr-2">
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('settings.user.delete', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal Tambah/Edit User -->
<div id="userModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-auto z-10">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800 dark:text-white">Tambah User</h3>
                <button onclick="closeUserModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="px-6 py-4">
                <form id="userForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <input type="hidden" name="user_id" id="userId">
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Nama</label>
                        <input type="text" name="name" id="userName" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" id="userEmail" required class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    
                    <div class="mb-4" id="passwordField">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Password</label>
                        <input type="password" name="password" id="userPassword" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Minimal 6 karakter</p>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">Role</label>
                        <select name="role" id="userRole" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="admin">Admin</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" id="userActive" class="mr-2">
                            <span class="text-gray-700 dark:text-gray-300 text-sm">Aktif</span>
                        </label>
                    </div>
                    
                    <div class="flex justify-end gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="closeUserModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const modal = document.getElementById('userModal');
    
    function openUserModal() {
        document.getElementById('modalTitle').textContent = 'Tambah User';
        document.getElementById('userForm').action = '{{ route("settings.user.store") }}';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('userId').value = '';
        document.getElementById('userName').value = '';
        document.getElementById('userEmail').value = '';
        document.getElementById('userPassword').value = '';
        document.getElementById('userRole').value = 'admin';
        document.getElementById('userActive').checked = true;
        document.getElementById('passwordField').style.display = 'block';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function editUser(id) {
        fetch(`/settings/users/${id}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit User';
                document.getElementById('userForm').action = `/settings/users/${id}`;
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('userId').value = data.id;
                document.getElementById('userName').value = data.name;
                document.getElementById('userEmail').value = data.email;
                document.getElementById('userRole').value = data.role;
                document.getElementById('userActive').checked = data.is_active;
                document.getElementById('userPassword').value = '';
                document.getElementById('passwordField').style.display = 'none';
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data user');
            });
    }
    
    function closeUserModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    // Tutup modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeUserModal();
        }
    });
    
    // Preview tema saat radio button berubah
    document.querySelectorAll('.theme-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'dark') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        });
    });
    
    // Klik di luar modal untuk menutup
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeUserModal();
        }
    });
</script>
@endsection