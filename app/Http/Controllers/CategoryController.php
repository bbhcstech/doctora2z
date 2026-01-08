<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        
        
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
       // return $request;
       // Combine validations into one
        $validatedData = $request->validate([
            'type' => 'required',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        // $imageName = ''; // Default empty value for the image
         // Default image path if no image is uploaded
        $imageName = 'default-category.jpg'; // Ensure this file exists in 'public/admin/uploads/category/'
    
        // Handle image upload if present
        if ($request->hasFile('image')) {
            $uniqueId = str_pad(Category::max('id') + 1, 5, '0', STR_PAD_LEFT); // Generate a unique ID
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = "cat-{$uniqueId}.{$extension}";
    
            // Move the uploaded image to the desired directory
            $request->file('image')->move(public_path('admin/uploads/category'), $imageName);
        }
    
        // Create the category with the validated data
        Category::create([
            'type' => $validatedData['type'], // Use the validated name
            'name' => $validatedData['name'], // Use the validated name
            'image' => $imageName, // Use the generated image name (empty if no image)
        ]);
    
        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        
      // Validate the input data
    $validatedData = $request->validate([
        'type' => 'required',
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the existing category
    $category = Category::findOrFail($id);
     //dd($request->file('image'));
   

   // Handle image upload if a new image is provided
if ($request->hasFile('image')) {
    // Generate a unique name for the new image based on category ID
    $uniqueId = str_pad($category->id, 5, '0', STR_PAD_LEFT);
    $extension = $request->file('image')->getClientOriginalExtension();
    $imageName = "cat-{$uniqueId}.{$extension}";

    // Check if the folder exists and create it if it doesn't
    $folderPath = public_path('admin/uploads/category');
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0775, true); // Create folder if not exists
    }

    // Check if the file already exists and create a new image name
    $imagePath = $folderPath . '/' . $imageName;
    $counter = 1;
    while (file_exists($imagePath)) {
        // If the file exists, generate a new name by appending a counter
        $imageName = "cat-{$uniqueId}-{$counter}.{$extension}";
        $imagePath = $folderPath . '/' . $imageName;
        $counter++; // Increment counter for uniqueness
    }

    // Move the new image to the desired directory
    $request->file('image')->move($folderPath, $imageName);

    // Log the image name for debugging purposes
    Log::info("New image uploaded: {$imageName}");
} else {
    // If no new image, retain the old image name
    $imageName = $category->image;
}

// Update the category with the new image name (or retain the old image if no new image)
$category->update([
     'type' => $validatedData['type'], // Use the validated name
    'name' => $validatedData['name'],  // Update name
    'image' => $imageName,             // Update image (new or old)
]);

// Log the category update for debugging
Log::info("Category updated with image: {$imageName}");


        return redirect()->route('category.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('category.index')->with('success', 'Category deleted successfully.');
    }
    
    public function bulkDelete(Request $request)
{
    $categoryIds = $request->category_ids;

    if (!$categoryIds) {
        return back()->with('error', 'No categories selected!');
    }

    // Delete selected categories
    Category::whereIn('id', $categoryIds)->delete();

    return back()->with('success', 'Selected categories deleted successfully!');
}

}

