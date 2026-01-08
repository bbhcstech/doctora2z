<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
//use App\Http\Controllers\Frontend\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Country;
use App\Models\State;
use App\Models\District;
use App\Models\City;
use App\Models\Pincode;
use App\Models\Rating;
use App\Models\BannerImage;
use App\Models\Doctor; // doctor_profiles model
use App\Models\Category;
use App\Models\Hospital;
use App\Models\Medicashop;
use App\Models\AboutUs;
use App\Models\ContactUs;
use App\Models\Page;
use App\Models\Advertisement;
use Illuminate\Support\Facades\Log;



class FrontendController extends Controller
{
    // ---------- Home / index ----------
    public function index(Request $request)
    {
     Log::debug('function index: ' . now()->toDateTimeString() );
        $authId = null;
        if ($request->has('auth_id')) {
            try {
                $authId = decrypt($request->query('auth_id'));
            } catch (\Throwable $e) {
                $authId = null;
            }
        }

        return view('frontend.index', [
            'bannerImages'   => BannerImage::all(),
            'countries'      => Country::all(),
            'states'         => State::all(),
            'districts'      => District::all(),
            'cities'         => City::all(),
            'pages'          => Page::all(),
            'contactus'      => ContactUs::first(),
            'totalClinics'   => Client::count(),
            'totalDoctors'   => Doctor::count(),
            'hospital'       => Hospital::all(),
            'medicashop'     => Medicashop::all(),
            'authId'         => $authId,
            'advertisements' => Advertisement::get(),
        ]);
    }

    // ---------- General search / suggestions / helper endpoints ----------
    public function generalSearch()
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        return view('frontend.general_search', [
            'bannerImages' => BannerImage::all(),
            'countries'    => Country::orderBy('name')->get(),
            'states'       => State::orderBy('name')->get(),
            'districts'    => District::orderBy('name')->get(),
            'cities'       => City::orderBy('name')->get(),
        ]);
    }

    public function getClinicsByCity($city_name)
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        $cat_clinic = Client::where('clinics.city_name', $city_name)
            ->join('category', 'category.id', '=', 'clinics.category_id')
            ->select('category.*')->get();

        $doc_clinic = Doctor::whereHas('clinic', fn($q) => $q->where('city_name', $city_name))
            ->pluck('category_id');

        $cat_doc = Category::whereIn('id', $doc_clinic)->get();

        $totalDoctors = Doctor::select('category_id', DB::raw('COUNT(*) as doctor_count'))
            ->whereIn('category_id', $doc_clinic)
            ->groupBy('category_id')
            ->pluck('doctor_count', 'category_id');

        foreach ($cat_doc as $category) {
            $category->doctor_count = $totalDoctors[$category->id] ?? 0;
        }

        return response()->json([
            'cat_clinic' => $cat_clinic,
            'cat_doc'    => $cat_doc,
            'doctors_count' => $totalDoctors,
            'hospitals'  => Hospital::where('city_name', $city_name)->get(),
            'medicashops'=> Medicashop::where('city_name', $city_name)->get(),
        ]);
    }

    public function getClinicsByState($state_name)
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        $cat_clinic = Client::where('clinics.state_name', $state_name)
            ->join('category', 'category.id', '=', 'clinics.category_id')
            ->select('category.*')->get();

        $doc_clinic = Doctor::whereHas('clinic', fn($q) => $q->where('state_name', $state_name))
            ->pluck('category_id');

        $cat_doc = Category::whereIn('id', $doc_clinic)->get();

        $totalDoctors = Doctor::select('category_id', DB::raw('COUNT(*) as doctor_count'))
            ->whereIn('category_id', $doc_clinic)
            ->groupBy('category_id')
            ->pluck('doctor_count', 'category_id');

        foreach ($cat_doc as $category) {
            $category->doctor_count = $totalDoctors[$category->id] ?? 0;
        }

        return response()->json([
            'cat_clinic' => $cat_clinic,
            'cat_doc'    => $cat_doc,
            'doctors_count' => $totalDoctors,
            'hospitals'  => Hospital::where('state_name', $state_name)->get(),
            'medicashops'=> Medicashop::where('state_name', $state_name)->get(),
        ]);
    }



//     public function getTopCategories()
// {
//     $categories = Category::take(100)->get();
//     $categoryIds = $categories->pluck('id');

//     // $totalDoctors = Doctor::where('status', 'active') // 🔥 IMPORTANT
//     //     ->whereIn('category_id', $categoryIds)
//     //     ->select('category_id', DB::raw('COUNT(*) as doctor_count'))
//     //     ->groupBy('category_id')
//     //     ->pluck('doctor_count', 'category_id');

//     $totalDoctors = Doctor::where('status', 'active')
//     ->select('category_id', DB::raw('COUNT(*) as doctor_count'))
//     ->whereIn('category_id', $categoryIds)
//     ->groupBy('category_id')
//     ->pluck('doctor_count', 'category_id');


//     return response()->json([
//         'cat_doc' => $categories->map(function ($c) use ($totalDoctors) {
//             $c->doctor_count = $totalDoctors[$c->id] ?? 0;
//             return $c;
//         })
//     ]);
// }


    public function getTopCategories()
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        $categories = Category::take(100)->get();
        $categoryIds = $categories->pluck('id')->toArray();

        $totalDoctors = Doctor::select('category_id', DB::raw('COUNT(*) as doctor_count'))
            ->whereIn('category_id', $categoryIds)
            ->groupBy('category_id')
            ->pluck('doctor_count', 'category_id');

        return response()->json([
            'cat_doc' => $categories->map(function ($c) use ($totalDoctors) {
                $c->doctor_count = $totalDoctors->get($c->id, 0);
                return $c;
            })
        ]);
    }

    /**
     * Doctor-only search: queries doctor_profiles (Doctor model) only.
     */
