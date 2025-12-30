<?php

namespace App\Http\Controllers;
use App\Models\TrendingClinic;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrendingClinicControllers extends Controller
{
     public function index()
    {
        $trendingclinics   = TrendingClinic::all();

        return view('admin.trending-clinic.index', compact('trendingclinics'));
    }
    public function create()
    {
        $clinics = Client::all();

        return view('admin.trending-clinic.create', compact('clinics'));
    }


    public function store(Request $request)
    {
        $name = $request->input('name');

        $clinicId = $request->input('clinic_id');
  
        // Save or process the data
        TrendingClinic::create([
            'name' => $name,
            'clinic_id' => $clinicId,
        ]);

       
         return redirect()->route('trending-clinic.index')->with('success', 'Trending Clinic added successfully!');
    }
    
    //  public function edit($id)
    // {
    //   // Fetch the trending doctor to edit
    // $trendingClinic = TrendingClinic::findOrFail($id);


    // return view('admin.trending-clinic.edit', compact('trendingDoctor'));
    // }

    
    
     // Remove the specified resource from storage
  public function destroy($id)
  {
 
    $trendingClinic = TrendingClinic::findOrFail($id);
    
    // Delete the doctor
    $trendingClinic->delete();

    // Redirect back with a success message
    return redirect()->route('trending-clinic.index')->with('success', 'Trending Clinic deleted successfully.');

  }
  
}
