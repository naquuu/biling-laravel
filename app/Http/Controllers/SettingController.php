<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function index()
    {
        // Cek apakah super admin
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Hanya Super Admin yang bisa mengakses halaman ini.');
        }
        
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $users = User::all();
        
        return view('settings.index', compact('settings', 'users'));
    }

    public function updateSettings(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'app_name' => 'nullable|string|max:255',
            'default_payment_term_days' => 'nullable|integer|min:0',
            'currency' => 'nullable|string|size:3',
            'theme' => 'nullable|in:light,dark',
        ]);

        Setting::set('app_name', $request->app_name, 'Nama aplikasi');
        Setting::set('default_payment_term_days', $request->default_payment_term_days, 'Default jatuh tempo (hari)');
        Setting::set('currency', $request->currency, 'Mata uang default');
        Setting::set('theme', $request->theme, 'Tema aplikasi (light/dark)');

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function storeUser(Request $request)
    {
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:super_admin,admin',
            'permissions' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'permissions' => $request->permissions ?? [],
        ]);

        return redirect()->route('settings.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function editUser(User $user)
    {
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'permissions' => $user->permissions ?? [],
        ]);
    }

    public function updateUser(Request $request, User $user)
    {
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        if ($user->id === Auth::id() && $request->role !== $user->role) {
            return redirect()->back()->with('error', 'Anda tidak dapat mengubah role sendiri.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:super_admin,admin',
            'permissions' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->has('is_active'),
            'permissions' => $request->permissions ?? [],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('settings.index')->with('success', 'User berhasil diupdate.');
    }

    public function deleteUser(User $user)
    {
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();
        return redirect()->route('settings.index')->with('success', 'User berhasil dihapus.');
    }
}