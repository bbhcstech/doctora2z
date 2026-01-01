<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;                       // <-- ADDED
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;                  // <-- ADDED
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

use App\Models\Doctor;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\Pincode;
use App\Models\Client;
use App\Models\User;
use App\Models\DoctorClinicScheduler;
use App\Models\Category;


class DoctorProfileController extends Controller
{
    /**
     * Show profile (read only).
     */
     
    public function show($id = null): View
    {
        $user = Auth::user();

        if ($id && $user && $user->can('view', Doctor::class)) {
            $doctor = Doctor::with(['country','state','district','city','clinic','pincode','clinicSchedules'])->findOrFail($id);
        } else {
            $doctor = Doctor::with(['country','state','district','city','clinic','pincode','clinicSchedules'])
                ->where('user_id', optional($user)->id)
                ->firstOrFail();
        }

        return view('Doctor.Profile.show', compact('doctor'));
    }

    /**
     * Update entire doctor profile (single Save Profile button).
     * Validates clinic times (H:i or H:i:s), normalizes and persists clinics via DoctorClinicScheduler.
     */
public function update(Request $request, $id = null): RedirectResponse
{
    $doctor = $this->getEditableDoctor($id);

    // Normalize degrees_text
    if ($request->filled('degrees_text')) {
        $degrees = preg_split("/\r\n|\n|\r/", $request->degrees_text);
        $degrees = array_values(array_filter(array_map('trim', $degrees)));
        $request->merge(['degrees' => $degrees]);
    }

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'profile_picture' => ['nullable', 'image', 'max:5120'],
        'date_of_birth' => ['nullable', 'date'],
        'phone_number' => ['nullable', 'string', 'max:50'],
        'phone_number_2' => ['nullable', 'string', 'max:50'],
        'whatsapp' => ['nullable', 'string', 'max:30'],
        'email' => ['nullable', 'email', 'max:255'],
        'address' => ['nullable', 'string', 'max:2000'],
        'status' => ['nullable', Rule::in(['active', 'inactive'])],
        'consultation_mode' => ['nullable', Rule::in(['online', 'offline', 'both', 'face-to-face'])],

        'speciality' => ['nullable', 'string', 'max:255'],
        'degree' => ['nullable', 'string', 'max:255'],
        'registration_no' => ['nullable', 'string', 'max:255'],
        'council' => ['nullable', 'string', 'max:255'],
        'category_id' => ['nullable', 'integer'],
        'website' => ['nullable', 'url'],
        'facebook' => ['nullable', 'url'],
        'instagram' => ['nullable', 'url'],
        'experience_years' => ['nullable', 'integer', 'min:0', 'max:120'],
        'languages' => ['nullable'],
        'degrees' => ['nullable', 'array'],
        'degrees.*' => ['string', 'max:255'],

        'clinics' => ['required', 'array', 'min:1'],
        'clinics.*.clinic_id' => ['nullable', 'integer', Rule::exists('clinics', 'id')],
        'clinics.*.clinic_name' => ['nullable', 'string', 'max:255'],
        'clinics.*.clinic_address' => ['nullable', 'string', 'max:2000'],
        'clinics.*.schedules' => ['nullable', 'array'],
        'clinics.*.schedules.*.day' => [
            'nullable',
            Rule::in(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'])
        ],
        'clinics.*.schedules.*.start' => ['nullable'],
        'clinics.*.schedules.*.end' => ['nullable'],
    ]);

    /* ===================== 🔴 REQUIRED MANUAL VALIDATION ===================== */
    foreach ($request->clinics as $index => $clinic) {
        if (
            empty($clinic['clinic_id']) &&
            empty(trim($clinic['clinic_name'] ?? ''))
        ) {
            return back()
                ->withErrors([
                    "clinics.$index.clinic_name" =>
                        "Please select a clinic or enter a clinic name."
                ])
                ->withInput();
        }
    }
    /* ======================================================================= */

