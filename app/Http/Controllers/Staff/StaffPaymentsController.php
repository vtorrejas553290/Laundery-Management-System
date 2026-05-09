<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\ServiceType;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffPaymentsController extends Controller
{
    public function index()
    {
        $staffId = Auth::guard('staff')->user()->id;
        $staffName = Auth::guard('staff')->user()->first_name . ' ' . Auth::guard('staff')->user()->last_name;
        
        // ✅ Get paid payments by joining with transactions table
        $paidPayments = DB::table('vw_payment_details')
            ->where('payment_status', 'Paid')
            ->orderBy('paid_at', 'desc')
            ->get()
            ->map(function($payment) use ($staffId) {
                // Verify this payment belongs to the staff member by checking the transaction
                $transaction = Transaction::find($payment->transaction_id);
                if ($transaction && $transaction->staff_id === $staffId) {
                    $payment->payment_status = 'Paid';
                    return $payment;
                }
                return null;
            })
            ->filter(); // Remove null values
        
        // ✅ Get unpaid transactions using vw_transaction_details filtered by staff_name
        $unpaidTransactions = DB::table('vw_transaction_details')
            ->where('staff_name', $staffName)
            ->where(function($query) {
                $query->where('payment_status', '!=', 'Paid')
                      ->orWhereNull('payment_status');
            })
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
        
        // Get transactions for the dropdown (only for this staff member)
        $transactions = Transaction::with(['customer', 'serviceType'])
            ->where('staff_id', $staffId)
            ->get();
        
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
        
        return view('staff.payments', compact('allPayments', 'transactions'));
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
            // Get the transaction and verify it belongs to this staff
            $transaction = Transaction::where('id', $validated['transaction_id'])
                ->where('staff_id', Auth::guard('staff')->user()->id)
                ->first();
            
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

            // Let the database trigger handle payment_amount
            $paymentData = [
                'id' => $newId,
                'transaction_id' => $validated['transaction_id'],
                'payment_amount' => 0, // Placeholder - trigger will override
                'payment_method' => $validated['payment_status'] === 'Paid' ? $validated['payment_method'] : null,
                'payment_status' => $validated['payment_status'],
                'paid_at' => $validated['payment_status'] === 'Paid' ? now() : null,
            ];

            // Validate payment method for Paid status
            if ($validated['payment_status'] === 'Paid' && empty($validated['payment_method'])) {
                return response()->json(['error' => 'Payment method is required for Paid status'], 422);
            }

            $payment = Payment::create($paymentData);
            
            // Refresh to get trigger-calculated values
            $payment->refresh();
            
            // Update transaction payment status
            $this->updateTransactionPaymentStatus($transaction->id);
            
            DB::commit();
            
            // Get the created payment from the view for response
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

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method' => 'nullable|string',
            'payment_status' => 'required|string|in:Paid,Unpaid',
        ]);

        DB::beginTransaction();
        try {
            // Get payment and verify it belongs to this staff's transaction
            $payment = Payment::whereHas('transaction', function($query) {
                    $query->where('staff_id', Auth::guard('staff')->user()->id);
                })
                ->where('id', $id)
                ->first();
            
            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }
            
            $transaction = Transaction::find($payment->transaction_id);

            $updateData = [
                'payment_status' => $validated['payment_status'],
            ];

            if ($validated['payment_status'] === 'Paid') {
                $updateData['payment_amount'] = 0; // Placeholder - trigger will override
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
            
            // Refresh to get trigger-calculated values
            $payment->refresh();
            
            // Update transaction payment status
            $this->updateTransactionPaymentStatus($transaction->id);
            
            DB::commit();
            
            // Get the updated payment from the view for response
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

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Get payment and verify it belongs to this staff's transaction
            $payment = Payment::whereHas('transaction', function($query) {
                    $query->where('staff_id', Auth::guard('staff')->user()->id);
                })
                ->where('id', $id)
                ->first();
            
            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }
            
            $transactionId = $payment->transaction_id;
            $payment->delete();
            
            // Update transaction payment status
            $this->updateTransactionPaymentStatus($transactionId);
            
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to delete payment: ' . $e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        try {
            // Get payment and verify it belongs to this staff's transaction
            $payment = Payment::whereHas('transaction', function($query) {
                    $query->where('staff_id', Auth::guard('staff')->user()->id);
                })
                ->where('id', $id)
                ->first();
            
            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }
            
            // Get the transaction details
            $transaction = Transaction::with(['customer', 'serviceType'])->find($payment->transaction_id);
            
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
                'id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'payment_amount' => $payment->payment_amount,
                'payment_method' => $payment->payment_method,
                'payment_status' => $payment->payment_status,
                'paid_at' => $payment->paid_at,
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