public function search(Request $request)
{
     Log::debug('function about: ' . now()->toDateTimeString() );
    $q = trim($request->input('query', $request->input('q', '')));
    $filter = $request->input('filter_type');

    // -------- doctors ----------
    $doctors = collect();
    if ($filter !== 'clinic') {
        $dq = \App\Models\Doctor::query()
            ->select('doctor_profiles.*')
            ->with([
                'category:id,name',
                'clinic:id,name',
                'city:id,name',
                'state:id,name',
                'country:id,name',
            ]);

        if ($q !== '') {
            // Skip domain-like searches (like DOCTORA22)
            if (preg_match('/doctora?2?z?/i', $q)) {
                // This is likely searching for the website name, not actual doctors
                // Return empty or search for "doctor" instead
                $dq->where('name', 'LIKE', "%doctor%");
            } else {
                $dq->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('speciality', 'like', "%{$q}%")
                        ->orWhere('degree', 'like', "%{$q}%")
                        ->orWhere('phone_number', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('address', 'like', "%{$q}%")
                        ->orWhereHas('category', fn($c) => $c->where('name','like',"%{$q}%"))
                        ->orWhereHas('clinic', fn($c) => $c->where('name','like',"%{$q}%"));
                });
            }
        }

        foreach (['country_id','state_id','district_id','city_id','category_id'] as $fk) {
            if ($request->filled($fk)) $dq->where($fk, $request->input($fk));
        }

        $doctors = $dq->orderBy('name')->limit(100)->get()->map(function ($d) {
            // visiting time
            $visit = '-';
            if (!empty($d->clinic_start_time) && !empty($d->clinic_end_time)) {
                $visit = \Carbon\Carbon::parse($d->clinic_start_time)->format('H:i')
                       . ' - ' .
                       \Carbon\Carbon::parse($d->clinic_end_time)->format('H:i');
            }

            // image
            $raw = $d->profile_picture;
            if ($raw) {
                $raw = ltrim($raw, '/');
                $image = asset($raw);
            } else {
                $image = asset('admin/assets/adminimg/demo_doctor_image.jpeg');
            }

            return [
                'type'          => 'doctor',
                'id'            => $d->id,
                'name'          => $d->name,
                'speciality'    => $d->speciality,
                'degree'        => $d->degree,
                'visiting_time' => $visit,
                'phone_number'  => $d->phone_number,
                'address'       => $d->address,
                'city_name'     => $d->city?->name,
                'state_name'    => $d->state?->name,
                'country_name'  => $d->country?->name,
                'profile_picture'=> $image,
                'updated_at'    => $d->updated_at,
            ];
        });
    }

    // -------- clinics (optional) ----------
    $clinics = collect();
    if ($filter === 'clinic') {
        $cq = \App\Models\Client::query()
            ->with(['city:id,name','state:id,name']);

        if ($q !== '') {
            // Skip domain-like searches
            if (preg_match('/doctora?2?z?/i', $q)) {
                $cq->where('name', 'LIKE', "%clinic%");
            } else {
                $cq->where(function ($sub) use ($q) {
                    $sub->where('name','like',"%{$q}%")
                        ->orWhere('city_name','like',"%{$q}%")
                        ->orWhere('phone_number','like',"%{$q}%")
                        ->orWhere('address','like',"%{$q}%");
                });
            }
        }
        
        foreach (['country_id','state_id','district_id','city_id'] as $fk) {
            if ($request->filled($fk)) $cq->where($fk, $request->input($fk));
        }

        $clinics = $cq->limit(100)->get()->map(function($c){
            return [
                'type'          => 'clinic',
                'id'            => $c->id,
                'name'          => $c->name,
                'address'       => $c->address,
                'phone_number'  => $c->phone_number,
                'city_name'     => $c->city?->name ?? $c->city_name,
                'state_name'    => $c->state?->name ?? $c->state_name,
                'country_name'  => $c->country_name ?? null,
                'updated_at'    => $c->updated_at,
            ];
        });
    }

    $results = $doctors->concat($clinics)->values();

    // If no results and search is similar to "doctora2z", show all doctors
    if ($results->isEmpty() && preg_match('/doctora?2?z?/i', $q)) {
        $allDoctors = \App\Models\Doctor::with(['category', 'city', 'state'])
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(function ($d) {
                return [
                    'type'          => 'doctor',
                    'id'            => $d->id,
                    'name'          => $d->name,
                    'speciality'    => $d->speciality,
                    'degree'        => $d->degree,
                    'phone_number'  => $d->phone_number,
                    'city_name'     => $d->city?->name,
                    'state_name'    => $d->state?->name,
                ];
            });
        
        $results = $allDoctors;
    }

    // ads (unchanged)
    $advertisements = \App\Models\Advertisement::query();
    foreach (['country_id','state_id','district_id','city_id'] as $fk) {
        if ($request->filled($fk)) $advertisements->where($fk, $request->input($fk));
    }

    return view('frontend.search_results', [
        'query'          => $q,
        'results'        => $results,
        'advertisements' => $advertisements->get(),
    ]);
}

    public function suggestions(Request $request)
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        $q = trim($request->input('query', ''));
        $filter = $request->input('filter_type', null);
        $max = 10;

        $suggestions = collect();

        if ($filter === 'clinic') {
            if ($q !== '') {
                $clinics = Client::where('name', 'LIKE', "%{$q}%")
                    ->orWhere('city_name', 'LIKE', "%{$q}%")
                    ->limit($max)
                    ->pluck('name')
                    ->toArray();
                $suggestions = collect($clinics);
            } else {
                $suggestions = Client::orderBy('name')->limit($max)->pluck('name');
            }
        } elseif ($filter === 'doctor') {
            if ($q !== '') {
                $docs = Doctor::where('name', 'LIKE', "%{$q}%")
                    ->orWhere('speciality', 'LIKE', "%{$q}%")
                    ->orWhere('degree', 'LIKE', "%{$q}%")
                    ->limit($max)
                    ->pluck('name')
                    ->toArray();
                $suggestions = collect($docs);
            } else {
                $suggestions = Doctor::orderBy('name')->limit($max)->pluck('name');
            }
        } else {
            if ($q !== '') {
                $docNames = Doctor::where('name', 'LIKE', "%{$q}%")
                    ->orWhere('speciality', 'LIKE', "%{$q}%")
                    ->limit((int)($max * 0.6))
                    ->pluck('name')
                    ->toArray();

                $clinicNames = Client::where('name', 'LIKE', "%{$q}%")
                    ->orWhere('city_name', 'LIKE', "%{$q}%")
                    ->limit((int)($max * 0.4))
                    ->pluck('name')
                    ->toArray();

                $suggestions = collect(array_merge($docNames, $clinicNames));
            } else {
                $suggestions = Doctor::orderBy('name')->limit($max)->pluck('name');
            }
        }

        $suggestions = $suggestions->unique()->values()->take($max)->all();

        return response()->json(['suggestions' => $suggestions]);
    }

    // clinic details
    public function clinicDetails($id)
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        return view('frontend.clinic-details', ['clinic' => Client::with('doctors')->findOrFail($id)]);
    }

    // category details
