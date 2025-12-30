<?php

namespace App\Http\Controllers;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use Illuminate\Http\Request;

class TownVillageController extends Controller
{
    public function index()
    {
        $towns = City::with('district.state.country')->orderBy('id', 'desc')->get();
        
        
        return view('admin.town-village.index', compact('towns'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('admin.town-village.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'district_id' => 'required',
            'name' => 'required|string|max:255',
        ]);

        City::create($request->all());
        return redirect()->route('town-village.index')->with('success', 'City/Town/Village added successfully.');
    }

    public function edit($id)
    {
        $town = City::with('district.state.country')->findOrFail($id);
        $countries = Country::with('states')->get();
        
        $states = State::all();
         $districts  = District::all();
        return view('admin.town-village.edit', compact('town', 'countries','states','districts'));
    }
    public function update(Request $request,  $id)
    {
        $request->validate([
            'district_id' => 'required',
            'name' => 'required|string|max:255',
        ]);
        $townVillage = City::findOrFail($id);
        $townVillage->update($request->all());
        return redirect()->route('town-village.index')->with('success', 'City/Town/Village updated successfully.');
    }
    public function destroy($id)
    {
        // Find and delete the district
        $city = City::findOrFail($id);
        $city->delete();

        return redirect()->route('town-village.index')->with('success', 'City/Town/Village deleted successfully.');
    }
}
