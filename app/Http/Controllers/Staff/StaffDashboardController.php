<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        // Total transactions for this staff
        $totalTransactions = Transaction::where('staff_id', $staffId)->count();
        
        // Transactions this week
        $thisWeekTransactions = Transaction::where('staff_id', $staffId)
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->count();
        
        // In Progress transactions
        $inProgress = Transaction::where('staff_id', $staffId)
            ->whereHas('status', function($query) {
                $query->where('status_name', 'In Progress');
            })
            ->count();
        
        // Completed transactions
        $completed = Transaction::where('staff_id', $staffId)
            ->whereHas('status', function($query) {
                $query->where('status_name', 'Completed');
            })
            ->count();
        
        // Recent transactions (last 5)
        $recentTransactions = Transaction::with(['customer', 'serviceType', 'status'])
            ->where('staff_id', $staffId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $stats = [
            'totalTransactions' => $totalTransactions,
            'thisWeekTransactions' => $thisWeekTransactions,
            'inProgress' => $inProgress,
            'completed' => $completed,
        ];
        
        return view('staff.dashboard', compact('stats', 'recentTransactions'));
    }
}