public function categoryDetails($slug)
{
     Log::debug('function about: ' . now()->toDateTimeString() );
    $category = is_numeric($slug)
        ? Category::findOrFail((int) $slug)
        : Category::where('slug', $slug)->firstOrFail();

    $doctors = Doctor::with(['category', 'country', 'state', 'district', 'city'])
        ->where('category_id', $category->id)
        ->select(
            'id',
            'name',
            'degree',
            'speciality',
            'address',
            'category_id',
            'country_id',
            'state_id',
            'district_id',
            'city_id',
            'profile_picture',
            'phone_number',
            'whatsapp'
        )
        ->get();

    return view('frontend.categoryDetails', [
        'category' => $category,
        'bannerImages' => BannerImage::all(),
        'doctors' => $doctors,
        'advertisements' => Advertisement::get(),
    ]);
}





public function about()
{
     Log::debug('function about: ' . now()->toDateTimeString() );

    // ✅ Real-time counts directly from the database
    $doctorCount = DB::table('doctor_profiles')->count();   // total doctors
    $hospitalCount = DB::table('hospitals')->count();       // total hospitals
    $specializationCount = DB::table('category')->count();  // note: your table is singular
    $clinicCount = DB::table('clinics')->count();           // total clinics

    return view('frontend.about', compact(
        'about',
        'doctorCount',
        'hospitalCount',
        'specializationCount',
        'clinicCount'
    ));
}


    public function contact()
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        return view('frontend.contact', ['contactus' => ContactUs::first()]);
    }

    public function terms()
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        return view('frontend.terms');
    }

    public function privacy()
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        return view('frontend.privacy');
    }

    public function listclinic()
    {
     Log::debug('function about: ' . now()->toDateTimeString() );
        return view('frontend.clinic-listing', [
            'cities' => City::all(),
            'district' => District::all(),
            'states' => State::all(),
            'countries' => Country::all(),
            'bannerImages' => BannerImage::all()
        ]);
    }

    public function listclinicstore(Request $request)
    {
         Log::info('function listclinicstore: ' . now()->toDateTimeString() . ' - IP: ' . $request->ip() . ' - URL: ' . $request->fullUrl());
        return back()->with('success', 'Clinic saved (placeholder).');
    }




public function listdoctor()
{
    Log::info('listdoctor loaded at ' . now());

    $countries = cache()->remember('countries_list', 3600, function () {
        return Country::select('id','name')->orderBy('name')->get();
    });

    $categories = cache()->remember('categories_list', 3600, function () {
        return Category::select('id','name')->orderBy('name')->get();
    });

    // ✅ FIXED: Use actual database column names
    $states = State::select('id', 'name', 'country_id')  // 'id' and 'name' (not 'id_state' and 'state')
        // Remove ->where('is_active', 1) কারণ column নেই
        ->orderBy('name')  // Order by 'name' column
        ->get()
        ->map(function($state) {
            // Add compatibility fields for your existing blade
            $state->id_state = $state->id;
            $state->state = $state->name;
            return $state;
        });

    return view('frontend.doctor-listing', [
        'countries'  => $countries,
        'categories' => $categories,
        'state'      => $states,   // ✅ Now compatible
        'district'   => collect(),
        'city'       => collect(),
    ]);
}
//     public function listdoctor()
// {
//     Log::info('listdoctor loaded at ' . now());

//     // 🔹 Cache static data (1 hour)
//     $countries = cache()->remember('countries_list', 3600, function () {
//         return Country::select('id','name')->orderBy('name')->get();
//     });

//     $categories = cache()->remember('categories_list', 3600, function () {
//         return Category::select('id','name')->orderBy('name')->get();
//     });

//     // 🔹 Clinics (LIMITED + OPTIMIZED)
//     $clinics = Client::select(
//             'id','name','country_id','state_id','district_id','city_id'
//         )
//         ->with([
//             'country:id,name',
//             'state:id,name',
//             'district:id,name',
//             'city:id,name'
//         ])
//         ->orderBy('name')
//         ->limit(200) // 🔥 VERY IMPORTANT
//         ->get();

//     return view('frontend.doctor-listing', [
//         'countries'  => $countries,
//         'categories' => $categories,
//         'clinics'    => $clinics,

//         // ❌ DO NOT LOAD THESE ON FIRST PAGE
//         'state'      => collect(),
//         'district'   => collect(),
//         'city'       => collect(),
//         'pincodes'   => collect(),
//         'hospitals'  => collect(),
//         'medicas'    => collect(),