    DB::beginTransaction();
    try {

        $doctor->fill($validated);

        if ($request->hasFile('profile_picture')) {
            $this->storeProfilePicture($doctor, $request->file('profile_picture'));
        }

        if (!empty($validated['languages'])) {
            $doctor->languages = is_array($validated['languages'])
                ? implode(', ', array_map('trim', $validated['languages']))
                : trim($validated['languages']);
        }

        if (!empty($validated['degrees'])) {
            $doctor->degrees = $validated['degrees'];
        }

        $doctor->save();

        DoctorClinicScheduler::where('doctor_profile_id', $doctor->id)->delete();

        foreach ($request->clinics as $clinic) {

            $clinicId   = $clinic['clinic_id'] ?? null;
            $clinicAddr = $clinic['clinic_address'] ?? null;

            if (!$clinicId && !empty($clinic['clinic_name'])) {
                $clinicName = trim($clinic['clinic_name']);

                $clinicModel = Client::firstOrCreate(
                    ['name' => $clinicName],
                    [
                        'address' => $clinicAddr,
                        'status' => 'Approved',
                        'user_id' => auth()->id() ?? 0,
                        'auth_id' => auth()->id() ?? 1,
                        'category_id' => '',
                        'created_by' => 'doctor-profile',
                    ]
                );

                $clinicId = $clinicModel->id;
            }

            foreach ($clinic['schedules'] ?? [] as $schedule) {
                if (empty($schedule['day']) || empty($schedule['start']) || empty($schedule['end'])) {
                    continue;
                }

                DoctorClinicScheduler::create([
                    'doctor_profile_id' => $doctor->id,
                    'clinic_id' => $clinicId,
                    'days' => [$schedule['day']],
                    'start_time' => $schedule['start'],
                    'end_time' => $schedule['end'],
                    'clinic_address' => $clinicAddr,
                ]);
            }
        }

        DB::commit();
        return back()->with('success', 'Profile updated successfully.');

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Doctor profile update failed: '.$e->getMessage());
        return back()->withErrors(['error' => 'Update failed'])->withInput();
    }
}


    /**
     * Show edit page.
     */
