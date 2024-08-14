<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Store a newly created category in storage
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
           
        ]);

        // Create a new category
        $category = Category::create($request->all());

        // Return the created category with a 201 status code
        return response()->json($category, 201);
    }

    // Display the specified category
    public function show($id)
    {
        // Find the category by ID or fail
        $category = Category::findOrFail($id);

        // Return the found category
        return response()->json($category);
    }

    // Update the specified category in storage
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            
        ]);

        // Find the category by ID or fail
        $category = Category::findOrFail($id);

        // Update the category with the request data
        $category->update($request->all());

        // Return the updated category
        return response()->json($category);
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        // Find the category by ID or fail
        $category = Category::findOrFail($id);

        // Delete the category
        $category->delete();

        // Return a 204 status code (No Content)
        return response()->json(null, 204);
    }
}