//         // 🔹 JSON ONLY FOR REQUIRED FIELDS
//         'clinicsJson' => $clinics->map(fn($c) => [
//             'id'          => $c->id,
//             'name'        => $c->name,
//             'country_id'  => $c->country_id,
//             'state_id'    => $c->state_id,
//             'district_id' => $c->district_id,
//             'city_id'     => $c->city_id,
//         ])->toJson()
//     ]);
// }

    // show form page
    // public function listdoctor()
    // {
    //  Log::info('function about: ' . now()->toDateTimeString() );
    //     $clinics = Client::with(['country','state','district','city'])->orderBy('name')->get();

    //     return view('frontend.doctor-listing', [
    //         'countries' => Country::orderBy('name')->get(),
    //         'state'     => State::orderBy('name')->get(),
    //         'district'  => District::orderBy('name')->get(),
    //         'city'      => City::orderBy('name')->get(),
    //         'pincodes'  => Pincode::orderBy('pincode')->get(),
    //         'clinics'   => $clinics,
    //         'hospitals' => Hospital::with(['country','state','district','city'])->orderBy('name')->get(),
    //         'medicas'   => Medicashop::with(['country','state','district','city'])->orderBy('name')->get(),
    //         'categories'  => Category::orderBy('name')->get(),
    //         'clinicsJson' => $clinics->map(fn($c)=>[
    //             'id'=>$c->id,'name'=>$c->name,'country_id'=>$c->country_id,'state_id'=>$c->state_id,
    //             'district_id'=>$c->district_id,'city_id'=>$c->city_id
    //         ])->values()->toJson()
    //     ]);
    // }

    /**
     * Create doctor profile.
     * If AJAX -> return JSON {success:true, id}
     * If normal POST -> redirect to success page (doctor.success) and flash masked email/id
     */
