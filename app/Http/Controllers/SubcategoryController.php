<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->orderBy('id', 'desc')->get();
        return view('admin.subcategory.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.subcategory.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
        ]);

        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('subcategory.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $categories = Category::all();
        return view('admin.subcategory.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
        ]);

        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('subcategory.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy($id)
    {
        Subcategory::destroy($id);
        return redirect()->route('subcategory.index')->with('success', 'Subcategory deleted successfully.');
    }
}