public function edit(Request $request, $id = null): View
{
    $user = Auth::user();

    // Load doctor
    if ($id && $user && $user->can('update', Doctor::class)) {
        $doctor = Doctor::with([
            'country','state','district','city','clinic','pincode','clinicSchedules'
        ])->find($id);
    } else {
        $doctor = Doctor::with([
            'country','state','district','city','clinic','pincode','clinicSchedules'
        ])->where('user_id', optional($user)->id)->first();
    }

    if (!$doctor) {
        $doctor = new Doctor();
    }

    // Countries
    $countries = Country::orderBy('name')->pluck('name','id');

    // Categories
    try {
        if (class_exists(\App\Models\Category::class)) {
            $categories = \App\Models\Category::orderBy('name')->pluck('name','id');

            if ($categories->isEmpty()) {
                $categories = collect([
                    1=>'General',2=>'Dental',3=>'Cardiology',4=>'Pediatrics',5=>'Dermatology'
                ]);
            }
        } else {
            $categories = collect([
                1=>'General',2=>'Dental',3=>'Cardiology',4=>'Pediatrics',5=>'Dermatology'
            ]);
        }
    } catch (\Throwable $e) {
        Log::warning('Failed to load categories: '.$e->getMessage());
        $categories = collect([
            1=>'General',2=>'Dental',3=>'Cardiology',4=>'Pediatrics',5=>'Dermatology'
        ]);
    }

    // Location dependent lists
    $states     = $doctor->country_id ? State::where('country_id', $doctor->country_id)->orderBy('name')->pluck('name','id') : collect();
    $districts  = $doctor->state_id   ? District::where('state_id', $doctor->state_id)->orderBy('name')->pluck('name','id') : collect();
    $cities     = $doctor->district_id? City::where('district_id', $doctor->district_id)->orderBy('name')->pluck('name','id') : collect();

    // -------------------------------------------------------
    //  FIX: SHOW ALL CLINICS WITHOUT ANY RESTRICTIONS
    // -------------------------------------------------------
    $clinics = Client::orderBy('name')->pluck('name', 'id');

    // Build clinicsForSelect array
    $clinicsForSelect = [];
    $schedRows = $doctor->clinicSchedules ?? collect();
    $altByClinic = [];
    $addrByClinic = [];

    foreach ($schedRows as $r) {
        $cid = $r->clinic_id;
        if ($cid) {
            $altByClinic[$cid]  = trim((string)($r->alternative_text ?? ''));
            $addrByClinic[$cid] = trim((string)($r->clinic_address ?? ''));
        }
    }

    foreach ($clinics as $cid => $cname) {
        $clinicsForSelect[$cid] = [
            'name'    => $cname,
            'alt'     => $altByClinic[$cid]  ?? null,
            'address' => $addrByClinic[$cid] ?? null,
        ];
    }

    // Active tab
    $activeTab = $request->query('tab', session('active_tab', 'personal'));

    // Return view
    return view('Doctor.Profile.edit', compact(
        'doctor',
        'countries',
        'categories',
        'states',
        'districts',
        'cities',
        'clinics',
        'activeTab',
        'clinicsForSelect'
    ));
}

    /**
     * Update Personal-only tab (AJAX or form).
     */
    public function updatePersonal(Request $request, $id = null): RedirectResponse
    {
        $doctor = $this->getEditableDoctor($id);

        $rules = [
            'name'             => ['required','string','max:255'],
            'profile_picture'  => ['nullable','image','max:5120'],
            'date_of_birth'    => ['nullable','date'],
            'phone_number'     => ['nullable','string','max:50'],
            'phone_number_2'   => ['nullable','string','max:50'],
            'whatsapp'         => ['nullable','string','max:30'],
            'email'            => ['nullable','email:rfc','max:255',
                                    Rule::unique((new Doctor)->getTable(),'email')->ignore($doctor->id ?? null),
                                    Rule::unique((new User)->getTable(),'email')->ignore(optional($doctor->user)->id ?? null),
                                  ],
            'address'          => ['nullable','string','max:2000'],
            'status'           => ['nullable', Rule::in(['active','inactive'])],
            'consultation_mode'=> ['nullable', Rule::in(['online','offline','both','face-to-face'])],
            'profile_details'  => ['nullable','string'],
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $doctor->name = $validated['name'];
            if (!empty($validated['date_of_birth'])) {
                $doctor->date_of_birth = Carbon::parse($validated['date_of_birth'])->toDateString();
            }
            $doctor->phone_number = $validated['phone_number'] ?? $doctor->phone_number;
            $doctor->phone_number_2 = $validated['phone_number_2'] ?? $doctor->phone_number_2;
            $doctor->whatsapp = $validated['whatsapp'] ?? $doctor->whatsapp;
            $doctor->email = $validated['email'] ?? $doctor->email;
            $doctor->address = $validated['address'] ?? $doctor->address;
            $doctor->status = $validated['status'] ?? $doctor->status ?? 'active';
            $doctor->consultation_mode = $validated['consultation_mode'] ?? $doctor->consultation_mode ?? 'face-to-face';
            $doctor->profile_details = $validated['profile_details'] ?? $doctor->profile_details;

            if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
                $this->storeProfilePicture($doctor, $request->file('profile_picture'));
            }

            $doctor->save();
            DB::commit();
            return back()->with('success','Personal info saved.')->with('active_tab','personal');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('updatePersonal failed: '.$e->getMessage());
            return back()->withErrors(['error'=>'Failed: '.$e->getMessage()])->withInput()->with('active_tab','personal');
        }
    }

    /**
     * Update Professional tab
     */
    public function updateProfessional(Request $request, $id = null): RedirectResponse
{
    $doctor = $this->getEditableDoctor($id);

    $rules = [
        'speciality'      => ['nullable','string','max:255'],
        'degree'          => ['nullable','string','max:255'],
        'profile_details' => ['nullable','string'],
        'registration_no' => ['nullable','string','max:255'],
        'council'         => ['nullable','string','max:255'],
        'category_id'     => ['nullable','integer'],
        'status'          => ['nullable', Rule::in(['active','inactive'])],
        'consultation_mode'=> ['nullable', Rule::in(['online','offline','both','face-to-face'])],
        'website'         => ['nullable','url','max:255'],
        'facebook'        => ['nullable','url','max:255'],
        'instagram'       => ['nullable','url','max:255'],
        'clinic_id'       => ['nullable','integer', Rule::exists((new Client)->getTable(), 'id')],

        // newly added for Professional tab
        'experience_years' => ['nullable','integer','min:0','max:120'],
        'languages' => ['nullable'],
    ];

    $validated = $request->validate($rules);

    DB::beginTransaction();
    try {
        $doctor->speciality = $validated['speciality'] ?? $doctor->speciality;
        $doctor->degree = $validated['degree'] ?? $doctor->degree;
        $doctor->profile_details = $validated['profile_details'] ?? $doctor->profile_details;
        $doctor->registration_no = $validated['registration_no'] ?? $doctor->registration_no;
        $doctor->council = $validated['council'] ?? $doctor->council;
        $doctor->category_id = $validated['category_id'] ?? $doctor->category_id;
        $doctor->status = $validated['status'] ?? $doctor->status ?? 'active';
        $doctor->consultation_mode = $validated['consultation_mode'] ?? $doctor->consultation_mode ?? 'face-to-face';
        $doctor->website = $validated['website'] ?? $doctor->website;
        $doctor->facebook = $validated['facebook'] ?? $doctor->facebook;
        $doctor->instagram = $validated['instagram'] ?? $doctor->instagram;
        $doctor->clinic_id = $validated['clinic_id'] ?? $doctor->clinic_id;

        // persist experience_years if provided
        if (array_key_exists('experience_years', $validated)) {
            $doctor->experience_years = !is_null($validated['experience_years']) ? (int)$validated['experience_years'] : $doctor->experience_years;
        }

        // Normalize languages (string or array) and save
        if (array_key_exists('languages', $validated)) {
            $langsInput = $request->input('languages');
            if (is_array($langsInput)) {
                $langsNormalized = implode(', ', array_filter(array_map('trim', $langsInput)));
            } else {
                $langsNormalized = is_string($langsInput) ? trim($langsInput) : null;
            }
            if (!empty($langsNormalized)) {
                $doctor->languages = $langsNormalized;
            }
        }

        $doctor->save();
        DB::commit();
        return back()->with('success','Professional info saved.')->with('active_tab','professional');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('updateProfessional failed: '.$e->getMessage());
        return back()->withErrors(['error'=>'Failed: '.$e->getMessage()])->withInput()->with('active_tab','professional');
    }
}

    /**
     * Update Location tab
     */
   public function updateLocation(Request $request, $id = null): RedirectResponse
{
    $doctor = $this->getEditableDoctor($id);

    $validated = $request->validate([
        'country_id' => ['required','integer','exists:countries,id'],
        'state_id'   => ['required','integer','exists:states,id'],
        'district_id'=> ['nullable','integer','exists:districts,id'],
        'city_id'    => ['nullable','integer','exists:cities,id'],
        'other_city_name' => ['nullable','string','max:255'],
        'pincode_id' => ['nullable','integer','exists:pincodes,id'],
        'pincode'    => ['nullable','digits:6'],
        'address'    => ['nullable','string','max:2000'],
    ]);

    DB::beginTransaction();
    try {

        // resolve pincode
        $pincodeIdToSave = $validated['pincode_id'] ?? null;
        if (!$pincodeIdToSave && !empty($validated['pincode'])) {
            $local = Pincode::where('pincode', trim($validated['pincode']))->first();
            if ($local) $pincodeIdToSave = $local->id;
        }

        $doctor->country_id = $validated['country_id'];
        $doctor->state_id = $validated['state_id'];
        $doctor->district_id = $validated['district_id'] ?? null;

        // CITY LOGIC
        if (!empty($validated['other_city_name'])) {

            $cityName = trim($validated['other_city_name']);

            $city = City::whereRaw('LOWER(name) = ?', [strtolower($cityName)])
                ->where('district_id', $validated['district_id'] ?? null)
                ->first();

            if (!$city) {
                $city = City::create([
                    'name' => $cityName,
                    'district_id' => $validated['district_id'] ?? null,
                    'state_id' => $validated['state_id'],
                    'country_id' => $validated['country_id'],
                ]);
            }

            $doctor->city_id = $city->id;

        } else {
            $doctor->city_id = $validated['city_id'] ?? null;
        }

        $doctor->pincode_id = $pincodeIdToSave;
        $doctor->address = $validated['address'] ?? $doctor->address;

        $doctor->save();
        DB::commit();

        return back()->with('success','Location saved.')->with('active_tab','location');

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('updateLocation failed: '.$e->getMessage());
        return back()->withErrors(['error'=>'Failed'])->withInput()->with('active_tab','location');
    }
}


    /**
     * Update Education & Schedule tab.
     * Note: clinics detailed array should go through main update (but we still accept alternative_schedule array here).
     */
    public function updateEducationSchedule(Request $request, $id = null): RedirectResponse
    {
        $doctor = $this->getEditableDoctor($id);

        $pre = $request->only(['degrees', 'degrees_text', 'clinic_name', 'clinic_id', 'clinic_days', 'clinic_start_time', 'clinic_end_time', 'alternative_schedule']);
        $degreesArr = [];
        if (!empty($pre['degrees']) && is_array($pre['degrees'])) {
            $degreesArr = $pre['degrees'];
        } elseif (!empty($pre['degrees']) && is_string($pre['degrees'])) {
            $degreesArr = preg_split("/\r\n|\n|\r/", $pre['degrees']);
        } elseif (!empty($pre['degrees_text'])) {
            $degreesArr = preg_split("/\r\n|\n|\r/", $pre['degrees_text']);
        }
        $degreesArr = array_values(array_filter(array_map('trim',$degreesArr)));

        $request->merge(['degrees'=>$degreesArr]);

        $rules = [
            'degrees' => ['nullable','array'],
            'degrees.*' => ['string','max:255'],
            'clinic_name' => ['nullable','string','max:255'],
            'clinic_id'   => ['nullable','integer', Rule::exists((new Client)->getTable(), 'id')],
            'clinic_days' => ['nullable','array'],
            'clinic_days.*' => Rule::in(['all','monday','tuesday','wednesday','thursday','friday','saturday','sunday']),
            'clinic_start_time' => ['nullable','regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'clinic_end_time'   => ['nullable','regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'alternative_schedule' => ['nullable','array'],
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            if (!empty($validated['degrees'])) {
                $doctor->degrees = $validated['degrees'];
            }
            $doctor->clinic_name = $validated['clinic_name'] ?? $doctor->clinic_name;
            $doctor->clinic_id = $validated['clinic_id'] ?? $doctor->clinic_id;

            // NOTE: legacy clinic_days/start/end are validated but NOT stored on doctor record.
            // If you still send clinic_days/clinic_start_time/clinic_end_time from forms,
            // convert them into DoctorClinicScheduler rows in the main update flow instead.

            if (!empty($validated['alternative_schedule']) && is_array($validated['alternative_schedule'])) {
                $doctor->alternative_schedule = json_encode(array_values($validated['alternative_schedule']), JSON_UNESCAPED_UNICODE);
            }

            $doctor->save();
            DB::commit();
            return back()->with('success','Education & Schedule saved.')->with('active_tab','education_schedule');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('updateEducationSchedule failed: '.$e->getMessage());
            return back()->withErrors(['error'=>'Failed: '.$e->getMessage()])->withInput()->with('active_tab','education_schedule');
        }
    }

    /**
     * Helper: store profile picture
     */
    protected function storeProfilePicture(Doctor $doctor, $file): void
    {
        $ext = $file->getClientOriginalExtension();
        $filename = 'doctor_'.time().'_'.Str::random(8).'.'.$ext;
        $path = $file->storeAs('doctor',$filename,'public');

        if (!empty($doctor->profile_picture) && Storage::disk('public')->exists($doctor->profile_picture)) {
            Storage::disk('public')->delete($doctor->profile_picture);
        }
        $doctor->profile_picture = $path;
    }

    /**
     * Helper: get doctor editable by user
     */
    protected function getEditableDoctor($id = null): Doctor
    {
        $user = Auth::user();
        abort_unless($user,403);

        if ($id && $user->can('update', Doctor::class)) {
            return Doctor::findOrFail($id);
        }
        return Doctor::firstOrNew(['user_id'=>$user->id]);
    }

    /* ===== AJAX Helpers ===== */

    /**
     * Get states by country id
     */
    public function getStates($countryId): JsonResponse
    {
        $states = State::where('country_id',$countryId)->orderBy('name')->get(['id','name']);
        return response()->json($states);
    }

    /**
     * Get districts by state id
     */
    public function getDistricts($stateId): JsonResponse
    {
        $districts = District::where('state_id',$stateId)->orderBy('name')->get(['id','name']);
        return response()->json($districts);
    }

    /**
     * Get cities by district id
     */
    public function getCities($districtId): JsonResponse
    {
        $cities = City::where('district_id',$districtId)->orderBy('name')->get(['id','name']);
        return response()->json($cities);
    }

    /**
     * Pincode lookup endpoint (normalized payload)
     */
    public function pincodeLookup($pincode): JsonResponse
    {
        $pin = trim((string)$pincode);
        if ($pin === '' || !preg_match('/^\d{6}$/', $pin)) {
            return response()->json(['success' => false, 'message' => 'Invalid pincode format. Expect 6 digits.'], 422);
        }

        // try local DB first
        $local = Pincode::with(['country','state','district','city'])
            ->where('pincode', $pin)
            ->first();

            if ($local) {
    $payload = [
        'id' => $local->id,
        'pincode' => $local->pincode,

        'country' => optional($local->country)->name,
        'state' => optional($local->state)->name,
        'district' => optional($local->district)->name,
        'city' => optional($local->city)->name,

        'country_id' => $local->country_id,
        'state_id' => $local->state_id,
        'district_id' => $local->district_id,
        'city_id' => $local->city_id,
    ];

    return response()->json([
        'success' => true,
        'source' => 'local',
        'payload' => $payload
    ]);
}


        // if ($local) {
        //     $payload = method_exists($local, 'toPayloadArray') ? $local->toPayloadArray() : $local->toArray();
        //     return response()->json(['success' => true, 'source' => 'local', 'payload' => $payload], 200);
        // }

        // cache
        $cacheKey = 'pincode_api_' . $pin;
        if ($cached = Cache::get($cacheKey)) {
            return response()->json(['success' => true, 'source' => 'api_cache', 'payload' => $cached], 200);
        }

        // fallback to India Post API
        $apiResult = $this->fetchPincodeFromIndiaPost($pin);
        if (! $apiResult['success'] || empty($apiResult['payload'])) {
            return response()->json(['success' => false, 'message' => 'Not found'], 404);
        }

        $payload = $apiResult['payload'];

        // map names -> ids (defensive)
        $countryId  = !empty($payload['country'])
                        ? Country::whereRaw('LOWER(name) LIKE ?', ['%'.strtolower(trim($payload['country'])).'%'])->value('id')
                        : null;
        $stateId    = !empty($payload['state'])
                        ? State::whereRaw('LOWER(name) LIKE ?', ['%'.strtolower(trim($payload['state'])).'%'])->value('id')
                        : null;
        $districtId = !empty($payload['district'])
                        ? District::whereRaw('LOWER(name) LIKE ?', ['%'.strtolower(trim($payload['district'])).'%'])->value('id')
                        : null;
        $officeName = $payload['office_name'] ?? ($payload['city'] ?? null);
        $cityId     = $officeName ? City::whereRaw('LOWER(name) LIKE ?', ['%'.strtolower(trim($officeName)).'%'])->value('id') : null;

        $payload['country_id']  = $countryId;
        $payload['state_id']    = $stateId;
        $payload['district_id'] = $districtId;
        $payload['city_id']     = $cityId;

        // persist pincode record (defensive)
        try {
            $p = new Pincode();
            $p->pincode     = $pin;
            $p->country_id  = $countryId;
            $p->state_id    = $stateId;
            $p->district_id = $districtId;
            $p->city_id     = $cityId;
            $p->office_name = $officeName;
            $p->raw_json    = is_string($payload['raw'] ?? null) ? $payload['raw'] : json_encode($payload['raw'] ?? $payload);
            $p->save();
            $payload['id'] = $p->id;
        } catch (\Throwable $e) {
            Log::warning("Failed to create pincode row for {$pin}: ".$e->getMessage());
        }

        Cache::put($cacheKey, $payload, now()->addDays(7));

        return response()->json(['success' => true, 'source' => 'api', 'payload' => $payload], 200);
    }

    /**
     * Get clinics by category id (AJAX)
     */
    public function clinicsByCategory($categoryId): JsonResponse
    {
        $cat = (int)$categoryId;
        $clinics = Client::query()
            ->where(function($q) use ($cat){
                $q->orWhereRaw("JSON_CONTAINS(category_id,'\"{$cat}\"')")
                  ->orWhereRaw("FIND_IN_SET(?,category_id)",[$cat])
                  ->orWhere('category_id',$cat);
            })
            ->orderBy('name')
            ->get(['id','name']);
        return response()->json($clinics);
    }

    /**
     * India Post helper
     */
    protected function fetchPincodeFromIndiaPost(string $pin): array
    {
        try {
            $res = Http::timeout(6)->retry(2, 100)->get("https://api.postalpincode.in/pincode/{$pin}");
            if (! $res->ok()) {
                Log::warning("India Post API non-ok for {$pin}: ".$res->status());
                return ['success' => false, 'payload' => null];
            }

            $json = $res->json();
            if (empty($json) || !isset($json[0]['PostOffice']) || empty($json[0]['PostOffice'])) {
                return ['success' => false, 'payload' => null];
            }

            $po = $json[0]['PostOffice'][0] ?? null;
            if (! $po) return ['success'=>false,'payload'=>null];

            $payload = [
                'pincode'     => $pin,
                'country'     => $po['Country'] ?? 'India',
                'state'       => $po['State'] ?? null,
                'district'    => $po['District'] ?? null,
                'office_name' => $po['Name'] ?? null,
                'city'        => $po['Name'] ?? null,
                'raw'         => $json,
            ];

            return ['success' => true, 'payload' => $payload];
        } catch (\Throwable $e) {
            Log::warning('India Post API error for pin ' . $pin . ': ' . $e->getMessage());
            return ['success' => false, 'payload' => null];
        }
    }

    /* ===== Clinic Schedule CRUD Methods (unchanged) ===== */

    public function clinicSchedulesIndex(Request $request, $doctorId): JsonResponse
    {
        $doctor = $this->getEditableDoctor($doctorId);
        $clinicId = $request->query('clinic_id');

        $schedules = DoctorClinicScheduler::where('doctor_profile_id', $doctorId)
            ->when($clinicId, function ($query) use ($clinicId) {
                return $query->where('clinic_id', $clinicId);
            })
            ->get();

        return response()->json($schedules);
    }

    public function clinicSchedulesStore(Request $request, $doctorId): JsonResponse
    {
        $doctor = $this->getEditableDoctor($doctorId);

        $validated = $request->validate([
            'clinic_id' => ['nullable', 'integer', 'exists:clients,id'],
            'days' => ['required', 'array'],
            'days.*' => ['string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'start_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'end_time' => ['required', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'alternative_text' => ['nullable', 'string', 'max:255'],
            'clinic_address' => ['nullable','string','max:2000'],
        ]);

        $schedule = new DoctorClinicScheduler($validated);
        $schedule->doctor_profile_id = $doctorId;
        $schedule->save();

        return response()->json($schedule, 201);
    }

    public function clinicSchedulesUpdate(Request $request, $doctorId, $id): JsonResponse
    {
        $doctor = $this->getEditableDoctor($doctorId);

        $schedule = DoctorClinicScheduler::where('doctor_profile_id', $doctorId)
            ->findOrFail($id);

        $validated = $request->validate([
            'clinic_id' => ['nullable', 'integer', 'exists:clients,id'],
            'days' => ['sometimes', 'array'],
            'days.*' => ['string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'start_time' => ['sometimes', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'end_time' => ['sometimes', 'regex:/^\d{2}:\d{2}(:\d{2})?$/'],
            'alternative_text' => ['nullable', 'string', 'max:255'],
            'clinic_address' => ['nullable','string','max:2000'],
        ]);

        $schedule->update($validated);

        return response()->json($schedule);
    }

    public function clinicSchedulesDestroy(Request $request, $doctorId, $id): JsonResponse
    {
        $doctor = $this->getEditableDoctor($doctorId);

        $schedule = DoctorClinicScheduler::where('doctor_profile_id', $doctorId)
            ->findOrFail($id);

        $schedule->delete();

        return response()->json(null, 204);
    }
    
    
    
    
    
    
    /**
 * Show Change Password form
 */
/**
 * Show Change Password form
 */
public function editPassword(Request $request, $id = null): View
{
    // Ensure the doctor exists for this user (authorization)
    $this->getEditableDoctor($id);

    return view('Doctor.Profile.change-password');
}

/**
 * Handle password update
 */
public function updatePassword(Request $request, $id = null): RedirectResponse
{
    $user = Auth::user();
    abort_unless($user, 403);

    $rules = [
        'current_password' => ['required', 'string'],
        'new_password' => [
            'required',
            'string',
            'confirmed', // This automatically checks for new_password_confirmation
            Password::min(8)->mixedCase()->numbers()->symbols(),
            'different:current_password',
        ],
        // Add this explicit rule so "Please confirm your new password" appears when missing
        'new_password_confirmation' => ['required', 'string'],
    ];

    $messages = [
        'current_password.required' => 'Please enter your current password.',
        'new_password.required' => 'Please enter a new password.',
        'new_password.different' => 'New password must be different from your current password.',
        'new_password.confirmed' => 'Passwords do not match.',
        'new_password_confirmation.required' => 'Please confirm your new password.', // ✅ Your desired message
    ];

    // Validate inputs
    $validated = $request->validate($rules, $messages);

    // Check current password manually (for fallback)
    try {
        $request->validate(['current_password' => ['current_password']]);
    } catch (\Exception $e) {
        if (! Hash::check($request->input('current_password'), $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The provided current password is incorrect.'])
                ->withInput();
        }
    }

    // Update the password
    $user->password = Hash::make($validated['new_password']);
    $user->setRememberToken(Str::random(60));
    $user->save();

    try {
        Auth::logoutOtherDevices($validated['new_password']);
    } catch (\Throwable $e) {
        \Log::warning('logoutOtherDevices failed: '.$e->getMessage());
    }

    $request->session()->regenerate();

    return redirect()
        ->route('doctor.profile.password.edit')
        ->with('success', 'Password updated successfully.');
}

              
public function removePhoto(Request $request, $doctorId)
{
    $user = Auth::user();
    abort_unless($user, 403);

    // ensure user may update this doctor
    $doctor = $this->getEditableDoctor($doctorId);

    if (! $doctor || empty($doctor->profile_picture)) {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'No photo to remove'], 404);
        }
        return back()->withErrors(['error' => 'No photo to remove.']);
    }

    try {
        // delete file from storage (public disk)
        if (Storage::disk('public')->exists($doctor->profile_picture)) {
            Storage::disk('public')->delete($doctor->profile_picture);
        }

        // clear DB column and save
        $doctor->profile_picture = null;
        $doctor->save();

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Photo removed'], 200);
        }

        return redirect()->back()->with('success', 'Photo removed.');
    } catch (\Throwable $e) {
        \Log::error('removePhoto failed for doctor '.$doctor->id.': '.$e->getMessage());
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Failed to remove photo'], 500);
        }
        return back()->withErrors(['error' => 'Failed to remove photo.']);
    }
}
    
    
    
}