public function listdoctorstore(Request $request)
{
    if ($request->input('city_id') === 'others') {
        $request->merge(['city_id' => null]);
    }

    $emailInput = (string) $request->input('email', '');
    $normalizedEmail = strtolower(trim($emailInput));
    $request->merge(['email' => $normalizedEmail]);

    $rules = [
        'name' => 'required|string|max:255',
        'phone_number' => [
            'required',
            'string',
            'regex:/^[0-9\-\s\(\)\+]*$/',
            function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D/', '', $value);
                if (strlen($digits) !== 10) {
                    $fail('Contact Number 1 must contain exactly 10 digits.');
                }
            }
        ],
        'contact_number_2' => [
            'nullable',
             'string',
            'regex:/^\+?[0-9\s\-\(\)]*$/',
            function ($attribute, $value, $fail) {
                $digits = preg_replace('/\D/', '', $value);
                if (strlen($digits) > 10) {
                    $fail('Contact number 2 must not contain more than 10 digits.');
                }
            }
        ],
        'degree' => 'required|string|max:255',
        'country_id' => 'required|integer|exists:countries,id',
        'state_id' => 'required|integer|exists:states,id',
        'district_id' => 'required|integer|exists:districts,id',
        'city_id' => 'nullable|integer|exists:cities,id',
        'city_other' => 'nullable|string|max:255',
        'pincode_id' => 'nullable|integer|exists:pincodes,id',
        'pincode' => 'nullable|digits:6',
        'category_id' => 'required|integer|exists:category,id',
        'clinic_id' => 'nullable|integer|exists:clients,id',
        'email' => [
            'required',
            'email:rfc,dns',
            'max:255',
            Rule::unique((new Doctor)->getTable(), 'email'),
        ],
        'profile_picture' => 'nullable|mimes:jpg,jpeg,png|mimetypes:image/jpeg,image/png|max:4096',
    ];

    $validator = Validator::make($request->all(), $rules);
    $validator->after(function ($v) use ($request) {
        if (empty($request->input('city_id')) && trim($request->input('city_other', '')) === '') {
            $v->errors()->add('city', 'Please select an area or enter an area name.');
        }
    });
    $validated = $validator->validate();

    $existingUser = User::where('email', $normalizedEmail)->first();
    if ($existingUser) {
        $existingProfile = Doctor::where('user_id', $existingUser->id)
            ->orWhere('email', $normalizedEmail)
            ->first();

        if ($existingProfile) {
            return back()->withInput()->withErrors([
                'email' => 'A doctor profile already exists for this email. Please use a different email or log in.',
            ]);
        }
    }

    DB::beginTransaction();
    try {
        // 1) upload profile picture (unchanged)
        $profilePictureRelativePath = null;
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                throw new \RuntimeException('Unsupported image type. Only JPG and PNG allowed.');
            }
            $safeName = 'doctor_' . time() . '_' . Str::random(8) . '.' . $ext;
            $path = $file->storeAs('doctor', $safeName, 'public');
            $profilePictureRelativePath = 'storage/' . $path;
        }

        // 2) Resolve pincode (existing logic), but don't decide flags yet
        $pincodeIdToSave = null;
        $manualPincode = null;
        $isPincodeUnknown = 0;
        $locationSource = 'manual'; // default until proven otherwise

        if ($request->filled('pincode_id')) {
            $pincodeIdToSave = (int) $request->pincode_id;
            $locationSource = 'auto';
        } elseif ($request->filled('pincode')) {
            $pin = trim($request->pincode);
            $local = Pincode::where('pincode', $pin)->first();
            if ($local) {
                $pincodeIdToSave = $local->id;
                $locationSource = 'auto';
            } else {
                // attempt IndiaPost API (optional) — keep result if success
                $apiResult = $this->fetchPincodeFromIndiaPost($pin);
                if (!empty($apiResult['success']) && !empty($apiResult['payload'])) {
                    $payload = $apiResult['payload'];
                    $state = State::whereRaw('LOWER(name)=?', [strtolower($payload['state'] ?? '')])->first();
                    $district = District::whereRaw('LOWER(name)=?', [strtolower($payload['district'] ?? '')])->first();
                    $city = City::whereRaw('LOWER(name)=?', [strtolower($payload['office_name'] ?? '')])->first();
                    $countryId = Country::whereRaw('LOWER(name) LIKE ?', ['%india%'])->value('id');

                    $newPin = Pincode::create([
                        'pincode' => $pin,
                        'country_id' => $countryId,
                        'state_id' => $state->id ?? null,
                        'district_id' => $district->id ?? null,
                        'city_id' => $city->id ?? null,
                        'office_name' => $payload['office_name'] ?? null,
                        'raw_json' => json_encode($payload),
                    ]);
                    $pincodeIdToSave = $newPin->id ?? null;
                    if ($pincodeIdToSave) $locationSource = 'auto';
                } else {
                    // unresolved pincode -> mark manual
                    $manualPincode = $pin;
                    $isPincodeUnknown = 1;
                    $locationSource = 'manual';
                }
            }
        }

        // 3) Resolve city (create if city_other)
        $resolvedCityId = null;
        $createdCityIsManual = false;
        if ($request->filled('city_id')) {
            $resolvedCityId = (int) $request->city_id;
        } elseif ($request->filled('city_other')) {
            $cityName = trim($request->city_other);
            $city = City::whereRaw('LOWER(name) = ?', [strtolower($cityName)])->first();

            if (!$city) {
                $slugBase = Str::slug($cityName) ?: 'city-' . time();
                $slug = $slugBase;
                $i = 1;
                while (City::where('slug', $slug)->exists()) {
                    $slug = $slugBase . '-' . $i++;
                }

                $city = City::create([
                    'name' => $cityName,
                    'slug' => $slug,
                    'country_id' => $request->country_id,
                    'state_id' => $request->state_id,
                    'district_id' => $request->district_id,
                ]);
                $createdCityIsManual = true;
            }

            $resolvedCityId = $city->id ?? null;
        }

        if (!$resolvedCityId) {
            throw new \RuntimeException('Unable to resolve a valid city. Please select or enter an area.');
        }

        $request->merge(['city_id' => $resolvedCityId]);

        // 3b) If we don't yet have pincode but have a city, try reverse logic
        if (empty($pincodeIdToSave) && empty($manualPincode)) {
            $cityPincodeRows = Pincode::where('city_id', $resolvedCityId)->pluck('id','pincode');
            $count = $cityPincodeRows->count();
            if ($count === 1) {
                // exactly one pincode row mapped to city -> pick it and mark reverse
                $pincodeIdToSave = $cityPincodeRows->first();
                $locationSource = $locationSource === 'auto' ? 'auto' : 'reverse';
            } elseif ($count > 1) {
                // multiple: frontend should have asked user to pick a specific pincode_id.
                // If frontend did not, we just mark reverse and leave pincode_id null so user can set.
                $locationSource = $locationSource === 'auto' ? 'auto' : 'reverse';
            } else {
                // no pincode rows for city -> mark manual if not already resolved
                if (empty($pincodeIdToSave)) {
                    $isPincodeUnknown = 1;
                    $locationSource = 'manual';
                }
            }
        }

        // If a city was newly created via city_other, treat as manual unless pincode resolved via API earlier
        if ($createdCityIsManual && $locationSource !== 'auto') {
            $isPincodeUnknown = 1;
            $locationSource = 'manual';
        }

        // 4) Resolve clinic (unchanged)
        $clinicIdToSave = null;
        if ($request->filled('clinic_id') && Client::where('id', $request->clinic_id)->exists()) {
            $clinicIdToSave = (int) $request->clinic_id;
        } else {
            $foundClinic = Client::where('city_id', $resolvedCityId)->first()
                ?? Client::where('state_id', $request->state_id)->first();

            if ($foundClinic) {
                $clinicIdToSave = $foundClinic->id;
            } else {
                $cityName = City::find($resolvedCityId)?->name ?? 'General';
                $slug = Str::slug('unassigned-clinic-' . $cityName);

                $clinic = Client::firstOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => 'Unassigned Clinic - ' . $cityName,
                        'city_id' => $resolvedCityId,
                        'state_id' => $request->state_id,
                        'district_id' => $request->district_id,
                        'country_id' => $request->country_id,
                        'category_id' => $request->category_id,
                        'user_id' => 1,
                        'is_placeholder' => 1,
                    ]
                );
                $clinicIdToSave = $clinic->id;
            }
        }

        // 5) Create Doctor (now include the new location flags)
        $doctor = Doctor::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'state_id' => $request->state_id,
            'district_id' => $request->district_id,
            'city_id' => $resolvedCityId,
            'pincode_id' => $pincodeIdToSave,
            'manual_pincode' => $manualPincode,
            'is_pincode_unknown' => $isPincodeUnknown ? 1 : 0,
            'location_source' => $locationSource,
            'category_id' => $request->category_id,
            'clinic_id' => $clinicIdToSave,
            'phone_number' => preg_replace('/\D/', '', $request->phone_number),
            'phone_number_2' => $request->filled('contact_number_2')
                ? preg_replace('/\D/', '', $request->contact_number_2)
                : null,
            'email' => $normalizedEmail,
            'degree' => $request->degree,
            'speciality' => $request->speciality,
            'address' => $request->address,
            'profile_picture' => $profilePictureRelativePath,
            'status' => 'active',
            'consultation_mode' => 'face-to-face',
        ]);

        // 6) Link user account (unchanged)
        $plainPassword = null;
        $user = User::where('email', $normalizedEmail)->first();
        if (!$user) {
            $plainPassword = Str::random(10);
            $user = User::create([
                'name' => $request->name,
                'email' => $normalizedEmail,
                'password' => Hash::make($plainPassword),
                'role' => 'doctor',
                'otp_verified' => 1,
            ]);
        }

        $doctor->update(['user_id' => $user->id]);

        DB::commit();

        // 7) Email sending (unchanged)
        $emailSent = false;
        $emailType = null;
        try {
            if ($plainPassword) {
                Mail::to($normalizedEmail)->send(new \App\Mail\DoctorWelcomeMail($user, $plainPassword));
                $emailType = 'welcome';
            } else {
                $token = Password::createToken($user);
                $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));
                Mail::send([], [], function ($m) use ($user, $resetUrl) {
                    $m->from(env('MAIL_FROM_ADDRESS', 'noreply@doctora2z.com'), env('MAIL_FROM_NAME', 'DoctorA2Z'));
                    $m->to($user->email, $user->name)
                      ->subject('DoctorA2Z - Password Reset Link')
                      ->setBody(
                          "<p>Hello {$user->name},</p>
                           <p>Your profile has been created. Click below to reset your password:</p>
                           <p><a href=\"{$resetUrl}\">Reset Password</a></p>",
                          'text/html'
                      );
                });
                $emailType = 'reset';
            }
            $emailSent = true;
        } catch (\Throwable $ex) {
            Log::warning('Email sending failed: ' . $ex->getMessage());
            $emailType = 'failed';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'id' => $doctor->id,
                'redirect_url' => route('listdoctor.success', ['id' => $doctor->id]),
                'email_sent' => $emailSent,
                'email_type' => $emailType,
            ]);
        }

        $maskedEmail = $this->maskEmail($normalizedEmail);
        return redirect()->route('listdoctor.success')
            ->with('created_doctor_email', $maskedEmail)
            ->with('new_doctor_id', $doctor->id)
            ->with('email_sent', $emailSent)
            ->with('email_type', $emailType);

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('listdoctorstore error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'input' => $request->except(['profile_picture', 'password']),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return back()->withInput()->withErrors('Create failed: ' . $e->getMessage());
    }
}






    // helper for masking email
    protected function maskEmail(?string $email): ?string
    {
        if (empty($email)) return null;
        $parts = explode('@', $email);
        $local = $parts[0] ?? '';
        $domain = $parts[1] ?? '';
        if (strlen($local) <= 2) {
            $maskedLocal = str_repeat('*', strlen($local));
        } else {
            $maskedLocal = substr($local, 0, 1) . str_repeat('*', max(1, min(3, strlen($local)-1))) . substr($local, -1);
        }
        return $maskedLocal . '@' . $domain;
    }

    // show doctor creation success page
    public function doctorSuccess(Request $request)
    {
        try {
            $maskedEmail = session('created_doctor_email') ?? $request->query('e') ?? null;
            $doctorId    = session('new_doctor_id') ?? $request->query('id') ?? null;
            $emailSent   = session('email_sent') ?? null;
            $emailType   = session('email_type') ?? null;

            return view('frontend.doctor_success', compact('maskedEmail', 'doctorId', 'emailSent', 'emailType'));
        } catch (\InvalidArgumentException $e) {
            Log::error('Doctor success page not found: '.$e->getMessage());
            abort(404);
        }
    }

    // AJAX: districts by state
    public function getDistricts($state_id)
    {
        if (!is_numeric($state_id)) return response()->json([],400);
        return response()->json(District::where('state_id',(int)$state_id)->orderBy('name')->get(['id','name']));
    }

    // -------------------------
    // AJAX: pincode lookup (updated)
    // -------------------------
    public function lookupPincode(Request $request, $pincode = null)
    {
        $pincode = $pincode ?: $request->input('pincode');

        if (empty($pincode) || !preg_match('/^\d{6}$/', trim($pincode))) {
            return response()->json(['success' => false, 'message' => 'Invalid pincode format. Expect 6 digits.'], 422);
        }

        $pin = trim($pincode);

        // try local db
        $local = Pincode::with(['country','state','district','city'])
            ->where('pincode', $pin)
            ->first();

        if ($local) {
            // build payload from local record and raw_json if available
            $payload = [
                'pincode_id'  => $local->id,
                'pincode'     => $local->pincode,
                'country'     => $local->country?->name ?? null,
                'state'       => $local->state?->name ?? ($local->state_id ? State::find($local->state_id)?->name : null),
                'district'    => $local->district?->name ?? ($local->district_id ? District::find($local->district_id)?->name : null),
                'office_name' => $local->office_name ?? null,
                'raw'         => null,
                'areas'       => [],
            ];

            // if raw_json stored, try to extract areas from it (supports IndiaPost format)
            try {
                $raw = $local->raw_json ?? null;
                if ($raw) {
                    $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
                    // attempt to handle both direct PostOffice array or full API array
                    if (isset($decoded[0]['PostOffice']) && is_array($decoded[0]['PostOffice'])) {
                        $postOffices = $decoded[0]['PostOffice'];
                    } elseif (isset($decoded['PostOffice']) && is_array($decoded['PostOffice'])) {
                        $postOffices = $decoded['PostOffice'];
                    } else {
                        $postOffices = null;
                    }

                    if (is_array($postOffices)) {
                        $areas = [];
                        foreach ($postOffices as $po) {
                            $name = $po['Name'] ?? null;
                            if ($name) {
                                $areas[] = ['id' => $name, 'name' => $name];
                            }
                        }
                        if (!empty($areas)) {
                            $payload['areas'] = $areas;
                        }
                        $payload['raw'] = $decoded;
                    }
                }
            } catch (\Throwable $e) {
                // ignore parsing errors and proceed with fallback
            }

            // fallback: if areas empty but we have an office_name or district we can return them
            if (empty($payload['areas'])) {
                $fallback = [];
                if (!empty($payload['office_name'])) {
                    $fallback[] = ['id' => $payload['office_name'], 'name' => $payload['office_name']];
                }
                if (!empty($payload['district'])) {
                    // don't duplicate
                    if (!collect($fallback)->pluck('name')->contains($payload['district'])) {
                        $fallback[] = ['id' => $payload['district'], 'name' => $payload['district']];
                    }
                }
                $payload['areas'] = $fallback;
            }

            return response()->json([
                'success' => true,
                'source'  => 'local',
                'payload' => $payload,
            ]);
        }

        // fallback to India Post API
        $apiResult = $this->fetchPincodeFromIndiaPost($pin);

        if (! $apiResult['success']) {
            return response()->json(['success' => false, 'message' => 'Invalid Pincode!'], 404);
        }

        return response()->json([
            'success' => true,
            'source' => 'api',
            'payload' => $apiResult['payload'],
        ]);
    }

    // -------------------------
    // India Post helper (updated) - returns ['success'=>bool,'payload'=>[...] ]
    // -------------------------
    protected function fetchPincodeFromIndiaPost(string $pin): array
    {
        try {
            $res = Http::timeout(6)->get("https://api.postalpincode.in/pincode/{$pin}");
            if (!$res->ok()) {
                return ['success' => false, 'payload' => null];
            }

            $json = $res->json();
            if (empty($json) || !isset($json[0]) || empty($json[0]['PostOffice'])) {
                return ['success' => false, 'payload' => null];
            }

            $postOffices = $json[0]['PostOffice'];
            // build areas array from PostOffice list
            $areas = [];
            foreach ($postOffices as $po) {
                $name = $po['Name'] ?? null;
                if ($name) {
                    // use name as id too (frontend handles either numeric or string ids)
                    $areas[] = ['id' => $name, 'name' => $name];
                }
            }

            $po0 = $postOffices[0] ?? [];

            $payload = [
                'pincode'     => $pin,
                'pincode_id'  => null,
                'country'     => $po0['Country'] ?? 'India',
                'state'       => $po0['State'] ?? null,
                'district'    => $po0['District'] ?? null,
                'office_name' => $po0['Name'] ?? null,
                'raw'         => $json,
                'areas'       => $areas,
            ];

            return ['success' => true, 'payload' => $payload];
        } catch (\Throwable $e) {
            Log::warning('India Post API error: ' . $e->getMessage());
            return ['success' => false, 'payload' => null];
        }
    }
    
   public function getAreas($districtId, $pincode = null)
    {
        // Step 1: Get pincodes by district (and optional pincode)
        $pinQuery = DB::table('pincodes')->where('district_id', $districtId);
    
        if (!empty($pincode)) {
            $pinQuery->where('pincode', $pincode);
        }
    
        $cityIds = $pinQuery->pluck('city_id')->unique()->filter();
    
        // Step 2: Fetch areas (cities)
        $areas = DB::table('cities')
            ->whereIn('id', $cityIds)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    
        // Step 3: Return JSON
        if ($areas->isEmpty()) {
            return response()->json(['success' => false, 'areas' => []]);
        }
    
        return response()->json(['success' => true, 'areas' => $areas]);
    }


