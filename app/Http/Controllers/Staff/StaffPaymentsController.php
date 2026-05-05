<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffPaymentsController extends Controller
{
    public function index()
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        // Get payments only for transactions assigned to this staff
        $payments = Payment::with('transaction')
            ->whereHas('transaction', function($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get transactions assigned to this staff
        $transactions = Transaction::where('staff_id', $staffId)->get();
        
        return view('staff.payments', compact('payments', 'transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        // Verify transaction belongs to this staff
        $transaction = Transaction::where('id', $validated['transaction_id'])
            ->where('staff_id', Auth::guard('staff')->user()->id)
            ->first();
            
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Generate new ID (PAY1, PAY2, PAY3, etc.)
        $lastPayment = Payment::orderBy('id', 'desc')->first();
        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment->id, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'PAY' . $newNumber;

        $validated['id'] = $newId;
        $validated['paid_at'] = now();

        $payment = Payment::create($validated);
        
        // Update transaction payment status
        $transaction->updatePaymentStatus();
        
        return response()->json(['success' => true, 'payment' => $payment]);
    }

    public function show($id)
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        $payment = Payment::with('transaction')
            ->whereHas('transaction', function($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            })
            ->where('id', $id)
            ->first();
            
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        
        return response()->json([
            'id' => $payment->id,
            'transaction_id' => $payment->transaction_id,
            'payment_amount' => (float) $payment->payment_amount,
            'payment_method' => $payment->payment_method,
            'payment_status' => $payment->payment_status,
            'paid_at' => $payment->paid_at,
            'transaction' => [
                'customer_name' => $payment->transaction->customer->first_name . ' ' . $payment->transaction->customer->last_name ?? 'N/A',
                'total_amount' => (float) $payment->transaction->total_amount,
                'payment_status' => $payment->transaction->payment_status,
                'created_at' => $payment->transaction->created_at,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        $payment = Payment::whereHas('transaction', function($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            })
            ->where('id', $id)
            ->first();
            
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        
        $validated = $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
        ]);

        $payment->update($validated);
        
        // Update transaction payment status
        $transaction = Transaction::find($payment->transaction_id);
        $transaction->updatePaymentStatus();
        
        return response()->json(['success' => true, 'payment' => $payment]);
    }

    public function destroy($id)
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        $payment = Payment::whereHas('transaction', function($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            })
            ->where('id', $id)
            ->first();
            
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        
        $transactionId = $payment->transaction_id;
        $payment->delete();
        
        // Update transaction payment status after deletion
        $transaction = Transaction::find($transactionId);
        if ($transaction) {
            $transaction->updatePaymentStatus();
        }
        
        return response()->json(['success' => true]);
    }
}