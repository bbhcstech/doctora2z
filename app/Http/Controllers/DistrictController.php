<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistrictController extends Controller
{
    public function index()
    {
        // Load all districts with their state, country, and all pincodes
        $districts = District::with([
                'state.country',
                'pincodes' // load all pincodes, we’ll show city_id directly
            ])
            ->orderBy('id', 'desc')
            ->paginate(50);

        return view('admin.district.index', compact('districts'));
    }

    public function create()
    {
        $countries = Country::all();
        return view('admin.district.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'state_id'  => 'required|exists:states,id',
            'city_name' => 'nullable|string|max:100|required_with:pincode',
            'pincode'   => 'nullable|digits:6',
        ]);

        $district = District::create([
            'name'     => trim($request->name),
            'state_id' => (int) $request->state_id,
        ]);

        if ($request->filled('pincode')) {
            $pin = substr(preg_replace('/\D+/', '', $request->pincode), 0, 6);

            $city = City::firstOrCreate([
                'name'        => trim($request->city_name) ?: $district->name,
                'district_id' => $district->id,
            ]);

            // Unique per (pincode, city_id)
            $request->validate([
                'pincode' => [
                    'digits:6',
                    Rule::unique('pincodes', 'pincode')->where(fn($q) => $q->where('city_id', $city->id)),
                ],
            ]);

            Pincode::create([
                'pincode'     => $pin,
                'country_id'  => $district->state->country_id,
                'state_id'    => $district->state_id,
                'district_id' => $district->id,
                'city_id'     => $city->id,
            ]);
        }

        return redirect()->route('district.index')->with('success', 'District added successfully.');
    }

    public function edit($id)
    {
        $district  = District::with(['state.country', 'pincodes'])->findOrFail($id);
        $countries = Country::with('states')->get();

        return view('admin.district.edit', compact('district', 'countries'));
    }

    public function update(Request $request, $id)
    {
        $district = District::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'state_id'  => 'required|exists:states,id',
            'city_name' => 'nullable|string|max:100|required_with:pincode',
            'pincode'   => 'nullable|digits:6',
        ]);

        $district->update([
            'name'     => trim($request->name),
            'state_id' => (int) $request->state_id,
        ]);

        if ($request->filled('pincode')) {
            $pin = substr(preg_replace('/\D+/', '', $request->pincode), 0, 6);

            $city = City::firstOrCreate([
                'name'        => trim($request->city_name) ?: $district->name,
                'district_id' => $district->id,
            ]);

            $request->validate([
                'pincode' => [
                    'digits:6',
                    Rule::unique('pincodes', 'pincode')
                        ->where(fn($q) => $q->where('city_id', $city->id)),
                ],
            ]);

            // Upsert per (pincode, city_id)
            Pincode::updateOrCreate(
                ['pincode' => $pin, 'city_id' => $city->id],
                [
                    'country_id'  => $district->state->country_id,
                    'state_id'    => $district->state_id,
                    'district_id' => $district->id,
                ]
            );
        }

        return redirect()->route('district.index')->with('success', 'District updated successfully.');
    }

    public function destroy($id)
    {
        $district = District::findOrFail($id);

        $district->pincodes()->delete();
        $district->delete();

        return redirect()->route('district.index')->with('success', 'District deleted successfully.');
    }
}