public function show($id)
{
    // Load doctor with required relations
    $doctor = Doctor::with([
        'category:id,name',
        'clinic:id,name,address,city_id,state_id',
        'city:id,name',
        'state:id,name',
        'country:id,name',
        'district:id,name',
        'pincode:id,pincode'
    ])->findOrFail($id);

    /* ---------------- Ratings ---------------- */
    $avgRating = (float) Rating::where('doctor_id', $doctor->id)->avg('rating_point');
    $ratingCount = Rating::where('doctor_id', $doctor->id)->count();

    /* ---------------- Experience ---------------- */
    $experienceYears = null;
    if (!empty($doctor->experience_years)) {
        $experienceYears = (int) $doctor->experience_years;
    } elseif (!empty($doctor->created_at)) {
        $experienceYears = now()->diffInYears($doctor->created_at);
    }

    /* ---------------- Languages ---------------- */
    $languages = [];
    if (!empty($doctor->languages)) {
        if (is_string($doctor->languages)) {
            $languages = array_map('trim', explode(',', $doctor->languages));
        } elseif (is_array($doctor->languages)) {
            $languages = $doctor->languages;
        }
    }

    if (empty($languages)) {
        $languages = ['Not specified'];
    }

    /* ---------------- Visiting Locations ---------------- */
    $visitingLocations = collect();

    // Clinic address
    if ($doctor->clinic) {
        $visitingLocations->push([
            'name'    => $doctor->clinic->name,
            'address' => $doctor->clinic->address,
        ]);
    }

    // Doctor address fallback
    if (!empty($doctor->address)) {
        $visitingLocations->push([
            'name'    => $doctor->name,
            'address' => $doctor->address,
        ]);
    }

    $visitingLocations = $visitingLocations
        ->filter(fn ($v) => !empty($v['address']))
        ->unique('address')
        ->values();

    if ($visitingLocations->isEmpty()) {
        $visitingLocations->push([
            'name' => null,
            'address' => 'No visiting location provided',
        ]);
    }

    /* ---------------- Membership ---------------- */
    $membership = $doctor->council ?: 'Not provided';

    /* ---------------- Map Address ---------------- */
    $mapAddress = $visitingLocations->first()['address'] ?? null;

    /* ---------------- Related Doctors ---------------- */
    $relatedDoctors = Doctor::with('category:id,name')
        ->where('category_id', $doctor->category_id)
        ->where('id', '!=', $doctor->id)
        ->latest()
        ->take(4)
        ->get();

    foreach ($relatedDoctors as $rd) {
        $rd->avg_rating = round(
            (float) Rating::where('doctor_id', $rd->id)->avg('rating_point'),
            1
        );
        $rd->rating_count = Rating::where('doctor_id', $rd->id)->count();
    }

    /* ---------------- Attach computed fields ---------------- */
    $doctor->avg_rating        = round($avgRating, 1);
    $doctor->rating_count     = $ratingCount;
    $doctor->experience_years = $experienceYears;
    $doctor->languages_array  = $languages;
    $doctor->languages_display = implode(', ', $languages);
    $doctor->visiting_locations = $visitingLocations;
    $doctor->membership       = $membership;
    $doctor->map_address      = $mapAddress;
    $doctor->main_phone       = $doctor->phone_number ?: $doctor->phone_number_2;
    $doctor->whatsapp_number  = $doctor->whatsapp ?: $doctor->phone_number;

    return view('frontend.doctor_details', [
        'doctor'         => $doctor,
        'relatedDoctors' => $relatedDoctors,
        'advertisements' => Advertisement::latest()->get(),
        'contactus'      => ContactUs::first(),
    ]);
}





    // contact/send email
    public function sendEmail(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255','email'=>'required|email|max:255','phone'=>'required',
            'subject'=>'required|string|max:255','message'=>'required|string',
        ]);
        $data=$request->only('name','email','phone','subject','message');
        Mail::send([],[],function($mail) use($data){
            $mail->from(env('MAIL_FROM_ADDRESS','noreply@doctora2z.com'),env('MAIL_FROM_NAME','DoctorA2Z.com'));
            $mail->to('saha10ankita@gmail.com','DoctorA2Z Support')->subject($data['subject']);
            $mail->html("<p><strong>Name:</strong> {$data['name']}</p><p><strong>Email:</strong> {$data['email']}</p><p><strong>Phone:</strong> {$data['phone']}</p><p><strong>Message:</strong><br>{$data['message']}</p>");
        });
        return back()->with('success','Message has been sent successfully.');
    }

    // rating endpoints...
