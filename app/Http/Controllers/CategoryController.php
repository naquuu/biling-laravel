<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua buku
        $books = Book::where('is_active', true)->orderBy('code')->get();
        
        // Ambil buku dari parameter atau buku pertama
        $bookId = $request->get('book');
        $firstBook = $bookId ? Book::find($bookId) : $books->first();
        
        if ($firstBook) {
            // Ambil data transaksi untuk buku tersebut
            $transactions = Transaction::with('party')
                ->where('book_id', $firstBook->id)
                ->where('created_by', Auth::id())
                ->orderBy('date', 'desc')
                ->take(5)
                ->get();
            
            $totalAmount = Transaction::where('book_id', $firstBook->id)
                ->where('created_by', Auth::id())
                ->sum('amount');
            
            $transactionCount = Transaction::where('book_id', $firstBook->id)
                ->where('created_by', Auth::id())
                ->count();
        } else {
            $transactions = collect();
            $totalAmount = 0;
            $transactionCount = 0;
        }
        
        return view('categories.index', compact('books', 'firstBook', 'transactions', 'totalAmount', 'transactionCount'));
    }

    public function getTransactions($bookId)
    {
        $book = Book::findOrFail($bookId);
        
        $transactions = Transaction::with('party')
            ->where('book_id', $bookId)
            ->where('created_by', Auth::id())
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        
        $totalAmount = Transaction::where('book_id', $bookId)
            ->where('created_by', Auth::id())
            ->sum('amount');
        
        $transactionCount = Transaction::where('book_id', $bookId)
            ->where('created_by', Auth::id())
            ->count();
        
        return response()->json([
            'book' => $book,
            'total_amount' => $totalAmount,
            'transaction_count' => $transactionCount,
            'transactions' => $transactions->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'date' => $transaction->date->format('d/m/Y'),
                    'party_name' => $transaction->party->name ?? '-',
                    'amount' => number_format($transaction->amount, 0, ',', '.'),
                    'payment_status' => $transaction->payment_status,
                    'status_badge' => $this->getStatusBadge($transaction->payment_status),
                ];
            }),
        ]);
    }
    
    private function getStatusBadge($status)
    {
        return match($status) {
            'lunas' => '<span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs">✅ Lunas</span>',
            'cicil' => '<span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full text-xs">🔄 Cicil</span>',
            'hutang' => '<span class="px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full text-xs">⚠️ Hutang</span>',
            default => '<span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full text-xs">-</span>',
        };
    }
}