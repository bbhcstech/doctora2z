<?php
namespace App\Http\Controllers;

use App\Models\Page;
// use App\Models\PageSection;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        // Fetch all pages
        $pages = Page::all();
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        // Show create page form
        return view('admin.pages.create');
    }

   public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'banner_image' => 'required|image',
        'desc' => 'required|string',
    ]);

    try {
        $imageName = null;

        // Handle image upload if a file is uploaded
        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $imageName = time() . '-' . $image->getClientOriginalName();

            // Move the image to the desired folder (e.g., public/admin/uploads/pages)
            $image->move(public_path('admin/uploads/pages'), $imageName);
        }

        $page = Page::create([
            'title' => $validatedData['title'],
            'slug' => $validatedData['slug'],
            'banner_image' => $imageName,
            'desc' => $validatedData['desc'],
        ]);

        return redirect()->route('pages.index')->with('success', 'Patients Say created successfully.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}


    public function edit($id)
    {
        // Get the page by ID and its associated sections
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

   public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
        'banner_image' => 'nullable|image',
        'desc' => 'required|string',
    ]);

    $page = Page::findOrFail($id);

    // Handle banner image upload if a new image is provided
    if ($request->hasFile('banner_image')) {
        // Delete the old image if it exists
        if (!empty($page->banner_image) && file_exists(public_path('admin/uploads/pages/' . $page->banner_image))) {
            unlink(public_path('admin/uploads/pages/' . $page->banner_image));
        }

        // Generate a unique name for the new image
        $imageName = time() . '-' . $request->file('banner_image')->getClientOriginalName();

        // Move the new image to the desired directory
        $request->file('banner_image')->move(public_path('admin/uploads/pages'), $imageName);
    } else {
        $imageName = $page->banner_image; // Retain the old image name if no new image is uploaded
    }

    // Update page data
    $page->update([
        'title' => $validatedData['title'],
        'slug' => $validatedData['slug'],
        'banner_image' => $imageName,
        'desc' => $validatedData['desc'],
    ]);

    return redirect()->route('pages.index')->with('success', 'Page updated successfully.');
}


    public function destroy($id)
    {
        // Find the page and delete it
        $page = Page::findOrFail($id);
        
        // Delete related sections
        // $page->sections()->delete();

        // Delete the page
        $page->delete();

        return redirect()->route('pages.index')->with('success', 'Page deleted successfully.');
    }
}
