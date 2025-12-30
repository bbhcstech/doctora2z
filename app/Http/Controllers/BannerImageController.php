<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannerImage;


class BannerImageController extends Controller
{
     public function index()
    {
        $banner = BannerImage::all();
        
       //echo'<pre>'; print_r($banner );die;
        return view('admin.banners.index', compact('banner'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }
    
      public function edit($id)
    {
        $banner = BannerImage::findOrFail($id);
        return view('admin.banners.edit', compact('banner'));
    }

  public function store(Request $request)
    {
        
       // print_r($request->file('image'));die;
       // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Single image validation
        ]);
    
        
    
        // Handle image upload if a file is uploaded
        if ($request->hasFile('image')) {
            // Get the image file
            $image = $request->file('image');
            
            // Generate a unique name for the image
            $imageName = time() . '-' . $image->getClientOriginalName();
            
            // Move the image to the desired folder (e.g., public/uploads/banners)
            $image->move(public_path('admin/uploads/banners'), $imageName);
          
        }
        
         if ($request->hasFile('mobile_image')) {
            // Get the image file
            $mobile_image = $request->file('mobile_image');
            
            // Generate a unique name for the image
            $mobile_imageName = time() . '-' . $mobile_image->getClientOriginalName();
            
            // Move the image to the desired folder (e.g., public/uploads/banners)
            $mobile_image->move(public_path('admin/uploads/banners'), $mobile_imageName);
          
        }
        
        // Store the banner details (name) in the database
        $banner = BannerImage::create([
            'name' => $request->name,
            'image' =>  $imageName,
            'mobile_image' => $mobile_imageName
        ]);

        return redirect()->route('banner.index')->with('success', 'Banner created successfully.');
    }

    // Edit function for updating banner images
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Find the existing banner
    $banner = BannerImage::findOrFail($id);

    // Handle image upload if a new image is provided
    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if (!empty($banner->image) && file_exists(public_path('admin/uploads/banners/' . $banner->image))) {
            unlink(public_path('admin/uploads/banners/' . $banner->image)); // Alternatively, use Storage::delete
        }

        // Generate a unique name for the new image using a hash
        $image = $request->file('image');
        $imageName = time() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

        // Move the new image to the desired directory
        $image->move(public_path('admin/uploads/banners'), $imageName);
    } else {
        $imageName = $banner->image;
    }


    if ($request->hasFile('mobile_image')) {
        // Delete the old image if it exists
        if (!empty($banner->mobile_image) && file_exists(public_path('admin/uploads/banners/' . $banner->mobile_image))) {
            unlink(public_path('admin/uploads/banners/' . $banner->mobile_image)); // Alternatively, use Storage::delete
        }

        // Generate a unique name for the new image using a hash
        $mobile_image = $request->file('mobile_image');
        $mobile_imageName = time() . '-' . uniqid() . '.' . $mobile_image->getClientOriginalExtension();

        // Move the new image to the desired directory
        $mobile_image->move(public_path('admin/uploads/banners'), $mobile_imageName);
    } else {
        $mobile_imageName = $banner->mobile_image;
    }

    // Update the banner details
    $banner->update([
        'name' => $request->name,
        'image' => $imageName,
        'mobile_image' => $mobile_imageName
    ]);

    return redirect()->route('banner.index')->with('success', 'Banner updated successfully.');
}


       public function destroy($id)
    {
        BannerImage::destroy($id);
        return redirect()->route('banner.index')->with('success', 'Banner deleted successfully.');
    }
}
