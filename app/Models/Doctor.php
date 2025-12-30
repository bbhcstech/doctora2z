<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctor_profiles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'name',
        'date_of_birth',
        'country_id',
        'state_id',
        'district_id',
        'city_id',
        'pincode_id',
        'manual_pincode',
        'is_pincode_unknown',
        'location_source',
        'category_id',

        'clinic_id',
        'clinic_name',
        'clinic_days',
        'clinic_start_time',
        'clinic_end_time',
        'alternative_schedule',

        'phone_number',
        'phone_number_2',
        'email',
        'website',
        'whatsapp',
        'facebook',
        'instagram',
        'address',

        'speciality',
        'degree',
        'degrees',
        'profile_details',
        'profile_picture',
        'registration_no',
        'council',
        'status',
        'consultation_mode',

        // newly added columns
        'experience_years',
        'languages',
    ];

    protected $casts = [
        'user_id'               => 'integer',
        'country_id'            => 'integer',
        'state_id'              => 'integer',
        'district_id'           => 'integer',
        'city_id'               => 'integer',
        'pincode_id'            => 'integer',
        'is_pincode_unknown'    => 'boolean',
        'manual_pincode'        => 'string',
        'location_source'       => 'string',
        'category_id'           => 'integer',
        'clinic_id'             => 'integer',
        'clinic_days'           => 'array',
        'degrees'               => 'array',
        'alternative_schedule'  => 'array',
        'date_of_birth'         => 'date:Y-m-d',
        'clinic_start_time'     => 'datetime:H:i',
        'clinic_end_time'       => 'datetime:H:i',
        'created_at'            => 'datetime',
        'updated_at'            => 'datetime',

        // newly added casts
        'experience_years'     => 'integer',
        'languages'            => 'string',
    ];

    /* ----------------- Relationships ----------------- */

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'clinic_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Country::class, 'country_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(\App\Models\State::class, 'state_id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(\App\Models\District::class, 'district_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }

    public function pincode(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Pincode::class, 'pincode_id');
    }

    public function clinicSchedules(): HasMany
    {
        return $this->hasMany(\App\Models\DoctorClinicScheduler::class, 'doctor_profile_id', 'id');
    }

    public function schedules(): HasMany
    {
        return $this->clinicSchedules();
    }

    /* ----------------- Accessors & Mutators ----------------- */

    /**
     * Return alternative_schedule decoded as array (alias 'clinics')
     */
    public function getClinicsAttribute(): array
    {
        $raw = $this->attributes['alternative_schedule'] ?? null;
        if (is_null($raw) || $raw === '') {
            return [];
        }
        if (is_array($raw)) {
            return $raw;
        }
        if (is_string($raw)) {
            try {
                return json_decode($raw, true, 512, JSON_THROW_ON_ERROR) ?: [];
            } catch (\Throwable $e) {
                return [];
            }
        }
        return [];
    }

    /**
     * Set clinics attribute → saves into alternative_schedule column
     */
    public function setClinicsAttribute($val): void
    {
        $array = is_string($val)
            ? (json_decode($val, true) ?: [])
            : (is_array($val) ? $val : []);

        $normalized = [];
        $weekdayMap = [
            'mon'=>'monday','monday'=>'monday',
            'tue'=>'tuesday','tuesday'=>'tuesday',
            'wed'=>'wednesday','wednesday'=>'wednesday',
            'thu'=>'thursday','thursday'=>'thursday',
            'fri'=>'friday','friday'=>'friday',
            'sat'=>'saturday','saturday'=>'saturday',
            'sun'=>'sunday','sunday'=>'sunday',
        ];

        foreach ($array as $clinic) {
            if (!is_array($clinic)) $clinic = (array)$clinic;

            $name = trim($clinic['clinic_name'] ?? ($clinic['name'] ?? ''));
            $cid  = !empty($clinic['clinic_id']) ? (int)$clinic['clinic_id'] : null;

            $schedules = [];
            if (!empty($clinic['schedules']) && is_array($clinic['schedules'])) {
                foreach ($clinic['schedules'] as $s) {
                    $rawDay = strtolower(trim($s['day'] ?? ''));
                    $day = $weekdayMap[$rawDay] ?? null;
                    $start = !empty($s['start']) ? $this->normalizeTime($s['start']) : null;
                    $end   = !empty($s['end'])   ? $this->normalizeTime($s['end'])   : null;
                    if ($day) {
                        $schedules[] = ['day'=>$day,'start'=>$start,'end'=>$end];
                    }
                }
            }

            if (empty($cid) && empty($name) && empty($schedules)) {
                continue;
            }

            $normalized[] = [
                'clinic_id'   => $cid,
                'clinic_name' => $name,
                'schedules'   => $schedules,
            ];
        }

        $this->attributes['alternative_schedule'] = !empty($normalized) 
            ? json_encode($normalized, JSON_UNESCAPED_UNICODE) 
            : null;
    }

    /**
     * Normalize time format to H:i:s
     */
    private function normalizeTime($time): ?string
    {
        if (empty($time)) return null;
        
        try {
            $timestamp = strtotime($time);
            if ($timestamp === false) return null;
            return date('H:i:s', $timestamp);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function setPhoneNumberAttribute($value)
    {
        $this->attributes['phone_number'] = $value ? preg_replace('/\D+/', '', (string)$value) : null;
    }

    public function setPhoneNumber2Attribute($value)
    {
        $this->attributes['phone_number_2'] = $value ? preg_replace('/\D+/', '', (string)$value) : null;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? mb_strtolower(trim((string)$value)) : null;
    }

    /**
     * Safe pincode accessor
     */
    public function getPincodeAttribute(): ?string
{
    // 1) If manual pincode exists, return it
    if (!empty($this->manual_pincode)) {
        return $this->manual_pincode;
    }

    // 2) If the 'pincode' relation has already been eager-loaded, read it
    if ($this->relationLoaded('pincode')) {
        $related = $this->getRelation('pincode'); // avoids calling the accessor
        return $related->pincode ?? null;
    }

    // 3) Otherwise try to fetch the related pincode value directly from DB
    try {
        // this runs a direct query on the relation and returns the 'pincode' column value
        return $this->pincode()->value('pincode');
    } catch (\Throwable $e) {
        return null;
    }
}

    /**
     * Get languages as array
     */
    public function getLanguagesArrayAttribute(): array
    {
        if (empty($this->languages)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->languages));
    }

    /**
     * Set languages from array
     */
    public function setLanguagesArrayAttribute(array $languages): void
    {
        $this->attributes['languages'] = !empty($languages) 
            ? implode(',', array_map('trim', $languages)) 
            : null;
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        $parts = [];
        
        if ($this->address) $parts[] = $this->address;
        if ($this->city && $this->city->name) $parts[] = $this->city->name;
        if ($this->state && $this->state->name) $parts[] = $this->state->name;
        if ($this->pincode) $parts[] = $this->pincode;
        if ($this->country && $this->country->name) $parts[] = $this->country->name;
        
        return implode(', ', array_filter($parts));
    }

    /**
     * Get clinic days as array
     */
    public function getClinicDaysArrayAttribute(): array
    {
        if (empty($this->clinic_days)) {
            return [];
        }
        
        if (is_array($this->clinic_days)) {
            return $this->clinic_days;
        }
        
        try {
            return json_decode($this->clinic_days, true) ?: [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    /* ----------------- Scopes ----------------- */

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    public function scopeByState($query, $stateId)
    {
        return $query->where('state_id', $stateId);
    }

    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearchByName($query, $name)
    {
        return $query->where('name', 'LIKE', "%{$name}%");
    }

    public function scopeHasOnlineConsultation($query)
    {
        return $query->whereIn('consultation_mode', ['online', 'both']);
    }

    /* ----------------- Helpers ----------------- */

    public function primaryClinic(): ?array
    {
        foreach ($this->clinics as $c) {
            if (!empty($c['is_primary'])) return $c;
        }
        return $this->clinics[0] ?? null;
    }

    public function clinicById($clinicId): ?array
    {
        foreach ($this->clinics as $c) {
            if (!empty($c['clinic_id']) && $c['clinic_id'] == $clinicId) return $c;
            if (!empty($c['clinic_name']) && $c['clinic_name'] == $clinicId) return $c;
        }
        return null;
    }

    /**
     * Check if doctor has online consultation
     */
    public function hasOnlineConsultation(): bool
    {
        return in_array($this->consultation_mode, ['online', 'both']);
    }

    /**
     * Get display name with degree
     */
public function getDisplayNameAttribute(): string
{
    // normalize name and remove any leading Dr/Dr. variants at runtime
    $name = trim((string) $this->name);
    // remove leading dr, dr., doctor variants case-insensitive
    $name = preg_replace('/^\s*(?i:(dr|doctor))[\.]?\s*/u', '', $name);

    $degree = trim((string) ($this->degree ?? ''));
    if ($degree !== '') {
        // prefer degree before name like "Dr. MBBS Name"
        return 'Dr. ' . $degree . ' ' . $name;
    }

    return 'Dr. ' . $name;
}


    /**
     * Get experience display text
     */
    public function getExperienceDisplayAttribute(): string
    {
        if (empty($this->experience_years)) {
            return 'Not specified';
        }
        
        return $this->experience_years . ' year' . ($this->experience_years > 1 ? 's' : '');
    }
    public function getVisitingTimeAttribute(): ?string
{
    $schedule = $this->clinicSchedules->first();
    if (!$schedule) {
        return null;
    }

    // Days text
    $days = is_array($schedule->days)
        ? implode(', ', $schedule->days)
        : $schedule->days;

    // Time text
    $start = $schedule->start_time ? date('h:i A', strtotime($schedule->start_time)) : null;
    $end   = $schedule->end_time   ? date('h:i A', strtotime($schedule->end_time))   : null;

    if ($start && $end) {
        return "$days, $start - $end";
    }

    return $days;
}

}