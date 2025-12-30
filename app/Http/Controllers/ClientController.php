<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\ClinicImage;
use App\Models\UserType;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
  // Display a listing of the resource
  public function index()
  {
      
      // Get the authenticated user's ID
    $userId = Auth::id();
      
      if (auth()->user()->role == 'admin') {
      $clinics = Client::with(['city','district', 'state', 'country'])->orderBy('id', 'desc')
                 ->get();
                 
     
      return view('admin.clinics.index', compact('clinics'));
      
      }
      
       if (auth()->user()->role == 'clinic') {
           
      
      $clinics = Client::with(['city','district', 'state', 'country'])->where('auth_id',$userId)->orderBy('id', 'desc')
                 ->get();
      return view('admin.clinics.index', compact('clinics'));
      
      }
  }

  // Show the form for creating a new resource
  public function create()
  {
    return view('admin.clinics.create', [
        'cities' => City::all(),
         'district' => District::all(),
        'states' => State::all(),
        'countries' => Country::all(),
        'category' => Category::where('type', 'clinic')->get()
    ]);
  }

  // Store a newly created resource in storage
  public function store(Request $request)
  {
      
   // dd($request->file('images'));
      // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
           // 'email' => 'nullable|email', // Email is not mandatory, but must be unique if provided
            'phone_number' => 'nullable',
            'phone_number2' => 'nullable',
            'address' => 'nullable|string',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            'district_id' => 'required|integer|exists:districts,id',
            'city_id' => 'required|integer|exists:cities,id',
            'pincode' => 'nullable', // Optional field
            'other_information' => 'nullable|string', // Optional field
            'website' => 'nullable|url', // Optional field
            'images' => 'nullable|array', // Make the field optional and ensure it's an array
           'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate individual images if provided
           'status' => 'nullable|string', // Optional field
          'category_id.*' => 'exists:category,id', // Validate each category ID
          'latitude' => 'nullable|string', // Optional field
          'logitude' => 'nullable|string', // Optional field
        ]);
        
        // Validate user data separately
            $userData = $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
                //'password' => 'required|string|min:8|confirmed',
            ]);
           
    
        // Retrieve related names for location data
        $country = Country::find($request->country_id);
        $state = State::find($request->state_id);
        $district = District::find($request->district_id);
        $city = City::find($request->city_id);
    
        $validatedData['country_name'] = $country->name ?? null;
        $validatedData['state_name'] = $state->name ?? null;
        $validatedData['district_name'] = $district->name ?? null;
        $validatedData['city_name'] = $city->name ?? null;
        $validatedData['pincode'] = $request->pincode ?? null;
        $validatedData['status'] = 'Approved';
        
         // Process category IDs into a comma-separated string
        $validatedData['category_id'] = implode(',', $request->category_id);
        $validatedData['latitude'] = $request->latitude ?? null;
        $validatedData['logitude'] = $request->logitude ?? null;
        $validatedData['created_by'] = auth()->id()?? null;
        $validatedData['tags'] = $request->input('tags', null);
         $imagePaths = [];
    
      // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
             $clinicName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $request->name);
            // Get the original extension of each image file
            $imageName = $image->getClientOriginalName(); // Use getClientOriginalName() to get the full file name (including extension)
            
            // Generate a unique name for the image to avoid overwriting
            $imageName = 'clinic-'.$clinicName. '-' .time().'-'.$imageName;
            
            // Move the image to the public/uploads/clinic-image folder
            $image->move(public_path('admin/uploads/clinic-image'), $imageName);
            
            // Get the file path after moving
            $path = 'admin/uploads/clinic-image/' . $imageName; // The path where the image is stored
            
            // Add the image path to the array
            $imagePaths[] = $path;
        }
        // Store the image paths as a JSON array
        $validatedData['images'] = json_encode($imagePaths);
    }
    
   // Insert into user_type table
        $userType = UserType::create([
            'type' => 'Clinic', // Set type as 'Clinic'
        ]);
        
         // Create the user
    $user = User::create([
        'name' => $request->name, // Assuming clinic name and user name are the same
        'email' => $userData['email'],
        // 'password' => Hash::make($userData['password']),
        'password' => Hash::make('123456789'),
        'role' => 'clinic', // Assign a default role
    ]);
  //echo'<pre>';print_r($user);die;
    // Trigger the Registered event (if needed)
    event(new Registered($user));

        // Insert into clinics table
        $clinic = Client::create(array_merge($validatedData, [
            'user_id' => $userType->id,
            'auth_id' => $user->id,
        ]));
    
    // If you are saving images in a separate `clinic_images` table
    if (!empty($imagePaths)) {
        foreach ($imagePaths as $path) {
            ClinicImage::create([
                'clinic_id' => $clinic->id,
                'path' => $path,
            ]);
        }
    }
    
    // Redirect with success message
    return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    
  }

  // Display the specified resource
  public function show($id)
  {
      $client = Client::with(['city','district', 'state', 'country'])->findOrFail($id);
          // Get the associated clinic images
      $existingImages = $client->clinicImages; // This will retrieve all the related images
      
       $user = User::findOrFail($client->auth_id);
    
      return view('admin.clinics.show', compact('client', 'existingImages','user'));
  }

  // Show the form for editing the specified resource
  public function edit($id)
  {
      
       $countries = Country::all();
        $states  = State::all();
        $districts = District::all();
        $cities = City::all();
        $client = Client::findOrFail($id);
        $category = Category::where('type', 'clinic')->get();
      
      $user = User::findOrFail($client->auth_id);
      
       // Get the associated clinic images
    $existingImages = $client->clinicImages; // This will retrieve all the related images
      return view('admin.clinics.edit', compact('client','countries','states','districts','cities', 'existingImages','user','category'));
  }

  // Update the specified resource in storage
  public function update(Request $request, $id)
  {
      
    //   dd($request->file('images')); // If you're uploading multiple files with the name 'images[]'
         $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email', // Email is not mandatory, but must be unique if provided
        'phone_number' => 'required',
        'phone_number2' => 'nullable',
        'address' => 'nullable|string',
        'country_id' => 'required|integer|exists:countries,id',
        'state_id' => 'required|integer|exists:states,id',
        'district_id' => 'required|integer|exists:districts,id',
        'city_id' => 'required|integer|exists:cities,id',
        'other_information' => 'nullable|string',  // Optional field
        'website' => 'nullable|url',  // Optional field
       'images' => 'nullable|array', // Make the field optional and ensure it's an array
      'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate individual images if provided
      'category_id.*' => 'exists:category,id', // Validate each category ID
          'latitude' => 'nullable|string', // Optional field
           'logitude' => 'nullable|string', // Optional field
      
    ]);
    
     // Validate user data separately
            $userData = $request->validate([
                'email' => 'required|string|email|max:255',
                //'password' => 'required|string|min:8|confirmed',
            ]);
            
            
    
           $country_id = $request->country_id;
            $state_id = $request->state_id;
            $district_id = $request->district_id;
            $city_id = $request->city_id;
        
            // Retrieve the country_id and state_id based on the names
            $country = Country::where('id', $country_id)->first();
            $state = State::where('id', $state_id)->first();
            
            $district = District::where('id', $district_id)->first();
            $city = City::where('id', $city_id)->first();
        
            if (!empty($country) || !empty($state) || !empty($district)  || !empty($city) ){
               $country_name = $country->name;
               $state_name = $state->name;
               $district_name = $district->name;
               $city_name = $city->name;
            }
        
           // Add country_name and state_name to the data array
            $validatedData['country_name'] = $country->name;
            $validatedData['state_name'] = $state->name;
            
             $validatedData['district_name'] = $district->name;
             $validatedData['city_name'] = $city->name;
             $validatedData['pincode'] = $request->pincode ?? null;
             
             // Process category IDs into a comma-separated string
        $validatedData['category_id'] = implode(',', $request->category_id);
                $validatedData['latitude'] = $request->latitude ?? null;
                $validatedData['logitude'] = $request->logitude ?? null;
                $validatedData['updated_by'] = auth()->id()?? null;
                $validatedData['tags'] = $request->input('tags', null);
             
             $client = Client::findOrFail($id); 
             
             // Assuming you have the user ID or another way to retrieve the user
            
           $user = User::findOrFail($client->auth_id); // Fetch user using Eloquent model
            $user->update([
                'email' => $userData['email'],
            ]);
            
            // Trigger event after update
            event(new Registered($user));
            // Handle image update
     
          $imagePaths = [];
    
      // Handle image uploads
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
             $clinicName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $request->name);
            // Get the original extension of each image file
            $imageName = $image->getClientOriginalName(); // Use getClientOriginalName() to get the full file name (including extension)
            
            // Generate a unique name for the image to avoid overwriting
            $imageName = 'clinic-'.$clinicName. '-' .time().'-'.$imageName;
            
            // Move the image to the public/uploads/clinic-image folder
            $image->move(public_path('admin/uploads/clinic-image'), $imageName);
            
            // Get the file path after moving
            $path = 'admin/uploads/clinic-image/' . $imageName; // The path where the image is stored
            
            // Add the image path to the array
            $imagePaths[] = $path;
        }
        // Store the image paths as a JSON array
        $validatedData['images'] = json_encode($imagePaths);
    }
        
        // Update the rest of the clinic data
        $client->update($validatedData);
        
        // If you are saving images in a separate `clinic_images` table
    if (!empty($imagePaths)) {
        foreach ($imagePaths as $path) {
            ClinicImage::create([
                'clinic_id' => $id,
                'path' => $path,
            ]);
        }
    }
        
        // Redirect with success message
        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');

  }

  // Remove the specified resource from storage
  public function destroy($id)
  {
      $client = Client::findOrFail($id);
      $client->delete();
      return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
  }
  


  public function removeImage($imageId){
      
     // Debugging to confirm the received image ID
    // echo $imageId; die;

    // Validate if the image ID exists in the database
    $image = ClinicImage::find($imageId);

    // Check if the image exists
    if ($image) {
        // Check if the image file exists on the server
        if (file_exists(public_path($image->path))) {
            unlink(public_path($image->path)); // Delete the image file from the server
        }

        // Delete the image record from the database
        $image->delete();

        // Return a success response
        return response()->json(['success' => true]);
    }

    // Return an error if the image was not found
    return response()->json(['success' => false, 'message' => 'Image not found'], 404);
}

public function updateStatus(Request $request)
{
    try {
    
     \Log::info($request->all()); // Debug request payload
        $clinic = Client::findOrFail($request->clinic_id);
        $clinic->status = $request->status;
        $clinic->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating status. Please try again.'
        ], 500);
    }
}


}
