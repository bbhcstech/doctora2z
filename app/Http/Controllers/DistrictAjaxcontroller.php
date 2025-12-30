<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\District;
use App\Models\Pincode;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Barryvdh\DomPDF\Facade\Pdf;

class DistrictAjaxController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name')->get(['id','name']);
        $states    = State::orderBy('name')->get(['id','name','country_id']);
        $districts = District::with(['state:id,name,country_id','state.country:id,name'])
            ->orderByDesc('id')
            ->get(['id','name','state_id']);

        return view('admin.district.inline', compact('countries','districts','states'));
    }

    /**
     * POST /districts/import
     * Accepts .xls, .xlsx, .csv
     */
    public function import(Request $request)
    {
        $request->validate([
            'excel_file' => ['required','file','mimes:xls,xlsx,csv'],
        ], [
            'excel_file.required' => 'Please upload an Excel/CSV file.',
            'excel_file.mimes'    => 'Allowed file types: xls, xlsx, csv.',
        ]);

        try {
            $file = $request->file('excel_file');

            // read sheets -> first sheet
            $sheets = \Maatwebsite\Excel\Facades\Excel::toArray([], $file);
            $rows = is_array($sheets) && count($sheets) ? $sheets[0] : [];

            $rows = array_map(function($r) {
                return is_array($r) ? $r : (is_object($r) && method_exists($r,'toArray') ? $r->toArray() : (array)$r);
            }, $rows);

            if (empty($rows)) {
                return response()->json(['success' => false, 'message' => 'File parsed but contains no rows.'], 422);
            }

            $map = $this->detectHeaderMap($rows[0]);
            $startIndex = (isset($map['is_heading']) && $map['is_heading'] === true) ? 1 : 0;

            $created = 0;
            $errors = [];

            for ($i = $startIndex; $i < count($rows); $i++) {
                $r = $rows[$i];

                $country = $this->cell($r, $map['country'] ?? 0);
                $state   = $this->cell($r, $map['state'] ?? 1);
                $district= $this->cell($r, $map['district'] ?? 2);
                $city    = $this->cell($r, 3) ?? $this->cell($r, 'city') ?? null;
                $pincode = $this->cell($r, 4) ?? $this->cell($r, 'pincode') ?? null;

                if (! $district || ! $city || ! $pincode) {
                    $errors[] = ['row' => $i + 1, 'reason' => 'Missing district, city or pincode'];
                    continue;
                }

                $district = $this->normalizeName((string)$district);
                $city     = $this->normalizeName((string)$city);
                $state    = $this->normalizeName((string)$state);
                $country  = $this->normalizeName((string)$country);

                $countryModel = Country::where('name', $country)->first();
                if (! $countryModel && $country) {
                    $errors[] = ['row' => $i + 1, 'reason' => "Country '{$country}' not found"];
                    continue;
                }

                $stateModel = null;
                if ($countryModel) {
                    $stateModel = State::where('country_id', $countryModel->id)->where('name', $state)->first();
                } else {
                    $stateModel = State::where('name', $state)->first();
                }
                if (! $stateModel && $state) {
                    $errors[] = ['row' => $i + 1, 'reason' => "State '{$state}' not found"];
                    continue;
                }

                $districtModel = District::firstOrCreate(
                    ['name' => $district, 'state_id' => $stateModel->id],
                    ['name' => $district, 'state_id' => $stateModel->id]
                );

                $cityModel = City::firstOrCreate(
                    ['name' => $city, 'district_id' => $districtModel->id],
                    ['name' => $city, 'district_id' => $districtModel->id]
                );

                // Prepare payload
                $payload = [
                    'pincode'    => (string)$pincode,
                    'city_id'    => $cityModel->id,
                    'district_id'=> $districtModel->id,
                    'state_id'   => $stateModel->id,
                    'country_id' => $countryModel?->id ?? $stateModel->country_id ?? null,
                ];

                // Attempt to set raw_json if present in row (column 5 or 'raw_json')
                $rawVal = $this->cell($r, 5) ?? $this->cell($r, 'raw_json') ?? null;
                if ($rawVal !== null) {
                    if (is_string($rawVal)) {
                        $decoded = json_decode($rawVal, true);
                        $payload['raw_json'] = json_last_error() === JSON_ERROR_NONE ? $decoded : $rawVal;
                    } else {
                        $payload['raw_json'] = $rawVal;
                    }
                }

                // Because DB enforces unique(pincode), we must update existing pincode rows
                try {
                    $existing = Pincode::where('pincode', (string)$pincode)->first();
                    if ($existing) {
                        // update existing row to reflect the imported mapping
                        $existing->update($payload);
                        // we don't increment created count for updates
                    } else {
                        // safe to create
                        Pincode::create($payload);
                        $created++;
                    }
                } catch (\Illuminate\Database\QueryException $qe) {
                    // Fallback: if duplicate key error raced in, fetch existing and update
                    // errorInfo[1] == 1062 is duplicate entry (MySQL)
                    if (isset($qe->errorInfo[1]) && (int)$qe->errorInfo[1] === 1062) {
                        $existing = Pincode::where('pincode', (string)$pincode)->first();
                        if ($existing) {
                            $existing->update($payload);
                        } else {
                            // rethrow if unexpected
                            throw $qe;
                        }
                    } else {
                        throw $qe;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Import finished. Created: {$created}. Rows with issues: ".count($errors),
                'errors'  => $errors,
            ]);
        } catch (\Maatwebsite\Excel\Validators\Failure $ex) {
            return response()->json(['success'=>false,'message'=>'Import validation failed','errors'=>$ex->failures()], 422);
        } catch (\Throwable $e) {
            \Log::error('District import failed', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Failed to import file. See server logs for details.'], 500);
        }
    }

    /* =========================================================
     | EXPORTS
     * ========================================================= */
    public function exportExcel(Request $request)
    {
        [$rows, $headings] = $this->getExportRows($request);
        $export = new class($rows, $headings) implements FromCollection, WithHeadings {
            public function __construct(public $rows, public $headings) {}
            public function collection() { return $this->rows; }
            public function headings(): array { return $this->headings; }
        };
        return Excel::download($export, 'districts.xlsx');
    }

    public function exportCsv(Request $request)
    {
        [$rows, $headings] = $this->getExportRows($request);
        $export = new class($rows, $headings) implements FromCollection, WithHeadings {
            public function __construct(public $rows, public $headings) {}
            public function collection() { return $this->rows; }
            public function headings(): array { return $this->headings; }
        };
        return Excel::download($export, 'districts.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf(Request $request)
    {
        [$rows, $headings] = $this->getExportRows($request);

        $html = view('admin.district.export_pdf', [
            'headings' => $headings,
            'rows'     => $rows,
            'title'    => 'Districts Export',
        ])->render();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
        return $pdf->download('districts.pdf');
    }

    private function getExportRows(Request $request): array
    {
        $search = trim((string) ($request->input('search') ?? $request->input('search.value') ?? ''));

        $q = DB::table('districts as d')
            ->join('states as s', 's.id', '=', 'd.state_id')
            ->join('countries as c', 'c.id', '=', 's.country_id')
            ->select([
                'd.id as ID',
                'd.name as District',
                's.name as State',
                'c.name as Country',
            ]);

        if ($search !== '') {
            $like = "%{$search}%";
            $q->where(function($qq) use ($like) {
                $qq->where('d.name', 'like', $like)
                   ->orWhere('s.name', 'like', $like)
                   ->orWhere('c.name', 'like', $like);
            });
        }

        $rows = collect($q->orderBy('c.name')->orderBy('s.name')->orderBy('d.name')->get())
            ->map(fn($r) => (array) $r);

        $headings = ['ID','District','State','Country'];

        return [$rows, $headings];
    }

    /* =========================================================
     | CRUD + lookups (districts)
     * ========================================================= */
    public function storeOrUpdate(Request $request)
    {
        $request->merge(['name' => $this->normalizeName($request->input('name'))]);
        $id = $request->input('id');

        $validated = $request->validate([
            'id'       => ['nullable','integer','exists:districts,id'],
            'name'     => [
                'required','string','max:255',
                Rule::unique('districts','name')
                    ->where(fn($q) => $q->where('state_id', $request->input('state_id')))
                    ->ignore($id),
            ],
            'state_id' => ['required','integer','exists:states,id'],
        ], [
            'name.required'     => 'District name is required.',
            'name.unique'       => 'This district already exists in the selected state.',
            'state_id.required' => 'State is required.',
        ]);

        try {
            $district = District::updateOrCreate(
                ['id' => $id],
                ['name' => $validated['name'], 'state_id' => $validated['state_id']]
            );

            $district->load('state.country');

            return response()->json([
                'success'  => true,
                'message'  => $id ? 'District updated successfully.' : 'District created successfully.',
                'district' => [
                    'id'        => $district->id,
                    'name'      => $district->name,
                    'state_id'  => $district->state_id,
                    'state'     => [
                        'id'      => $district->state->id,
                        'name'    => $district->state->name,
                        'country' => [
                            'id'   => $district->state->country->id,
                            'name' => $district->state->country->name,
                        ],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('District store/update failed', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Failed to save district.'], 500);
        }
    }

    public function edit(int $id)
    {
        try {
            $district = District::with(['state.country'])->findOrFail($id);
            return response()->json([
                'success' => true,
                'data'    => [
                    'id'       => $district->id,
                    'name'     => $district->name,
                    'state_id' => $district->state_id,
                    'state'    => [
                        'id'      => $district->state->id,
                        'name'    => $district->state->name,
                        'country' => [
                            'id'   => $district->state->country->id,
                            'name' => $district->state->country->name,
                        ],
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Fetch district failed', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Unable to load district.'], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            District::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'District deleted successfully.']);
        } catch (\Throwable $e) {
            Log::error('District deletion failed', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Failed to delete district.'], 500);
        }
    }

    public function getStatesByCountry(Request $request)
    {
        $countryId = $request->integer('country_id') ?? $request->route('country_id');
        try {
            if (!$countryId) return response()->json(['success' => true, 'data' => []]);

            $states = State::where('country_id', $countryId)
                ->orderBy('name')
                ->get(['id','name']);

            return response()->json(['success' => true, 'data' => $states]);
        } catch (\Throwable $e) {
            Log::error('Get states by country failed', ['exception' => $e]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    // optional helper route used earlier in routes listing
    public function getAllStates()
    {
        try {
            $states = State::orderBy('name')->get(['id','name','country_id']);
            return response()->json(['success' => true, 'data' => $states]);
        } catch (\Throwable $e) {
            Log::error('Get all states failed', ['exception' => $e]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    public function getDistrictsByState(Request $request)
    {
        $stateId = $request->integer('state_id') ?? $request->route('state_id');
        try {
            if (!$stateId) return response()->json(['success' => true, 'data' => []]);

            $items = District::where('state_id', $stateId)
                ->orderBy('name')
                ->get(['id','name']);

            return response()->json(['success' => true, 'data' => $items]);
        } catch (\Throwable $e) {
            Log::error('Get districts by state failed', ['exception' => $e]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    public function getCitiesByDistrict(Request $request)
    {
        $districtId = $request->integer('district_id') ?? $request->route('district_id');
        try {
            if (!$districtId) return response()->json(['success' => true, 'data' => []]);

            $items = City::where('district_id', $districtId)
                ->orderBy('name')
                ->get(['id','name']);

            return response()->json(['success' => true, 'data' => $items]);
        } catch (\Throwable $e) {
            Log::error('Get cities by district failed', ['exception' => $e]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    /* -------------------- Pincode endpoints -------------------- */

    /**
     * Return pincodes for a given city
     * GET /pincodes/by-city?city_id=...
     */
    public function getPincodesByCity(Request $request)
    {
        $cityId = $request->integer('city_id') ?? $request->route('city_id');

        try {
            if (! $cityId) {
                return response()->json(['success' => true, 'data' => []]);
            }

            $items = Pincode::where('city_id', $cityId)
                ->orderBy('pincode')
                ->get(['id', 'pincode', 'city_id']);

            // attach city name for convenience
            $city = City::find($cityId);
            $data = $items->map(fn($p) => [
                'id' => $p->id,
                'pincode' => $p->pincode,
                'city_id' => $p->city_id,
                'city_name' => $city?->name ?? null,
            ]);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            Log::error('Get pincodes by city failed', ['exception' => $e]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    /**
     * Return pincodes for a given district
     * GET /pincodes/by-district?district_id=...
     * This returns city_name for each pincode (used by the blade)
     */
    public function getPincodesByDistrict(Request $request)
    {
        $districtId = $request->integer('district_id') ?? $request->route('district_id');

        try {
            if (! $districtId) {
                return response()->json(['success' => true, 'data' => []]);
            }

            $cityIds = City::where('district_id', $districtId)->pluck('id');
            if ($cityIds->isEmpty()) {
                return response()->json(['success' => true, 'data' => []]);
            }

            $items = Pincode::whereIn('city_id', $cityIds)
                ->orderBy('pincode')
                ->get(['id', 'pincode', 'city_id']);

            // load city names in one go
            $cities = City::whereIn('id', $items->pluck('city_id')->unique())->pluck('name', 'id')->toArray();

            $data = $items->map(fn($p) => [
                'id' => $p->id,
                'pincode' => $p->pincode,
                'city_id' => $p->city_id,
                'city_name' => $cities[$p->city_id] ?? null,
            ]);

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            Log::error('Get pincodes by district failed', ['exception' => $e]);
            return response()->json(['success' => false, 'data' => []], 500);
        }
    }

    /**
     * Find exact pincode
     * GET /pincodes/find?pincode=712409
     */
    public function findByPincode(Request $request)
    {
        $pincode = trim((string) $request->input('pincode', ''));

        if ($pincode === '') {
            return response()->json(['success' => false, 'message' => 'pincode is required'], 422);
        }

        try {
            $pin = Pincode::where('pincode', $pincode)
                ->with(['country:id,name', 'state:id,name', 'district:id,name', 'city:id,name'])
                ->first();

            if (! $pin) {
                return response()->json(['success' => true, 'data' => null]);
            }

            $data = [
                'id'         => $pin->id,
                'pincode'    => $pin->pincode,
                'country'    => $pin->country?->only('id','name') ?? null,
                'state'      => $pin->state?->only('id','name') ?? null,
                'district'   => $pin->district?->only('id','name') ?? null,
                'city'       => $pin->city?->only('id','name') ?? null,
                'raw'        => $pin->raw_json,
            ];

            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Throwable $e) {
            Log::error('Find pincode failed', ['exception' => $e, 'pincode' => $pincode]);
            return response()->json(['success' => false, 'message' => 'Lookup failed'], 500);
        }
    }

    /**
     * Create or update pincode
     * POST /pincodes/store-or-update
     */
    public function storeOrUpdatePincode(Request $request)
    {
        $request->merge(['pincode' => trim((string) $request->input('pincode'))]);
        $id = $request->input('id');

        $validated = $request->validate([
            'id'        => ['nullable','integer','exists:pincodes,id'],
            'pincode'   => ['required','string','max:20', Rule::unique('pincodes','pincode')->ignore($id)],
            'city_id'   => ['required','integer','exists:cities,id'],
            'raw_json'  => ['nullable'],
        ], [
            'pincode.required' => 'Pincode is required.',
            'pincode.unique'   => 'This pincode already exists.',
            'city_id.required' => 'City is required.',
        ]);

        try {
            // get city to derive country/state/district
            $city = City::with(['district','district.state','district.state.country'])->find($validated['city_id']);
            $payload = [
                'pincode' => $validated['pincode'],
                'city_id' => $validated['city_id'],
            ];

            // if city exists, fill fk columns to avoid DB NOT NULL errors
            if ($city) {
                $payload['district_id'] = $city->district_id ?? null;
                $payload['state_id']   = $city->district->state_id ?? ($city->district->state->id ?? null ?? null);
                $payload['country_id'] = $city->district->state->country_id ?? ($city->district->state->country->id ?? null ?? null);
            }

            if (isset($validated['raw_json'])) {
                $raw = $validated['raw_json'];
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $payload['raw_json'] = json_last_error() === JSON_ERROR_NONE ? $decoded : $raw;
                } else {
                    $payload['raw_json'] = $raw;
                }
            }

            // scope update/create by both pincode and city_id to avoid collisions
            $pincode = Pincode::updateOrCreate(
                ['pincode' => $validated['pincode'], 'city_id' => $validated['city_id']],
                $payload
            );

            $pincode->load('country','state','district','city');

            return response()->json([
                'success' => true,
                'message' => $id ? 'Pincode updated successfully.' : 'Pincode created successfully.',
                'pincode' => $pincode->toArray(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Pincode store/update failed', ['exception' => $e, 'payload' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Failed to save pincode.'], 500);
        }
    }

    /**
     * Delete a pincode
     * DELETE /pincodes/{id}
     */
    public function destroyPincode(int $id)
    {
        try {
            Pincode::findOrFail($id)->delete();
            return response()->json(['success' => true, 'message' => 'Pincode deleted successfully.']);
        } catch (\Throwable $e) {
            Log::error('Pincode deletion failed', ['exception' => $e]);
            return response()->json(['success' => false, 'message' => 'Failed to delete pincode.'], 500);
        }
    }

    /**
     * Download unified sample CSV (no office_name)
     */
    public function downloadPincodeSample()
    {
        $headers = ['Content-Type' => 'text/csv'];
        $callback = function () {
            $out = fopen('php://output', 'w');
            // columns: country, state, district, city, pincode
            fputcsv($out, ['country', 'state', 'district', 'city', 'pincode']);
            fputcsv($out, ['India', 'West Bengal', 'Hooghly', 'Singur', '712409']);
            fputcsv($out, ['India', 'West Bengal', 'Hooghly', 'Bansberia', '712502']);
            fclose($out);
        };

        return response()->streamDownload($callback, 'locations_sample.csv', $headers);
    }

    /* -------------------- helpers -------------------- */

    private function normalizeName(?string $s): string
    {
        $s = preg_replace('/\s+/u', ' ', trim((string)$s));
        return ucwords(mb_strtolower($s));
    }

    private function detectHeaderMap($firstRow): array
    {
        if (is_array($firstRow)) {
            $asArray = $firstRow;
        } elseif (is_object($firstRow) && method_exists($firstRow, 'toArray')) {
            $asArray = $firstRow->toArray();
        } else {
            $asArray = (array) $firstRow;
        }

        // default mapping (legacy): country,state,district in first three cols
        $map = ['is_heading' => false, 'country' => 0, 'state' => 1, 'district' => 2];

        // associative keys
        $keys = array_map(fn($k)=>mb_strtolower(trim((string)$k)), array_keys($asArray));
        if (in_array('country',$keys,true) && in_array('state',$keys,true) && in_array('district',$keys,true)) {
            return ['is_heading'=>true,'country'=>'country','state'=>'state','district'=>'district'];
        }

        // values as header row
        $vals = array_map(fn($v)=>mb_strtolower(trim((string)$v)), array_values($asArray));
        if (in_array('country',$vals,true) && in_array('state',$vals,true) && in_array('district',$vals,true)) {
            $map['is_heading'] = true;
        }
        return $map;
    }

    private function cell($row, $key)
    {
        if (is_array($row))               return $row[$key] ?? null;
        if ($row instanceof \ArrayAccess) return $row[$key] ?? null;
        if (is_object($row) && method_exists($row,'get')) return $row->get($key, null);
        if (is_int($key))                 return $row[$key] ?? null;
        return null;
    }

    /**
     * Create (or find) a city and create/update its pincode in one request.
     * POST /locations/store-city-pincode
     */
    public function storeCityWithPincode(Request $request)
    {
        // normalize incoming city name
        $request->merge(['city' => $this->normalizeName($request->input('city'))]);

        $validated = $request->validate([
            'country_id' => ['required','integer','exists:countries,id'],
            'state_id'   => ['required','integer','exists:states,id'],
            'district_id'=> ['required','integer','exists:districts,id'],
            'city'       => ['required','string','max:255'],
            'pincode'    => ['required','string','max:20'],
            'raw_json'   => ['nullable'],
        ], [
            'country_id.required'  => 'Country is required.',
            'state_id.required'    => 'State is required.',
            'district_id.required' => 'District is required.',
            'city.required'        => 'City name is required.',
            'pincode.required'     => 'Pincode is required.',
        ]);

        DB::beginTransaction();
        try {
            // ensure the city exists (firstOrCreate scoped to district)
            $city = City::firstOrCreate(
                ['name' => $validated['city'], 'district_id' => $validated['district_id']],
                ['name' => $validated['city'], 'district_id' => $validated['district_id']]
            );

            // Build payload including required FK columns to avoid DB errors
            $payload = [
                'pincode'    => $validated['pincode'],
                'city_id'    => $city->id,
                'district_id'=> $validated['district_id'],
                'state_id'   => $validated['state_id'],
                'country_id' => $validated['country_id'],
            ];

            if (isset($validated['raw_json'])) {
                $raw = $validated['raw_json'];
                if (is_string($raw)) {
                    $decoded = json_decode($raw, true);
                    $payload['raw_json'] = json_last_error() === JSON_ERROR_NONE ? $decoded : $raw;
                } else {
                    $payload['raw_json'] = $raw;
                }
            }

            // scope by both pincode + city_id to avoid cross-city collisions
            $pincode = Pincode::updateOrCreate(
                ['pincode' => $validated['pincode'], 'city_id' => $city->id],
                $payload
            );

            DB::commit();

            // eager load relations used elsewhere
            $pincode->load('country','state','district','city');

            return response()->json([
                'success' => true,
                'message' => 'City and pincode saved successfully.',
                'city'    => $city->toArray(),
                'pincode' => $pincode->toArray(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('storeCityWithPincode failed', ['exception' => $e, 'payload' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Failed to save city and pincode.'], 500);
        }
    }

    // updated: now returns readable names alongside ids
    public function listAllPincodes()
    {
        $rows = Pincode::with([
                'country:id,name',
                'state:id,name',
                'district:id,name',
                'city:id,name',
            ])
            ->orderByDesc('id')
            ->get(['id','pincode','country_id','state_id','district_id','city_id','created_at','updated_at'])
            ->map(function($p){
                return [
                    'id'            => $p->id,
                    'pincode'       => $p->pincode,

                    // keep ids for editing
                    'country_id'    => $p->country_id,
                    'state_id'      => $p->state_id,
                    'district_id'   => $p->district_id,
                    'city_id'       => $p->city_id,

                    // names for display
                    'country_name'  => $p->country?->name,
                    'state_name'    => $p->state?->name,
                    'district_name' => $p->district?->name,
                    'city_name'     => $p->city?->name,

                    'created_at'    => optional($p->created_at)->toDateTimeString(),
                    'updated_at'    => optional($p->updated_at)->toDateTimeString(),
                ];
            });

        return response()->json(['success'=>true,'data'=>$rows]);
    }
}
