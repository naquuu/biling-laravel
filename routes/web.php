<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes sudah termasuk dari Breeze
require __DIR__.'/auth.php';

// Group dengan middleware auth
Route::middleware(['auth'])->group(function () {
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Books (Buku)
    Route::resource('books', BookController::class);
    
    // Parties (Pelanggan & Supplier)
    Route::get('/parties/suggestions', [PartyController::class, 'searchSuggestions'])->name('parties.suggestions');
    Route::resource('parties', PartyController::class);
    
    // Transactions
    Route::get('/transactions/suggestions', [TransactionController::class, 'searchSuggestions'])->name('transactions.suggestions');
    Route::resource('transactions', TransactionController::class);
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    
    // Categories (Kategori Buku)
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/api/categories/{book}/transactions', [CategoryController::class, 'getTransactions'])->name('categories.api');
    
    // Export sementara dinonaktifkan
    // Route::get('/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('reports.export-pdf');
    // Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])->name('reports.export-excel');

    // Pengaturan (Super Admin only)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'updateSettings'])->name('update');
        
        // Manajemen User
        Route::post('/users', [SettingController::class, 'storeUser'])->name('user.store');
        Route::get('/users/{user}/edit', [SettingController::class, 'editUser'])->name('user.edit');
        Route::put('/users/{user}', [SettingController::class, 'updateUser'])->name('user.update');
        Route::delete('/users/{user}', [SettingController::class, 'deleteUser'])->name('user.delete');
        
        // Legacy routes (masih dipertahankan untuk kompatibilitas)
        Route::put('/users/{user}/role', [SettingController::class, 'updateUserRole'])->name('user.role');
        Route::put('/users/{user}/toggle', [SettingController::class, 'toggleUserStatus'])->name('user.toggle');
    });
});