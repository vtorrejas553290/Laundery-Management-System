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
        $staffId = Auth::guard('staff')->user()->id;
        
        $transactions = Transaction::with(['customer', 'serviceType', 'staff', 'status', 'extraItems'])
            ->where('staff_id', $staffId)
            ->orderBy('created_at', 'desc')
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
        $staffId = Auth::guard('staff')->user()->id;
        
        $transaction = Transaction::with(['customer', 'serviceType', 'staff', 'status', 'extraItems'])
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        return response()->json([
            'id' => $transaction->id,
            'customer_id' => $transaction->customer_id,
            'customer_name' => $transaction->customer ? $transaction->customer->first_name . ' ' . $transaction->customer->last_name : 'N/A',
            'service_type_id' => $transaction->service_type_id,
            'service_name' => $transaction->serviceType ? $transaction->serviceType->name : 'N/A',
            'staff_id' => $transaction->staff_id,
            'staff_name' => $transaction->staff ? $transaction->staff->first_name . ' ' . $transaction->staff->last_name : 'N/A',
            'status_id' => $transaction->status_id,
            'status_name' => $transaction->status ? $transaction->status->status_name : 'N/A',
            'weight' => (float) $transaction->weight,
            'number_of_loads' => (int) $transaction->number_of_loads,
            'total_amount' => (float) $transaction->total_amount,
            'remarks' => $transaction->remarks ?? '',
            'transaction_date' => $transaction->transaction_date,
            'extra_items_formatted' => $transaction->extraItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'price' => (float) $item->price,
                    'quantity' => (int) $item->pivot->quantity,
                    'subtotal' => (float) $item->pivot->subtotal
                ];
            })
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'staff_id' => 'required|exists:staff,id',
            'status_id' => 'required|exists:statuses,id',
            'weight' => 'required|numeric|min:0',
            'number_of_loads' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'extra_items' => 'nullable|array',
        ]);

        // Generate new ID (TR1, TR2, TR3, etc.)
        $lastTransaction = Transaction::orderBy('id', 'desc')->first();
        if ($lastTransaction) {
            $lastNumber = intval(substr($lastTransaction->id, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'TR' . $newNumber;

        DB::beginTransaction();
        try {
            $transaction = Transaction::create([
                'id' => $newId,
                'customer_id' => $validated['customer_id'],
                'service_type_id' => $validated['service_type_id'],
                'staff_id' => $validated['staff_id'],
                'status_id' => $validated['status_id'],
                'weight' => $validated['weight'],
                'number_of_loads' => $validated['number_of_loads'],
                'total_amount' => $validated['total_amount'],
                'remarks' => $validated['remarks'],
                'transaction_date' => now()->format('Y-m-d'),
                'payment_status' => 'Pending',
            ]);
            
            // Handle extra items if any
            if (isset($validated['extra_items']) && !empty($validated['extra_items'])) {
                foreach ($validated['extra_items'] as $item) {
                    $extraItem = ExtraItem::find($item['id']);
                    if ($extraItem) {
                        $subtotal = $extraItem->price * $item['quantity'];
                        $transaction->extraItems()->attach($extraItem->id, [
                            'id' => 'TEI' . time() . rand(10, 99),
                            'quantity' => $item['quantity'],
                            'subtotal' => $subtotal
                        ]);
                    }
                }
            }
            
            DB::commit();
            return response()->json(['success' => true, 'transaction' => $transaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        $transaction = Transaction::where('id', $id)->where('staff_id', $staffId)->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'service_type_id' => 'required|exists:service_types,id',
            'staff_id' => 'required|exists:staff,id',
            'status_id' => 'required|exists:statuses,id',
            'weight' => 'required|numeric|min:0',
            'number_of_loads' => 'required|integer|min:1',
            'total_amount' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',
            'extra_items' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $transaction->update([
                'customer_id' => $validated['customer_id'],
                'service_type_id' => $validated['service_type_id'],
                'staff_id' => $validated['staff_id'],
                'status_id' => $validated['status_id'],
                'weight' => $validated['weight'],
                'number_of_loads' => $validated['number_of_loads'],
                'total_amount' => $validated['total_amount'],
                'remarks' => $validated['remarks'],
            ]);
            
            // Sync extra items
            $transaction->extraItems()->detach();
            
            if (isset($validated['extra_items']) && !empty($validated['extra_items'])) {
                foreach ($validated['extra_items'] as $item) {
                    $extraItem = ExtraItem::find($item['id']);
                    if ($extraItem) {
                        $subtotal = $extraItem->price * $item['quantity'];
                        $transaction->extraItems()->attach($extraItem->id, [
                            'id' => 'TEI' . time() . rand(10, 99),
                            'quantity' => $item['quantity'],
                            'subtotal' => $subtotal
                        ]);
                    }
                }
            }
            
            DB::commit();
            return response()->json(['success' => true, 'transaction' => $transaction]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $staffId = Auth::guard('staff')->user()->id;
        
        $transaction = Transaction::where('id', $id)->where('staff_id', $staffId)->first();
        
        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        $transaction->delete();
        return response()->json(['success' => true]);
    }
}