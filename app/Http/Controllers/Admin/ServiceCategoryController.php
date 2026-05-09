<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::with('serviceTypes')->orderBy('created_at', 'desc')->get();
        return view('categories', compact('categories'));
    }

    public function getCategoriesData()
    {
        $categories = ServiceCategory::with('serviceTypes')->orderBy('created_at', 'desc')->get();
        return response()->json(['categories' => $categories]);
    }
    
    public function show(ServiceCategory $serviceCategory)
    {
        $serviceCategory->loadCount('serviceTypes');
        
        return response()->json([
            'id' => $serviceCategory->id,
            'category_name' => $serviceCategory->category_name,
            'description' => $serviceCategory->description ?? 'No description provided',
            'service_types_count' => $serviceCategory->service_types_count,
            'created_at' => $serviceCategory->created_at,
        ]);
    }

    public function showJson(ServiceCategory $serviceCategory)
    {
        return $this->show($serviceCategory);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:service_categories,category_name',
            'description' => 'nullable|string',
        ]);

        $lastCategory = ServiceCategory::orderBy('id', 'desc')->first();
        if ($lastCategory) {
            $lastNumber = intval(substr($lastCategory->id, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'SC' . $newNumber;

        $category = ServiceCategory::create(array_merge($validated, ['id' => $newId]));
        return response()->json(['success' => true, 'category' => $category]);
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:100|unique:service_categories,category_name,' . $serviceCategory->id . ',id',
            'description' => 'nullable|string',
        ]);

        $serviceCategory->update($validated);
        return response()->json(['success' => true, 'category' => $serviceCategory]);
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        if ($serviceCategory->serviceTypes()->count() > 0) {
            return response()->json([
                'success' => false, 
                'message' => 'Cannot delete category with existing services. Please delete or move the services first.'
            ], 400);
        }
        
        $serviceCategory->delete();
        return response()->json(['success' => true]);
    }
}