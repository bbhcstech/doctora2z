<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Category;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index()
    {
        $advertisements = Advertisement::with('category')->get();
        return view('admin.advertisement.index', compact('advertisements'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.advertisement.create', [
            'categories' => $categories,
            'cities' => City::all(),
            'districts' => District::all(),
            'states' => State::all(),
            'countries' => Country::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|integer|exists:category,id',
            'title' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            // 'district_id' => 'required|integer|exists:districts,id',
            // 'city_id' => 'required|integer|exists:cities,id',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean', // Validate status as a boolean
        ]);
    
        // Fetch related names for the advertisement
        $country = Country::find($request->country_id);
        $state = State::find($request->state_id);
        $district = District::find($request->district_id);
        $city = City::find($request->city_id);
    
        // Prepare validated data
        $validatedData = [
            'category_id' => $request->category_id,
            'category_name' => Category::findOrFail($request->category_id)->name,
            'title' => $request->title,
            'country_id'=>$request->country_id,
            'country_name' => $country->name ?? null,
            'state_id'=>$request->state_id,
            'state_name' => $state->name ?? null,
            'district_id'=>$request->district_id?? null,
            'district_name' => $district->name ?? null,
            'city_id'=> $request->city_id,
            'city_name' => $city->name ?? null,
            'status' => $request->status, // Include status in the data
        ];
    
        // Handle image upload
        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('admin/uploads/advertisement'), $imageName);
        $validatedData['image'] = $imageName;
    
        // Create the advertisement
        Advertisement::create($validatedData);
    
        return redirect()->route('advertisement.index')->with('success', 'Advertisement created successfully.');
    }


   public function edit($id)
    {
        // Find the advertisement or fail if it doesn't exist
        $advertisement = Advertisement::findOrFail($id);
    
        // Fetch all related data
        $categories = Category::all();
        $countries = Country::all();
        $states = State::where('country_id', $advertisement->country_id)->get();
        $districts = District::where('state_id', $advertisement->state_id)->get();
        $cities = City::where('district_id', $advertisement->district_id)->get();
    
        // Pass data to the edit view
        return view('admin.advertisement.edit', compact(
            'advertisement', 
            'categories', 
            'countries', 
            'states', 
            'districts'
            // 'cities'
        ));
    }

    public function update(Request $request, $id)
    {
        $advertisement = Advertisement::findOrFail($id);

        $request->validate([
            'category_id' => 'required|integer|exists:category,id',
            'title' => 'required|string|max:255',
            'country_id' => 'required|integer|exists:countries,id',
            'state_id' => 'required|integer|exists:states,id',
            // 'district_id' => 'required|integer|exists:districts,id',
            // 'city_id' => 'required|integer|exists:cities,id',
             'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',  // Make image optional
            'status' => 'required|boolean', // Validate status as a boolean
        ]);

        $category = Category::findOrFail($request->category_id);
        $country = Country::find($request->country_id);
        $state = State::find($request->state_id);
        $district = District::find($request->district_id);
        $city = City::find($request->city_id);
    
       
        
        
        if ($request->hasFile('image')) {
            
            // Delete the old image if it exists
            if (!empty($advertisement->image) && file_exists(public_path($advertisement->image))) {
                unlink(public_path($advertisement->image)); // Alternatively, use Storage::delete
            }
    
            // Generate a unique name for the new image
            $imageName = time() . '-' . $request->file('image')->getClientOriginalName();
    
            // Move the new image to the desired directory
            $request->file('image')->move(public_path('admin/uploads/advertisement'), $imageName);
    
        }else{
            
         $imageName = $advertisement->image;
        }
        
        
       
       
        $advertisement->update([
            'category_id' => $request->category_id,
            'category_name' => Category::findOrFail($request->category_id)->name,
            'title' => $request->title,
            'country_id'=>$request->country_id,
            'country_name' => $country->name ?? null,
            'state_id'=>$request->state_id,
            'state_name' => $state->name ?? null,
            'district_id'=>$request->district_id,
            'district_name' => $district->name ?? null,
            // 'city_id'=> $request->city_id,
            // 'city_name' => $city->name ?? null,
            'status' => $request->status,
            'image' => $imageName,
        ]);

        return redirect()->route('advertisement.index')->with('success', 'Advertisement updated successfully.');
    }

    public function destroy($id)
    {
        $advertisement = Advertisement::findOrFail($id);
       // unlink(public_path('admin/uploads/advertisement' . $advertisement->image));
        $advertisement->delete();

        return redirect()->route('advertisement.index')->with('success', 'Advertisement deleted successfully.');
    }
}
