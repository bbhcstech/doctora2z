<?php

namespace App\Http\Controllers;

use App\Models\DoctorList;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\Client;
use App\Models\Category;
use App\Models\UserType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Hash;

class DoctorListController extends Controller
{
   
    public function index()
    {
        
         // Get the authenticated user's ID
         $userId = Auth::id();
         
         if (auth()->user()->role == 'admin') {
        // Retrieve all doctors from the database
        $doctors = DoctorList::orderBy('id', 'desc')->get();

        // Return the view with the doctors data
        return view('admin.doctor_lists.index', compact('doctors'));
        
         }
         if (auth()->user()->role == 'clinic') {
             
              $clinics = Client::with(['city','district', 'state', 'country'])->where('auth_id',$userId)->first();
        // Retrieve all doctors from the database
        $doctors = DoctorList::orderBy('id', 'desc')->where('clinic_ids',$clinics->id)->get();
              
        // Return the view with the doctors data
        return view('admin.doctor_lists.index', compact('doctors'));
        
         }
         
          if (auth()->user()->role == 'doctor') {
          
          
        // Retrieve all doctors from the database
        $doctors = DoctorList::orderBy('id', 'desc')->where('auth_id',$userId)->get();
        

        // Return the view with the doctors data
        return view('admin.doctor_lists.index', compact('doctors'));
        
         }
    }

     // Show the form for creating a new doctor
     public function create()
     {  
          // Get the authenticated user's ID
         $userId = Auth::id();
         if (auth()->user()->role == 'admin') {
         // Retrieve all location data from the database
        $clinics= Client::with(['country', 'state', 'district', 'city'])->orderBy('id', 'desc')->get();
        //  dd($clinics);
        $countries = Country::all();
        $state = State::all();
        $district = District::all();
        $city = City::all();
        $category = Category::all();
         }
        if (auth()->user()->role == 'clinic') {
            $clinics= Client::with(['country', 'state', 'district', 'city'])->where('auth_id',$userId)->orderBy('id', 'desc')->get();
            //  dd($clinics);
            $countries = Country::all();
            $state = State::all();
            $district = District::all();
            $city = City::all();
            $category = Category::all(); 
        }
         if (auth()->user()->role == 'doctor') {
             $clinics= Client::with(['country', 'state', 'district', 'city'])->where('auth_id',$userId)->orderBy('id', 'desc')->get();
            //  dd($clinics);
            $countries = Country::all();
            $state = State::all();
            $district = District::all();
            $city = City::all();
            $category = Category::all(); 
         }
     
        
         return view('admin.doctor_lists.create', compact('countries','state','district','city','clinics','category'));  // View for the create form
     }
 
     // Store a new doctor in the database
  // Store a new doctor in the database
    public function store(Request $request)
        {
             //return $request;
            // Default validation rules
            // Default validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'phone_number' => 'required',
            'degree' => 'nullable|string|max:255',
            'active' => 'required|boolean',
            'profile_text' => 'nullable|string',
            'tags' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
           
            'date_wise_checkbox' => 'nullable|boolean',
            'day_wise_checkbox' => 'nullable|boolean',
            'time_slot' => 'nullable|array',
            'time_slot.*.start' => [
                'nullable',
                'required_if:time_checkbox,1', // Required if the time_checkbox is checked
                'date_format:H:i'
            ],
            'time_slot.*.end' => [
                'nullable',
                'required_if:time_checkbox,1', // Required if the time_checkbox is checked
                'date_format:H:i'
            ],

            
            'clinic_ids' => 'nullable|integer|exists:clinics,id', // Single clinic ID, not an array
            'category_id' => 'required|array', // Ensure it's an array for multiple categories
            'category_id.*' => 'exists:category,id', // Validate each category ID
            'sub_category' => 'nullable',
            'fees'=> 'nullable',
            'whatsapp'=> 'nullable',
            'facebook'=> 'nullable',
            'instagram'=> 'nullable',
            'website'=> 'nullable',
            'latitude'=> 'nullable',
            'logitude'=> 'nullable',
            'language'=> 'nullable',
            
             'mode_of_payment'=> 'nullable',
            'loc1'=> 'nullable',
            'loc2'=> 'nullable',
            'loc3'=> 'nullable',
            
            'loc4'=> 'nullable',
            'loc5'=> 'nullable',
            'membership'=> 'nullable',
        ];
        
