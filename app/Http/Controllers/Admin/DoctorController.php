<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\District;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\State;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorController extends Controller
{
    /**
     * Display the doctor listing page
     */
public function index()
{
    $categories = Category::orderBy('name')->get();
    $cities = City::orderBy('name')->get();
    
    // Create the view
    $view = view('admin.doctor_lists.dcor', compact('categories', 'cities'));
    
    // The composer will automatically be called when we return the view
    return $view;
}

    /**
     * Show the add doctor form page
     */
    public function create()
    {
        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $clinics = Client::orderBy('name')->get();

        return view('admin.doctor_lists.add_doctor', compact(
            'countries', 'states', 'districts', 'cities', 'categories', 'clinics'
        ));
    }

    /**
     * Show the import page
     */
    public function importPage()
    {
        return view('admin.doctor_lists.import');
    }

    /**
     * Store a new doctor
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules(true));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $data['profile_picture'] = $this->storeImage($request->file('profile_picture'));
            }

            // Handle pincode
            if (!empty($data['pincode'])) {
                $pincodeData = $this->processPincode($data['pincode']);
                $data['pincode_id'] = $pincodeData['pincode_id'];
                $data['is_pincode_unknown'] = $pincodeData['is_unknown'];
                $data['manual_pincode'] = $pincodeData['is_unknown'] ? $data['pincode'] : null;
                $data['location_source'] = $pincodeData['is_unknown'] ? 'manual' : 'auto';
            }
            unset($data['pincode']);

            // Handle schedules if provided
            if ($request->filled('schedules_json')) {
                $schedules = json_decode($request->input('schedules_json'), true);
                if ($schedules) {
                    $this->processSchedules($schedules, $data);
                }
            }

            // Handle clinic_id from schedules if not provided
            if (empty($data['clinic_id']) && isset($schedules)) {
                $clinicId = $this->resolveClinicFromSchedules($schedules);
                if ($clinicId) {
                    $data['clinic_id'] = $clinicId;
                }
            }

            // Handle array fields
            if ($request->has('languages_array') && is_array($request->languages_array)) {
                $data['languages'] = implode(',', array_map('trim', $request->languages_array));
            }

            if ($request->has('degrees') && is_array($request->degrees)) {
                $data['degrees'] = json_encode($request->degrees);
            }

            if ($request->has('clinic_days') && is_array($request->clinic_days)) {
                $data['clinic_days'] = json_encode($request->clinic_days);
            }

            // Create doctor
            $doctor = Doctor::create($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Doctor created successfully',
                'redirect' => route('admin.doctors.index')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Doctor creation failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create doctor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display doctor details
     */
    public function show($id)
    {
        $doctor = Doctor::with([
            'country', 'state', 'district', 'city', 
            'category', 'clinic', 'pincode', 'clinicSchedules'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $doctor
        ]);
    }

    /**
     * Show the edit doctor form
     */
    public function edit($id)
    {
        $doctor = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                        ->findOrFail($id);

        $countries = Country::orderBy('name')->get();
        $states = State::orderBy('name')->get();
        $districts = District::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $clinics = Client::orderBy('name')->get();

        $doctor->languages_array = $doctor->languages ? explode(',', $doctor->languages) : [];
        $doctor->clinic_days_array = $doctor->clinic_days ? json_decode($doctor->clinic_days, true) : [];

        return view('admin.doctor_lists.edit2', compact(
            'doctor', 'countries', 'states', 'districts', 'cities', 'categories', 'clinics'
        ));
    }

    /**
     * Update the specified doctor
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validator = Validator::make($request->all(), $this->validationRules(false, $id));

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Handle profile picture upload
            if ($request->hasFile('profile_picture')) {
                $this->deleteImage($doctor->profile_picture);
                $data['profile_picture'] = $this->storeImage($request->file('profile_picture'));
            }

            // Handle pincode
            if (isset($data['pincode'])) {
                $pincodeData = $this->processPincode($data['pincode']);
                $data['pincode_id'] = $pincodeData['pincode_id'];
                $data['is_pincode_unknown'] = $pincodeData['is_unknown'];
                $data['manual_pincode'] = $pincodeData['is_unknown'] ? $data['pincode'] : null;
                $data['location_source'] = $pincodeData['is_unknown'] ? 'manual' : 'auto';
                unset($data['pincode']);
            }

            // Handle array fields
            if ($request->has('languages_array') && is_array($request->languages_array)) {
                $data['languages'] = implode(',', array_map('trim', $request->languages_array));
            }

            if ($request->has('degrees') && is_array($request->degrees)) {
                $data['degrees'] = json_encode($request->degrees);
            }

            if ($request->has('clinic_days') && is_array($request->clinic_days)) {
                $data['clinic_days'] = json_encode($request->clinic_days);
            }

            // Handle schedules
            if ($request->filled('schedules_json')) {
                $schedules = json_decode($request->input('schedules_json'), true);
                if ($schedules) {
                    $this->processSchedules($schedules, $data);
                }
            }

            // Update doctor
            $doctor->update($data);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Doctor updated successfully',
                'redirect' => route('admin.doctors.index')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Doctor update failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update doctor',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Remove the specified doctor
     */
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);

        try {
            $this->deleteImage($doctor->profile_picture);
            $doctor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Doctor deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Doctor deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctor'
            ], 500);
        }
    }

    /**
     * AJAX: Get states by country
     */
    public function getStates($countryId)
    {
        $states = State::where('country_id', $countryId)->orderBy('name')->get(['id', 'name']);
        return response()->json($states);
    }

    /**
     * AJAX: Get districts by state
     */
    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)->orderBy('name')->get(['id', 'name']);
        return response()->json($districts);
    }

    /**
     * AJAX: Get cities by district
     */
    public function getCities($districtId)
    {
        $cities = City::where('district_id', $districtId)->orderBy('name')->get(['id', 'name']);
        return response()->json($cities);
    }

    /**
     * AJAX: Get clinics by category
     */
    public function getClinics($categoryId)
    {
        $clinics = Client::where('category_id', $categoryId)->orderBy('name')->get(['id', 'name']);
        return response()->json($clinics);
    }

    /**
     * ============================================
     * HELPER METHODS
     * ============================================
     */

    /**
     * Validation rules
     */
    private function validationRules($create = false, $id = null)
    {
        $rules = [
            'name' => [$create ? 'required' : 'sometimes', 'string', 'max:255'],
            'email' => [
                $create ? 'required' : 'sometimes',
                'nullable',
                'email',
                'max:255',
                Rule::unique('doctor_profiles', 'email')->ignore($id)
            ],
            'phone_number' => [$create ? 'required' : 'sometimes', 'nullable', 'string', 'max:20'],
            'phone_number_2' => ['nullable', 'string', 'max:20'],
            'registration_no' => ['nullable', 'string', 'max:255'],
            'council' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:20'],
            'speciality' => [$create ? 'required' : 'sometimes', 'nullable', 'string', 'max:255'],
            'degree' => ['nullable', 'string', 'max:255'],
            'country_id' => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:countries,id'],
            'state_id' => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:states,id'],
            'city_id' => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:cities,id'],
            'category_id' => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'clinic_id' => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:clinics,id'],
            'website' => ['nullable', 'url', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:25'],
            'facebook' => ['nullable', 'string', 'max:255'],
            'instagram' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', Rule::in(['active', 'inactive'])],
            'consultation_mode' => [$create ? 'required' : 'sometimes', Rule::in(['online', 'face-to-face', 'both', 'offline'])],
            'profile_picture' => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'schedules_json' => ['sometimes', 'nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:100'],
            'languages' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date'],
            'profile_details' => ['nullable', 'string'],
        ];

        return $rules;
    }

    /**
     * Store uploaded image
     */
    private function storeImage($file)
    {
        $path = $file->store('doctors', 'public');
        return basename($path);
    }

    /**
     * Delete image if exists
     */
    private function deleteImage($filename)
    {
        if (!$filename) return;
        $path = 'doctors/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Process pincode data
     */
    private function processPincode($pincode)
    {
        $pincode = trim($pincode);
        if (empty($pincode)) {
            return ['pincode_id' => null, 'is_unknown' => true];
        }

        $pincodeRecord = Pincode::where('pincode', $pincode)->first();
        if ($pincodeRecord) {
            return ['pincode_id' => $pincodeRecord->id, 'is_unknown' => false];
        }

        return ['pincode_id' => null, 'is_unknown' => true];
    }

    /**
     * Process schedules
     */
    private function processSchedules($schedules, &$data)
    {
        // Extract days
        $days = [];
        foreach ($schedules as $schedule) {
            if (!empty($schedule['days'])) {
                $dayList = is_array($schedule['days']) 
                    ? $schedule['days'] 
                    : explode(',', $schedule['days']);
                $days = array_merge($days, array_map('trim', $dayList));
            }
        }
        if (!empty($days)) {
            $data['clinic_days'] = array_unique($days);
        }

        // Extract times
        $startTimes = [];
        $endTimes = [];
        foreach ($schedules as $schedule) {
            if (!empty($schedule['start_time'])) {
                $startTimes[] = date('H:i:s', strtotime($schedule['start_time']));
            }
            if (!empty($schedule['end_time'])) {
                $endTimes[] = date('H:i:s', strtotime($schedule['end_time']));
            }
        }
        if (!empty($startTimes)) {
            $data['clinic_start_time'] = min($startTimes);
        }
        if (!empty($endTimes)) {
            $data['clinic_end_time'] = max($endTimes);
        }

        // Store alternative schedule
        if (!empty($schedules)) {
            $data['alternative_schedule'] = json_encode($schedules);
        }
    }

    /**
     * Resolve clinic from schedules
     */
    private function resolveClinicFromSchedules($schedules)
    {
        foreach ($schedules as $schedule) {
            if (!empty($schedule['clinic_id'])) {
                $clinic = Client::find($schedule['clinic_id']);
                if ($clinic) return $clinic->id;
            }
            if (!empty($schedule['clinic_name'])) {
                $clinic = Client::where('name', 'like', '%' . $schedule['clinic_name'] . '%')->first();
                if ($clinic) return $clinic->id;
            }
        }
        return null;
    }
}