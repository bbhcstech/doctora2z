<?php

namespace App\Http\Controllers;
use App\Models\District;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
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
}
