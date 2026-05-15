<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['book', 'party'])
            ->where('created_by', Auth::id());

        // Filter berdasarkan tanggal
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        // Filter berdasarkan buku
        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        // Filter berdasarkan party
        if ($request->filled('party_id')) {
            $query->where('party_id', $request->party_id);
        }

        // Filter berdasarkan status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $transactions = $query->orderBy('date', 'desc')->get();
        
        // Hitung total
        $totalAmount = $transactions->sum('amount');
        $totalPaid = $transactions->sum('paid_amount');
        $totalRemaining = $totalAmount - $totalPaid;
        
        // Data untuk filter dropdown
        $books = Book::where('is_active', true)->get();
        $parties = Party::where('is_active', true)->get();
        
        return view('reports.index', compact(
            'transactions', 'books', 'parties',
            'totalAmount', 'totalPaid', 'totalRemaining',
            'startDate', 'endDate'
        ));
    }
}