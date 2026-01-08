<?php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts = District::with(['state', 'state.country'])
            ->orderBy('id', 'desc')
            ->paginate(25);

        return view('admin.district.index', compact('districts'));
    }

    public function create()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        $states    = State::orderBy('name', 'asc')->get();

        return view('admin.district.create', compact('countries', 'states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'name'     => 'required|string|max:255|unique:districts,name',
        ]);

        District::create([
            'state_id' => $request->state_id,
            'name'     => $request->name,
        ]);

        return redirect()->route('district.index')->with('success', 'District created successfully.');
    }

    public function edit($id)
    {
        $district  = District::findOrFail($id);
        $countries = Country::orderBy('name', 'asc')->get();
        $states    = State::where('country_id', $district->state->country_id ?? null)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.district.edit', compact('district', 'countries', 'states'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'state_id' => 'required|exists:states,id',
            'name'     => 'required|string|max:255|unique:districts,name,' . $id,
        ]);

        $district = District::findOrFail($id);
        $district->update([
            'state_id' => $request->state_id,
            'name'     => $request->name,
        ]);

        return redirect()->route('district.index')->with('success', 'District updated successfully.');
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);
        $district->delete();

        return redirect()->route('district.index')->with('success', 'District deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $districtIds = $request->ids;

        if (! $districtIds) {
            return back()->with('error', 'No districts selected!');
        }

        District::whereIn('id', $districtIds)->delete();
        return back()->with('success', 'Selected districts deleted successfully!');
    }

    // AJAX method for getting states by country
    public function getStatesByCountry($countryId)
    {
        $states = State::where('country_id', $countryId)
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($states);
    }
}
