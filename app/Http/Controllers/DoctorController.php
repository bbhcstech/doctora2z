<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Category;
use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Display the inline form for managing doctors.
     */
    public function inlineForm()
{
    $doctors = Doctor::with(['category','client','country','state','district','city'])->get();
    $categories = Category::all();
    $clients = Client::all();
    $countries = Country::all();
    $states = State::all();
    $districts = District::all();
    $cities = City::all();

    return view('admin.doctor_lists.inline2', compact(
        'doctors',
        'categories',
        'clients',
        'countries',
        'states',
        'districts',
        'cities'
    ));
}


    /**
     * Bulk store or update doctors.
     */
    public function bulkStore(Request $request)
    {
        $request->validate([
            'doctors' => 'required|array',
            'doctors.*.name' => 'required|string|max:255',
            'doctors.*.country_id' => 'required|exists:countries,id',
            'doctors.*.state_id' => 'required|exists:states,id',
            'doctors.*.district_id' => 'required|exists:districts,id',
            'doctors.*.city_id' => 'required|exists:cities,id',
            'doctors.*.category_id' => 'required|exists:categories,id',
            'doctors.*.client_id' => 'required|exists:clients,id',
            'doctors.*.phone_number' => 'required|string|max:20',
            'doctors.*.email' => 'required|email',
            'doctors.*.speciality' => 'required|string|max:255',
            'doctors.*.status' => 'required|in:active,inactive',
            'doctors.*.consultation_mode' => 'required|in:online,offline,both',
        ]);

        $doctors = $request->input('doctors', []);
        foreach ($doctors as $id => $fields) {
            $emailRule = $id ? 'unique:doctor_profiles,email,' . $id : 'unique:doctor_profiles,email';
            Validator::make($fields, [
                'email' => $emailRule,
            ])->validate();

            Doctor::updateOrCreate(
                ['id' => $id],
                [
                    'name' => $fields['name'],
                    'country_id' => $fields['country_id'],
                    'state_id' => $fields['state_id'],
                    'district_id' => $fields['district_id'],
                    'city_id' => $fields['city_id'],
                    'category_id' => $fields['category_id'],
                    'client_id' => $fields['client_id'],
                    'phone_number' => $fields['phone_number'],
                    'email' => $fields['email'],
                    'speciality' => $fields['speciality'],
                    'status' => $fields['status'],
                    'consultation_mode' => $fields['consultation_mode'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()->back()->with('success', 'Doctors saved successfully!');
    }

    /**
     * Bulk delete doctors.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('selected_ids', []);
        if (!is_array($ids) || empty(array_filter($ids))) {
            return redirect()->back()->with('error', 'No doctors selected for deletion.');
        }

        Doctor::whereIn('id', $ids)->delete();
        return redirect()->back()->with('success', 'Selected doctors deleted!');
    }

    /**
     * Save or update a doctor via AJAX.
     */
    public function inlineSave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'nullable|exists:doctor_profiles,id',
                'name' => 'required|string|max:255',
                'country_id' => 'required|exists:countries,id',
                'state_id' => 'required|exists:states,id',
                'district_id' => 'required|exists:districts,id',
                'city_id' => 'required|exists:cities,id',
                'category_id' => 'required|exists:categories,id',
                'client_id' => 'required|exists:clients,id',
                'phone_number' => 'required|string|max:20',
                'email' => 'required|email|unique:doctor_profiles,email,' . ($request->id ?? 'NULL'),
                'speciality' => 'required|string|max:255',
                'status' => 'required|in:active,inactive',
                'consultation_mode' => 'required|in:online,offline,both',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $doctor = Doctor::updateOrCreate(
                ['id' => $request->id],
                [
                    'name' => $request->name,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'district_id' => $request->district_id,
                    'city_id' => $request->city_id,
                    'category_id' => $request->category_id,
                    'client_id' => $request->client_id,
                    'phone_number' => $request->phone_number,
                    'email' => $request->email,
                    'speciality' => $request->speciality,
                    'status' => $request->status,
                    'consultation_mode' => $request->consultation_mode,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'id' => $doctor->id,
                'message' => 'Doctor saved successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a doctor via AJAX.
     */
    public function ajaxDestroy($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['success' => false, 'message' => 'Doctor not found.'], 404);
        }

        $doctor->delete();
        return response()->json(['success' => true, 'message' => 'Doctor deleted successfully!']);
    }

    /**
     * Display a specific doctor's details.
     */
    public function show($id)
    {
        $doctor = Doctor::with(['category', 'client', 'country', 'state', 'district', 'city'])->findOrFail($id);
        return view('admin.doctor_lists.show', compact('doctor'));
    }
}