<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\DoctorList;
use Illuminate\Http\Request;

class LocationFrontController extends Controller
{
    public function getStates($country_id)
    {
         
        // Fetch states based on the selected country ID
        $states = State::where('country_id', $country_id)->get();
        // Return the states as a JSON response
        return response()->json($states);
    }

    public function getDistricts($state_id) {
        $districts = District::where('state_id', $state_id)->get();
        return response()->json($districts);
    }

    public function getTowns($district_id) {
        $towns = City::where('district_id', $district_id)->get();
        return response()->json($towns);
    }
    // public function getDoctors( $cityName)
    // {
        
    //     $doctors = DoctorList::where('city_name', $cityName)
    //         ->get();
           
    // //   print_r($doctors);die;
    //     return response()->json($doctors);
    // }
    
     public function getDoctors( $cityName)
    {
        
        $doctors = DoctorList::where('district_name', $cityName)
            ->get();
           
    //   print_r($doctors);die;
        return response()->json($doctors);
    }
    public function getDoctorRating($id)
    {
        $averageRating = DB::table('rating')
            ->where('doctor_id', $id)
            ->avg('rating_point');
    
        return response()->json(['averageRating' => round($averageRating, 1)]);
    }
}
