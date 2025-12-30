<?php

namespace App\Http\Controllers;

use App\Models\Client;   // clinics
use App\Models\Doctor;   // doctor_profiles
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user->id;

        // -------------------------------
        // Admin Dashboard
        // -------------------------------
        if ($user->role === 'admin') {
            $totalClinics = Client::count();
            $totalDoctors = Doctor::count();

            $approvedDoctorCount = Doctor::where('status', 'active')->count();
            $pendingDoctorCount  = Doctor::where('status', 'inactive')->count();

            $approvedClinicCount = Client::where('status', 'Approved')->count();
            $pendingClinicCount  = Client::where('status', 'Pending')->count();

            return view('dashboard', compact(
                'totalClinics',
                'totalDoctors',
                'approvedDoctorCount',
                'approvedClinicCount',
                'pendingDoctorCount',
                'pendingClinicCount'
            ));
        }

        // -------------------------------
        // Clinic Dashboard
        // -------------------------------
        if ($user->role === 'clinic') {
            // Find the clinic linked to this user
            $clinic = Client::with(['city', 'district', 'state', 'country'])
                            ->where('auth_id', $userId)
                            ->first();

            if (! $clinic) {
                return view('clinic-dashboard')->with('error', 'No clinic found for your account.');
            }

            // Doctors linked to this clinic
            $totalDoctorCount = Doctor::where('clinic_id', $clinic->id)->count();
            $approvedDoctorCount = Doctor::where('status', 'active')
                                         ->where('clinic_id', $clinic->id)
                                         ->count();
            $pendingDoctorCount = Doctor::where('status', 'inactive')
                                        ->where('clinic_id', $clinic->id)
                                        ->count();

            // Example doctor visit counter (if you have doctor_visiting_count table)
            $doctorVisitCount = DB::table('doctor_visiting_count')
                ->whereIn('doctor_id', function ($query) use ($clinic) {
                    $query->select('id')
                          ->from('doctor_profiles')
                          ->where('clinic_id', $clinic->id);
                })
                ->sum('visit_count');

            return view('clinic-dashboard', compact(
                'approvedDoctorCount',
                'totalDoctorCount',
                'pendingDoctorCount',
                'doctorVisitCount'
            ));
        }

        // -------------------------------
        // Doctor Dashboard
        // -------------------------------
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('email', $user->email)->first();

            // ✅ Ensure degree shows correctly
            $degreesArr = [];
            if ($doctor) {
                if (is_array($doctor->degree)) {
                    $degreesArr = $doctor->degree;
                } elseif (is_string($doctor->degree) && $doctor->degree !== '') {
                    $parsed = @json_decode($doctor->degree, true);
                    $degreesArr = is_array($parsed)
                        ? $parsed
                        : preg_split("/\r\n|\n|\r/", $doctor->degree);
                }
                $degreesArr = array_values(array_filter(array_map('trim', $degreesArr)));
            }

            return view('doctor-dashboard', compact('doctor', 'degreesArr'));
        }

        abort(403, 'Unauthorized');
    }
}