public function ratingstore(Request $request)
{
    // validation - ensure we demand integer rating and existing doctor
    $validator = Validator::make($request->all(), [
        'doctor_id'    => 'required|exists:doctor_profiles,id',
        'user_email'   => 'required|email|max:255',
        'rating_point' => 'required|integer|min:1|max:5',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'message' => 'Validation failed', 'errors' => $validator->errors()], 422);
    }

    try {
        $doctorId = (int) $request->input('doctor_id');
        $userEmail = strtolower(trim($request->input('user_email')));
        $ratingPoint = (int) $request->input('rating_point');

        // OPTIONAL: prevent one email rating multiple times for same doctor
        // $existing = Rating::where('doctor_id', $doctorId)->where('user_email', $userEmail)->first();
        // if ($existing) {
        //     return response()->json(['success' => false, 'message' => 'You have already rated this doctor.'], 409);
        // }

        // Save (model accepts fillable fields as defined)
        $rating = Rating::create([
            'doctor_id'    => $doctorId,
            'doctor_name'  => Doctor::find($doctorId)?->name ?? null,
            'user_email'   => $userEmail,
            'rating_point' => (string) $ratingPoint, // kept as string to match existing DB schema
        ]);

        // compute fresh stats (cast avg to float)
        $avg = (float) Rating::where('doctor_id', $doctorId)->avg(DB::raw('CAST(rating_point AS DECIMAL(3,2))')) ?? 0;
        $count = (int) Rating::where('doctor_id', $doctorId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Rating submitted successfully!',
            'average' => round($avg, 1),
            'count'   => $count,
            'rating_id'=> $rating->id ?? null,
        ], 201);

    } catch (\Throwable $e) {
        Log::error('ratingstore error: '.$e->getMessage(), ['trace'=>$e->getTraceAsString(), 'input'=>$request->all()]);
        return response()->json(['success' => false, 'message' => 'Server error while saving rating'], 500);
    }
}

