<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\ServiceType;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        // Get all paid payments from the view
        $paidPayments = DB::table('vw_payment_details')
            ->where('payment_status', 'Paid')
            ->orderBy('paid_at', 'desc')
            ->get()
            ->map(function($payment) {
                $payment->payment_status = 'Paid';
                return $payment;
            });
        
        // Get all unpaid transactions (transactions without payment or with payment_status = 'Unpaid')
        $unpaidTransactions = DB::table('vw_transaction_details')
            ->where('payment_status', '!=', 'Paid')
            ->orWhereNull('payment_status')
            ->get()
            ->map(function($transaction) {
                // Create a payment-like object for unpaid transactions
                return (object)[
                    'payment_id' => null,
                    'transaction_id' => $transaction->transaction_id,
                    'customer_name' => $transaction->customer_name,
                    'service_type' => $transaction->service_type,
                    'payment_amount' => $transaction->total_amount,
                    'payment_method' => null,
                    'payment_status' => 'Unpaid',
                    'paid_at' => null,
                    'transaction_date' => $transaction->transaction_date,
                ];
            });
        
        // Combine paid payments and unpaid transactions
        $allPayments = $paidPayments->concat($unpaidTransactions)
            ->sortByDesc(function($item) {
                return $item->paid_at ?? $item->transaction_date;
            });
        
        // Get transactions for the dropdown
        $transactions = Transaction::with(['customer', 'serviceType'])->get();
        
        $transactions = $transactions->map(function($transaction) {
            $serviceTotal = ($transaction->serviceType->price_per_load ?? 0) * ($transaction->number_of_loads ?? 0);
            $extraTotal = DB::table('transaction_extra_items')
                ->where('transaction_id', $transaction->id)
                ->sum('subtotal');
            $totalAmount = $serviceTotal + $extraTotal;
            
            $transaction->calculated_total = $totalAmount;
            $transaction->payment_status = $transaction->payment_status ?? 'Unpaid';
            $transaction->customer_name = $transaction->customer ? 
                $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 'N/A';
            
            return $transaction;
        });
        
        return view('payments', compact('allPayments', 'transactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaction_id' => 'required|exists:transactions,id',
            'payment_method' => 'nullable|string',
            'payment_status' => 'required|string|in:Paid,Unpaid',
        ]);

        DB::beginTransaction();
        try {
            // Get the transaction
            $transaction = Transaction::find($validated['transaction_id']);
            
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            
            // Check if transaction is already paid
            if ($transaction->payment_status === 'Paid') {
                return response()->json(['error' => 'Transaction already paid'], 422);
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

            $paymentData = [
                'id' => $newId,
                'transaction_id' => $validated['transaction_id'],
                'payment_amount' => 0,
                'payment_method' => $validated['payment_status'] === 'Paid' ? $validated['payment_method'] : null,
                'payment_status' => $validated['payment_status'],
                'paid_at' => $validated['payment_status'] === 'Paid' ? now() : null,
            ];

            if ($validated['payment_status'] === 'Paid' && empty($validated['payment_method'])) {
                return response()->json(['error' => 'Payment method is required for Paid status'], 422);
            }

            $payment = Payment::create($paymentData);
            $payment->refresh();
            $this->updateTransactionPaymentStatus($transaction->id);
            
            DB::commit();
            
            $createdPayment = DB::table('vw_payment_details')
                ->where('payment_id', $newId)
                ->first();
            
            return response()->json([
                'success' => true, 
                'payment' => $createdPayment ?? $payment
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to create payment: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|string',
            'payment_status' => 'required|string|in:Paid,Unpaid',
        ]);

        DB::beginTransaction();
        try {
            $transaction = Transaction::find($payment->transaction_id);

            $updateData = [
                'payment_status' => $validated['payment_status'],
            ];

            if ($validated['payment_status'] === 'Paid') {
                $updateData['payment_amount'] = 0;
                $updateData['payment_method'] = $validated['payment_method'];
                $updateData['paid_at'] = now();
                
                if (empty($validated['payment_method'])) {
                    return response()->json(['error' => 'Payment method is required for Paid status'], 422);
                }
            } elseif ($validated['payment_status'] === 'Unpaid') {
                $updateData['payment_method'] = null;
                $updateData['paid_at'] = null;
            }

            $payment->update($updateData);
            $payment->refresh();
            $this->updateTransactionPaymentStatus($transaction->id);
            
            DB::commit();
            
            $updatedPayment = DB::table('vw_payment_details')
                ->where('payment_id', $payment->id)
                ->first();
            
            return response()->json([
                'success' => true, 
                'payment' => $updatedPayment ?? $payment
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to update payment: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Payment $payment)
    {
        DB::beginTransaction();
        try {
            $transactionId = $payment->transaction_id;
            $payment->delete();
            $this->updateTransactionPaymentStatus($transactionId);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete payment: ' . $e->getMessage()], 500);
        }
    }
    
    public function show(Payment $payment)
    {
        try {
            // First, get the payment details from the payments table directly
            $paymentData = Payment::find($payment->id);
            
            if (!$paymentData) {
                return response()->json(['error' => 'Payment not found'], 404);
            }
            
            // Get the transaction details
            $transaction = Transaction::with(['customer', 'serviceType'])->find($paymentData->transaction_id);
            
            if (!$transaction) {
                return response()->json(['error' => 'Transaction not found'], 404);
            }
            
            // Get extra items details
            $extraItems = DB::table('transaction_extra_items')
                ->join('extra_items', 'transaction_extra_items.extra_item_id', '=', 'extra_items.id')
                ->where('transaction_extra_items.transaction_id', $transaction->id)
                ->select('extra_items.item_name', 'transaction_extra_items.quantity', 'transaction_extra_items.subtotal')
                ->get();
            
            // Calculate totals
            $serviceTotal = ($transaction->serviceType->price_per_load ?? 0) * ($transaction->number_of_loads ?? 0);
            $extraTotal = $extraItems->sum('subtotal');
            $correctTotal = $serviceTotal + $extraTotal;
            
            // Get customer name
            $customerName = $transaction->customer ? 
                $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 'N/A';
            
            return response()->json([
                'id' => $paymentData->id,
                'transaction_id' => $paymentData->transaction_id,
                'payment_amount' => $paymentData->payment_amount,
                'payment_method' => $paymentData->payment_method,
                'payment_status' => $paymentData->payment_status,
                'paid_at' => $paymentData->paid_at,
                'transaction' => [
                    'customer_name' => $customerName,
                    'service_type' => $transaction->serviceType->name ?? 'N/A',
                    'total_amount' => $correctTotal,
                    'service_total' => $serviceTotal,
                    'extra_total' => $extraTotal,
                    'payment_status' => $transaction->payment_status,
                    'created_at' => $transaction->created_at,
                    'extra_items' => $extraItems->map(function($item) {
                        return [
                            'item_name' => $item->item_name,
                            'quantity' => $item->quantity,
                            'subtotal' => (float) $item->subtotal
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Payment show error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load payment details: ' . $e->getMessage()], 500);
        }
    }
    
    private function updateTransactionPaymentStatus($transactionId)
    {
        $transaction = Transaction::find($transactionId);
        if (!$transaction) return;
        
        $hasPaidPayment = Payment::where('transaction_id', $transactionId)
            ->where('payment_status', 'Paid')
            ->exists();
        
        if ($hasPaidPayment) {
            $transaction->payment_status = 'Paid';
        } else {
            $hasAnyPayment = Payment::where('transaction_id', $transactionId)->exists();
            if ($hasAnyPayment) {
                $transaction->payment_status = 'Unpaid';
            } else {
                $transaction->payment_status = 'Pending';
            }
        }
        
        $transaction->save();
    }
}