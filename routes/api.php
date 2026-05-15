<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class);
Route::apiResource('transactions', TransactionController::class);

// Laporan khusus
Route::get('reports/balance/{book_id}', [TransactionController::class, 'getBalance']);
Route::get('reports/ledger/{book_id}', [TransactionController::class, 'getLedger']);