public function rateDoctor(Request $request)
{
    $validator = Validator::make($request->all(), [
        'doctor_id'    => 'required|exists:doctor_profiles,id',
        'user_email'   => 'required|email',
        'rating'       => 'nullable|integer|min:1|max:5',
        'rating_point' => 'nullable|string',
    ]);

    if ($validator->fails()) {
        return back()->withInput()->withErrors($validator->errors());
    }

    // coerce rating_point
    if (!is_null($request->input('rating'))) {
        $ratingPoint = (int) $request->input('rating');
    } elseif (!is_null($request->input('rating_point'))) {
        $rp = preg_replace('/[^0-9]/', '', (string)$request->input('rating_point'));
        $ratingPoint = $rp === '' ? 0 : (int)$rp;
    } else {
        return back()->withInput()->withErrors(['rating' => 'rating or rating_point is required']);
    }

    if ($ratingPoint < 1 || $ratingPoint > 5) {
        return back()->withInput()->withErrors(['rating' => 'Rating must be between 1 and 5']);
    }

    try {
        $existing = Rating::where('doctor_id', $request->doctor_id)
                          ->where('user_email', $request->user_email)
                          ->first();

        if ($existing) {
            return back()->with('error', 'You have already rated this doctor.');
        }

        Rating::create([
            'doctor_id'    => (int)$request->doctor_id,
            'doctor_name'  => Doctor::find($request->doctor_id)->name ?? null,
            'user_email'   => $request->user_email,
            'rating_point' => (string)$ratingPoint,
            'created_at'   => Carbon::now()->toDateTimeString(),
            'updated_at'   => Carbon::now()->toDateTimeString(),
        ]);

        return back()->with('success', 'Rating submitted successfully!');
    } catch (\Throwable $e) {
        Log::error('rateDoctor error: '.$e->getMessage(), ['input' => $request->all(), 'trace' => $e->getTraceAsString()]);
        return back()->withInput()->with('error', 'Server error while saving rating.');
    }
}

    public function ajaxCheckEmail(Request $request)
    {
        $email = strtolower(trim((string)$request->input('email', '')));

        // basic sanity
        if ($email === '') {
            return response()->json([
                'ok' => false,
                'exists' => null,
                'message' => 'Please enter a valid email address in the correct format (example@domain.com).'
            ], 422);
        }

        // quick server-side format check
        if (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
            return response()->json([
                'ok' => false,
                'exists' => null,
                'message' => 'Please enter a valid email address in the correct format (example@domain.com).'
            ], 422);
        }

        // normalize for case-insensitive comparison
        $lower = $email;

        // 1) is there already a doctor profile with this email?
        $doctorExists = \App\Models\Doctor::whereRaw('LOWER(email) = ?', [$lower])->exists();
        if ($doctorExists) {
            return response()->json([
                'ok' => true,
                'exists' => true,
                'message' => 'This email is already registered, please try another.'
            ], 200);
        }

        // 2) is there a User with this email?
        $user = \App\Models\User::whereRaw('LOWER(email) = ?', [$lower])->first();
        if ($user) {
            // user exists but we already checked there is no doctor profile with this email.
            // check if that user already has a doctor relationship
            $hasDoctorProfile = $user->doctor()->exists();

            if ($hasDoctorProfile) {
                // defensive: user has doctor profile (should have been caught above), still treat as taken
                return response()->json([
                    'ok' => true,
                    'exists' => true,
                    'message' => 'This email is already registered, please try another.'
                ], 200);
            }

            // user exists but no doctor profile -> allowed, but inform that the account will be linked and a reset link will be sent
            return response()->json([
                'ok' => true,
                'exists' => 'linked',
                'message' => 'This email is associated with an existing account. A doctor profile will be linked and the user will receive a password reset link. You may continue filling the form.'
            ], 200);
        }

        // available
        return response()->json([
            'ok' => true,
            'exists' => false,
            'message' => 'Email is available, you may continue filling the form.'
        ], 200);
    }

}
