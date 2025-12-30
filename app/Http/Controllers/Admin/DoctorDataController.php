<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\District;
use App\Models\State;
use App\Models\Category;
use App\Models\Pincode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DoctorDataController extends Controller
{
    /**
     * Get doctors for DataTables
     */
    public function datatable(Request $request)
    {
        $query = Doctor::with(['country', 'state', 'district', 'city', 'category', 'clinic', 'pincode']);

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('phone_number_2', 'like', "%{$search}%")
                  ->orWhere('speciality', 'like', "%{$search}%")
                  ->orWhere('registration_no', 'like', "%{$search}%")
                  ->orWhere('council', 'like', "%{$search}%")
                  ->orWhere('manual_pincode', 'like', "%{$search}%")
                  ->orWhere('website', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhere('facebook', 'like', "%{$search}%")
                  ->orWhere('instagram', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('pincode', function($q) use ($search) {
                      $q->where('pincode', 'like', "%{$search}%");
                  });
            });
        }

        // Total records
        $totalRecords = $query->count();

        // Order by
        if ($request->has('order') && count($request->order)) {
            $orderColumn = $request->order[0]['column'];
            $orderDirection = $request->order[0]['dir'];
            
            $columns = [
                0 => 'id',
                1 => 'name',
                2 => 'email',
                3 => 'phone_number',
                4 => 'phone_number_2',
                5 => 'speciality',
                6 => 'registration_no',
                7 => 'council',
                8 => 'manual_pincode',
                9 => 'website',
                10 => 'whatsapp',
                11 => 'facebook',
                12 => 'instagram',
                13 => 'address',
            ];

            if (isset($columns[$orderColumn])) {
                $query->orderBy($columns[$orderColumn], $orderDirection);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $query->skip($start)->take($length);

        // Get data
        $doctors = $query->get();

        $data = $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'display_name' => $doctor->display_name,
                'email' => $doctor->email,
                'phone_number' => $doctor->phone_number,
                'phone_number_2' => $doctor->phone_number_2,
                'speciality' => $doctor->speciality,
                'registration_no' => $doctor->registration_no,
                'council' => $doctor->council,
                'pincode' => $doctor->pincode,
                'website' => $doctor->website,
                'whatsapp' => $doctor->whatsapp,
                'facebook' => $doctor->facebook,
                'instagram' => $doctor->instagram,
                'address' => $doctor->address,
                'country' => $doctor->country ? $doctor->country->name : '',
                'state' => $doctor->state ? $doctor->state->name : '',
                'city' => $doctor->city ? $doctor->city->name : '',
                'category' => $doctor->category ? $doctor->category->name : '',
                'clinic' => $doctor->clinic ? $doctor->clinic->name : '',
                'status' => $doctor->status,
                'consultation_mode' => $doctor->consultation_mode,
                'experience_years' => $doctor->experience_years,
                'languages' => $doctor->languages,
                'profile_picture_url' => $doctor->profile_picture ? Storage::url($doctor->profile_picture) : null,
                'actions' => '' // You can add action buttons here
            ];
        });

        return response()->json([
            'draw' => $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    /**
     * Search doctors with filters
     */
    public function search(Request $request)
    {
        $query = Doctor::with(['category', 'city', 'state', 'clinic']);

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

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Consultation mode
        if ($request->filled('consultation_mode')) {
            if ($request->consultation_mode === 'online') {
                $query->whereIn('consultation_mode', ['online', 'both']);
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
    }

    /**
     * Get doctor statistics
     */
    public function statistics()
    {
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
            'by_city' => Doctor::with('city')
                ->select('city_id', DB::raw('count(*) as total'))
                ->groupBy('city_id')
                ->orderBy('total', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    return [
                        'city_id' => $item->city_id,
                        'city_name' => $item->city?->name,
                        'total' => $item->total
                    ];
                })
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'message' => 'Statistics retrieved successfully.'
        ]);
    }

    /**
     * Bulk operations
     */
    public function bulkUpdateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_profiles,id',
            'status' => 'required|in:active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $count = Doctor::whereIn('id', $request->ids)
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

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array',
            'ids.*' => 'exists:doctor_profiles,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $doctors = Doctor::whereIn('id', $request->ids)->get();
            
            foreach ($doctors as $doctor) {
                if ($doctor->profile_picture) {
                    Storage::disk('public')->delete('doctors/' . $doctor->profile_picture);
                }
                $doctor->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($doctors) . ' doctor(s) deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete doctors.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all doctors for export
     */
    public function getAllForExport()
    {
        $doctors = Doctor::with(['country','state','district','city','category','clinic','pincode'])
                        ->orderBy('id', 'desc')
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $doctors
        ]);
    }
}