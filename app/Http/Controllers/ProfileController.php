<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show profile. If $id provided and current user is admin, show that doctor's profile.
     * Otherwise: if user is doctor -> show their doctor profile; else show generic user profile.
     */
    public function show($id = null): View
    {
        $user = Auth::user();

        // Admin viewing a doctor's profile
        if ($id && $user->role === 'admin') {
            $doctor = Doctor::find($id);
            if (! $doctor) abort(404, 'Doctor not found.');
            return view('profile.show', compact('doctor'));
        }

        // Doctor viewing their own profile
        if ($user->role === 'doctor') {
            $doctor = Doctor::where('auth_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if (! $doctor) abort(404, 'Doctor profile not found.');
            return view('profile.show', compact('doctor'));
        }

        // Fallback: generic user profile view
        return view('profile.show', ['user' => $user]);
    }

    /**
     * Edit profile form.
     * - Admin may edit any doctor by passing $id.
     * - Doctor edits their own doctor record.
     * - Other roles edit user profile.
     */
   public function edit($id = null): View
{
    $user = Auth::user();

    // Admin editing any doctor's profile
    if ($id && $user->role === 'admin') {
        $doctor = Doctor::find($id);
        if (! $doctor) abort(404, 'Doctor not found.');
        return view('admin.doctor.edit', compact('doctor'));
    }

    // Doctor editing own profile
    if ($user->role === 'doctor') {
        $doctor = Doctor::where('user_id', $user->id)   // fixed: user_id instead of auth_id
            ->orWhere('email', $user->email)
            ->first();

        if (! $doctor) abort(404, 'Doctor profile not found.');
        return view('admin.doctor.edit', compact('doctor'));
    }

    // Other users edit their user profile
    if (in_array($user->role, ['admin', 'clinic', 'manager', 'user'], true)) {
        return view('profile.edit', ['user' => $user]);
    }

    abort(403, 'Unauthorized');
}

/**
 * Update profile.
 * - If $id passed and admin: update that doctor.
 * - If authenticated doctor: update own doctor record.
 * - Otherwise update User model.
 */
public function update(Request $request, $id = null): RedirectResponse
{
    $user = Auth::user();

    // Resolve target: doctor or user
    $targetDoctor = null;

    if ($id && $user->role === 'admin') {
        $targetDoctor = Doctor::find($id);
        if (! $targetDoctor) abort(404, 'Doctor not found.');
    } elseif ($user->role === 'doctor') {
        $targetDoctor = Doctor::where('user_id', $user->id)   // fixed here too
            ->orWhere('email', $user->email)
            ->first();

        if (! $targetDoctor) abort(404, 'Doctor profile not found.');
    }

    // -------------------------
    // Doctor update flow
    // -------------------------
    if ($targetDoctor) {

        $rules = [
            // basic
            'name'            => ['required', 'string', 'max:255'],
            'speciality'      => ['nullable', 'string', 'max:255'],
            'degree'          => ['nullable', 'string', 'max:255'],
            'phone_number'    => ['nullable', 'string', 'max:30'],
            'phone_number_2'  => ['nullable', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:255'],
            'website'         => ['nullable', 'url', 'max:255'],
            'whatsapp'        => ['nullable', 'string', 'max:50'],
            'facebook'        => ['nullable', 'string', 'max:255'],
            'instagram'       => ['nullable', 'string', 'max:255'],
            'address'         => ['nullable', 'string'],
            'profile_details' => ['nullable', 'string'],
            'registration_no' => ['nullable', 'string', 'max:100'],
            'council'         => ['nullable', 'string', 'max:255'],

            // location
            'country_id'        => ['nullable', 'integer', 'exists:countries,id'],
            'state_id'          => ['nullable', 'integer', 'exists:states,id'],
            'district_id'       => ['nullable', 'integer', 'exists:districts,id'],
            'city_id'           => ['nullable', 'integer', 'exists:cities,id'],
            'pincode_id'        => ['nullable', 'integer', 'exists:pincodes,id'],
            'manual_pincode'    => ['nullable', 'string', 'max:20'],
            'is_pincode_unknown'=> ['nullable', 'boolean'],
            'location_source'   => ['nullable', 'in:auto,reverse,manual'],

            // category / clinic
            'category_id'        => ['nullable', 'integer', 'exists:category,id'],
            'clinic_id'          => ['nullable', 'integer', 'exists:clinics,id'],
            'clinic_name'        => ['nullable', 'string', 'max:255'],
            'clinic_days'        => ['nullable', 'array'],
            'clinic_days.*'      => ['string'],
            'clinic_start_time'  => ['nullable', 'date_format:H:i'],
            'clinic_end_time'    => ['nullable', 'date_format:H:i'],

            // consultation + status
            'consultation_mode' => ['nullable', 'in:online,offline,both,face-to-face'],
            'status'            => ['nullable', 'in:active,inactive'],

            // experience + languages
            'experience_years'  => ['nullable', 'integer', 'min:0', 'max:80'],
            'languages'         => ['nullable', 'string', 'max:255'],  // or handle as array -> see below

            // file
            'profile_picture'   => ['nullable', 'image', 'max:5120'], // 5MB
        ];

        $validated = $request->validate($rules);

        // Normalize clinic_days: store as JSON array to match cast
        if (isset($validated['clinic_days']) && is_array($validated['clinic_days'])) {
            $validated['clinic_days'] = json_encode(
                array_values($validated['clinic_days'])
            );
        }

        // Optional: if you want languages as array from form (languages[])
        // and store as comma-separated string:
        // if ($request->has('languages') && is_array($request->languages)) {
        //     $validated['languages'] = implode(',', array_map('trim', $request->languages));
        // }

        // Handle profile picture
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $ext = $file->getClientOriginalExtension();
            $filename = 'doctor_' . $targetDoctor->id . '_' . time() . '.' . $ext;
            $stored = $file->storeAs('public/doctors', $filename);

            if ($stored) {
                // Delete old picture if exists (only if path is relative to /storage/app/public)
                if (! empty($targetDoctor->profile_picture)
                    && Storage::disk('public')->exists($targetDoctor->profile_picture)
                ) {
                    Storage::disk('public')->delete($targetDoctor->profile_picture);
                }

                $validated['profile_picture'] = 'doctors/' . $filename;
            }
        }

        // Fill allowed fields onto the model
        foreach ($validated as $key => $value) {
            $targetDoctor->{$key} = $value;
        }

        $targetDoctor->save();

        return redirect()
            ->route('doctor.profile.show', $targetDoctor->id)
            ->with('success', 'Doctor profile updated successfully.');
    }

    // -------------------------
    // User update flow (non-doctor)
    // -------------------------
    $rules = [
        'name'  => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255'],
        // add more user-specific rules here if needed
    ];

    $validated = $request->validate($rules);

    $user->fill($validated);
    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }
    $user->save();

    return redirect()->route('profile.edit')->with('status', 'profile-updated');
}

    /**
     * Delete account (user). If deleting a doctor user, also remove doctor record and file.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->role === 'doctor') {
            $doctor = Doctor::where('auth_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($doctor) {
                if (! empty($doctor->profile_picture) && Storage::disk('public')->exists($doctor->profile_picture)) {
                    Storage::disk('public')->delete($doctor->profile_picture);
                }
                $doctor->delete();
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
