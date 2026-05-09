<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceType;
use App\Models\Staff;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\ExtraItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffTransactionsController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        $staffId = $staff->id;
        $staffName = $staff->first_name . ' ' . $staff->last_name;
        
        // ✅ USING THE VIEW for transaction details, filtered by staff_name
        $transactions = DB::table('vw_transaction_details')
            ->where('staff_name', $staffName)
            ->orderBy('transaction_date', 'desc')
            ->get();
        
        $customers = Customer::all();
        $services = ServiceType::with('category')->get();
        $staff = Staff::all();
        $statuses = Status::all();
        $extraItems = ExtraItem::all();
        
        return view('staff.transactions', compact('transactions', 'customers', 'services', 'staff', 'statuses', 'extraItems'));
    }

    public function show($id)
    {
        $staff = Auth::guard('staff')->user();
        $staffName = $staff->first_name . ' ' . $staff->last_name;
        
        // ✅ USING THE VIEW for transaction details, filtered by staff_name
        $transaction = DB::table('vw_transaction_details')
            ->where('transaction_id', $id)
            ->where('staff_name', $staffName)
            ->first();
        
        // Get extra items separately using the extra items view
        $extraItems = DB::table('vw_transaction_extra_items')
            ->where('transaction_id', $id)
            ->get();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        // Get the full transaction for relationships needed in edit
        $fullTransaction = Transaction::with(['customer', 'serviceType', 'staff', 'status', 'extraItems'])
            ->where('id', $id)
            ->where('staff_id', $staff->id)
            ->first();
        
        // Calculate service total properly
        $servicePrice = $fullTransaction->serviceType->price_per_load ?? 0;
        $numberOfLoads = $transaction->number_of_loads ?? $fullTransaction->number_of_loads ?? 0;
        $serviceTotal = $servicePrice * $numberOfLoads;
        $extraTotal = (float) $extraItems->sum('subtotal');
        $grandTotal = $serviceTotal + $extraTotal;
        
        return response()->json([
            'id' => $transaction->transaction_id,
            'customer_id' => $fullTransaction->customer_id,
            'customer_name' => $transaction->customer_name,
            'service_type_id' => $fullTransaction->service_type_id,
            'service_name' => $transaction->service_type,
            'staff_id' => $fullTransaction->staff_id,
            'staff_name' => $transaction->staff_name,
            'status_id' => $fullTransaction->status_id,
            'status_name' => $transaction->transaction_status,
            'weight' => (float) $transaction->weight,
            'number_of_loads' => (int) $numberOfLoads,
            'total_amount' => (float) $grandTotal,
            'remarks' => $fullTransaction->remarks ?? '',
            'transaction_date' => $transaction->transaction_date,
            'service_price' => (float) $servicePrice,
            'service_total' => (float) $serviceTotal,
            'extra_total' => (float) $extraTotal,
            'extra_items_formatted' => $extraItems->map(function($item) {
                return [
                    'id' => $item->extra_item_id ?? $item->transaction_extra_item_id,
                    'item_name' => $item->item_name,
                    'price' => (float) $item->item_price,
                    'quantity' => (int) $item->quantity,
                    'subtotal' => (float) $item->subtotal
                ];
            })
        ]);
    }

    public function getExtraItemsTotal($id)
    {
        $staff = Auth::guard('staff')->user();
        $staffName = $staff->first_name . ' ' . $staff->last_name;
        
        // Verify the transaction belongs to this staff member
        $transaction = DB::table('vw_transaction_details')
            ->where('transaction_id', $id)
            ->where('staff_name', $staffName)
            ->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $extraTotal = DB::table('vw_transaction_extra_items')
            ->where('transaction_id', $id)
            ->sum('subtotal');
        
        return response()->json(['extra_total' => (float) $extraTotal]);
    }

    public function getServicePrice($id)
    {
        $staff = Auth::guard('staff')->user();
        $staffName = $staff->first_name . ' ' . $staff->last_name;
        
        // Verify the transaction belongs to this staff member
        $transaction = DB::table('vw_transaction_details')
            ->where('transaction_id', $id)
            ->where('staff_name', $staffName)
            ->first();
        
        // Get the full transaction for service price
        $fullTransaction = Transaction::with('serviceType')
            ->where('id', $id)
            ->first();
            
        $servicePrice = $fullTransaction && $fullTransaction->serviceType ? $fullTransaction->serviceType->price_per_load : 0;
        
        return response()->json(['service_price' => (float) $servicePrice]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'staff_id' => 'required|exists:staff,id',
            'status_id' => 'required|exists:statuses,id',
            'weight' => 'nullable|numeric|min:0',
            'number_of_loads' => 'nullable|integer|min:1',
            'remarks' => 'nullable|string',
            'extra_items' => 'nullable|array',
        ]);

        // Generate new ID (TR1, TR2, TR3, etc.)
        $allTransactions = Transaction::all();
        $maxNumber = 0;
        
        foreach ($allTransactions as $transaction) {
            $number = intval(substr($transaction->id, 2));
            if ($number > $maxNumber) {
                $maxNumber = $number;
            }
        }
        
        $newNumber = $maxNumber + 1;
        $newId = 'TR' . $newNumber;

        DB::beginTransaction();
        try {
            // Let the database trigger handle number_of_loads and total_amount
            $transaction = Transaction::create([
                'id' => $newId,
                'customer_id' => $validated['customer_id'],
                'service_type_id' => $validated['service_type_id'],
                'staff_id' => $validated['staff_id'],
                'status_id' => $validated['status_id'],
                'weight' => $validated['weight'] ?? null,
                'number_of_loads' => $validated['number_of_loads'] ?? null,
                'total_amount' => 0, // Placeholder - trigger will calculate
                'remarks' => $validated['remarks'],
                'transaction_date' => now()->format('Y-m-d'),
                'payment_status' => 'Pending',
            ]);
            
            // Refresh to get trigger-calculated values
            $transaction->refresh();
            
            if (isset($validated['extra_items']) && !empty($validated['extra_items'])) {
                foreach ($validated['extra_items'] as $item) {
                    $extraItem = ExtraItem::find($item['id']);
                    if ($extraItem) {
                        // Let trigger calculate subtotal
                        $transaction->extraItems()->attach($extraItem->id, [
                            'id' => 'TEI' . time() . rand(10, 99),
                            'quantity' => $item['quantity'],
                            'subtotal' => 0 // Trigger will calculate
                        ]);
                    }
                }
            }
            
            DB::commit();
            
            // Get the created transaction from the view for response
            $createdTransaction = DB::table('vw_transaction_details')
                ->where('transaction_id', $newId)
                ->first();
            
            return response()->json(['success' => true, 'transaction' => $createdTransaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $staff = Auth::guard('staff')->user();
        $staffName = $staff->first_name . ' ' . $staff->last_name;
        
        // Verify transaction belongs to this staff member using the view
        $transactionView = DB::table('vw_transaction_details')
            ->where('transaction_id', $id)
            ->where('staff_name', $staffName)
            ->first();
        
        if (!$transactionView) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $transaction = Transaction::where('id', $id)->first();
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'staff_id' => 'required|exists:staff,id',
            'status_id' => 'required|exists:statuses,id',
            'weight' => 'nullable|numeric|min:0',
            'number_of_loads' => 'nullable|integer|min:1',
            'remarks' => 'nullable|string',
            'extra_items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            // Prepare update data - let trigger handle calculations
            $updateData = [
                'customer_id' => $validated['customer_id'],
                'service_type_id' => $validated['service_type_id'],
                'staff_id' => $validated['staff_id'],
                'status_id' => $validated['status_id'],
                'weight' => $validated['weight'] ?? null,
                'remarks' => $validated['remarks'],
            ];
            
            // Only set number_of_loads if provided (for non-wash services)
            if (isset($validated['number_of_loads'])) {
                $updateData['number_of_loads'] = $validated['number_of_loads'];
            }
            
            // DO NOT set total_amount - let the trigger calculate it
            $transaction->update($updateData);
            
            // Update extra items
            $transaction->extraItems()->detach();
            
            if (isset($validated['extra_items']) && !empty($validated['extra_items'])) {
                foreach ($validated['extra_items'] as $item) {
                    $extraItem = ExtraItem::find($item['id']);
                    if ($extraItem) {
                        // Let trigger calculate subtotal
                        $transaction->extraItems()->attach($extraItem->id, [
                            'id' => 'TEI' . time() . rand(10, 99),
                            'quantity' => $item['quantity'],
                            'subtotal' => 0 // Trigger will calculate
                        ]);
                    }
                }
            }
            
            // Refresh to get trigger-calculated values
            $transaction->refresh();
            
            DB::commit();
            
            // Get the updated transaction from the view for response
            $updatedTransaction = DB::table('vw_transaction_details')
                ->where('transaction_id', $id)
                ->first();
            
            return response()->json(['success' => true, 'transaction' => $updatedTransaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $staff = Auth::guard('staff')->user();
        $staffName = $staff->first_name . ' ' . $staff->last_name;
        
        // Verify transaction belongs to this staff member using the view
        $transactionView = DB::table('vw_transaction_details')
            ->where('transaction_id', $id)
            ->where('staff_name', $staffName)
            ->first();
        
        if (!$transactionView) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $transaction = Transaction::where('id', $id)->first();
        $transaction->delete();
        return response()->json(['success' => true]);
    }
}