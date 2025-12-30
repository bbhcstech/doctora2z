<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('country')->orderBy('id', 'desc')->get(); // Load states with their associated country
        return view('admin.state.index', compact('states'));
    }

    public function create()
    {
        $countries = Country::all(); // Fetch all countries for the dropdown
        return view('admin.state.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name',
            'country_id' => 'required|exists:countries,id',
        ]);

        State::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('state.index')->with('success', 'State(Part) added successfully.');
    }

    public function edit($id)
    {
        $state = State::findOrFail($id); // Find the state or throw a 404
        $countries = Country::all(); // Fetch all countries for the dropdown
        return view('admin.state.edit', compact('state', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $id,
            'country_id' => 'required|exists:countries,id',
        ]);

        $state = State::findOrFail($id);
        $state->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route('state.index')->with('success', 'State(Part) updated successfully.');
    }

    public function destroy($id)
    {
        $state = State::findOrFail($id);
        $state->delete();

        return redirect()->route('state.index')->with('success', 'State(Part) deleted successfully.');
    }
}