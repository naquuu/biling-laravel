<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['book', 'party', 'creator'])
            ->where('created_by', Auth::id());

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('party', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $transactions = $query->paginate(10);
        $books = Book::where('is_active', true)->get();
        $parties = Party::where('is_active', true)->get();

        return view('transactions.index', compact('transactions', 'books', 'parties'));
    }

    public function searchSuggestions(Request $request)
    {
        $search = $request->get('q');
        
        $parties = Party::where('created_by', Auth::id())
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'phone', 'address', 'type']);
        
        return response()->json($parties);
    }

    public function create()
    {
        $books = Book::where('is_active', true)->get();
        $parties = Party::where('is_active', true)->get();
        
        return view('transactions.create', compact('books', 'parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:lunas,cicil,hutang',
            'payment_method' => 'nullable|in:cash,transfer',
            'paid_amount' => 'nullable|numeric|min:0',
            'proof_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $proofImage = null;
        if ($request->hasFile('proof_image')) {
            $file = $request->file('proof_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/proofs', $filename);
            $proofImage = $filename;
        }

        Transaction::create([
            'book_id' => $request->book_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'amount' => $request->amount,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'paid_amount' => $request->paid_amount ?? 0,
            'proof_image' => $proofImage,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan');
    }

    // METHOD EDIT - INI YANG PERLU DITAMBAHKAN
    public function edit(Transaction $transaction)
    {
        $books = Book::where('is_active', true)->get();
        $parties = Party::where('is_active', true)->get();
        
        return view('transactions.edit', compact('transaction', 'books', 'parties'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'party_id' => 'required|exists:parties,id',
            'date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'payment_status' => 'required|in:lunas,cicil,hutang',
            'payment_method' => 'nullable|in:cash,transfer',
            'paid_amount' => 'nullable|numeric|min:0',
            'proof_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $proofImage = $transaction->proof_image;
        if ($request->hasFile('proof_image')) {
            if ($proofImage && Storage::exists('public/proofs/' . $proofImage)) {
                Storage::delete('public/proofs/' . $proofImage);
            }
            $file = $request->file('proof_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/proofs', $filename);
            $proofImage = $filename;
        }

        $transaction->update([
            'book_id' => $request->book_id,
            'party_id' => $request->party_id,
            'date' => $request->date,
            'due_date' => $request->due_date,
            'description' => $request->description,
            'amount' => $request->amount,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'paid_amount' => $request->paid_amount ?? 0,
            'proof_image' => $proofImage,
        ]);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diupdate');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->proof_image && Storage::exists('public/proofs/' . $transaction->proof_image)) {
            Storage::delete('public/proofs/' . $transaction->proof_image);
        }
        
        $transaction->delete();
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}