        // Conditional validation based on checkboxes
        if ($request->has('date_wise_checkbox') && $request->input('date_wise_checkbox') == 1) {
            // Date Wise is checked, so date_picker is required and month/day should be nullable
            $rules['date_picker'] = 'required|date';
            $rules['month'] = 'nullable';
            $rules['day'] = 'nullable';
        } elseif ($request->has('day_wise_checkbox') && $request->input('day_wise_checkbox') == 1) {
            // Day Wise is checked, so month and day are required and date_picker should be nullable
            $rules['month'] = 'required|array';
            $rules['day'] = 'required|array';
            $rules['date_picker'] = 'nullable';
        } else {
            // You can add custom logic here if needed for when neither checkbox is checked
        }
        
       // Validate the data using the rules
        $validatedData = $request->validate($rules);
    
    // if ($request->has('time_slot')) {
    //     $formattedTimeSlots = [];
    //     foreach ($request->input('time_slot') as $index => $timeSlot) {
    //         $formattedTimeSlots[] = $timeSlot['start'] . " - " . $timeSlot['end'];
    //     }
    //     // Join all the formatted times as a single string
    //     $validatedData['time_slot'] = implode(', ', $formattedTimeSlots);
    // }
    
    if ($request->has('time_slot')) {
    $formattedTimeSlots = [];
    foreach ($request->input('time_slot') as $timeSlot) {
        $start = $timeSlot['start'] ?? '';
        $end = $timeSlot['end'] ?? '';
        if ($start && $end) {
            $formattedTimeSlots[] = "$start - $end";
        }
    }
    $validatedData['time_slot'] = implode(', ', $formattedTimeSlots);
} else {
    $validatedData['time_slot'] = null;
}

        
        
        // Ensure all fields are captured, including checkboxes
        $validatedData['month'] = json_encode($request->input('month')); // Store as JSON
        $validatedData['day'] = json_encode($request->input('day'));     // Store as JSON
        $validatedData['date_picker'] = $request->input('date_picker');
        // $validatedData['email'] = $request->input('email');
        $validatedData['visiting_time'] = $request->input('visiting_time');
        $validatedData['personal_phone_number'] = $request->input('personal_phone_number');
       
        $validatedData['date_wise_checkbox'] = $request->has('date_wise_checkbox') ? 1 : 0;
        $validatedData['day_wise_checkbox'] = $request->has('day_wise_checkbox') ? 1 : 0;
        $validatedData['time_checkbox'] = $request->has('time_checkbox') ? 1 : 0;
        
       
        $validatedData['created_by'] = auth()->id()?? null;
        
        
        
        // new field add  optinal
        
         $validatedData['fees'] = $request->input('fees');
         $validatedData['whatsapp'] = $request->input('whatsapp');
         $validatedData['facebook'] = $request->input('facebook');
         $validatedData['instagram'] = $request->input('instagram');
         
         $validatedData['website'] = $request->input('website');
         $validatedData['latitude'] = $request->input('latitude');
         $validatedData['logitude'] = $request->input('logitude');
         $validatedData['language'] = $request->input('language');
         
         $validatedData['mode_of_payment'] = $request->input('mode_of_payment');
         $validatedData['loc1'] = $request->input('loc1');
         $validatedData['loc2'] = $request->input('loc2');
         $validatedData['loc3'] = $request->input('loc3');
         
          $validatedData['loc4'] = $request->input('loc4');
         $validatedData['loc5'] = $request->input('loc5');
         $validatedData['membership'] = $request->input('membership');
         
