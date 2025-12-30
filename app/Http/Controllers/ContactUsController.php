<?php
namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;


class ContactUsController extends Controller
{
    public function edit()
    {
        $contactUs = ContactUs::first(); // Retrieve the first record
        if (!$contactUs) {
        $contactUs = ContactUs::create([
            'title' => null,
            'address' => null,
            'banner_image' => null,
            'mail' => null,
            'phone' => null,
            'map_url' => null,
          ]);
        }
        return view('admin.contact-us.edit', compact('contactUs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'address' => 'required|string',
            'mail' => 'required|email',
            'phone' => 'required|string|max:15',
            'map_url' => 'required|url',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $contactUs = ContactUs::first(); // Retrieve the first record

        // Update fields
        $contactUs->title = $request->title;
        $contactUs->address = $request->address;
        $contactUs->mail = $request->mail;
        $contactUs->phone = $request->phone;
        $contactUs->map_url = $request->map_url;

        // Handle Banner Image
        if ($request->hasFile('banner_image')) {
            if (!empty($contactUs->banner_image) && file_exists(public_path($contactUs->banner_image))) {
                unlink(public_path($contactUs->banner_image)); 
            }
            $bannerImageName = time() . '_Contactbanner.' . $request->file('banner_image')->getClientOriginalName();
            $request->file('banner_image')->move(public_path('admin/uploads/contact'), $bannerImageName);
            }else{
             $bannerImageName = $contactUs->banner_image;
            }
            
           $contactUs->update([
            'title' => $request->title,
            'address' => $request->address,
            'mail' => $request->mail,
            'phone' => $request->phone,
            'banner_image' =>  $bannerImageName,
            'map_url' => $request->map_url
        ]);  

        $contactUs->save();

        return redirect()->route('contact-us.edit')->with('success', 'Contact Us updated successfully.');
    }
    
    
}
