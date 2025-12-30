<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\District;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\State;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorListAjax extends Controller
{
    /**
     * Render index view with lookup lists
     */
    public function index()
    {
        $countries  = Country::orderBy('name')->get();
        $states     = State::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();
        $cities     = City::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $clinics    = Client::orderBy('name')->get();

        return view('admin.doctor_lists.inline3', compact(
            'countries','states','districts','cities','categories','clinics'
        ));
    }

    public function create() { return $this->index(); }

    /**
     * Listing endpoint (supports DataTables server-side params)
     */
    public function list(Request $request)
    {
        $base = Doctor::query()->select('doctor_profiles.*');

        if ($request->has('draw')) {
            $draw   = (int)$request->input('draw', 1);
            $start  = (int)$request->input('start', 0);
            $length = (int)$request->input('length', 10);
            $search = $request->input('search.value');
            $order  = $request->input('order', []);

            $recordsTotal = (clone $base)->count();

            if (!empty($search)) {
                $term = trim($search);
                $base->where(function($q) use ($term){
                    $q->where('doctor_profiles.name','like',"%{$term}%")
                      ->orWhere('doctor_profiles.email','like',"%{$term}%")
                      ->orWhere('doctor_profiles.phone_number','like',"%{$term}%")
                      ->orWhere('doctor_profiles.phone_number_2','like',"%{$term}%")
                      ->orWhere('doctor_profiles.speciality','like',"%{$term}%")
                      ->orWhere('doctor_profiles.registration_no','like',"%{$term}%")
                      ->orWhere('doctor_profiles.council','like',"%{$term}%")
                      ->orWhere('doctor_profiles.manual_pincode','like',"%{$term}%")
                      ->orWhere('doctor_profiles.experience_years','like',"%{$term}%")
                      ->orWhere('doctor_profiles.languages','like',"%{$term}%")
                      ->orWhereHas('pincode', function($q) use ($term) {
                          $q->where('pincode', 'like', "%{$term}%");
                      });
                });
            }

            $map = [
                0 => null,
                1 => 'doctor_profiles.id',
                2 => 'doctor_profiles.name',
                3 => 'doctor_profiles.email',
                4 => 'doctor_profiles.phone_number',
                5 => 'doctor_profiles.phone_number_2',
                6 => 'doctor_profiles.speciality',
                7 => 'doctor_profiles.registration_no',
                8 => 'doctor_profiles.council',
                9 => 'doctor_profiles.manual_pincode',
                10 => 'doctor_profiles.website',
                11 => 'doctor_profiles.whatsapp',
                12 => 'doctor_profiles.facebook',
                13 => 'doctor_profiles.instagram',
                14 => 'doctor_profiles.address',
                15 => 'doctor_profiles.country_id',
                16 => 'doctor_profiles.state_id',
                17 => 'doctor_profiles.district_id',
                18 => 'doctor_profiles.city_id',
                19 => 'doctor_profiles.category_id',
                20 => 'doctor_profiles.clinic_id',
                21 => 'doctor_profiles.status',
                22 => 'doctor_profiles.consultation_mode',
                23 => 'doctor_profiles.experience_years',
                24 => 'doctor_profiles.languages',
                25 => null,
            ];

            foreach ($order ?? [] as $o) {
                $colIndex = (int)($o['column'] ?? 1);
                $col = $map[$colIndex] ?? null;
                $dir = strtolower($o['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
                if ($col) $base->orderBy($col, $dir);
            }
            if (empty($order)) $base->orderByDesc('doctor_profiles.id');

            $recordsFiltered = (clone $base)->count();
            if ($length > 0) $base->skip($start)->take($length);

            $rows = $base->with(['country','state','district','city','category','clinic','pincode'])->get();

            // Transform data with accessors
            $rows->transform(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'display_name' => $doctor->display_name,
                    'email' => $doctor->email,
                    'phone_number' => $doctor->phone_number,
                    'phone_number_2' => $doctor->phone_number_2,
                    'speciality' => $doctor->speciality,
                    'degree' => $doctor->degree,
                    'degrees' => $doctor->degrees,
                    'experience_years' => $doctor->experience_years,
                    'experience_display' => $doctor->experience_display,
                    'languages' => $doctor->languages,
                    'languages_array' => $doctor->languages_array,
                    'registration_no' => $doctor->registration_no,
                    'council' => $doctor->council,
                    'status' => $doctor->status,
                    'consultation_mode' => $doctor->consultation_mode,
                    'profile_picture' => $doctor->profile_picture ? Storage::url($doctor->profile_picture) : null,
                    'address' => $doctor->address,
                    'full_address' => $doctor->full_address,
                    'country' => $doctor->country?->name,
                    'state' => $doctor->state?->name,
                    'district' => $doctor->district?->name,
                    'city' => $doctor->city?->name,
                    'pincode' => $doctor->pincode,
                    'manual_pincode' => $doctor->manual_pincode,
                    'clinic' => $doctor->clinic?->only(['id', 'name']),
                    'clinic_name' => $doctor->clinic_name,
                    'category' => $doctor->category?->name,
                    'clinic_days' => $doctor->clinic_days,
                    'clinic_start_time' => $doctor->clinic_start_time,
                    'clinic_end_time' => $doctor->clinic_end_time,
                    'visiting_time' => $doctor->visiting_time,
                    'clinics' => $doctor->clinics,
                    'website' => $doctor->website,
                    'whatsapp' => $doctor->whatsapp,
                    'facebook' => $doctor->facebook,
                    'instagram' => $doctor->instagram,
                    'date_of_birth' => $doctor->date_of_birth,
                    'created_at' => $doctor->created_at,
                    'updated_at' => $doctor->updated_at,
                    'has_online_consultation' => $doctor->hasOnlineConsultation(),
                ];
            });

            return response()->json([
                'draw'            => $draw,
                'recordsTotal'    => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data'            => $rows->toArray(),
            ]);
        }

        $rows = $base->with(['country','state','district','city','category','clinic','pincode'])->orderByDesc('id')->get();
        
        // Transform data
        $rows->transform(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'display_name' => $doctor->display_name,
                'email' => $doctor->email,
                'phone_number' => $doctor->phone_number,
                'speciality' => $doctor->speciality,
                'experience_years' => $doctor->experience_years,
                'status' => $doctor->status,
                'clinic_name' => $doctor->clinic_name,
                'city' => $doctor->city?->name,
                'pincode' => $doctor->pincode,
            ];
        });
        
        return response()->json(['success'=>true,'data'=>$rows->toArray()]);
    }

    /**
     * Store a single doctor
     */
    public function store(Request $request)
    {
        try {
            $data = $request->validate($this->rules(create: true));
        } catch (ValidationException $e) {
            // Try to resolve missing clinic_id from schedules_json as a convenience
            $errors = $e->validator->errors()->getMessages();
            $onlyClinicIdMissing = (count($errors) === 1 && array_key_exists('clinic_id', $errors));
            if ($onlyClinicIdMissing && $request->filled('schedules_json')) {
                $schedules = json_decode($request->input('schedules_json'), true) ?: [];
                $resolvedClinicId = $this->resolveClinicFromSchedules($schedules);
                if ($resolvedClinicId) {
                    $merge = array_merge($request->all(), ['clinic_id' => $resolvedClinicId]);
                    $data = Validator::make($merge, $this->rules(create:true))->validate();
                } else {
                    throw $e;
                }
            } else {
                throw $e;
            }
        }

        $schedules = [];
        if ($request->filled('schedules_json')) {
            $decoded = json_decode($request->input('schedules_json'), true);
            if (is_array($decoded)) $schedules = $decoded;
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('photo')) {
                $filename = $this->storeUploadedImage($request->file('photo'));
                $data['profile_picture'] = $filename;
            }

            $data = $this->processPincode($data);

            if (empty($data['clinic_id']) && !empty($schedules)) {
                $resolved = $this->resolveClinicFromSchedules($schedules);
                if ($resolved) $data['clinic_id'] = $resolved;
            }

            if (!empty($schedules)) {
                $this->processSchedules($schedules, $data);
            }

            $data = $this->normalizeRequestAliases($data);
            $clean = $this->allowedDoctorAttributes($data);

            // Handle languages if provided as array
            if ($request->has('languages_array') && is_array($request->languages_array)) {
                $clean['languages'] = implode(',', array_map('trim', $request->languages_array));
            }

            // Handle degrees if provided as array
            if ($request->has('degrees') && is_array($request->degrees)) {
                $clean['degrees'] = $request->degrees;
            }

            // Handle clinic_days if provided as array
            if ($request->has('clinic_days') && is_array($request->clinic_days)) {
                $clean['clinic_days'] = $request->clinic_days;
            }

            $doc = Doctor::create($clean);

            DB::commit();
            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => 'Doctor profile created successfully',
                'data' => ['id' => $doc->id, 'doctor' => $doc]
            ], 201);
        } catch (\Throwable $ex) {
            DB::rollBack();
            \Log::error('Doctor creation failed: ' . $ex->getMessage(), ['exception' => $ex]);
            return response()->json([
                'success' => false,
                'message' => 'Server error creating doctor profile',
                'error' => config('app.debug') ? $ex->getMessage() : 'Please try again later'
            ], 500);
        }
    }

    /**
     * Update existing doctor
     */
    public function update(Request $request, int $id)
    {
        $doctor = Doctor::findOrFail($id);

        $payload = $request->all();
        foreach (['country_id','state_id','district_id','city_id','category_id','clinic_id'] as $k) {
            if (array_key_exists($k, $payload) && ($payload[$k] === '' || $payload[$k] === null)) {
                unset($payload[$k]);
            }
        }
        $request->replace($payload);

        $data = $request->validate($this->rules(create: false));

        $schedules = [];
        if ($request->filled('schedules_json')) {
            $decoded = json_decode($request->input('schedules_json'), true);
            if (is_array($decoded)) $schedules = $decoded;
        }

        DB::beginTransaction();
        try {
            if ($request->hasFile('photo')) {
                $this->deleteImageIfExists($doctor->profile_picture ?? null);
                $filename = $this->storeUploadedImage($request->file('photo'));
                $data['profile_picture'] = $filename;
            }

            $data = $this->processPincode($data, $doctor);

            if (empty($data['clinic_id']) && !empty($schedules)) {
                $resolved = $this->resolveClinicFromSchedules($schedules);
                if ($resolved) $data['clinic_id'] = $resolved;
            }

            if (!empty($schedules)) {
                $this->processSchedules($schedules, $data);
            }

            $data = $this->normalizeRequestAliases($data);
            $clean = $this->allowedDoctorAttributes($data);

            // Handle languages if provided as array
            if ($request->has('languages_array') && is_array($request->languages_array)) {
                $clean['languages'] = implode(',', array_map('trim', $request->languages_array));
            }

            // Handle degrees if provided as array
            if ($request->has('degrees') && is_array($request->degrees)) {
                $clean['degrees'] = $request->degrees;
            }

            // Handle clinic_days if provided as array
            if ($request->has('clinic_days') && is_array($request->clinic_days)) {
                $clean['clinic_days'] = $request->clinic_days;
            }

            $doctor->update($clean);

            DB::commit();
            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => 'Doctor updated successfully',
                'data' => ['id' => $doctor->id, 'doctor' => $doctor]
            ]);
        } catch (\Throwable $ex) {
            DB::rollBack();
            \Log::error('Doctor update failed: ' . $ex->getMessage(), ['id' => $id, 'exception' => $ex]);
            return response()->json([
                'success' => false,
                'message' => 'Server error updating doctor',
                'error' => config('app.debug') ? $ex->getMessage() : 'Please try again later'
            ], 500);
        }
    }

    /**
     * Bulk update/create rows
     */
    public function bulkUpdate(Request $request)
    {
        $rows = $request->input('rows', []);
        if (!is_array($rows) || !count($rows)) {
            return response()->json(['success'=>false,'message'=>'No rows provided'], 422);
        }

        $created=0; $updated=0; $failed=[];

        DB::beginTransaction();
        try {
            foreach ($rows as $i => $row) {
                try {
                    $row = (array)$row;

                    foreach (['country_id','state_id','district_id','city_id','category_id','clinic_id'] as $k) {
                        if (array_key_exists($k, $row) && ($row[$k] === '' || $row[$k] === null)) {
                            unset($row[$k]);
                        }
                    }

                    if (!empty($row['id'])) {
                        $data = validator($row, $this->rules(create:false))->validate();
                        $doctor = Doctor::find($row['id']);
                        if (!$doctor) {
                            $failed[] = ['index'=>$i,'error'=>'Doctor not found'];
                            continue;
                        }

                        if (!empty($row['schedules_json'])) {
                            $schedules = json_decode($row['schedules_json'], true) ?: [];
                            if (is_array($schedules) && count($schedules)) {
                                $this->processSchedules($schedules, $data);
                            }
                        }

                        $data = $this->normalizeRequestAliases($data);
                        $clean = $this->allowedDoctorAttributes($data);
                        
                        // Handle languages array
                        if (isset($row['languages_array']) && is_array($row['languages_array'])) {
                            $clean['languages'] = implode(',', array_map('trim', $row['languages_array']));
                        }
                        
                        $doctor->update($clean);
                        $updated++;
                    } else {
                        $data = validator($row, $this->rules(create:true))->validate();

                        if (!empty($row['schedules_json'])) {
                            $schedules = json_decode($row['schedules_json'], true) ?: [];
                            if (is_array($schedules) && count($schedules)) {
                                $this->processSchedules($schedules, $data);
                            }
                        }

                        $data = $this->normalizeRequestAliases($data);
                        $clean = $this->allowedDoctorAttributes($data);
                        
                        // Handle languages array
                        if (isset($row['languages_array']) && is_array($row['languages_array'])) {
                            $clean['languages'] = implode(',', array_map('trim', $row['languages_array']));
                        }
                        
                        Doctor::create($clean);
                        $created++;
                    }
                } catch (\Throwable $e) {
                    $failed[] = ['index'=>$i,'error'=>$e->getMessage()];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => "Bulk update complete. Updated {$updated}, Created {$created}.",
                'failed' => $failed,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single doctor
     */
    public function show($id)
    {
        try {
            $doctor = Doctor::with([
                'user:id,name,email',
                'category:id,name',
                'clinic:id,name,address,phone_number',
                'country:id,name',
                'state:id,name',
                'district:id,name',
                'city:id,name',
                'pincode:id,pincode',
                'clinicSchedules'
            ])->findOrFail($id);

            // Add computed attributes
            $doctor->display_name = $doctor->display_name;
            $doctor->full_address = $doctor->full_address;
            $doctor->experience_display = $doctor->experience_display;
            $doctor->languages_array = $doctor->languages_array;
            $doctor->clinic_days_array = $doctor->clinic_days_array;
            $doctor->visiting_time = $doctor->visiting_time;
            $doctor->has_online_consultation = $doctor->hasOnlineConsultation();
            
            if ($doctor->profile_picture) {
                $doctor->profile_picture_url = Storage::url($doctor->profile_picture);
            }

            return response()->json([
                'success' => true,
                'data' => $doctor,
                'message' => 'Doctor retrieved successfully.'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve doctor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Edit form for doctor
     */
    public function edit(int $id)
    {
        $doctor = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                        ->findOrFail($id);

        $countries  = Country::orderBy('name')->get();
        $states     = State::orderBy('name')->get();
        $districts  = District::orderBy('name')->get();
        $cities     = City::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $clinics    = Client::orderBy('name')->get();

        // Add computed attributes for view
        $doctor->languages_array = $doctor->languages_array;
        $doctor->clinic_days_array = $doctor->clinic_days_array;

        return view('admin.doctor_lists.inline3', compact(
            'doctor','countries','states','districts','cities','categories','clinics'
        ));
    }

    /**
     * Delete single doctor
     */
    public function destroy(int $id)
    {
        try {
            $doc = Doctor::findOrFail($id);
            $this->deleteImageIfExists($doc->profile_picture ?? null);
            $doc->delete();

            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => 'Doctor deleted successfully'
            ]);
        } catch (\Throwable $e) {
            \Log::error('Doctor deletion failed: ' . $e->getMessage(), ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctor'
            ], 500);
        }
    }

    /**
     * Bulk delete
     */
    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids'   => ['required','array','min:1'],
            'ids.*' => ['integer','exists:doctor_profiles,id'],
        ]);

        try {
            $photos = Doctor::whereIn('id', $data['ids'])->pluck('profile_picture')->filter()->all();
            foreach ($photos as $p) $this->deleteImageIfExists($p);

            Doctor::whereIn('id', $data['ids'])->delete();

            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => 'Selected doctors deleted successfully'
            ]);
        } catch (\Throwable $e) {
            \Log::error('Bulk doctor deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete selected doctors'
            ], 500);
        }
    }

    /**
     * Upload a photo (AJAX)
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate(['photo'=>['required','image','mimes:jpeg,png,jpg,gif','max:4096']]);

        try {
            $filename = $this->storeUploadedImage($request->file('photo'));
            return response()->json([
                'success' => true,
                'ok' => true,
                'message' => 'Photo uploaded successfully',
                'filename' => $filename,
                'url' => Storage::disk('public')->url('doctors/'.$filename)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload photo'
            ], 500);
        }
    }

    /**
     * Download sample CSV
     */
    public function downloadSample()
    {
        $headers = [
            'id','user_id','name','date_of_birth','country_id','state_id','district_id','city_id','pincode_id',
            'is_pincode_unknown','manual_pincode','location_source','category_id','clinic_id','clinic_name','clinic_days',
            'clinic_start_time','clinic_end_time','alternative_schedule','phone_number','phone_number_2','email','website',
            'whatsapp','facebook','instagram','address','speciality','degree','degrees','profile_details','profile_picture',
            'registration_no','council','status','consultation_mode','created_at','updated_at','experience_years','languages'
        ];

        $sampleRow = [
            '', '245', 'Dr. John Doe', '1980-02-15', '87', '78', '606', '79093', '72204', '0', '', 'auto', '8', '383',
            'Basu Pathology Clinic', '["monday","tuesday","friday"]', '10:00:00', '14:00:00',
            '[{"clinic_id":383,"clinic_name":"Basu Pathology Clinic","schedules":[{"day":"monday","start":"10:00:00","end":"14:00:00"}]}]',
            '9999999999','8888888888','john.doe@example.com','https://example.com','9999999999','https://facebook.com/dr.john',
            'https://instagram.com/dr.john','123 Main Street, Kolkata','Cardiology','MBBS, MD','["MBBS","MD"]',
            'Experienced heart specialist with 10+ years of service.','storage/doctor/sample.jpg','REG-12345','Medical Council of India',
            'active','face-to-face','2025-11-11 10:30:00','2025-11-11 10:30:00','10','English,Hindi,Bengali'
        ];

        return new StreamedResponse(function () use ($headers, $sampleRow) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fputcsv($handle, $sampleRow);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="doctors_sample.csv"',
        ]);
    }

    /**
     * Export CSV (memory friendly streaming using cursor)
     */
    public function exportCsv()
    {
        $columns = [
            'ID','Name','Email','Phone','Secondary Phone','Speciality','Degree','Experience Years','Languages',
            'Clinic ID','Clinic Name','Clinic Days','Start Time','End Time','Alternative Schedule',
            'Registration No','Council','Pincode','Website','WhatsApp','Facebook','Instagram','Address',
            'Country','State','District','City','Category','Status','Consultation Mode','Created At'
        ];

        $filename = 'doctors_' . date('Y-m-d') . '.csv';

        $callback = function() use ($columns) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $columns);

            $query = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                          ->orderBy('id', 'desc');

            foreach ($query->cursor() as $r) {
                fputcsv($out, [
                    $r->id,
                    $r->name,
                    $r->email,
                    $r->phone_number,
                    $r->phone_number_2,
                    $r->speciality,
                    $r->degree,
                    $r->experience_years,
                    $r->languages,
                    $r->clinic_id,
                    $r->clinic_name,
                    $r->clinic_days,
                    $r->clinic_start_time,
                    $r->clinic_end_time,
                    $r->alternative_schedule,
                    $r->registration_no,
                    $r->council,
                    $r->pincode,
                    $r->website,
                    $r->whatsapp,
                    $r->facebook,
                    $r->instagram,
                    $r->address,
                    $r->country->name ?? '',
                    $r->state->name ?? '',
                    $r->district->name ?? '',
                    $r->city->name ?? '',
                    $r->category->name ?? '',
                    $r->status,
                    $r->consultation_mode,
                    optional($r->created_at)->toDateTimeString(),
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * Import CSV/Excel (streaming CSV) - processes rows one-by-one to reduce memory
     */
public function import(Request $request)
{
    $request->validate([
        'excel_file' => ['required','file','mimes:csv,txt,xls,xlsx','max:10240']
    ]);

    $file = $request->file('excel_file');
    $ext  = strtolower($file->getClientOriginalExtension());

    $created = 0;
    $updated = 0;
    $skipped = 0;
    $failed  = [];

    DB::beginTransaction();
    try {

        if (in_array($ext, ['csv','txt'])) {

            $handle = fopen($file->getRealPath(), 'r');
            if (!$handle) {
                throw new \Exception('Unable to read file');
            }

            $header = null;
            $rowNum = 0;

            while (($row = fgetcsv($handle)) !== false) {
                $rowNum++;

                if (!$header) {
                    $header = array_map('strtolower', array_map('trim', $row));
                    continue;
                }

                if (implode('', $row) === '') {
                    $skipped++;
                    continue;
                }

                if (count($row) < count($header)) {
                    $row = array_pad($row, count($header), '');
                }

                $rowArr = array_combine($header, $row);

                $result = $this->importRow($rowArr);
                if ($result === 'created') $created++;
                elseif ($result === 'updated') $updated++;
                else $skipped++;
            }

            fclose($handle);

        } else {

            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getRealPath());
            $sheet  = $reader->load($file->getRealPath())->getActiveSheet();

            $header = null;
            $rowIndex = 0;

            foreach ($sheet->getRowIterator() as $row) {
                $rowIndex++;

                $cells = [];
                foreach ($row->getCellIterator() as $cell) {
                    $cells[] = trim((string)$cell->getValue());
                }

                if (!$header) {
                    $header = array_map('strtolower', $cells);
                    continue;
                }

                if (implode('', $cells) === '') {
                    $skipped++;
                    continue;
                }

                if (count($cells) < count($header)) {
                    $cells = array_pad($cells, count($header), '');
                }

                $rowArr = array_combine($header, $cells);

                $result = $this->importRow($rowArr);
                if ($result === 'created') $created++;
                elseif ($result === 'updated') $updated++;
                else $skipped++;
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Import complete. Created {$created}, Updated {$updated}, Skipped {$skipped}.",
            'failed'  => $failed
        ]);

    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}



    /**
     * Import single row processor used by import()
     */
 protected function importRow(array $r): string
{
    $r = $this->normalizeImportRow($r);

    $name = trim((string)($r['name'] ?? ''));
    if ($name === '') {
        return 'skipped';
    }

    $email = trim((string)($r['email'] ?? ''));

    $payload = [
        'name' => $name,
        'email' => $email ?: null,
        'phone_number' => $r['phone_number'] ?? null,
        'phone_number_2' => $r['phone_number_2'] ?? null,
        'speciality' => $r['speciality'] ?? null,
        'degree' => $r['degree'] ?? null,
        'experience_years' => $r['experience_years'] ?? null,
        'languages' => $r['languages'] ?? null,

        'country_id' => $r['country_id'] ?? null,
        'state_id' => $r['state_id'] ?? null,
        'district_id' => $r['district_id'] ?? null,
        'city_id' => $r['city_id'] ?? null,
        'category_id' => $r['category_id'] ?? null,

        'clinic_id' => $r['clinic_id'] ?? null,
        'clinic_name' => $r['clinic_name'] ?? null,

        'registration_no' => $r['registration_no'] ?? null,
        'council' => $r['council'] ?? null,
        'website' => $r['website'] ?? null,
        'whatsapp' => $r['whatsapp'] ?? null,
        'facebook' => $r['facebook'] ?? null,
        'instagram' => $r['instagram'] ?? null,
        'address' => $r['address'] ?? null,

        'status' => in_array($r['status'] ?? '', ['active','inactive']) ? $r['status'] : 'active',
        'consultation_mode' => in_array($r['consultation_mode'] ?? '', ['online','offline','both','face-to-face'])
            ? $r['consultation_mode']
            : 'face-to-face',
    ];

    // 🔑 Pincode handling
    if (!empty($r['pincode'])) {
        $pin = $this->findOrCreatePincode($r['pincode']);
        if ($pin) {
            $payload['pincode_id'] = $pin['pincode_id'];
            $payload['is_pincode_unknown'] = $pin['is_unknown'];
            $payload['manual_pincode'] = $pin['is_unknown'] ? $r['pincode'] : null;
        }
    }

    /**
     * ===========================
     * 🔥 CORE FIX STARTS HERE
     * ===========================
     */

    if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $doctor = Doctor::where('email', $email)->first();

        if ($doctor) {
            // 🚫 DO NOT overwrite DB values with NULL
            $payload = array_filter(
                $payload,
                fn ($v) => !is_null($v)
            );

            $doctor->update($payload);
            return 'updated';
        }

        // CREATE new doctor
        Doctor::create($payload);
        return 'created';
    }

    // No email → always create
    Doctor::create($payload);
    return 'created';
}


protected function normalizeImportRow(array $row): array
{
    foreach ($row as $k => $v) {
        if (is_string($v)) {
            $row[$k] = trim($v);
        }
    }

    foreach (['clinic_days','degrees','alternative_schedule'] as $json) {
        if (!empty($row[$json]) && is_string($row[$json])) {
            $decoded = json_decode($row[$json], true);
            $row[$json] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }
    }

    foreach (['clinic_start_time','clinic_end_time'] as $t) {
        if (!empty($row[$t])) {
            $ts = strtotime($row[$t]);
            $row[$t] = $ts ? date('H:i:s', $ts) : null;
        }
    }

    foreach (['phone_number','phone_number_2','whatsapp'] as $p) {
        if (!empty($row[$p])) {
            $row[$p] = preg_replace('/\D+/', '', $row[$p]);
        }
    }

    return $row;
}



    /**
     * Validation rules
     */
    private function rules(bool $create = false): array
    {
        return [
            'name'              => [$create ? 'required' : 'sometimes', 'string', 'max:255'],
            'email'             => [$create ? 'required' : 'sometimes', 'nullable', 'email', 'max:255', 'unique:doctor_profiles,email' . ($create ? '' : ',' . request()->route('id'))],
            'phone_number'      => [$create ? 'required' : 'sometimes', 'nullable', 'string', 'max:20'],
            'phone_number_2'    => ['sometimes', 'nullable', 'string', 'max:20'],

            'registration_no'   => ['sometimes', 'nullable', 'string', 'max:255'],
            'council'           => ['sometimes', 'nullable', 'string', 'max:255'],
            'pincode'           => ['sometimes', 'nullable', 'string', 'max:20'],

            'speciality'        => [$create ? 'required' : 'sometimes', 'nullable', 'string', 'max:255'],
            'degree'            => ['sometimes', 'nullable', 'string', 'max:255'],

            'country_id'        => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:countries,id'],
            'state_id'          => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:states,id'],
            'district_id'       => ['sometimes', 'nullable', 'integer', 'exists:districts,id'],
            'city_id'           => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:cities,id'],

            'website'           => ['sometimes', 'nullable', 'url', 'max:255'],
            'whatsapp'          => ['sometimes', 'nullable', 'string', 'max:25'],
            'facebook'          => ['sometimes', 'nullable', 'string', 'max:255'],
            'instagram'         => ['sometimes', 'nullable', 'string', 'max:255'],
            'address'           => ['sometimes', 'nullable', 'string', 'max:1000'],

            // Use actual table name 'category' (singular) in exists rule
            'category_id'       => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:categories,id'],

            'clinic_id'         => [$create ? 'required' : 'sometimes', 'nullable', 'integer', 'exists:clinics,id'],

            'status'            => ['sometimes', Rule::in(['active','inactive'])],
            'consultation_mode' => [$create ? 'required' : 'sometimes', Rule::in(['online','face-to-face','both','offline'])],

            'photo'             => ['sometimes', 'nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:4096'],
            'schedules_json'    => ['sometimes', 'nullable', 'string'],

            // New fields
            'experience_years'  => ['sometimes', 'nullable', 'integer', 'min:0', 'max:100'],
            'languages'         => ['sometimes', 'nullable', 'string', 'max:255'],
            'date_of_birth'     => ['sometimes', 'nullable', 'date'],
            'profile_details'   => ['sometimes', 'nullable', 'string'],
        ];
    }

    /**
     * Process pincode data - find existing pincode or mark as unknown
     */
    protected function processPincode(array $data, ?Doctor $doctor = null): array
    {
        if (isset($data['pincode'])) {
            $pincodeData = $this->findOrCreatePincode($data['pincode']);
            if ($pincodeData) {
                $data['pincode_id'] = $pincodeData['pincode_id'];
                $data['is_pincode_unknown'] = $pincodeData['is_unknown'];
                $data['manual_pincode'] = $pincodeData['is_unknown'] ? $data['pincode'] : null;
                $data['location_source'] = $pincodeData['is_unknown'] ? 'manual' : 'auto';
            }
            unset($data['pincode']);
        }
        return $data;
    }

    protected function findOrCreatePincode(string $pincode): ?array
    {
        $pincode = trim($pincode);
        if (empty($pincode)) return null;

        $existing = Pincode::where('pincode', $pincode)->first();
        if ($existing) {
            return [
                'pincode_id' => $existing->id,
                'is_unknown' => false
            ];
        }

        return [
            'pincode_id' => null,
            'is_unknown' => true
        ];
    }

    protected function processSchedules(array $schedules, array & $data): void
    {
        $days = $this->extractDaysFromSchedules($schedules);
        $data['clinic_days'] = count($days) ? json_encode(array_values($days)) : null;

        $times = $this->extractTimeRangeFromSchedules($schedules);
        if ($times['start']) $data['clinic_start_time'] = $times['start'];
        if ($times['end'])   $data['clinic_end_time']   = $times['end'];

        $alt = $this->collectAlternativeTexts($schedules);
        if ($alt !== '') $data['alternative_schedule'] = $alt;
    }

    protected function resolveClinicFromSchedules(array $schedules): ?int
    {
        foreach ($schedules as $s) {
            if (!empty($s['clinic_id'])) {
                $cid = intval($s['clinic_id']);
                if (Client::where('id', $cid)->exists()) return $cid;
            }
            if (!empty($s['clinic_name'])) {
                $name = trim($s['clinic_name']);
                if ($name === '') continue;
                $found = Client::where('name', $name)->first();
                if (!$found) $found = Client::where('name', 'like', '%' . $name . '%')->first();
                if ($found) return $found->id;
            }
        }
        return null;
    }

    protected function extractDaysFromSchedules(array $schedules): array
    {
        $map = [
            'monday'    => 'Mon', 'mon' => 'Mon',
            'tuesday'   => 'Tue', 'tue' => 'Tue',
            'wednesday' => 'Wed', 'wed' => 'Wed',
            'thursday'  => 'Thu', 'thu' => 'Thu',
            'friday'    => 'Fri', 'fri' => 'Fri',
            'saturday'  => 'Sat', 'sat' => 'Sat',
            'sunday'    => 'Sun', 'sun' => 'Sun',
        ];

        $tokens = [];
        foreach ($schedules as $slot) {
            if (empty($slot['days'])) continue;
            $raw = $slot['days'];
            if (is_array($raw)) {
                $list = $raw;
            } else {
                $list = array_map('trim', explode(',', (string)$raw));
            }
            foreach ($list as $d) {
                $k = strtolower(trim($d));
                if ($k === '') continue;
                $tokens[] = $map[$k] ?? ucfirst(substr($k,0,3));
            }
        }
        $tokens = array_values(array_unique(array_filter($tokens)));
        return $tokens;
    }

    protected function extractTimeRangeFromSchedules(array $schedules): array
    {
        $starts = [];
        $ends = [];
        foreach ($schedules as $slot) {
            if (!empty($slot['start_time'])) {
                $t = $this->normalizeTime($slot['start_time']);
                if ($t) $starts[] = $t;
            }
            if (!empty($slot['end_time'])) {
                $t = $this->normalizeTime($slot['end_time']);
                if ($t) $ends[] = $t;
            }
        }
        if (!count($starts) && !count($ends)) return ['start'=>null,'end'=>null];

        sort($starts);
        sort($ends);
        $start = count($starts) ? $starts[0] : null;
        $end   = count($ends)   ? end($ends)        : null;

        return ['start'=>$start,'end'=>$end];
    }

    protected function collectAlternativeTexts(array $schedules): string
    {
        $parts = [];
        foreach ($schedules as $slot) {
            $txt = trim((string)($slot['alternative_text'] ?? ''));
            if ($txt !== '') $parts[] = $txt;
        }
        return implode(' | ', array_values(array_unique($parts)));
    }

    protected function normalizeTime($value): ?string
    {
        if (empty($value)) return null;
        try {
            $ts = strtotime((string)$value);
            if ($ts === false) return null;
            return date('H:i:s', $ts);
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function storeUploadedImage(\Illuminate\Http\UploadedFile $file): string
    {
        $path = $file->store('doctors', 'public');
        return basename($path);
    }

    protected function deleteImageIfExists(?string $filename): void
    {
        if (!$filename) return;
        $path = 'doctors/' . $filename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function allowedDoctorAttributes(array $input = []): array
    {
        $allowed = (new Doctor())->getFillable();
        return array_intersect_key($input, array_flip($allowed));
    }

    protected function normalizeRequestAliases(array $data): array
    {
        $map = [
            'photo' => 'profile_picture',
            'reg_no' => 'registration_no',
            'profile_text' => 'profile_details',
            'sub_category' => 'speciality',
            'clinic_free_text' => 'clinic_name',
        ];

        foreach ($map as $from => $to) {
            if (array_key_exists($from, $data) && !array_key_exists($to, $data)) {
                $data[$to] = $data[$from];
                unset($data[$from]);
            } elseif (array_key_exists($to, $data) && empty($data[$to]) && array_key_exists($from, $data)) {
                $data[$to] = $data[$from];
                unset($data[$from]);
            } else {
                if (array_key_exists($from, $data)) unset($data[$from]);
            }
        }

        return $data;
    }

    public function statesByCountry($countryId)
    {
        $countryId = intval($countryId);
        if ($countryId <= 0) {
            return response()->json(['success'=>false,'data'=>[]], 400);
        }

        $states = State::where('country_id', $countryId)
                   ->orderBy('name')->get(['id','name']);

        return response()->json(['success'=>true,'data'=>$states]);
    }

    public function districtsByState($stateId)
    {
        $stateId = intval($stateId);
        if ($stateId <= 0) {
            return response()->json(['success'=>false,'data'=>[]], 400);
        }

        $districts = District::where('state_id', $stateId)
                      ->orderBy('name')->get(['id','name']);

        return response()->json(['success'=>true,'data'=>$districts]);
    }

    public function citiesByDistrict($districtId)
    {
        $districtId = intval($districtId);
        if ($districtId <= 0) {
            return response()->json(['success'=>false,'data'=>[]], 400);
        }

        $cities = City::where('district_id', $districtId)
                  ->orderBy('name')->get(['id','name']);

        return response()->json(['success'=>true,'data'=>$cities]);
    }

    public function clinicsByCategory($categoryId)
    {
        $categoryId = intval($categoryId);
        if ($categoryId <= 0) {
            return response()->json(['success'=>true,'data'=>[]]);
        }

        $clinics = Client::where('category_id', $categoryId)
                   ->orderBy('name')->get(['id','name']);

        return response()->json(['success'=>true,'data'=>$clinics]);
    }

    /**
     * Export Excel (fallback to CSV if PhpSpreadsheet not configured)
     */
    public function exportExcel()
    {
        if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\IOFactory')) {
            // Fallback to CSV stream if phpspreadsheet isn't installed
            return $this->exportCsv();
        }

        // Minimal Excel export using PhpSpreadsheet
        $rows = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                    ->orderBy('id','desc')
                    ->cursor();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'ID','Name','Email','Phone','Secondary Phone','Speciality','Degree','Experience Years','Languages',
            'Clinic ID','Clinic Name','Clinic Days','Start Time','End Time','Alternative Schedule',
            'Registration No','Council','Pincode','Website','WhatsApp','Facebook','Instagram','Address',
            'Country','State','District','City','Category','Status','Consultation Mode','Created At'
        ];

        $sheet->fromArray($headers, null, 'A1');
        $rowNum = 2;
        foreach ($rows as $r) {
            $sheet->fromArray([
                $r->id,
                $r->name,
                $r->email,
                $r->phone_number,
                $r->phone_number_2,
                $r->speciality,
                $r->degree,
                $r->experience_years,
                $r->languages,
                $r->clinic_id,
                $r->clinic_name,
                $r->clinic_days,
                $r->clinic_start_time,
                $r->clinic_end_time,
                $r->alternative_schedule,
                $r->registration_no,
                $r->council,
                $r->pincode,
                $r->website,
                $r->whatsapp,
                $r->facebook,
                $r->instagram,
                $r->address,
                $r->country->name ?? '',
                $r->state->name ?? '',
                $r->district->name ?? '',
                $r->city->name ?? '',
                $r->category->name ?? '',
                $r->status,
                $r->consultation_mode,
                optional($r->created_at)->toDateTimeString(),
            ], null, 'A'. $rowNum);
            $rowNum++;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'doctors_'.date('Y-m-d').'.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ]);
    }

    /**
     * Export PDF placeholder — implement using your preferred PDF library (dompdf/snappy) if needed
     */
    public function exportPdf()
    {
        // Simple placeholder: return CSV if PDF not implemented
        // You can implement a proper PDF rendering using Dompdf or Snappy
        return $this->exportCsv();
    }

    /**
     * Search doctors with filters
     */
    public function search(Request $request)
    {
        try {
            $query = Doctor::with(['category', 'city', 'state', 'clinic'])
                ->when($request->filled('status'), function ($q) use ($request) {
                    return $q->where('status', $request->status);
                });

            // Basic search
            if ($request->filled('q')) {
                $searchTerm = $request->q;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('speciality', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('degree', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('category', function ($q) use ($searchTerm) {
                          $q->where('name', 'LIKE', "%{$searchTerm}%");
                      });
                });
            }

            // Location filters
            if ($request->filled('city_id')) {
                $query->where('city_id', $request->city_id);
            }

            if ($request->filled('state_id')) {
                $query->where('state_id', $request->state_id);
            }

            if ($request->filled('pincode')) {
                $query->where(function ($q) use ($request) {
                    $q->where('manual_pincode', $request->pincode)
                      ->orWhereHas('pincode', function ($q) use ($request) {
                          $q->where('pincode', $request->pincode);
                      });
                });
            }

            // Category filter
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Consultation mode
            if ($request->filled('consultation_mode')) {
                if ($request->consultation_mode === 'online') {
                    $query->hasOnlineConsultation();
                } else {
                    $query->where('consultation_mode', $request->consultation_mode);
                }
            }

            // Languages
            if ($request->filled('language')) {
                $query->where('languages', 'LIKE', "%{$request->language}%");
            }

            // Experience range
            if ($request->filled('min_experience')) {
                $query->where('experience_years', '>=', $request->min_experience);
            }
            if ($request->filled('max_experience')) {
                $query->where('experience_years', '<=', $request->max_experience);
            }

            // Pagination
            $perPage = $request->get('per_page', 15);
            $doctors = $query->paginate($perPage);

            // Transform results
            $doctors->getCollection()->transform(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'display_name' => $doctor->display_name,
                    'speciality' => $doctor->speciality,
                    'degree' => $doctor->degree,
                    'experience_years' => $doctor->experience_years,
                    'experience_display' => $doctor->experience_display,
                    'category' => $doctor->category?->name,
                    'city' => $doctor->city?->name,
                    'state' => $doctor->state?->name,
                    'pincode' => $doctor->pincode,
                    'profile_picture' => $doctor->profile_picture ? Storage::url($doctor->profile_picture) : null,
                    'consultation_mode' => $doctor->consultation_mode,
                    'has_online' => $doctor->hasOnlineConsultation(),
                    'visiting_time' => $doctor->visiting_time,
                    'phone_number' => $doctor->phone_number,
                    'clinic_name' => $doctor->clinic_name ?: ($doctor->clinic?->name),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $doctors,
                'total' => $doctors->total(),
                'filters' => $request->all(),
                'message' => 'Search completed successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get doctors statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total' => Doctor::count(),
                'active' => Doctor::where('status', 'active')->count(),
                'inactive' => Doctor::where('status', 'inactive')->count(),
                'consultation_mode' => [
                    'offline' => Doctor::where('consultation_mode', 'offline')->count(),
                    'online' => Doctor::where('consultation_mode', 'online')->count(),
                    'both' => Doctor::where('consultation_mode', 'both')->count(),
                    'face-to-face' => Doctor::where('consultation_mode', 'face-to-face')->count(),
                ],
                'by_category' => Doctor::with('category')
                    ->select('category_id', DB::raw('count(*) as total'))
                    ->groupBy('category_id')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'category_id' => $item->category_id,
                            'category_name' => $item->category?->name,
                            'total' => $item->total
                        ];
                    }),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistics retrieved successfully.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update doctor status
     */
    public function bulkStatusUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_ids' => 'required|array',
            'doctor_ids.*' => 'exists:doctor_profiles,id',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $count = Doctor::whereIn('id', $request->doctor_ids)
                ->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => "Status updated for {$count} doctor(s)."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update doctor status.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}