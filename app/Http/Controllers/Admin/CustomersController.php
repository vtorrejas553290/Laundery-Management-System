<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('customers', compact('customers'));
    }

    public function show(Customer $customer)
    {
        // Load the transactions count
        $customer->loadCount('transactions');
        
        return response()->json([
            'id' => $customer->id,
            'first_name' => $customer->first_name,
            'middle_name' => $customer->middle_name,
            'last_name' => $customer->last_name,
            'contact_number' => $customer->contact_number,
            'email' => $customer->email,
            'address' => $customer->address,
            'created_at' => $customer->created_at,
            'transactions_count' => $customer->transactions_count,
            'full_name' => $customer->full_name
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string|max:500',
        ]);

        // Generate new ID (CO1, CO2, CO3, etc.)
        $lastCustomer = Customer::orderBy('id', 'desc')->first();
        if ($lastCustomer) {
            $lastNumber = intval(substr($lastCustomer->id, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'CO' . $newNumber;

        $customer = Customer::create(array_merge($validated, ['id' => $newId]));
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:500',
        ]);

        $customer->update($validated);
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function destroy(Customer $customer)
    {
        // Check if customer has transactions before deleting
        if ($customer->transactions()->count() > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete customer with existing transactions. Please delete their transactions first.'
            ], 400);
        }
        
        $customer->delete();
        return response()->json(['success' => true]);
    }
}