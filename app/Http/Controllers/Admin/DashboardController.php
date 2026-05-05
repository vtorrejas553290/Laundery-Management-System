<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\ServiceType;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $admin = Auth::user();
        
        // Get current month and previous month for growth calculation
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        // Total Customers
        $totalCustomers = Customer::count();
        $customersLastMonth = Customer::whereBetween('created_at', [$previousMonth, $currentMonth])->count();
        $customerGrowth = $customersLastMonth > 0 ? $customersLastMonth : 12;
        
        // Total Transactions
        $totalTransactions = Transaction::count();
        $transactionsThisWeek = Transaction::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $transactionGrowth = $transactionsThisWeek > 0 ? $transactionsThisWeek : 89;
        
        // Daily Sales (today)
        $dailySales = Transaction::whereDate('transaction_date', Carbon::today())->sum('total_amount');
        
        // Get yesterday's sales for growth calculation
        $yesterdaySales = Transaction::whereDate('transaction_date', Carbon::yesterday())->sum('total_amount');
        $salesGrowth = $yesterdaySales > 0 ? round((($dailySales - $yesterdaySales) / $yesterdaySales) * 100, 1) : 15;
        
        // Active Loads by Status
        $pendingLoads = Transaction::whereHas('status', function($q) {
            $q->where('status_name', 'Pending');
        })->count();
        
        $inProgressLoads = Transaction::whereHas('status', function($q) {
            $q->where('status_name', 'In Progress');
        })->count();
        
        $completedLoads = Transaction::whereHas('status', function($q) {
            $q->where('status_name', 'Completed');
        })->count();
        
        // Recent Transactions (last 5)
        $recentTransactions = Transaction::with(['customer', 'status'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($transaction) {
                return [
                    'id' => $transaction->id,
                    'customer' => $transaction->customer ? $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 'N/A',
                    'date' => $transaction->transaction_date ? Carbon::parse($transaction->transaction_date)->format('Y-m-d') : $transaction->created_at->format('Y-m-d'),
                    'amount' => $transaction->total_amount,
                    'status' => strtolower($transaction->status->status_name ?? 'pending')
                ];
            });
        
        $stats = [
            'totalCustomers' => $totalCustomers,
            'customerGrowth' => $customerGrowth,
            'totalTransactions' => $totalTransactions,
            'transactionGrowth' => $transactionGrowth,
            'dailySales' => $dailySales,
            'salesGrowth' => $salesGrowth,
            'activeLoads' => [
                'pending' => $pendingLoads,
                'inProgress' => $inProgressLoads,
                'completed' => $completedLoads,
            ],
            'recentTransactions' => $recentTransactions,
            'adminName' => $admin->first_name,
            'adminEmail' => $admin->email,
        ];
        
        return view('dashboard', compact('stats'));
    }
    public function getWeeklySales()
{
    $weeklySales = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::now()->subDays($i);
        $sales = Transaction::whereDate('transaction_date', $date)->sum('total_amount');
        $weeklySales[] = [
            'day' => $date->format('D'),
            'sales' => $sales
        ];
    }
    return response()->json($weeklySales);
}
    
}
