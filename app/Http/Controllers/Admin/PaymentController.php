<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('transaction')->orderBy('created_at', 'desc')->get();
        $transactions = Transaction::all();
        return view('payments', compact('payments', 'transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
        ]);

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
        $transaction = Transaction::find($validated['transaction_id']);
        $transaction->updatePaymentStatus();
        
        return response()->json(['success' => true, 'payment' => $payment]);
    }

    public function update(Request $request, Payment $payment)
    {
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

    public function destroy(Payment $payment)
    {
        $transactionId = $payment->transaction_id;
        $payment->delete();
        
        // Update transaction payment status after deletion
        $transaction = Transaction::find($transactionId);
        if ($transaction) {
            $transaction->updatePaymentStatus();
        }
        
        return response()->json(['success' => true]);
    }
    public function show(Payment $payment)
{
    $payment->load('transaction');
    return response()->json([
        'id' => $payment->id,
        'transaction_id' => $payment->transaction_id,
        'payment_amount' => $payment->payment_amount,
        'payment_method' => $payment->payment_method,
        'payment_status' => $payment->payment_status,
        'paid_at' => $payment->paid_at,
        'transaction' => [
            'customer_name' => $payment->transaction->customer->name ?? 'N/A',
            'total_amount' => $payment->transaction->total_amount,
            'payment_status' => $payment->transaction->payment_status,
            'created_at' => $payment->transaction->created_at,
        ]
    ]);
}
}