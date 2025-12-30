<?php

namespace App\Http\Controllers;
use App\Models\TrendingDoctor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class TrendingDoctorController extends Controller
{
     public function index()
    {
        $trendingdoctors = TrendingDoctor::all();

        return view('admin.trending-doctors.index', compact('trendingdoctors'));
    }
    public function create()
    {
        $doctors = DB::table('doctor_visiting_count')
        ->select(DB::raw('MIN(id) as id'), 'doctor_name', 'doctor_id', DB::raw('SUM(visit_count) as total_visits'))
        ->groupBy('doctor_name', 'doctor_id')
        ->get();

        return view('admin.trending-doctors.create', compact('doctors'));
    }


    public function store(Request $request)
    {
       //return $request;
        // $request->validate([
        //     'name' => 'required',
        // ]);
        $name = $request->input('name');

        $doctorId = $request->input('doctor_id');
        $doctorVisitingCountId = $request->input('doctor_visiting_tbl_id');
        $totalVisitCount = $request->input('total_visit_count');

        // Save or process the data
        TrendingDoctor::create([
            'name' => $name,
            'doctor_id' => $doctorId,
            'doctor_visiting_count_id' => $doctorVisitingCountId,
            'total_visit_count' => $totalVisitCount,
        ]);

       
         return redirect()->route('trending-doctors.index')->with('success', 'Trending Doctor added successfully!');
    }
    
     public function edit($id)
    {
      // Fetch the trending doctor to edit
    $trendingDoctor = TrendingDoctor::findOrFail($id);

    // Fetch the related doctor details from the doctor_visiting_count table using the doctor_id from the trending_doctor
  $doctors = DB::table('doctor_visiting_count')
        ->select(DB::raw('MIN(id) as id'), 'doctor_name', 'doctor_id', DB::raw('SUM(visit_count) as total_visits'))
        ->groupBy('doctor_name', 'doctor_id')
        ->get();


    return view('admin.trending-doctors.edit', compact('trendingDoctor', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $trendingDoctor = TrendingDoctor::findOrFail($id);
        $trendingDoctor->update($request->all());

        return redirect()->route('trending-doctors.index')
            ->with('success', 'Trending Doctor updated successfully!');
    }
    
     // Remove the specified resource from storage
  public function destroy($id)
  {
 
    $trendingDoctor = TrendingDoctor::findOrFail($id);
    
    // Delete the doctor
    $trendingDoctor->delete();

    // Redirect back with a success message
    return redirect()->route('trending-doctors.index')->with('success', 'Trending Doctor deleted successfully.');

  }
  
}
