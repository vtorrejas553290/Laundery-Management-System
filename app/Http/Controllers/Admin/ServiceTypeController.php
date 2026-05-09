<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::with('category')->orderBy('created_at', 'desc')->get();
        $categories = ServiceCategory::all();
        return view('services', compact('serviceTypes', 'categories'));
    }

    public function show(ServiceType $serviceType)
    {
        $serviceType->load('category');
        return response()->json([
            'id' => $serviceType->id,
            'name' => $serviceType->name,
            'category_id' => $serviceType->category_id,
            'category_name' => $serviceType->category->category_name,
            'category_description' => $serviceType->category->description ?? 'No description available',
            'price_per_load' => $serviceType->price_per_load,
        ]);
    }

    public function showJson(ServiceType $serviceType)
    {
        return $this->show($serviceType);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'price_per_load' => 'required|numeric|min:0',
        ]);

        $lastService = ServiceType::orderBy('id', 'desc')->first();
        if ($lastService) {
            $lastNumber = intval(substr($lastService->id, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'ST' . $newNumber;

        $service = ServiceType::create(array_merge($validated, ['id' => $newId]));
        return response()->json(['success' => true, 'service' => $service]);
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:service_categories,id',
            'price_per_load' => 'required|numeric|min:0',
        ]);

        $serviceType->update($validated);
        return response()->json(['success' => true, 'service' => $serviceType]);
    }

    public function destroy(ServiceType $serviceType)
    {
        $serviceType->delete();
        return response()->json(['success' => true]);
    }
}