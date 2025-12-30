<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\AboutUs;

class AboutController extends Controller
{
    public function edit()
    {
        $aboutUs = AboutUs::first();
        if (!$aboutUs) {
        $aboutUs = AboutUs::create([
            'title' => 'Default Title',
            'description' => 'Default Description',
            'banner_image' => null,
            'page_image' => null,
            'button_text' => null,
            'button_url' => null,
        ]);
    }
        return view('admin.about-us.edit', compact('aboutUs'));
    }

    public function update(Request $request)
    {
        

        $aboutUs = AboutUs::first();

        $aboutUs->title = $request->title;
        $aboutUs->description = $request->description;
        $aboutUs->button_text = $request->button_text;
        $aboutUs->button_url = $request->button_url;
        
         if ($request->hasFile('banner_image')) {
            if (!empty($aboutUs->banner_image) && file_exists(public_path($aboutUs->banner_image))) {
                unlink(public_path($aboutUs->banner_image)); 
            }
            $bannerImageName = time() . '_Aboutbanner.' . $request->file('banner_image')->getClientOriginalName();
            $request->file('banner_image')->move(public_path('admin/uploads/about'), $bannerImageName);
            }else{
             $bannerImageName = $aboutUs->banner_image;
            }
            
            
        if ($request->hasFile('page_image')) {
            if (!empty($aboutUs->page_image) && file_exists(public_path($aboutUs->page_image))) {
                unlink(public_path($aboutUs->page_image)); 
            }
            $pageImageName = time() . '_Aboutpage.' . $request->file('page_image')->getClientOriginalName();
            $request->file('page_image')->move(public_path('admin/uploads/about'), $pageImageName);
            }else{
             $pageImageName = $aboutUs->page_image;
            }
            
             $aboutUs->update([
            'title' => $request->title,
            'description' => $request->description,
            'button_text' => $request->button_text,
            'button_url' => $request->button_url,
            'banner_image' =>  $bannerImageName,
            'page_image' => $pageImageName
        ]);

        $aboutUs->save();

        return redirect()->route('about-us.edit')->with('success', 'About Us page updated successfully.');
    }
}
