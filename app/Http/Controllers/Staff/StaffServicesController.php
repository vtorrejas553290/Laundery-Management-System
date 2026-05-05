<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceType;

class StaffServicesController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::with('category')->orderBy('name', 'asc')->get();
        $categories = ServiceCategory::all();
        return view('staff.services', compact('serviceTypes', 'categories'));
    }
}