        // $validatedData['language'] = is_array($request->language) ? implode(',', $request->language) : $request->language;
        // $validatedData['mode_of_payment'] = is_array($request->mode_of_payment) ? implode(',', $request->mode_of_payment) : $request->mode_of_payment;
        // $validatedData['loc1'] = is_array($request->loc1) ? implode(',', $request->loc1) : $request->loc1;
        // $validatedData['loc2'] = is_array($request->loc2) ? implode(',', $request->loc2) : $request->loc2;
        // $validatedData['loc3'] = is_array($request->loc3) ? implode(',', $request->loc3) : $request->loc3;
        // $validatedData['loc4'] = is_array($request->loc4) ? implode(',', $request->loc4) : $request->loc4;
        // $validatedData['loc5'] = is_array($request->loc5) ? implode(',', $request->loc5) : $request->loc5;
        // $validatedData['membership'] = is_array($request->membership) ? implode(',', $request->membership) : $request->membership;

         
        
        
         
         
        // Handle image upload (same as before)
        $imageName = 'demo_doctor_image.jpeg'; // Default image

        // Check if the request contains an image
        if ($request->hasFile('image')) {
            $doctorName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $request->name);
            // Generate a unique name for the image
            $uniqueId = str_pad(DoctorList::max('id') + 1, 5, '0', STR_PAD_LEFT); // Get the next ID padded to 5 digits
            $extension = $request->file('image')->getClientOriginalExtension();
            $imageName = "doctor-{$doctorName}.{$uniqueId}.{$extension}";
            
