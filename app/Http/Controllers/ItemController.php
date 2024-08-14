<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        return response()->json($items);
    }

    public function show($id)
    {
        $item = Item::with('category')->findOrFail($id);
        return response()->json($item);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $item = Item::create(array_merge($validated, ['image' => $imagePath]));

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'image' => 'nullable|mimes:png,jpg,jpeg',
            'category_id' => 'sometimes|required|exists:categories,id',
        ]);

        $item = Item::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            // Store new image
            $validated['image'] = $request->file('image')->store('images', 'public');
        }

        $item->update($validated);

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // Delete image if it exists
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return response()->json(null, 204);
    }
}
