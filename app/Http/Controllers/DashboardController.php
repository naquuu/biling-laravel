<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'month');
        
        // Data untuk grafik
        $chartData = $this->getChartData($filter);
        
        // Transaksi baru masuk (5 terbaru)
        $recentTransactions = Transaction::with(['book', 'party'])
            ->where('created_by', Auth::id())
            ->latest()
            ->take(5)
            ->get();
        
        // Pembayaran terlambat
        $overduePayments = Transaction::with(['book', 'party'])
            ->where('created_by', Auth::id())
            ->where('payment_status', '!=', 'lunas')
            ->where('due_date', '<', Carbon::today())
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Ringkasan statistik
        $totalIncome = Transaction::where('created_by', Auth::id())
            ->whereHas('book', function($q) {
                $q->where('name', 'like', '%Penjualan%');
            })
            ->sum('amount');
            
        $totalExpense = Transaction::where('created_by', Auth::id())
            ->whereHas('book', function($q) {
                $q->where('name', 'like', '%Pakan%')
                  ->orWhere('name', 'like', '%Karyawan%')
                  ->orWhere('name', 'like', '%Sopir%');
            })
            ->sum('amount');
            
        $balance = $totalIncome - $totalExpense;
        $totalTransactions = Transaction::where('created_by', Auth::id())->count();
        
        return view('dashboard', compact(
            'chartData', 'recentTransactions', 'overduePayments',
            'totalIncome', 'totalExpense', 'balance', 'filter', 'totalTransactions'
        ));
    }
    
    private function getChartData($filter)
    {
        $userId = Auth::id();
        $labels = [];
        $incomeData = [];
        $expenseData = [];
        
        switch($filter) {
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('D, d/m');
                    $incomeData[] = $this->getDailyTotal($userId, $date, 'income');
                    $expenseData[] = $this->getDailyTotal($userId, $date, 'expense');
                }
                break;
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::today()->subMonths($i);
                    $labels[] = $date->translatedFormat('M Y');
                    $incomeData[] = $this->getMonthlyTotal($userId, $date, 'income');
                    $expenseData[] = $this->getMonthlyTotal($userId, $date, 'expense');
                }
                break;
            default: // month
                $daysInMonth = Carbon::now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $labels[] = $i;
                    $date = Carbon::now()->day($i);
                    $incomeData[] = $this->getDailyTotal($userId, $date, 'income');
                    $expenseData[] = $this->getDailyTotal($userId, $date, 'expense');
                }
                break;
        }
        
        return [
            'labels' => $labels,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }
    
    private function getDailyTotal($userId, $date, $type)
    {
        $query = Transaction::where('created_by', $userId)
            ->whereDate('date', $date);
        
        if ($type == 'income') {
            $query->whereHas('book', function($q) {
                $q->where('name', 'like', '%Penjualan%');
            });
        } else {
            $query->whereHas('book', function($q) {
                $q->where('name', 'like', '%Pakan%')
                  ->orWhere('name', 'like', '%Karyawan%')
                  ->orWhere('name', 'like', '%Sopir%');
            });
        }
        
        return (int) $query->sum('amount');
    }
    
    private function getMonthlyTotal($userId, $date, $type)
    {
        $query = Transaction::where('created_by', $userId)
            ->whereYear('date', $date->year)
            ->whereMonth('date', $date->month);
        
        if ($type == 'income') {
            $query->whereHas('book', function($q) {
                $q->where('name', 'like', '%Penjualan%');
            });
        } else {
            $query->whereHas('book', function($q) {
                $q->where('name', 'like', '%Pakan%')
                  ->orWhere('name', 'like', '%Karyawan%')
                  ->orWhere('name', 'like', '%Sopir%');
            });
        }
        
        return (int) $query->sum('amount');
    }
}