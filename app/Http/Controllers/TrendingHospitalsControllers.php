<?php

namespace App\Http\Controllers;
use App\Models\TrendingHospitalls;
use App\Models\Hospital;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrendingHospitalsControllers extends Controller
{
     public function index()
    {
        $trendinghospitals  = TrendingHospitalls::all();

        return view('admin.trending-hospital.index', compact('trendinghospitals'));
    }
    public function create()
    {
        $hospitals = Hospital::all();

        return view('admin.trending-hospital.create', compact('hospitals'));
    }


    public function store(Request $request)
    {
        $name = $request->input('name');

        $hospitalId = $request->input('hospital_id');
  
        // Save or process the data
        TrendingHospitalls::create([
            'name' => $name,
            'hospital_id' => $hospitalId,
        ]);

       
         return redirect()->route('trending-hospital.index')->with('success', 'Trending Hospitals added successfully!');
    }
    
   
    
     // Remove the specified resource from storage
  public function destroy($id)
  {
 
    $trendingHospital = TrendingHospitalls::findOrFail($id);
    
    // Delete the doctor
    $trendingHospital->delete();

    // Redirect back with a success message
    return redirect()->route('trending-hospital.index')->with('success', 'Trending Hospital deleted successfully.');

  }
  
}
