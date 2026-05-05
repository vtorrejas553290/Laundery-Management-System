<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExtraItem;
use Illuminate\Http\Request;

class ExtraItemsController extends Controller
{
    public function index()
    {
        $extraItems = ExtraItem::orderBy('created_at', 'desc')->get();
        return view('extra-items', compact('extraItems'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Generate new ID (EI1, EI2, EI3, etc.)
        $lastItem = ExtraItem::orderBy('id', 'desc')->first();
        if ($lastItem) {
            $lastNumber = intval(substr($lastItem->id, 2));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $newId = 'EI' . $newNumber;

        $item = ExtraItem::create(array_merge($validated, ['id' => $newId]));
        return response()->json(['success' => true, 'item' => $item]);
    }

    public function update(Request $request, ExtraItem $extraItem)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $extraItem->update($validated);
        return response()->json(['success' => true, 'item' => $extraItem]);
    }

    public function destroy(ExtraItem $extraItem)
    {
        $extraItem->delete();
        return response()->json(['success' => true]);
    }
    public function show(ExtraItem $extraItem)
{
    return response()->json([
        'id' => $extraItem->id,
        'item_name' => $extraItem->item_name,
        'price' => $extraItem->price,
        'created_at' => $extraItem->created_at,
        'updated_at' => $extraItem->updated_at,
    ]);
}
}