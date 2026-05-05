<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class StaffCustomersController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->get();
        return view('staff.customers', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
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
        ]);

        $customer->update($validated);
        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json(['success' => true]);
    }
    public function show(Customer $customer)
{
    return response()->json([
        'id' => $customer->id,
        'first_name' => $customer->first_name,
        'middle_name' => $customer->middle_name,
        'last_name' => $customer->last_name,
        'contact_number' => $customer->contact_number,
        'created_at' => $customer->created_at,
        'updated_at' => $customer->updated_at,
    ]);
}
}