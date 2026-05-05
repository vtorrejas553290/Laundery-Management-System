<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffProfileController extends Controller
{
    public function index()
    {
        $staff = Auth::guard('staff')->user();
        return view('staff.profile', compact('staff'));
    }
    
    public function update(Request $request)
    {
        $staff = Auth::guard('staff')->user();
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'contact' => 'required|string|max:20',
            'address' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        $staff->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'],
            'last_name' => $validated['last_name'],
            'contact' => $validated['contact'],
            'address' => $validated['address'],
        ]);
        
        if (!empty($validated['password'])) {
            $staff->update(['password' => Hash::make($validated['password'])]);
        }
        
        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }
}