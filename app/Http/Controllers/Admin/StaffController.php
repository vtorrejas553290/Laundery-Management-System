<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::orderBy('created_at', 'desc')->get();
        return view('staff', compact('staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:0',
            'contact' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'required|email|unique:staff,email',
            'password' => 'required|string|min:8',
        ]);

        // Generate new ID (SF1, SF2, SF3, etc.)
        $lastStaff = Staff::orderBy('id', 'desc')->first();
        if ($lastStaff) {
            $lastNumber = intval(substr($lastStaff->id, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'SF' . $newNumber;

        // Hash the password
        $validated['password'] = Hash::make($validated['password']);
        $validated['id'] = $newId;

        $staff = Staff::create($validated);
        return response()->json(['success' => true, 'staff' => $staff]);
    }

    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'age' => 'required|integer|min:0',
            'contact' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'required|email|unique:staff,email,' . $staff->id . ',id',
            'password' => 'nullable|string|min:8',
        ]);

        // Only hash and update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $staff->update($validated);
        return response()->json(['success' => true, 'staff' => $staff]);
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->json(['success' => true]);
    }
    public function show(Staff $staff)
{
    return response()->json([
        'id' => $staff->id,
        'first_name' => $staff->first_name,
        'middle_name' => $staff->middle_name,
        'last_name' => $staff->last_name,
        'email' => $staff->email,
        'contact' => $staff->contact,
        'address' => $staff->address,
        'birthday' => $staff->birthday,
        'age' => $staff->age,
        'created_at' => $staff->created_at,
        'updated_at' => $staff->updated_at,
    ]);
}
}