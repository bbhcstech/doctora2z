<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorClinicScheduler extends Model
{
    // Table mapping
    protected $table = 'doctor_clinic_schedules';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    // Allow mass assignment
    protected $fillable = [
        'doctor_profile_id',
        'clinic_id',
        'days',
        'start_time',
        'end_time',
        'alternative_text',
        'clinic_address',   // ✅ new field
    ];

    // Type casting
    protected $casts = [
        'doctor_profile_id' => 'integer',
        'clinic_id'         => 'integer',
        // days is stored as JSON in DB, cast to array
        'days'              => 'array',
        'start_time'        => 'string',
        'end_time'          => 'string',
        'clinic_address'    => 'string', // ✅ optional, ensures TEXT is returned as string
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    // Relationships
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_profile_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'clinic_id');
    }
}
