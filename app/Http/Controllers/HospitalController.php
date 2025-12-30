<?php
namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function index()
    {
        $hospitals = Hospital::with(['city','district', 'state', 'country'])->orderBy('id', 'desc')
                     ->get();
        return view('admin.hospital.index', compact('hospitals'));
    }

    public function create()
    {
        return view('admin.hospital.create',[
            'cities' => City::all(),
         'district' => District::all(),
        'states' => State::all(),
        'countries' => Country::all()
            ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'address_link'=>'nullable|url',
        ]);
         
        $country = Country::find($request->country_id);
        $state = State::find($request->state_id);
        $district = District::find($request->district_id);
        $city = City::find($request->city_id);
    
        $validated['country_name'] = $country->name ?? null;
        $validated['state_name'] = $state->name ?? null;
        $validated['district_name'] = $district->name ?? null;
        $validated['city_name'] = $city->name ?? null;
        
        // Handle image upload (same as before)
        $imageName = 'hospital.jpg'; // Default image

        // Check if the request contains an image
        if ($request->hasFile('image')) {
            $hospitalName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $request->name);
            // Generate a unique name for the image
            $uniqueId = str_pad(Hospital::max('id') + 1, 5, '0', STR_PAD_LEFT); // Get the next ID padded to 5 digits
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = "hospital-{$hospitalName}.{$uniqueId}.{$extension}";
            
            // Move the image to the public/uploads/pages folder
            $request->file('image')->move(public_path('admin/uploads/hospitals'), $imageName);
        }
        
        // Add the image (either default or uploaded) to the validated data
        $validated['image'] = $imageName;

        Hospital::create($validated);

        return redirect()->route('hospital.index')->with('success', 'Hospital created successfully!');
    }

    public function edit(Hospital $hospital)
    {
        $countries = Country::all();
        $states  = State::all();
        $districts = District::all();
        $cities = City::all();
        return view('admin.hospital.edit', compact('hospital','countries','states','districts','cities'));
    }

    public function update(Request $request, Hospital $hospital)
    {
        $validated = $request->validate([
            'name' => 'required',
            'image' => 'nullable|image',
            'country_id' => 'required',
            'state_id' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'address_link'=>'nullable|url',
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
            $validated['country_name'] = $country->name;
            $validated['state_name'] = $state->name;
            
             $validated['district_name'] = $district->name;
             $validated['city_name'] = $city->name;

         if ($request->hasFile('image')) {
            $hospitalName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $request->name);
            $file = $request->file('image');
    
            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file upload'], 400);
            }
    
            $uniqueId = str_pad($id, 5, '0', STR_PAD_LEFT);
            $extension = $file->getClientOriginalExtension();
            $imageName = "hospital-{$hospitalName}.{$uniqueId}.{$extension}";
    
            $destinationPath = public_path('admin/uploads/hospitals');
            $file->move($destinationPath, $imageName);
    
            // Set the image in the validated data
            $validated['image'] = $imageName;
        }
        $hospital->update($validated);

        return redirect()->route('hospital.index')->with('success', 'Hospital updated successfully!');
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete();
        return redirect()->route('hospital.index')->with('success', 'Hospital deleted successfully!');
    }
}
