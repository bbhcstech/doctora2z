<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    // Index: Display all countries
    public function index()
    {
        $countries = Country::orderBy('id', 'desc')->get();
        return view('admin.country.index', compact('countries'));
    }

    // Create: Show the form to add a new country
    public function create()
    {
        return view('admin.country.create');
    }

    // Store: Save the new country
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
        ]);
    
        // Create a new country record
        Country::create([
            'name' => $request->name,
        ]);
    
        // Redirect with a success message
        return redirect()->route('country.index')->with('success', 'Country added successfully.');
    }

    // Edit: Show the form to edit a country
    public function edit(Country $country)
    {
        return view('admin.country.edit', compact('country'));
    }

    // Update: Save changes to a country
    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
        ]);

        $country->update(['name' => $request->name]);

        return redirect()->route('country.index')->with('success', 'Country updated successfully.');
    }

    // Destroy: Delete a country
    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('country.index')->with('success', 'Country deleted successfully.');
    }
}