            // Move the image to the public/uploads/pages folder
            $request->file('image')->move(public_path('admin/uploads/doctor'), $imageName);
        }
        
        // Add the image (either default or uploaded) to the validated data
        $validatedData['image'] = $imageName;
        
        // Process the category IDs
        $validatedData['category_id'] = implode(',', $request->category_id);
        
        // Optional fields
        $validatedData['sub_category'] = $request->sub_category;
        $validatedData['reg_no'] = $request->reg_no;
        $validatedData['country_id'] = $request->input('country_id');
        $validatedData['country_name'] = $request->input('country_name');
        $validatedData['state_id'] = $request->input('state_id');
        $validatedData['state_name'] = $request->input('state_name');
        $validatedData['district_id'] = $request->input('district_id');
        $validatedData['district_name'] = $request->input('district_name');
        $validatedData['city_id'] = $request->input('city_id');
        $validatedData['city_name'] = $request->input('city_name');
        $validatedData['tags'] = $request->input('tags', null);
        
         // Validate user data separately
            $userData = $request->validate([
                'email' => 'nullable|string|email|max:255|unique:users',
                //'password' => 'required|string|min:8|confirmed',
            ]);
        // Create the user
            $user = User::create([
                'name' => $request->name, // Assuming clinic name and user name are the same
                'email' => $userData['email'],
                // 'password' => Hash::make($userData['password']),
                'password' => Hash::make('123456789'),
                'role' => 'doctor', // Assign a default role
            ]);
          //echo'<pre>';print_r($user);die;
            // Trigger the Registered event (if needed)
            event(new Registered($user));
            
            
        // Insert into user_type table
        $userType = UserType::create([
            'type' => 'doctor', // Set type as 'doctor'
        ]);

        // Insert into clinics table
        $doctor = DoctorList::create(array_merge($validatedData, [
            'user_id' => $userType->id,
            'auth_id' => $user->id,
        ]));
        
        
        //  return $doctor;
        // Redirect with success message
        return redirect()->route('doctors.index')->with('success', 'Doctor added successfully.');

}


      // Show the form for editing a doctor
    public function edit($id)
    {
         //dd($id);
         // Retrieve all location data from the database
         $clinics= Client::all();
        $countries = Country::all();
        $states  = State::all();
        $districts = District::all();
        $cities = City::all();
        $category = Category::all();
        $doctor = DoctorList::findOrFail($id);
        //dd($doctor);
       // $user = User::findOrFail($doctor->auth_id);
          //dd($doctor);
        // Retrieve the clinic_ids as a comma-separated string and convert it into an array
    // $clinicIdsArray = explode(',', $doctor->clinic_ids);

    
        
        return view('admin.doctor_lists.edit', compact('doctor','countries','states','districts','cities','clinics','category'));
    }

    // Update the specified doctor
    public function update(Request $request, $id)
    {
        
        $doctor = DoctorList::findOrFail($id);
       
      
        // return $request;
         $rules = [
            'name' => 'required|string|max:255',
             'phone_number' => 'nullable',
              'email' => 'nullable|string|email|max:255|unique:users,email,' . $doctor->auth_id, // allow current user email
            'degree' => 'nullable|string|max:255',
            'active' => 'required|boolean',
            'profile_text' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            
            'date_wise_checkbox' => 'nullable|boolean',
            'day_wise_checkbox' => 'nullable|boolean',
             'time_checkbox' => 'nullable|boolean',
             'time_slot' => 'nullable|array',
            'time_slot.*.start' => [
                'nullable',
                'required_if:time_checkbox,1', // Required if the time_checkbox is checked
                'date_format:H:i'
            ],
            'time_slot.*.end' => [
                'nullable',
                'required_if:time_checkbox,1', // Required if the time_checkbox is checked
                'date_format:H:i'
            ],
            // 'clinic_ids' => 'nullable|integer|exists:clinics,id', // Single clinic ID, not an array
            'category_id' => 'required|array', // Ensure it's an array for multiple categories
            'category_id.*' => 'exists:category,id', // Validate each category ID
            'sub_category' => 'nullable',
            'fees'=> 'nullable',
            'whatsapp'=> 'nullable',
            'facebook'=> 'nullable',
            'instagram'=> 'nullable',
            'website'=> 'nullable',
            'latitude'=> 'nullable',
            'logitude'=> 'nullable',
            'language'=> 'nullable',
            
             'mode_of_payment'=> 'nullable',
            'loc1'=> 'nullable',
            'loc2'=> 'nullable',
            'loc3'=> 'nullable',
            
            'loc4'=> 'nullable',
            'loc5'=> 'nullable',
            'membership'=> 'nullable'
        
        ];
        
        // Conditional validation based on checkboxes
        if ($request->has('date_wise_checkbox') && $request->input('date_wise_checkbox') == 1) {
            // Date Wise is checked, so date_picker is required and month/day should be nullable
            $rules['date_picker'] = 'required|date';
            $rules['month'] = 'nullable';
            $rules['day'] = 'nullable';
        } elseif ($request->has('day_wise_checkbox') && $request->input('day_wise_checkbox') == 1) {
            // Day Wise is checked, so month and day are required and date_picker should be nullable
            $rules['month'] = 'required|array';
            $rules['day'] = 'required|array';
            $rules['date_picker'] = 'nullable';
        } else {
            // You can add custom logic here if needed for when neither checkbox is checked
        }
        
    //     if ($request->has('time_slot')) {
    //     $formattedTimeSlots = [];
    //     foreach ($request->input('time_slot') as $index => $timeSlot) {
    //         $formattedTimeSlots[] = $timeSlot['start'] . " - " . $timeSlot['end'];
    //     }
    //     // Join all the formatted times as a single string
    //     $validatedData['time_slot'] = implode(', ', $formattedTimeSlots);
    // }
    
                if ($request->has('time_slot')) {
                $formattedTimeSlots = [];
                foreach ($request->input('time_slot') as $timeSlot) {
                    $start = $timeSlot['start'] ?? '';
                    $end = $timeSlot['end'] ?? '';
                    if ($start && $end) {
                        $formattedTimeSlots[] = "$start - $end";
                    }
                }
                $validatedData['time_slot'] = implode(', ', $formattedTimeSlots);
            } else {
                $validatedData['time_slot'] = null;
            }

        // Validate the data using the rules
        $validatedData = $request->validate($rules);
        
        // Ensure all fields are captured, including checkboxes
        $validatedData['month'] = json_encode($request->input('month')); // Store as JSON
        $validatedData['day'] = json_encode($request->input('day'));     // Store as JSON
        $validatedData['date_picker'] = $request->input('date_picker');
        // $validatedData['email'] = $request->input('email');
        $validatedData['visiting_time'] = $request->input('visiting_time');
         $validatedData['personal_phone_number'] = $request->input('personal_phone_number');
       
        $validatedData['date_wise_checkbox'] = $request->has('date_wise_checkbox') ? 1 : 0;
        $validatedData['day_wise_checkbox'] = $request->has('day_wise_checkbox') ? 1 : 0;
        $validatedData['time_checkbox'] = $request->has('time_checkbox') ? 1 : 0;
        //  $validatedData['time_slot'] = implode(', ', $formattedTimeSlots);
        // Handle image upload (same as before)
        // Process clinic_id as a single value (no array)
        // if ($request->has('clinic_ids')) {
        //     $validatedData['clinic_ids'] = $request->clinic_ids;
        // } else {
        //     $validatedData['clinic_ids'] = null; // No clinic selected
        // }
    
        // Default image path if no image is uploaded
        if ($request->hasFile('image')) {
            $doctorName = preg_replace('/[^a-zA-Z0-9_-]/', '-', $request->name);
            $file = $request->file('image');
    
            if (!$file->isValid()) {
                return response()->json(['error' => 'Invalid file upload'], 400);
            }
    
            $uniqueId = str_pad($id, 5, '0', STR_PAD_LEFT);
            $extension = $file->getClientOriginalExtension();
            $imageName = "doctor-{$doctorName}.{$uniqueId}.{$extension}";
    
            $destinationPath = public_path('admin/uploads/doctor');
            $file->move($destinationPath, $imageName);
    
            // Set the image in the validated data
            $validatedData['image'] = $imageName;
        }
    
        
        // Process category IDs into a comma-separated string
        $validatedData['category_id'] = implode(',', $request->category_id);
        
        // Other optional fields
        $validatedData['sub_category'] =  $request->sub_category;
        $validatedData['reg_no'] =  $request->reg_no;
        $validatedData['country_id'] = $request->input('country_id');
        $validatedData['country_name'] = $request->input('country_name');
        $validatedData['state_id'] = $request->input('state_id');
        $validatedData['state_name'] = $request->input('state_name');
        $validatedData['district_id'] = $request->input('district_id');
        $validatedData['district_name'] = $request->input('district_name');
        $validatedData['city_id'] = $request->input('city_id');
        $validatedData['city_name'] = $request->input('city_name');
        $validatedData['tags'] = $request->input('tags', null);
       $validatedData['updated_by'] = auth()->id()?? null;
       $validatedData['phone_number'] = $request->input('phone_number', null);
       $validatedData['email'] = $request->input('email');
       
       
       // new field add  optinal
        
         $validatedData['fees'] = $request->input('fees');
         $validatedData['whatsapp'] = $request->input('whatsapp');
         $validatedData['facebook'] = $request->input('facebook');
         $validatedData['instagram'] = $request->input('instagram');
         
         $validatedData['website'] = $request->input('website');
         $validatedData['latitude'] = $request->input('latitude');
         $validatedData['logitude'] = $request->input('logitude');
         $validatedData['language'] = $request->input('language');
         
         $validatedData['mode_of_payment'] = $request->input('mode_of_payment');
         $validatedData['loc1'] = $request->input('loc1');
         $validatedData['loc2'] = $request->input('loc2');
         $validatedData['loc3'] = $request->input('loc3');
         
          $validatedData['loc4'] = $request->input('loc4');
         $validatedData['loc5'] = $request->input('loc5');
         $validatedData['membership'] = $request->input('membership');
    
      //dd($validatedData);
        // Update the doctor's record in the database
        $doctor->update($validatedData);
        
         // Validate user data separately
         if ($doctor->auth_id && $validatedData['email']) {
            $user = User::findOrFail($doctor->auth_id);
            $user->update(['email' => $validatedData['email']]);
            event(new Registered($user));
        }

    
        // Redirect to the doctor listing page with success message
        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully.');
    }



    // Delete the specified doctor
    public function destroy($id)
    {
        $doctor = DoctorList::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
    
    public function doctorupdateStatus(Request $request)
{
    try {
    
     \Log::info($request->all()); // Debug request payload
        $doctor = DoctorList::findOrFail($request->doctors_id);
        $doctor->status = $request->status;
        $doctor->save();

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
